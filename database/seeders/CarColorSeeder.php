<?php

namespace Database\Seeders;

use App\Models\CarColor;
use Illuminate\Database\Seeder;

class CarColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            ['arabam_id' => 34, 'name' => 'Altın', 'sort_order' => 1],
            ['arabam_id' => 10, 'name' => 'Bej', 'sort_order' => 2],
            ['arabam_id' => 6,  'name' => 'Beyaz', 'sort_order' => 3],
            ['arabam_id' => 5,  'name' => 'Bordo', 'sort_order' => 4],
            ['arabam_id' => 14, 'name' => 'Füme', 'sort_order' => 5],
            ['arabam_id' => 42, 'name' => 'Gri', 'sort_order' => 6],
            ['arabam_id' => 12, 'name' => 'Gri (Gümüş)', 'sort_order' => 7],
            ['arabam_id' => 28, 'name' => 'Gri (Metalik)', 'sort_order' => 8],
            ['arabam_id' => 35, 'name' => 'Gri (Titanyum)', 'sort_order' => 9],
            ['arabam_id' => 13, 'name' => 'Kahverengi', 'sort_order' => 10],
            ['arabam_id' => 16, 'name' => 'Kırmızı', 'sort_order' => 11],
            ['arabam_id' => 8,  'name' => 'Lacivert', 'sort_order' => 12],
            ['arabam_id' => 40, 'name' => 'Mavi', 'sort_order' => 13],
            ['arabam_id' => 26, 'name' => 'Mavi (Metalik)', 'sort_order' => 14],
            ['arabam_id' => 7,  'name' => 'Mor', 'sort_order' => 15],
            ['arabam_id' => 4,  'name' => 'Sarı', 'sort_order' => 16],
            ['arabam_id' => 9,  'name' => 'Siyah', 'sort_order' => 17],
            ['arabam_id' => 30, 'name' => 'Şampanya', 'sort_order' => 18],
            ['arabam_id' => 39, 'name' => 'Turuncu', 'sort_order' => 19],
            ['arabam_id' => 1,  'name' => 'Yeşil', 'sort_order' => 20],
            ['arabam_id' => 24, 'name' => 'Yeşil (Metalik)', 'sort_order' => 21],
            ['arabam_id' => 41, 'name' => 'Diğer', 'sort_order' => 99],
        ];

        foreach ($colors as $color) {
            CarColor::updateOrCreate(
                ['arabam_id' => $color['arabam_id']],
                $color
            );
        }
    }
}
