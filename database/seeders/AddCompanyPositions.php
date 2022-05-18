<?php

namespace Database\Seeders;

use App\Models\business;
use App\Models\position;
use Illuminate\Database\Seeder;

class AddCompanyPositions extends Seeder
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
        position::truncate();
        $data = [
            [ 'business_id' => $business_id,'name' => 'IT Administrator','code' => 'IA','position_status' => 1],
            [ 'business_id' => $business_id,'name' => 'Account Manager','code' => 'AM','position_status' => 1],
            [ 'business_id' => $business_id,'name' => 'Sales manager','code' => 'SM','position_status' => 1],
            [ 'business_id' => $business_id,'name' => 'Designer','code' => 'D','position_status' => 1],
        ];
        $position = position::insert($data);
    }
}
