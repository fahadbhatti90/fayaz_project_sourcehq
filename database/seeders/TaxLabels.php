<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\tax_label;

class TaxLabels extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        tax_label::truncate();
        $tax_label = array(
            array('business_id' =>1 , 'label_name' => 'Label1', 'label_code' => 'la' , 'tax_label_status' => 1 ),
            array('business_id' =>1 , 'label_name' => 'Label2', 'label_code' => 'la' , 'tax_label_status' => 1 ),
            array('business_id' =>1 , 'label_name' => 'Label3', 'label_code' => 'la' , 'tax_label_status' => 1 ),
            array('business_id' =>1 , 'label_name' => 'Label4', 'label_code' => 'la' , 'tax_label_status' => 1 ),
            array('business_id' =>1 , 'label_name' => 'Label5', 'label_code' => 'la' , 'tax_label_status' => 1 ),
            array('business_id' =>1 , 'label_name' => 'Label6', 'label_code' => 'la' , 'tax_label_status' => 1 ),
        );
        $tax_labels = tax_label::insert($tax_label);
    }
}
