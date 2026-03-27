<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Data\VehicleFeatures;
use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\FeaturesCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    /**
     * Araç listesi
     */
    public function index(Request $request)
    {
        $query = Vehicle::query();

        // Arama
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
        }

        // Aktif/Pasif/Öne Çıkan filtresi
        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'passive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'featured') {
                $query->where('is_featured', true);
            }
        }

        // İlan Durumu filtresi (Satılık / Satıldı / Rezerve / Fırsat)
        if ($request->has('vehicle_status') && $request->vehicle_status !== '') {
            $query->where('vehicle_status', $request->vehicle_status);
        }

        // Araç Durumu filtresi (Sıfır / İkinci El)
        if ($request->has('condition') && $request->condition !== '') {
            $query->where('condition', $request->condition);
        }

        $vehicles = $query->orderBy('created_at', 'desc')->paginate(15);

        // Tüm sayaçlar tek sorguda
        $counts = Vehicle::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as passive,
            SUM(CASE WHEN is_featured = 1 THEN 1 ELSE 0 END) as featured
        ')->first();

        $totalCount    = $counts->total ?? 0;
        $activeCount   = $counts->active ?? 0;
        $passiveCount  = $counts->passive ?? 0;
        $featuredCount = $counts->featured ?? 0;

        return view('admin.vehicles.index', compact('vehicles', 'totalCount', 'activeCount', 'passiveCount', 'featuredCount'));
    }

    /**
     * Yeni araç formu
     */
    public function create()
    {
        $brands            = Cache::remember('car_brands_all', 86400, fn() => CarBrand::orderBy('name')->get());
        $featureCategories = FeaturesCatalog::grouped() ?: VehicleFeatures::all();
        return view('admin.vehicles.create', compact('brands', 'featureCategories'));
    }

    /**
     * Yeni araç kaydet
     */
    public function store(Request $request)
    {
        // Action'ı belirle (draft veya publish)
        $action       = $request->input('action', 'draft');
        $isPublishing = ($action === 'publish');

        $requiredRule  = $isPublishing ? 'required' : 'nullable';
        $mainImageRule = $isPublishing ? 'required|image|max:5120' : 'nullable|image|max:5120';

        $validated = $request->validate([
            // Temel Bilgiler - title her durumda zorunlu
            'title'     => 'required|string|max:255',
            'brand'     => $requiredRule . '|string|max:255',
            'model'     => $requiredRule . '|string|max:255',
            'year'      => $requiredRule . '|integer|min:1900|max:' . (date('Y') + 1),
            'price'     => $requiredRule . '|numeric|min:0',
            'kilometer' => $requiredRule . '|integer|min:0',

            // Teknik Özellikler
            'package_version' => 'nullable|string|max:255',
            'fuel_type'       => 'nullable|string|max:50',
            'transmission'    => 'nullable|string|max:50',
            'drive_type'      => 'nullable|in:Önden Çekiş,Arkadan İtiş,4x4',
            'body_type'       => 'nullable|string|max:100',
            'door_count'      => 'nullable|integer|min:2|max:8',
            'seat_count'      => 'nullable|integer|min:1|max:15',
            'color'           => 'nullable|string|max:255',
            'color_type'      => 'nullable|string|max:50',
            'engine_size'     => 'nullable|integer|min:0',
            'horse_power'     => 'nullable|integer|min:0',
            'torque'          => 'nullable|integer|min:0',

            // Hasar & Geçmiş
            'tramer_status'    => 'nullable|in:Yok,Var,Bilinmiyor',
            'tramer_amount'    => 'nullable|numeric|min:0',
            'painted_parts'    => 'nullable|array',
            'replaced_parts'   => 'nullable|array',
            'owner_number'     => 'nullable|integer|min:1',
            'inspection_date'  => 'nullable|date',
            'warranty_end_date'=> 'nullable|date',
            'has_warranty'     => 'nullable|boolean',

            // Donanımlar & Açıklama
            'description' => 'nullable|string',
            'features'    => 'nullable|array',
            'features.*'  => 'nullable|string|max:200',

            // Medya
            'main_image' => $mainImageRule,
            'images'     => 'nullable|array',
            'images.*'   => 'nullable|image|max:5120',

            // Durum
            'is_featured'    => 'nullable|boolean',
            'is_active'      => 'nullable|boolean',
            'vehicle_status' => 'nullable|in:available,sold,reserved,opportunity',
            'condition'      => 'nullable|in:second_hand,zero_km',
            'city'           => 'nullable|string|max:100',
            'swap'             => 'nullable|boolean',
            'price_negotiable' => 'nullable|boolean',

            // Entegrasyon
            'sahibinden_url' => 'nullable|url|max:500',
            'sahibinden_id'  => 'nullable|string|max:100',
            'source'         => 'nullable|string|max:50',

            // SEO Slug
            'slug' => 'nullable|string|max:255|alpha_dash',
        ], [
            'title.required'      => 'Başlık zorunludur.',
            'brand.required'      => 'Marka zorunludur.',
            'model.required'      => 'Model zorunludur.',
            'year.required'       => 'Yıl zorunludur.',
            'price.required'      => 'Fiyat zorunludur.',
            'kilometer.required'  => 'Kilometre zorunludur.',
            'main_image.required' => 'Ana görsel zorunludur.',
            'slug.alpha_dash'     => 'Slug yalnızca harf, rakam, tire ve alt çizgi içerebilir.',
            'condition.in'        => 'Araç durumu geçersiz.',
        ]);

        // Boolean değerleri: unchecked checkbox = false
        $validated['is_featured']  = $request->has('is_featured')  ? true : false;
        $validated['is_active']    = $request->has('is_active')    ? true : false;
        $validated['has_warranty'] = $request->input('has_warranty') == '1';
        $validated['swap']             = $request->has('swap')             ? true : false;
        $validated['price_negotiable'] = $request->has('price_negotiable') ? true : false;

        // vehicle_status default
        $validated['vehicle_status'] = $validated['vehicle_status'] ?? 'available';

        // Sahibinden alanları varsa source otomatik ata
        if (!empty($validated['sahibinden_id']) || !empty($validated['sahibinden_url'])) {
            $validated['source'] = $validated['source'] ?? 'sahibinden';
        }

        // Slug
        $manualSlug = trim($request->input('slug', ''));
        $validated['slug'] = !empty($manualSlug)
            ? Vehicle::generateUniqueSlug(Str::slug($manualSlug))
            : Vehicle::generateUniqueSlug($validated['title'] ?? '');

        // ─── Görsel işleme ────────────────────────────────────────────────────
        $images = [];

        if ($request->hasFile('main_image')) {
            $img      = $request->file('main_image');
            $imgName  = time() . '_main_' . Str::random(10) . '.' . $img->getClientOriginalExtension();
            $imgPath  = $img->storeAs('vehicles', $imgName, 'public');
            array_unshift($images, $imgPath);
            $validated['image'] = $imgPath;
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $img) {
                $imgName = time() . '_' . $index . '_' . Str::random(10) . '.' . $img->getClientOriginalExtension();
                $images[] = $img->storeAs('vehicles', $imgName, 'public');
            }
        }

        if (isset($validated['image']) && (empty($images) || $images[0] !== $validated['image'])) {
            $images = array_values(array_filter($images, fn ($p) => $p !== $validated['image']));
            array_unshift($images, $validated['image']);
        }

        $validated['images']      = $images;
        $validated['images_meta'] = Vehicle::buildImagesMeta($images, $validated['image'] ?? null);
        // ─────────────────────────────────────────────────────────────────────

        Vehicle::create($validated);

        $message = $isPublishing
            ? 'Araç başarıyla kaydedildi ve yayınlandı.'
            : 'Araç taslak olarak kaydedildi.';
        return redirect()->route('admin.vehicles.index')->with('success', $message);
    }

    /**
     * Araç düzenleme formu
     */
    public function edit($id)
    {
        $vehicle           = Vehicle::findOrFail($id);
        $brands            = Cache::remember('car_brands_all', 86400, fn() => CarBrand::orderBy('name')->get());
        $featureCategories = FeaturesCatalog::grouped() ?: VehicleFeatures::all();
        return view('admin.vehicles.edit', compact('vehicle', 'brands', 'featureCategories'));
    }

    /**
     * Araç güncelle
     */
    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $validated = $request->validate([
            // Temel Bilgiler (Zorunlu)
            'title'     => 'required|string|max:255',
            'brand'     => 'required|string|max:255',
            'model'     => 'required|string|max:255',
            'year'      => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'price'     => 'required|numeric|min:0',
            'kilometer' => 'required|integer|min:0',

            // Teknik Özellikler
            'package_version' => 'nullable|string|max:255',
            'fuel_type'       => 'nullable|string|max:50',
            'transmission'    => 'nullable|string|max:50',
            'drive_type'      => 'nullable|in:Önden Çekiş,Arkadan İtiş,4x4',
            'body_type'       => 'nullable|string|max:100',
            'door_count'      => 'nullable|integer|min:2|max:8',
            'seat_count'      => 'nullable|integer|min:1|max:15',
            'color'           => 'nullable|string|max:255',
            'color_type'      => 'nullable|string|max:50',
            'engine_size'     => 'nullable|integer|min:0',
            'horse_power'     => 'nullable|integer|min:0',
            'torque'          => 'nullable|integer|min:0',

            // Hasar & Geçmiş
            'tramer_status'    => 'nullable|in:Yok,Var,Bilinmiyor',
            'tramer_amount'    => 'nullable|numeric|min:0',
            'painted_parts'    => 'nullable|array',
            'replaced_parts'   => 'nullable|array',
            'owner_number'     => 'nullable|integer|min:1',
            'inspection_date'  => 'nullable|date',
            'warranty_end_date'=> 'nullable|date',
            'has_warranty'     => 'nullable|boolean',

            // Donanımlar & Açıklama
            'description' => 'nullable|string',
            'features'    => 'nullable|array',
            'features.*'  => 'nullable|string|max:200',

            // Medya
            'main_image' => 'nullable|image|max:5120',
            'images'     => 'nullable|array',
            'images.*'   => 'nullable|image|max:5120',

            // Durum
            'is_featured'    => 'nullable|boolean',
            'is_active'      => 'nullable|boolean',
            'vehicle_status' => 'nullable|in:available,sold,reserved,opportunity',
            'condition'      => 'nullable|in:second_hand,zero_km',
            'city'           => 'nullable|string|max:100',
            'swap'             => 'nullable|boolean',
            'price_negotiable' => 'nullable|boolean',

            // Entegrasyon
            'sahibinden_url' => 'nullable|url|max:500',
            'sahibinden_id'  => 'nullable|string|max:100',
            'source'         => 'nullable|string|max:50',

            // SEO Slug
            'slug' => 'nullable|string|max:255|alpha_dash',

            // Görsel yönetimi
            'remove_images'   => 'nullable|array',
            'remove_images.*' => 'nullable|string|max:500',
            'set_main_image'  => 'nullable|string|max:500',
        ], [
            'title.required'     => 'Başlık zorunludur.',
            'brand.required'     => 'Marka zorunludur.',
            'model.required'     => 'Model zorunludur.',
            'year.required'      => 'Yıl zorunludur.',
            'price.required'     => 'Fiyat zorunludur.',
            'kilometer.required' => 'Kilometre zorunludur.',
            'slug.alpha_dash'    => 'Slug yalnızca harf, rakam, tire ve alt çizgi içerebilir.',
            'condition.in'       => 'Araç durumu geçersiz.',
        ]);

        // Boolean değerleri: unchecked = false
        $validated['is_featured']  = $request->has('is_featured')  ? true : false;
        $validated['is_active']    = $request->has('is_active')    ? true : false;
        $validated['has_warranty'] = $request->input('has_warranty') == '1';
        $validated['swap']             = $request->has('swap')             ? true : false;
        $validated['price_negotiable'] = $request->has('price_negotiable') ? true : false;

        // vehicle_status: formdan gelmezse mevcut değer korunsun
        $validated['vehicle_status'] = $validated['vehicle_status'] ?? $vehicle->vehicle_status ?? 'available';

        // Sahibinden alanları varsa source otomatik koru
        if (!empty($validated['sahibinden_id']) || !empty($validated['sahibinden_url'])) {
            $validated['source'] = $validated['source'] ?? $vehicle->source ?? 'sahibinden';
        }

        // Slug yönetimi:
        // 1. Admin yeni bir slug girdiyse → benzersizleştirip kaydet
        // 2. Araçta hiç slug yoksa → başlıktan otomatik üret
        // 3. Diğer tüm durumlarda → $validated'dan slug'ı çıkar; mevcut değer korunsun
        $manualSlug = trim($request->input('slug', ''));
        if (!empty($manualSlug) && $manualSlug !== $vehicle->slug) {
            $validated['slug'] = Vehicle::generateUniqueSlug(Str::slug($manualSlug), $id);
        } elseif (empty($vehicle->slug)) {
            $validated['slug'] = Vehicle::generateUniqueSlug($validated['title'] ?? '', $id);
        } else {
            // Slug değiştirilmedi — validated array'den kaldır; null ile üzerine yazılmasın
            unset($validated['slug']);
        }

        // ============================================================
        // GÖRSEL YÖNETİMİ
        // ============================================================

        $allImages   = $vehicle->images ?? [];
        $currentMain = $vehicle->image;

        // 1. Silinmesi istenen görseller (edit formundan gelen remove_images[])
        $toRemove = array_filter($request->input('remove_images', []));
        foreach ($toRemove as $removePath) {
            Storage::disk('public')->delete($removePath);
            $allImages = array_values(array_filter($allImages, fn ($p) => $p !== $removePath));
        }
        // Eğer silinen görseller arasında mevcut main varsa bir sonrakini promote et
        if (!empty($currentMain) && in_array($currentMain, $toRemove)) {
            $currentMain = $allImages[0] ?? null;
        }

        // 2. Yeni ana görsel yüklendi
        if ($request->hasFile('main_image')) {
            // Eski ana görseli depodan sil (henüz silinmediyse)
            if ($currentMain && !in_array($currentMain, $toRemove)) {
                Storage::disk('public')->delete($currentMain);
            }
            $img      = $request->file('main_image');
            $imgName  = time() . '_main_' . Str::random(10) . '.' . $img->getClientOriginalExtension();
            $imgPath  = $img->storeAs('vehicles', $imgName, 'public');

            // Eski main'i images dizisinden kaldır, yenisini başa ekle
            $allImages = array_values(array_filter($allImages, fn ($p) => $p !== $currentMain));
            array_unshift($allImages, $imgPath);

            $currentMain           = $imgPath;
            $validated['image']    = $imgPath;
        }

        // 3. Mevcut bir görsel ana görsel olarak seçildi (set_main_image radio)
        $setMain = trim($request->input('set_main_image', ''));
        if (!empty($setMain) && !$request->hasFile('main_image') && in_array($setMain, $allImages)) {
            $allImages = array_values(array_filter($allImages, fn ($p) => $p !== $setMain));
            array_unshift($allImages, $setMain);
            $currentMain        = $setMain;
            $validated['image'] = $setMain;
        }

        // 4. Ek galeri görselleri yüklendi
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $img) {
                $imgName  = time() . '_' . $index . '_' . Str::random(10) . '.' . $img->getClientOriginalExtension();
                $imgPath  = $img->storeAs('vehicles', $imgName, 'public');
                $allImages[] = $imgPath;
            }
        }

        // 5. Finalize — benzersiz dizin, images_meta senkronizasyonu
        $allImages = array_values(array_unique($allImages));

        if (!isset($validated['image'])) {
            $validated['image'] = $currentMain ?? ($allImages[0] ?? null);
        }

        // Ana görsel images dizisinde değilse (eski kayıt backward compat) başa ekle
        if (!empty($validated['image']) && !in_array($validated['image'], $allImages)) {
            array_unshift($allImages, $validated['image']);
        }

        $validated['images']      = $allImages;
        $validated['images_meta'] = Vehicle::buildImagesMeta($allImages, $validated['image']);

        // Form-only alanları çıkar (fillable değil)
        unset($validated['remove_images'], $validated['set_main_image']);

        $vehicle->update($validated);

        return redirect()->route('admin.vehicles.index')->with('success', 'Araç başarıyla güncellendi.');
    }

    /**
     * Araç sil — ilişkili depolama dosyalarını da temizler
     */
    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);

        // Depodan görselleri sil
        $pathsToDelete = array_values(array_filter(array_unique(
            array_merge(
                is_array($vehicle->images) ? $vehicle->images : [],
                !empty($vehicle->image) ? [$vehicle->image] : []
            )
        )));

        foreach ($pathsToDelete as $path) {
            // Yalnızca relative storage path'leri sil; http:// ve /storage/... olanları bırak
            if (!filter_var($path, FILTER_VALIDATE_URL) && !str_starts_with($path, '/')) {
                Storage::disk('public')->delete($path);
            }
        }

        $vehicle->delete();

        return redirect()->route('admin.vehicles.index')->with('success', 'Araç başarıyla silindi.');
    }

    /**
     * Get brands from database (API endpoint for vehicle form)
     */
    public function getBrands()
    {
        $brands = Cache::remember('car_brands_active', 86400, fn() =>
            CarBrand::where('is_active', true)
                ->orderBy('order')
                ->orderBy('name')
                ->get(['id', 'name', 'arabam_id'])
        );

        return response()->json([
            'success' => true,
            'data' => [
                'Items' => $brands->map(function($brand) {
                    return [
                        'Id' => $brand->id,
                        'Name' => $brand->name,
                        'Value' => $brand->id,
                        'ArabamId' => $brand->arabam_id
                    ];
                })->toArray(),
                'SelectedItem' => null
            ]
        ]);
    }

    /**
     * Get models for a brand from database (API endpoint for vehicle form)
     */
    public function getModels(Request $request)
    {
        $brandId = $request->get('brandId');

        if (!$brandId) {
            return response()->json([
                'success' => false,
                'message' => 'Brand ID gerekli'
            ], 400);
        }

        $models = CarModel::where('car_brand_id', $brandId)
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('name')
            ->get(['id', 'name', 'arabam_id']);

        return response()->json([
            'success' => true,
            'data' => [
                'Items' => $models->map(function($model) {
                    return [
                        'Id' => $model->id,
                        'Name' => $model->name,
                        'Value' => $model->id,
                        'ArabamId' => $model->arabam_id
                    ];
                })->toArray(),
                'SelectedItem' => null
            ]
        ]);
    }

    /**
     * Araç aktif/pasif durumunu değiştir
     */
    public function toggleActive($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->is_active = !$vehicle->is_active;
        $vehicle->save();

        return response()->json([
            'success' => true,
            'is_active' => $vehicle->is_active,
            'message' => $vehicle->is_active ? 'Araç yayına alındı.' : 'Araç yayından kaldırıldı.'
        ]);
    }

    /**
     * Araç öne çıkan durumunu değiştir
     */
    public function toggleFeatured($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->is_featured = !$vehicle->is_featured;
        $vehicle->save();

        return response()->json([
            'success' => true,
            'is_featured' => $vehicle->is_featured,
            'message' => $vehicle->is_featured ? 'Araç öne çıkarıldı.' : 'Araç öne çıkandan kaldırıldı.'
        ]);
    }
}
