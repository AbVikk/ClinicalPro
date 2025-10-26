<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DrugMg;

class DrugMgTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mgValues = [
            ['mg_value' => '0.5mg'],
            ['mg_value' => '1mg'],
            ['mg_value' => '2mg'],
            ['mg_value' => '5mg'],
            ['mg_value' => '10mg'],
            ['mg_value' => '15mg'],
            ['mg_value' => '20mg'],
            ['mg_value' => '25mg'],
            ['mg_value' => '30mg'],
            ['mg_value' => '40mg'],
            ['mg_value' => '50mg'],
            ['mg_value' => '75mg'],
            ['mg_value' => '100mg'],
            ['mg_value' => '125mg'],
            ['mg_value' => '150mg'],
            ['mg_value' => '200mg'],
            ['mg_value' => '250mg'],
            ['mg_value' => '300mg'],
            ['mg_value' => '375mg'],
            ['mg_value' => '400mg'],
            ['mg_value' => '500mg'],
            ['mg_value' => '600mg'],
            ['mg_value' => '750mg'],
            ['mg_value' => '800mg'],
            ['mg_value' => '1000mg'],
            ['mg_value' => '1200mg'],
            ['mg_value' => '1500mg'],
            ['mg_value' => '2000mg']
        ];

        foreach ($mgValues as $mg) {
            DrugMg::updateOrCreate(
                ['mg_value' => $mg['mg_value']],
                $mg
            );
        }
    }
}