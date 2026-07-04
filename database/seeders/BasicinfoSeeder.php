<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Basicinfo;

class BasicinfoSeeder extends Seeder
{
    public function run()
    {
        if (Basicinfo::count() === 0) {
            Basicinfo::create([
                'phone_one' => '+8809648156710',
                'phone_two' => '+8809648156710',
                'email' => 'support@ayebazar.com',
                'usd_rate' => '110',
                'logo' => null,
                'address' => 'A.H. Tower (5th Floor), H# G-71, Alokar Mor, Ghoramara Rajshahi, 6100',
                'app' => null,
                'copyright' => 'All rights reserved',
                'meta_tittle' => env('APP_NAME'),
                'meta_description' => 'Online Shopping in Bangladesh',
                'meta_keyword' => 'online shopping, ecommerce, bangladesh',
                'site_sologan' => env('APP_NAME'),
                'inside_dhaka_charge' => '80',
                'outside_dhaka_charge' => '130',
            ]);
        }
    }
}
