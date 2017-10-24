<?php

use App\Province;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $provinces = ['Ontario', 'Québec', 'Nova Scotia', 'New Brunswick', 'Manitoba',
            'British Columbia', 'Prince Edward Island', 'Saskatchewan', 'Alberta',
            'Newfoundland and Labrador', 'Northwest Territories', 'Yukon', 'Nunavut'
        ];

        foreach($provinces as $province){
            Province::create(['name' => $province]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        User::truncate();

        $usersQuantity = 230;

        factory(User::class, $usersQuantity)->create();

    }
}
