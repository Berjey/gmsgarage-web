<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($source = $request->get('source')) {
            $query->where('source', $source);
        }

        $customers = $query->orderBy('created_at', 'desc')
                           ->paginate(20)
                           ->withQueryString();

        if (!$request->hasAny(['search', 'source'])) {
            Customer::where('is_new', true)->update(['is_new' => false]);
        }

        $stats = [
            'total'     => Customer::count(),
            'kvkk'      => Customer::where('kvkk_consent', true)->count(),
            'today'     => Customer::whereDate('created_at', today())->count(),
            'thisMonth' => Customer::whereMonth('created_at', now()->month)
                                   ->whereYear('created_at', now()->year)->count(),
        ];

        // Sadece DB'de gerçekten var olan source değerleri
        $availableSources = Customer::select('source')
            ->distinct()
            ->whereNotNull('source')
            ->where('source', '!=', '')
            ->orderBy('source')
            ->pluck('source');

        $sourceBadges = Customer::sourceBadges();

        return view('admin.customers.index', compact('customers', 'stats', 'availableSources', 'sourceBadges'));
    }

    public function show($id)
    {
        $customer     = Customer::findOrFail($id);
        $sourceBadges = Customer::sourceBadges();
        return view('admin.customers.show', compact('customer', 'sourceBadges'));
    }

    public function edit($id)
    {
        $customer     = Customer::findOrFail($id);
        $sourceBadges = Customer::sourceBadges();
        return view('admin.customers.edit', compact('customer', 'sourceBadges'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255|unique:customers,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ], [
            'name.required'  => 'Ad Soyad alanı zorunludur.',
            'email.required' => 'E-posta alanı zorunludur.',
            'email.email'    => 'Geçerli bir e-posta adresi girin.',
            'email.unique'   => 'Bu e-posta adresi başka bir müşteriye ait.',
        ]);

        $customer->update([
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Müşteri bilgileri başarıyla güncellendi.');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Müşteri başarıyla silindi.');
    }

    public function destroyAll()
    {
        $count = Customer::count();

        if ($count === 0) {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Silinecek müşteri bulunamadı.');
        }

        Customer::truncate();

        return redirect()->route('admin.customers.index')
            ->with('success', "Tüm {$count} müşteri kaydı başarıyla silindi.");
    }

    public function export(Request $request)
    {
        $query = Customer::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($source = $request->get('source')) {
            $query->where('source', $source);
        }

        $fileName = 'musteriler_' . now()->format('Y-m-d_His') . '.csv';
        $sep = ';';
        $esc = fn(?string $v): string => '"' . str_replace('"', '""', $v ?? '') . '"';

        $callback = function () use ($query, $sep, $esc) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, ['ID', 'Ad Soyad', 'E-posta', 'Telefon', 'Kaynak', 'Oluşturulma Tarihi', 'Not'], $sep);

            $query->orderBy('created_at', 'desc')->chunk(500, function ($customers) use ($file, $sep, $esc) {
                foreach ($customers as $customer) {
                    fputcsv($file, [
                        $customer->id,
                        $customer->name,
                        $customer->email,
                        $customer->phone,
                        $customer->source_name,
                        $customer->created_at->format('d.m.Y H:i'),
                        $customer->notes,
                    ], $sep);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ]);
    }

    public function sendBulkEmail(Request $request)
    {
        $request->validate([
            'subject'        => 'required|string|max:255',
            'message'        => 'required|string|max:5000',
            'customer_ids'   => 'required|array|min:1',
            'customer_ids.*' => 'exists:customers,id',
        ], [
            'subject.required'      => 'E-posta konusu zorunludur.',
            'message.required'      => 'E-posta mesajı zorunludur.',
            'customer_ids.required' => 'En az bir müşteri seçmelisiniz.',
            'customer_ids.min'      => 'En az bir müşteri seçmelisiniz.',
        ]);

        $customers = Customer::whereIn('id', $request->customer_ids)
                             ->whereNotNull('email')
                             ->get();

        $successCount = 0;
        $failCount    = 0;
        $subject      = $request->subject;
        $body         = $request->message;

        // 50'li gruplar halinde gönder — PHP timeout riskini önler
        $isFirstChunk = true;
        foreach ($customers->chunk(50) as $chunk) {
            // Mail sunucu aşırı yüklenmesini önlemek için chunk'lar arası bekleme
            if (!$isFirstChunk) {
                sleep(1);
            }
            $isFirstChunk = false;
            foreach ($chunk as $customer) {
                try {
                    Mail::send([], [], function ($message) use ($customer, $subject, $body) {
                        $message->to($customer->email, $customer->name)
                            ->subject($subject)
                            ->html(nl2br(e($body)));
                    });
                    $successCount++;
                    Log::info('Bulk email sent', ['to' => $customer->email, 'id' => $customer->id]);
                } catch (\Exception $e) {
                    $failCount++;
                    Log::error('Bulk email failed', [
                        'to'    => $customer->email,
                        'id'    => $customer->id,
                        'error' => $e->getMessage(),
                        'class' => get_class($e),
                    ]);
                }
            }
        }

        if ($successCount > 0) {
            $msg = "{$successCount} müşteriye e-posta başarıyla gönderildi.";
            if ($failCount > 0) {
                $msg .= " ({$failCount} başarısız)";
            }
            return redirect()->route('admin.customers.index')->with('success', $msg);
        }

        return redirect()->route('admin.customers.index')
            ->with('error', 'E-posta gönderimi başarısız oldu. Lütfen mail ayarlarınızı kontrol edin.');
    }
}
