<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SearchProfile;
use Faker\Factory as Faker;
use App\Models\Property;

class DatabaseSeeder extends Seeder
{
    protected $numberOfSearchProfilesToCreate = 1000;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        Property::truncate();
        SearchProfile::truncate();

        for ($i = 0; $i < 100; $i++) {

            $city = $faker->city;
            $property_type_id = uuid();
            $property_adjective = $faker->randomElement(['Awesome', 'Beautiful']);
            $property_type = $faker->randomElement(['house', 'villa']);
            $area = (int) round($faker->numberBetween(100, 500), -1); //make sure area is like 200, 250 and not 269, 284 etc
            $name = "$property_adjective {$property_type} in {$city}";
            $address = $faker->streetAddress.', '.$city; 

            $property = Property::create([
                'name' => $name,
                'address' => $address,
                'property_type' => $property_type_id,
                'fields' => [
                    "area" => $area,
                    "year_of_construction" => $faker->year(),
                    "rooms" => $faker->numberBetween(1, 6),
                    "heating_type" => $faker->randomElement(['gas', 'electricty']),
                    "parking_available" => $faker->boolean(50),
                    "return_actual" => $faker->randomFloat(1, 0, 100),
                    "price" => $faker->numberBetween(100000, 5000000)
                ]
            ]);

            $area_min = $faker->boolean(70) ? (int) round($faker->numberBetween(100, 500), -1) : null;
            $area_max = $faker->boolean(60) ? (int) round($faker->numberBetween($area_min ?? 100, 500), -1) : null;

            $year_of_construction_min = $faker->boolean(70) ? $faker->year() : null;
            $year_of_construction_max = $faker->boolean(50) ? $faker->year() : null;
            $year_of_construction_max = $year_of_construction_max > $year_of_construction_min ? $year_of_construction_max : null;

            $rooms_min = $faker->boolean(70) ? $faker->numberBetween(1, 6) : null;
            $rooms_max = $faker->boolean(50) ? $faker->numberBetween($rooms_min, 6) : null;

            $return_actual_min = $faker->boolean(70) ? $faker->randomFloat(1, 0, 100) : null;
            $return_actual_max = $faker->boolean(50) ? $faker->randomFloat(1, $return_actual_min, 100) : null;

            $price_min = $faker->boolean(70) ? $faker->numberBetween(100000, 5000000) : null;
            $price_max = $faker->boolean(60) ? $faker->numberBetween($price_min, 5000000) : null;

            for ($j = 0; $j < $this->numberOfSearchProfilesToCreate; $j++) {

                $property = SearchProfile::create([
                    'name' => $faker->sentence(5),
                    'property_type' => $property_type_id,
                    'search_fields' => [
                        "area" => [$area_min, $area_max],
                        "year_of_construction" => [$year_of_construction_min, $year_of_construction_max],
                        "rooms" => [$rooms_min, $rooms_max],
                        "heating_type" => $faker->randomElement(['gas', 'electricty']),
                        "parking_available" => $faker->boolean(70),
                        "return_actual" => [$return_actual_min, $return_actual_max],
                        "price" => [$price_min, $price_max]
                    ]
                ]);
            
            }

        }

    }
}
