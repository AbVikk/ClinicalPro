<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DrugMg;

class DrugMgSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mgValues = [
            ['mg_value' => '5mg'],
            ['mg_value' => '10mg'],
            ['mg_value' => '25mg'],
            ['mg_value' => '50mg'],
            ['mg_value' => '100mg'],
            ['mg_value' => '200mg'],
            ['mg_value' => '250mg'],
            ['mg_value' => '500mg'],
            ['mg_value' => '750mg'],
            ['mg_value' => '1000mg'],
        ];

        foreach ($mgValues as $mg) {
            DrugMg::updateOrCreate(
                ['mg_value' => $mg['mg_value']],
                $mg
            );
        }
    }
}