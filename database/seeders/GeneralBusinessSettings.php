<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\general_business_settings;

class GeneralBusinessSettings extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        general_business_settings::truncate();
        $data = [
            [ 'label_name' => 'Global Assets','business_setting_label_status' => 1],
            ['label_name' => 'Trail Blazers', 'business_setting_label_status' => 1],
            ['label_name' => 'Trail Blazers','business_setting_label_status' => 1],
        ];
        $general_business_settings = general_business_settings::insert($data);
    }
}
