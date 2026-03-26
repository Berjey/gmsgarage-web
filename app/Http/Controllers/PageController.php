<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Setting;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('pages.contact', compact('settings'));
    }

    public function contactSubmit(Request $request)
    {
        // Dinamik yasal onay validasyonu
        $formPages = \App\Models\LegalPage::getFormPages();
        $legalRules = [];
        $legalMessages = [];
        foreach ($formPages as $page) {
            // Opsiyonel sözleşmeler için HİÇBİR VALIDATION KOYMA
            if (!$page->is_optional_in_forms) {
                $field = 'legal_consent_' . $page->slug;
                $legalRules[$field] = 'required|accepted';
                $legalMessages[$field . '.required'] = $page->title . ' metnini kabul etmelisiniz.';
                $legalMessages[$field . '.accepted'] = $page->title . ' metnini kabul etmelisiniz.';
            }
        }

        $request->validate(array_merge([
            'name'    => 'required|string|min:2|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => ['nullable', 'string', 'regex:/^[0-9]{0,11}$/', 'max:11'],
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:10|max:1000',
        ], $legalRules), array_merge([
            'name.required'    => 'Ad Soyad alanı zorunludur.',
            'name.min'         => 'Ad Soyad en az 2 karakter olmalıdır.',
            'email.required'   => 'E-posta alanı zorunludur.',
            'email.email'      => 'Geçerli bir e-posta adresi girin.',
            'phone.regex'      => 'Telefon numarası sadece rakamlardan oluşmalı ve en fazla 11 haneli olmalıdır.',
            'phone.max'        => 'Telefon numarası en fazla 11 haneli olmalıdır.',
            'message.required' => 'Mesaj alanı zorunludur.',
            'message.min'      => 'Mesaj en az 10 karakter olmalıdır.',
            'message.max'      => 'Mesaj en fazla 1000 karakter olabilir.',
        ], $legalMessages));

        // Save contact message to database
        $contactMessage = ContactMessage::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject ?? 'İletişim Formu',
            'message' => $request->message,
        ]);

        // Save or update customer (CRM) - Sadece TÜM sözleşmeler onaylandıysa
        $customerData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'source' => 'contact_form',
            'kvkk_consent' => true,
            'ip_address' => $request->ip(),
        ];
        
        // Legal consent verilerini ekle
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'legal_consent_') === 0) {
                $customerData[$key] = $value === 'on' || $value === '1' || $value === true;
            }
        }
        
        Customer::findOrCreateFromRequest($customerData);

        // Send email to configured recipient
        $mailRecipient = Setting::get('contact_mail_recipient', 'info@gmsgarage.com');
        
        // Mail göndermeyi dene, hata olsa bile devam et
        try {
            Mail::send('emails.contact', [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject ?? 'İletişim Formu',
                'messageContent' => $request->message, // $message Laravel'in Mail Message objesi ile çakışıyor
                'created_at' => $contactMessage->created_at->format('d.m.Y H:i'),
            ], function ($message) use ($request, $mailRecipient) {
                $message->to($mailRecipient)
                       ->replyTo($request->email, $request->name) // Geri yanıt için kullanıcının e-postasını ayarla
                       ->subject('Yeni İletişim Formu Mesajı: ' . ($request->subject ?? 'İletişim Formu'));
            });
            
            \Log::info('Contact form email sent successfully', [
                'contact_message_id' => $contactMessage->id,
                'recipient' => $mailRecipient,
            ]);
        } catch (\Throwable $e) {
            // Mail gönderme hatası - log'a kaydet ama kullanıcıya hata gösterme
            \Log::error('Contact form email could not be sent', [
                'error' => $e->getMessage(),
                'contact_message_id' => $contactMessage->id,
                'recipient' => $mailRecipient,
            ]);
            // Mesaj veritabanına kaydedildi, mail gönderilemese bile devam et
        }

        return back()->with('success', 'Mesajınız başarıyla gönderildi! En kısa sürede size dönüş yapacağız.');
    }

    public function landing()
    {
        return view('pages.landing');
    }
}
