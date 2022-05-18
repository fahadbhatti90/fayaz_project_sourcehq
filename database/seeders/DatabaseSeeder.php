<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(CurrencySeeder::class);
        $this->call(GeneralBusinessSettings::class);
        $this->call(LanguageSeeder::class);
        $this->call(AddAllRoles::class);
        $this->call(CountriesStatesSeeder::class);
        $this->call(TaxLabels::class);
        $this->call(AddCompanyPositions::class);
        $this->call(AddJobCategories::class);
        $this->call(AddHireType::class);

    }
}
