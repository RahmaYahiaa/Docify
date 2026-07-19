<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClinicFactory extends Factory
{
    private array $clinicPrefixes = [
        'Clinic', 'Medical Center', 'Hospital', 'Health Center', 'Medical Complex',
    ];

    private array $clinicSuffixes = [
        'General Clinic',
        'Specialized Center',
        'Medical Center',
        'Health & Wellness',
        'Modern Clinic',
        'Healthcare Center',
        'Integrated Medical Center',
        'Medical Services',
    ];

    private array $egyptianCities = [
        'Cairo',
        'Giza',
        'Alexandria',
        'Mansoura',
        'Tanta',
        'Ismailia',
        'Port Said',
        'Suez',
        'Assiut',
        'Minya',
        'Fayoum',
        'Sohag',
        'Qena',
        'Aswan',
        'Hurghada',
    ];

    private array $egyptianStreets = [
        'Tahrir Street',
        'El Gomhoria Street',
        'El Salam Street',
        'Gamal Abdel Nasser Street',
        'Nile Street',
        'Pyramids Street',
        'Salah Salem Street',
        'Abbas El Akkad Street',
        'Corniche Street',
        'El Mahatta Street',
        'Bahr El Aazam Street',
        'El Thawra Street',
    ];

    public function definition(): array
    {
        $city = $this->faker->randomElement($this->egyptianCities);

        return [
            'name' => $this->faker->randomElement($this->clinicPrefixes)
                . ' '
                . $this->faker->lastName()
                . ' '
                . $this->faker->randomElement($this->clinicSuffixes),

            'address' => $this->faker->randomElement($this->egyptianStreets)
                . ' No. '
                . rand(1, 100),

            'city' => $city,

            'phone' => '0'
                . $this->faker->randomElement(['2', '3', '4', '5', '6', '8', '9'])
                . $this->faker->numerify('########'),
        ];
    }
}