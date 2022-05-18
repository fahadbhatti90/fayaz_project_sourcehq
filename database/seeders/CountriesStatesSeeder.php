<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\countries;
use App\Models\states;

class CountriesStatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        countries::truncate();
        states::truncate();
        $path=  storage_path('app/public/json/CountriesStates.json');
        $countries_states_array = json_decode(file_get_contents($path), true);
        $countries = $countries_states_array['countries'];
        foreach ($countries as $single_country){
            $country = $single_country['country'];
            $countriesDataArray = array(
                "country_name" => $country
            );
            $countries = countries::create($countriesDataArray);
            $country_id = $countries->id;
            $states = $single_country['states'];
            foreach ($states as $state){
                $statesDataArray = array(
                    "fk_country_id" => $country_id,
                    "state_name" => $state
                );
                $states = states::create($statesDataArray);
            }
        }
    }
}
