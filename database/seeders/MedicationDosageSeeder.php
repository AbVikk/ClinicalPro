<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DrugMg;

class MedicationDosageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Common medication dosages used in prescriptions
        $dosages = [
            '0.1mg', '0.25mg', '0.5mg', '1mg', '1.25mg', '1.5mg', '2mg', '2.5mg', '3mg', '4mg', '5mg', 
            '6mg', '7mg', '8mg', '9mg', '10mg', '12.5mg', '15mg', '20mg', '25mg', '30mg', '35mg', '40mg', 
            '50mg', '60mg', '70mg', '75mg', '80mg', '90mg', '100mg', '125mg', '150mg', '175mg', '200mg', 
            '225mg', '250mg', '300mg', '350mg', '375mg', '400mg', '450mg', '500mg', '550mg', '600mg', 
            '650mg', '700mg', '750mg', '800mg', '850mg', '900mg', '950mg', '1000mg', '1100mg', '1200mg', 
            '1300mg', '1400mg', '1500mg', '1600mg', '1700mg', '1800mg', '1900mg', '2000mg', '2500mg', 
            '3000mg', '4000mg', '5000mg'
        ];

        // Ensure we have dosage values in the database
        foreach ($dosages as $dosage) {
            DrugMg::updateOrCreate(
                ['mg_value' => $dosage],
                ['mg_value' => $dosage]
            );
        }
    }
}