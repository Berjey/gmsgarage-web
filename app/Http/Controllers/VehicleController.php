<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\CarBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VehicleController extends Controller
{
    /**
     * Araçlar listeleme sayfası
     */
    public function index(Request $request)
    {
        $query = Vehicle::active();

        // Filtreleme
        if ($request->has('brand') && $request->brand) {
            $query->where('brand', $request->brand);
        }

        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('fuel_type') && is_array($request->fuel_type) && count($request->fuel_type) > 0) {
            $query->whereIn('fuel_type', $request->fuel_type);
        } elseif ($request->has('fuel_type') && $request->fuel_type) {
            $query->where('fuel_type', $request->fuel_type);
        }

        if ($request->has('body_type') && $request->body_type) {
            $query->where('body_type', $request->body_type);
        }

        if ($request->has('min_year') && $request->min_year) {
            $query->where('year', '>=', $request->min_year);
        }

        if ($request->has('max_year') && $request->max_year) {
            $query->where('year', '<=', $request->max_year);
        }

        if ($request->has('transmission') && is_array($request->transmission) && count($request->transmission) > 0) {
            $query->whereIn('transmission', $request->transmission);
        } elseif ($request->has('transmission') && $request->transmission) {
            $query->where('transmission', $request->transmission);
        }

        if ($request->has('min_km') && $request->min_km) {
            $query->where('kilometer', '>=', $request->min_km);
        }

        if ($request->has('max_km') && $request->max_km) {
            $query->where('kilometer', '<=', $request->max_km);
        }

        if ($request->has('horse_power') && $request->horse_power) {
            $query->where('horse_power', '>=', $request->horse_power);
        }

        if ($request->has('engine_size') && $request->engine_size) {
            // UI litre cinsinden gönderir (1.6), DB cc olarak saklar (1600) — ±100cc aralık toleransı
            $engineCc = (float) $request->engine_size * 1000;
            $query->whereBetween('engine_size', [$engineCc - 100, $engineCc + 100]);
        }

        if ($request->has('color') && $request->color) {
            $query->where('color', $request->color);
        }

        // Çekiş (drive_type)
        if ($request->has('drive_type') && $request->drive_type) {
            $query->where('drive_type', $request->drive_type);
        }

        // Araç Durumu (Sıfır / İkinci El)
        if ($request->has('condition') && $request->condition) {
            $query->where('condition', $request->condition);
        }

        // İlan Tarihi
        if ($request->has('ad_date') && $request->ad_date) {
            $dateFilter = $request->ad_date;
            
            switch ($dateFilter) {
                case '24h':
                    $query->where('created_at', '>=', now()->subHours(24));
                    break;
                case '3d':
                    $query->where('created_at', '>=', now()->subDays(3));
                    break;
                case '7d':
                    $query->where('created_at', '>=', now()->subDays(7));
                    break;
                case '30d':
                    $query->where('created_at', '>=', now()->subDays(30));
                    break;
            }
        }

        // Kelime ile Filtre
        if ($request->has('keyword') && $request->keyword) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhere('brand', 'like', "%{$keyword}%")
                  ->orWhere('model', 'like', "%{$keyword}%");
            });
        }

        // Sıralama
        $sortBy = $request->get('sort', 'newest');
        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'km_asc':
                $query->orderBy('kilometer', 'asc');
                break;
            case 'km_desc':
                $query->orderBy('kilometer', 'desc');
                break;
            case 'year_asc':
                $query->orderBy('year', 'asc');
                break;
            case 'year_desc':
                $query->orderBy('year', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $vehicles = $query->paginate(12);

        // Filtreler için veriler — markalar cache'leniyor
        $brands = Cache::remember('car_brands_all', 86400, fn() => CarBrand::orderBy('name')->get());

        $currentYear = (int) date('Y');
        $years = range($currentYear + 1, 1990);

        return view('pages.vehicles.index', compact('vehicles', 'brands', 'years'));
    }

    /**
     * Araç detay sayfası (slug ile; eski ID tabanlı kayıtlar için fallback)
     */
    public function show($slug)
    {
        // Önce slug ile ara (normal durum)
        $vehicle = Vehicle::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        // Slug eşleşmedi ve parametre sayısal ise ID ile dene (geriye dönük uyumluluk)
        if (!$vehicle && ctype_digit((string) $slug)) {
            $vehicle = Vehicle::where('id', $slug)
                ->where('is_active', true)
                ->first();

            // ID ile bulunduysa ve slug'ı varsa SEO için kalıcı yönlendir
            if ($vehicle && !empty($vehicle->slug)) {
                return redirect()->route('vehicles.show', $vehicle->slug, 301);
            }
        }

        if (!$vehicle) {
            abort(404);
        }

        // Görüntülenme sayacı — aynı session içinde 30 dakikada bir artırılır
        $sessionKey = 'viewed_vehicle_' . $vehicle->id;
        if (!session()->has($sessionKey)) {
            $vehicle->increment('views');
            session()->put($sessionKey, now()->timestamp);
        } else {
            $lastViewed = session()->get($sessionKey);
            if (now()->timestamp - $lastViewed > 1800) {
                $vehicle->increment('views');
                session()->put($sessionKey, now()->timestamp);
            }
        }

        // Benzer araçlar (aynı marka)
        $relatedVehicles = Vehicle::active()
            ->where('brand', $vehicle->brand)
            ->where('id', '!=', $vehicle->id)
            ->limit(4)
            ->get();

        // Donanımları katalog kategorilerine göre grupla
        $featureGroups = [];
        if (is_array($vehicle->features) && count($vehicle->features) > 0) {
            $catalog = Cache::remember('features_catalog_active', 86400, function () {
                return \App\Models\FeaturesCatalog::where('is_active', true)
                    ->orderBy('sort_order')
                    ->get()
                    ->keyBy('name');
            });

            $grouped = [];
            $orphans = [];
            foreach ($vehicle->features as $feat) {
                if (isset($catalog[$feat])) {
                    $grouped[$catalog[$feat]->category][] = $feat;
                } else {
                    $orphans[] = $feat;
                }
            }
            $featureGroups = $grouped;
            if (!empty($orphans)) {
                $featureGroups['Diğer'] = $orphans;
            }
        }

        return view('pages.vehicles.show', compact('vehicle', 'relatedVehicles', 'featureGroups'));
    }

}
