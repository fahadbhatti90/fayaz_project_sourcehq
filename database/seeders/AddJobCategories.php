<?php

namespace Database\Seeders;

use App\Models\business;
use App\Models\category;
use Illuminate\Database\Seeder;

class AddJobCategories extends Seeder
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
        category::truncate();
        $data = [
            [ 'business_id' => $business_id,'name' => 'IT Administrator','code' => 'IA','category_status' => 1],
            [ 'business_id' => $business_id,'name' => 'Account Manager','code' => 'AM','category_status' => 1],
            [ 'business_id' => $business_id,'name' => 'Sales manager','code' => 'SM','category_status' => 1],
            [ 'business_id' => $business_id,'name' => 'Designer','code' => 'D','category_status' => 1],
        ];
        $category = category::insert($data);
    }
}
