<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of contact messages
     */
    public function index(Request $request)
    {
        $query = ContactMessage::query();
        
        // Search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }
        
        // Status filter
        $filter = $request->get('filter', 'all');
        if ($filter === 'read') {
            $query->where('is_read', true);
        } elseif ($filter === 'unread') {
            $query->where('is_read', false);
        }
        
        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }
        
        // Quick date filters
        if ($request->filled('date_range')) {
            $range = $request->get('date_range');
            if ($range === '7days') {
                $query->where('created_at', '>=', now()->subDays(7));
            } elseif ($range === '30days') {
                $query->where('created_at', '>=', now()->subDays(30));
            }
        }
        
        // Sort
        $sort = $request->get('sort', 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $perPage = $request->get('per_page', 25);
        $messages = $query->paginate($perPage)->withQueryString();
        
        // Stats
        $totalCount = ContactMessage::count();
        $unreadCount = ContactMessage::where('is_read', false)->count();
        $readCount = ContactMessage::where('is_read', true)->count();
        
        return view('admin.contact-messages.index', compact('messages', 'filter', 'totalCount', 'unreadCount', 'readCount'));
    }

    /**
     * Display a specific contact message
     */
    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);
        if (!$message->is_read) {
            $message->markAsRead();
        }
        
        // Get previous and next messages for navigation
        $previous = ContactMessage::where('id', '<', $message->id)
            ->orderBy('id', 'desc')
            ->first();
        $next = ContactMessage::where('id', '>', $message->id)
            ->orderBy('id', 'asc')
            ->first();
        
        return view('admin.contact-messages.show', compact('message', 'previous', 'next'));
    }

    /**
     * Mark message as read
     */
    public function markAsRead($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->markAsRead();
        return back()->with('success', 'Mesaj okundu olarak işaretlendi.');
    }

    /**
     * Mark message as unread
     */
    public function markAsUnread($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->update([
            'is_read' => false,
            'read_at' => null,
        ]);
        return back()->with('success', 'Mesaj okunmamış olarak işaretlendi.');
    }

    /**
     * İletişim mesajı göndericisine e-posta ile yanıtla
     */
    public function replyEmail(Request $request, $id)
    {
        $message = ContactMessage::findOrFail($id);

        if (empty($message->email)) {
            return response()->json([
                'success' => false,
                'message' => 'Bu kişinin e-posta adresi kayıtlı değil.',
            ], 422);
        }

        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ], [
            'subject.required' => 'E-posta konusu zorunludur.',
            'message.required' => 'Mesaj zorunludur.',
        ]);

        $result = EmailService::sendTo(
            email:   $message->email,
            name:    $message->name,
            subject: $request->subject,
            body:    $request->message,
            context: ['source' => 'contact_message', 'contact_id' => $message->id]
        );

        return response()->json($result, $result['success'] ? 200 : 500);
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:mark_read,mark_unread,delete',
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'integer|exists:contact_messages,id',
        ], [
            'action.required' => 'İşlem seçiniz.',
            'action.in'       => 'Geçersiz işlem.',
            'ids.required'    => 'Lütfen en az bir mesaj seçin.',
            'ids.array'       => 'Geçersiz seçim.',
            'ids.*.integer'   => 'Geçersiz mesaj ID\'si.',
            'ids.*.exists'    => 'Seçilen mesajlardan biri bulunamadı.',
        ]);

        $action = $request->get('action');
        $ids = $request->get('ids', []);

        if (empty($ids)) {
            return back()->with('error', 'Lütfen en az bir mesaj seçin.');
        }

        return DB::transaction(function () use ($action, $ids) {
            switch ($action) {
                case 'mark_read':
                    ContactMessage::whereIn('id', $ids)->update([
                        'is_read' => true,
                        'read_at' => now(),
                    ]);
                    return back()->with('success', count($ids) . ' mesaj okundu olarak işaretlendi.');

                case 'mark_unread':
                    ContactMessage::whereIn('id', $ids)->update([
                        'is_read' => false,
                        'read_at' => null,
                    ]);
                    return back()->with('success', count($ids) . ' mesaj okunmamış olarak işaretlendi.');

                case 'delete':
                    ContactMessage::whereIn('id', $ids)->delete();
                    return back()->with('success', count($ids) . ' mesaj silindi.');

                default:
                    return back()->with('error', 'Geçersiz işlem.');
            }
        });
    }

    /**
     * Delete a contact message
     */
    public function destroy(Request $request, $id)
    {
        $message = ContactMessage::findOrFail($id);
        $filter = $request->query('filter', 'all');
        $message->delete();
        
        $redirectUrl = route('admin.contact-messages.index');
        if ($filter !== 'all') {
            $redirectUrl .= '?filter=' . $filter;
        }
        
        return redirect($redirectUrl)
            ->with('success', 'Mesaj başarıyla silindi.');
    }

    /**
     * Export contact messages as CSV
     */
    public function export(Request $request)
    {
        $query = ContactMessage::query();

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $filter = $request->get('filter', 'all');
        if ($filter === 'read') {
            $query->where('is_read', true);
        } elseif ($filter === 'unread') {
            $query->where('is_read', false);
        }

        $filename = 'iletisim_mesajlari_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($query) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, ['Ad Soyad', 'E-posta', 'Konu', 'Mesaj', 'Durum', 'Tarih'], ';');

            $query->orderBy('created_at', 'desc')->chunk(500, function ($messages) use ($file) {
                foreach ($messages as $msg) {
                    fputcsv($file, [
                        $msg->name,
                        $msg->email,
                        $msg->subject ?? '',
                        $msg->message,
                        $msg->is_read ? 'Okundu' : 'Okunmamış',
                        $msg->created_at->format('d.m.Y H:i'),
                    ], ';');
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Delete all contact messages
     */
    public function destroyAll()
    {
        $count = ContactMessage::count();
        ContactMessage::truncate();
        return redirect()->route('admin.contact-messages.index')
            ->with('success', "Tüm mesajlar başarıyla silindi. ({$count} mesaj silindi)");
    }
}
