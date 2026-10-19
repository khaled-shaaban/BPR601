<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Authority;
use Illuminate\Database\Seeder;

class AuthoritySeeder extends Seeder
{
    /**
     * Seed a baseline set of emergency authorities (police, civil defence, ambulance)
     * in the first area of every governorate. Sample/demo data — skipped while testing.
     */
    public function run(): void
    {
        $templates = [
            ['name' => 'مركز الشرطة', 'suffix' => 'POL', 'icon' => 'shield', 'color' => '1E40AF', 'phone' => '112'],
            ['name' => 'الدفاع المدني', 'suffix' => 'FIRE', 'icon' => 'flame', 'color' => 'DC2626', 'phone' => '113'],
            ['name' => 'الإسعاف', 'suffix' => 'MED', 'icon' => 'ambulance', 'color' => '059669', 'phone' => '110'],
        ];

        Area::query()->get()->groupBy('city_id')->each(function ($areas) use ($templates) {
            $area = $areas->first();

            foreach ($templates as $template) {
                Authority::updateOrCreate(
                    ['code' => $area->code . '_' . $template['suffix']],
                    [
                        'name' => $template['name'],
                        'description' => $template['name'] . ' - ' . $area->name,
                        'icon' => $template['icon'],
                        'color' => $template['color'],
                        'area_id' => $area->id,
                        'contact_phone_1' => $template['phone'],
                        'active' => true,
                    ],
                );
            }
        });
    }
}
