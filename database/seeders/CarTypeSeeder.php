<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CarTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('car_types')->truncate();
        DB::insert(
        	"INSERT INTO `car_types` (`id`, `name`, `color`, `hex_code`, `model`, `number_plate`,`year`) VALUES
		(1, 'Flash', NULL, NULL, NULL, NULL, NULL),
		(2, 'Flash Plus', NULL, NULL, NULL, NULL, NULL),
		(3, 'Flash Premium', NULL, NULL, NULL, NULL, NULL)
        ");
    }
}
