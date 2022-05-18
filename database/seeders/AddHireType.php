<?php

namespace Database\Seeders;

use App\Models\business;
use App\Models\hire_type;
use Illuminate\Database\Seeder;

class AddHireType extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $get_business_ids = business::first();
        $business_id = $get_business_ids->id;
        hire_type::truncate();
        $data = [
            [ 'business_id' => $business_id,'name' => 'Contract Hire (Third Party)','code' => 'CH','hire_type_status' => 1],
            [ 'business_id' => $business_id,'name' => 'Contract to Hire','code' => 'CTH','hire_type_status' => 1],
            [ 'business_id' => $business_id,'name' => 'Freelancer','code' => 'F','hire_type_status' => 1],
            [ 'business_id' => $business_id,'name' => 'Full Time Hire','code' => 'FTH','hire_type_status' => 1],
            [ 'business_id' => $business_id,'name' => 'Contract Hire','code' => 'CH','hire_type_status' => 1],
        ];
        $category = hire_type::insert($data);
    }
}
