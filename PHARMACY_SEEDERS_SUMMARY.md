# Pharmacy Seeders Summary

## Overview
This document describes the new seeders created for the pharmacy system to populate the database with comprehensive medication data including categories, strengths, and medications.

## Seeders Created

### 1. DrugCategoriesTableSeeder
Populates the `drug_categories` table with 25 common medication categories:
- Analgesics
- Antibiotics
- Antivirals
- Antifungals
- Antihypertensives
- Antidiabetics
- Antidepressants
- Antihistamines
- Bronchodilators
- Diuretics
- Anticoagulants
- Antiplatelets
- Anticonvulsants
- Antipsychotics
- Anxiolytics
- Sedatives
- Stimulants
- Hormones
- Immunosuppressants
- Vaccines
- Gastrointestinal
- Cardiovascular
- Respiratory
- Neurological
- Endocrine

### 2. DrugMgTableSeeder
Populates the `drug_mg` table with 28 common medication strengths ranging from:
- 0.5mg to 2000mg

### 3. DrugsTableSeeder
Populates the `drugs` table with 20 common medications across various categories:
- Paracetamol (Analgesics)
- Ibuprofen (Analgesics)
- Aspirin (Analgesics)
- Morphine (Analgesics - Controlled)
- Amoxicillin (Antibiotics)
- Azithromycin (Antibiotics)
- Ciprofloxacin (Antibiotics)
- Lisinopril (Antihypertensives)
- Amlodipine (Antihypertensives)
- Losartan (Antihypertensives)
- Metformin (Antidiabetics)
- Insulin Glargine (Antidiabetics - Controlled)
- Glimepiride (Antidiabetics)
- Atorvastatin (Cardiovascular)
- Clopidogrel (Cardiovascular)
- Salbutamol (Respiratory)
- Fluticasone (Respiratory)
- Levothyroxine (Endocrine)
- Sertraline (Antidepressants)
- Loratadine (Antihistamines)

## Database Structure

### drug_categories Table
- id (Primary Key)
- name (Unique)
- description
- timestamps

### drug_mg Table
- id (Primary Key)
- mg_value (Unique, e.g., '500mg', '200mg', '10mg')
- timestamps

### drugs Table
- id (Primary Key)
- name
- category (Foreign key to drug_categories.name)
- strength_mg (Foreign key to drug_mg.mg_value)
- unit_price (Decimal)
- is_controlled (Boolean)
- timestamps

## Usage

To seed the database with the new pharmacy data, run:
```bash
php artisan db:seed
```

This will populate all three tables with comprehensive medication data that can be used in the admin pharmacy interface.

## Relationships

The models have the following relationships:
- Drug belongs to DrugCategory (via category field)
- Drug belongs to DrugMg (via strength_mg field)
- DrugCategory has many Drugs
- DrugMg has many Drugs

This structure allows for flexible querying and management of medications in the pharmacy system.