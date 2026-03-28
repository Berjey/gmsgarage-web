<?php

namespace App\Http\Controllers;

use App\Models\EvaluationRequest;
use App\Models\CarBrand;
use App\Models\ArabamVehicleConfig;
use App\Models\CarColor;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class VehicleEvaluationController extends Controller
{
    /**
     * Araç değerleme sayfası - Step-by-step Wizard
     */
    public function index(Request $request)
    {
        try {
            // URL parametrelerinden gelen değerler (Hero'dan) - safe defaults
            $selectedTip = $request->get('tip') ?? '';
            $selectedYil = $request->get('yil') ?? '';
            $selectedMarka = $request->get('marka') ?? '';
            
            // Vehicle types
            $vehicleTypes = [
                'AUTO' => 'Otomobil',
                'SUV' => 'SUV',
                'TICARI' => 'Ticari',
                'MOTOSIKLET' => 'Motosiklet',
            ];
            
            // Fuel types
            $fuelTypes = [
                'BENZIN' => 'Benzin',
                'DIZEL' => 'Dizel',
                'HIBRIT' => 'Hibrit',
                'ELEKTRIK' => 'Elektrik',
            ];
            
            // Transmission types
            $transmissionTypes = [
                'MANUEL' => 'Manuel',
                'OTOMATIK' => 'Otomatik',
                'YARI_OTOMATIK' => 'Yarı Otomatik',
            ];
            
            // Damage status
            $damageStatuses = [
                'YOK' => 'Yok',
                'VAR' => 'Var',
                'BILMIYORUM' => 'Bilmiyorum',
            ];
            
            // Contact methods
            $contactMethods = [
                'TELEFON' => 'Telefon',
                'WHATSAPP' => 'WhatsApp',
                'EMAIL' => 'E-posta',
            ];
            
            // Renk listesi DB'den
            $carColors = CarColor::where('is_active', true)
                ->orderBy('sort_order')
                ->get();

            // Render view with all required variables
            return view('pages.evaluation.wizard', compact(
                'selectedTip', 'selectedYil', 'selectedMarka',
                'vehicleTypes', 'fuelTypes', 'transmissionTypes',
                'damageStatuses', 'contactMethods', 'carColors'
            ));
            
        } catch (\Throwable $e) {
            \Log::error('evaluation.index error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->route('home')->with('error', 'Sayfa yüklenirken bir hata oluştu. Lütfen tekrar deneyin.');
        }
    }
    
    
    
    /**
     * Markaları döndürür — her zaman DB'den (arabam:sync ile doldurulmuş).
     * Arabam.com engellemelerinden etkilenmez.
     */
    public function getArabamBrands()
    {
        $brands = Cache::remember('arabam_brands_db', 60 * 60 * 24, function () {
            return CarBrand::where('is_active', true)
                ->orderBy('name')
                ->get(['arabam_id', 'name']);
        });

        if ($brands->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Marka verisi bulunamadı. Lütfen önce arabam:sync komutunu çalıştırın.'], 500);
        }

        $items = $brands->map(fn($b) => [
            'Id'        => $b->arabam_id,
            'Name'      => $b->name,
            'Value'     => $b->arabam_id,
            'Properties'=> [],
            'ShortName' => $b->name,
            'Active'    => true,
            'LogoPath'  => '',
        ])->toArray();

        return response()->json([
            'success' => true,
            'data'    => ['Items' => $items, 'SelectedItem' => null],
        ]);
    }

    /**
     * Cascade adım verilerini döndürür — DB öncelikli, arabam.com yedek.
     *
     * step=10 → yıllar      (brandId)
     * step=20 → model grubu (brandId, modelYear)
     * step=30 → kasa tipi   (brandId, modelYear, modelGroupId)
     * step=40 → yakıt tipi  (brandId, modelYear, modelGroupId, bodyTypeId)
     * step=50 → şanzıman    (brandId, modelYear, modelGroupId, bodyTypeId, fuelTypeId)
     * step=60 → versiyon    (brandId, modelYear, modelGroupId, bodyTypeId, fuelTypeId, transmissionTypeId)
     */
    public function getArabamStepData(Request $request)
    {
        $step               = (int) $request->get('step');
        $brandId            = (int) $request->get('brandId');
        $modelYear          = $request->get('modelYear');
        $modelGroupId       = (int) $request->get('modelGroupId');
        $bodyTypeId         = (int) $request->get('bodyTypeId');
        $fuelTypeId         = (int) $request->get('fuelTypeId');
        $transmissionTypeId = (int) $request->get('transmissionTypeId');

        // Renk (step 70): Her zaman DB'den servis et
        if ($step === 70) {
            $colors = CarColor::where('is_active', true)
                ->orderBy('sort_order')
                ->get()
                ->map(fn($c) => [
                    'Id'   => $c->arabam_id,
                    'Name' => $c->name,
                    'Value' => $c->arabam_id,
                    'Properties' => [],
                    'Active' => true,
                    'LogoPath' => '',
                ])
                ->toArray();

            return response()->json([
                'success' => true,
                'data'    => ['Items' => $colors, 'SelectedItem' => null],
            ]);
        }

        // DB'den servis et (arabam:sync --full yapıldıktan sonra)
        if (ArabamVehicleConfig::isSynced()) {
            $items = match($step) {
                10 => array_map(fn($y) => ['Id' => $y, 'Name' => $y, 'Value' => $y, 'Properties' => [], 'Active' => true, 'LogoPath' => ''],
                         ArabamVehicleConfig::getYears($brandId)),

                20 => ArabamVehicleConfig::getModelGroups($brandId, $modelYear),

                30 => ArabamVehicleConfig::getBodyTypes($brandId, $modelYear, $modelGroupId),

                40 => ArabamVehicleConfig::getFuelTypes($brandId, $modelYear, $modelGroupId, $bodyTypeId),

                50 => ArabamVehicleConfig::getTransmissions($brandId, $modelYear, $modelGroupId, $bodyTypeId, $fuelTypeId),

                60 => ArabamVehicleConfig::getVersions($brandId, $modelYear, $modelGroupId, $bodyTypeId, $fuelTypeId, $transmissionTypeId),

                default => [],
            };

            if (!empty($items)) {
                return response()->json([
                    'success' => true,
                    'data'    => ['Items' => $items, 'SelectedItem' => null],
                ]);
            }
        }

        // Yedek: DB sync yapılmamışsa arabam.com'dan canlı çek
        $cacheKey = 'arabam_step_' . md5(json_encode($request->all()));

        $data = Cache::remember($cacheKey, 3600, function () use ($request, $step) {
            $userAgents = [
                'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
                'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
            ];

            for ($attempt = 0; $attempt < 3; $attempt++) {
                try {
                    $params = ['CurrentStep' => $step];
                    foreach (['brandId' => 'BrandId', 'modelYear' => 'ModelYear', 'modelGroupId' => 'ModelGroupId',
                              'bodyTypeId' => 'BodyTypeId', 'fuelTypeId' => 'FuelTypeId',
                              'transmissionTypeId' => 'TransmissionTypeId', 'modelId' => 'ModelId'] as $reqKey => $apiKey) {
                        if ($request->get($reqKey)) $params[$apiKey] = $request->get($reqKey);
                    }

                    $response = Http::timeout(20)
                        ->withOptions(['verify' => false])
                        ->withHeaders([
                            'Accept'           => 'application/json, text/plain, */*',
                            'Accept-Language'  => 'tr-TR,tr;q=0.9,en-US;q=0.8,en;q=0.7',
                            'User-Agent'       => $userAgents[array_rand($userAgents)],
                            'Referer'          => 'https://www.arabam.com/fiyat-teklifi',
                            'Origin'           => 'https://www.arabam.com',
                            'Sec-Fetch-Dest'   => 'empty',
                            'Sec-Fetch-Mode'   => 'cors',
                            'Sec-Fetch-Site'   => 'same-origin',
                        ])
                        ->get('https://www.arabam.com/PriceOffer/step-definition', $params);

                    $body = $response->body();
                    if (str_contains($body, 'Just a moment') || str_contains($body, 'cloudflare') || $response->status() === 403) {
                        sleep(2 + $attempt);
                        continue;
                    }

                    if ($response->successful()) {
                        $json = $response->json();
                        if (isset($json['Data'])) return $json['Data'];
                    }
                    return null;
                } catch (\Exception $e) {
                    \Log::error('Arabam API step error (attempt ' . ($attempt+1) . '): ' . $e->getMessage());
                    if ($attempt < 2) { sleep(2); continue; }
                    return null;
                }
            }
            return null;
        });

        if ($data) {
            return response()->json(['success' => true, 'data' => $data]);
        }

        return response()->json(['success' => false, 'message' => 'Veri bulunamadı. Lütfen arabam:sync --full komutunu çalıştırın.'], 404);
    }

    /**
     * Submit evaluation form
     */
    public function submit(Request $request)
    {
        try {
            \Log::info('Evaluation form submission started', [
                'marka' => $request->get('marka'),
                'model' => $request->get('model'),
                'yil'   => $request->get('yil'),
                'method' => $request->method(),
            ]);

            $request->validate([
                'marka' => 'required|string|max:255',
                'yil' => 'required',
                'model' => 'required|string|max:255',
                'govde_tipi' => 'nullable|string|max:255',
                'yakit_tipi' => 'nullable|string|max:255',
                'vites_tipi' => 'nullable|string|max:255',
                'versiyon' => 'nullable|string|max:255',
                'kilometre' => 'required|string|max:50',
                'renk' => 'nullable|string|max:255',
                'tramer' => 'required|in:YOK,VAR,BILMIYORUM,AGIR_HASAR',
                'tramer_tutari' => 'required_if:tramer,VAR,AGIR_HASAR|nullable|string|max:50',
                'ekspertiz' => 'nullable|string',
                'ad' => 'required|string|max:255',
                'soyad' => 'required|string|max:255',
                'telefon' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'not' => 'nullable|string|max:1000',
            ], [
                'marka.required' => 'Marka alanı zorunludur.',
                'model.required' => 'Model alanı zorunludur.',
                'kilometre.required' => 'Kilometre alanı zorunludur.',
                'renk.max' => 'Renk alanı çok uzun.',
                'tramer.required' => 'Tramer bilgisi zorunludur.',
                'tramer_tutari.required_if' => 'Hasar/tramer durumunda toplam tutar girilmesi zorunludur.',
                'ad.required' => 'Ad alanı zorunludur.',
                'soyad.required' => 'Soyad alanı zorunludur.',
                'telefon.required' => 'Telefon alanı zorunludur.',
                'email.required' => 'E-posta alanı zorunludur.',
                'email.email' => 'Geçerli bir e-posta adresi girin.',
            ]);

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
            if (!empty($legalRules)) {
                $request->validate($legalRules, $legalMessages);
            }

            \Log::info('Validation passed');

            // Parse kilometre (remove dots)
            $kilometre = (int) str_replace('.', '', $request->kilometre);

            // Parse tramer tutari (remove dots)
            $tramerTutari = $request->tramer_tutari ? (int) str_replace('.', '', $request->tramer_tutari) : null;

            // Parse ekspertiz JSON
            $ekspertiz = $request->ekspertiz ? json_decode($request->ekspertiz, true) : [];

            // Determine condition based on tramer
            $condition = match($request->tramer) {
                'VAR' => 'Tramer Kayıtlı',
                'AGIR_HASAR' => 'Ağır Hasar Kayıtlı',
                'BILMIYORUM' => 'Bilinmiyor',
                default => 'Hasarsız',
            };

            \Log::info('Data parsed', [
                'kilometre' => $kilometre,
                'tramerTutari' => $tramerTutari,
                'ekspertiz' => $ekspertiz,
                'condition' => $condition,
            ]);

            // Save to database
            $evaluationRequest = EvaluationRequest::create([
                'name' => $request->ad . ' ' . $request->soyad,
                'email' => $request->email,
                'phone' => $request->telefon,
                'brand' => $request->marka,
                'model' => $request->model,
                'year' => $request->yil,
                'version' => $request->versiyon,
                'mileage' => $kilometre,
                'fuel_type' => $request->yakit_tipi,
                'transmission' => $request->vites_tipi,
                'condition' => $condition,
                'message' => json_encode([
                    'govde_tipi' => $request->govde_tipi,
                    'renk' => $request->renk,
                    'tramer' => $request->tramer,
                    'tramer_tutari' => $tramerTutari,
                    'ekspertiz' => $ekspertiz,
                    'not' => $request->not,
                ], JSON_UNESCAPED_UNICODE),
            ]);

            \Log::info('Evaluation request saved', ['id' => $evaluationRequest->id]);

            // Müşteriyi CRM listesine kaydet / güncelle - Sadece TÜM sözleşmeler onaylandıysa
            $customerData = [
                'name'         => $request->ad . ' ' . $request->soyad,
                'email'        => $request->email,
                'phone'        => $request->telefon,
                'source'       => 'evaluation_request',
                'kvkk_consent' => true,
                'ip_address'   => $request->ip(),
            ];
            
            // Legal consent verilerini ekle
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'legal_consent_') === 0) {
                    $customerData[$key] = $value === 'on' || $value === '1' || $value === true;
                }
            }
            
            Customer::findOrCreateFromRequest($customerData);

            return response()->json([
                'success' => true,
                'message' => 'Talebiniz başarıyla gönderildi. En kısa sürede sizinle iletişime geçeceğiz.'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Evaluation validation failed', [
                'errors' => $e->errors(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Doğrulama hatası: ' . implode(', ', collect($e->errors())->flatten()->toArray())
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Evaluation form submission error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu. Lütfen tekrar deneyin veya bizi arayın.'
            ], 500);
        }
    }

}
