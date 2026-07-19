<?php

namespace Database\Factories\User;

use App\Models\Clinic;
use App\Models\DoctorProfile;
use App\Models\Specialization;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    // Egyptian male first names
  // Egyptian male first names
private array $maleNames = [
    'Mohamed', 'Ahmed', 'Ali', 'Omar', 'Khaled', 'Youssef', 'Hassan', 'Hussein', 'Mostafa', 'Ibrahim',
    'Abdelrahman', 'Tarek', 'Walid', 'Maged', 'Samy', 'Karim', 'Belal', 'Anwar', 'Amr', 'Islam',
];

// Egyptian female first names
private array $femaleNames = [
    'Fatma', 'Mariam', 'Sara', 'Nour', 'Heba', 'Rania', 'Dina', 'Yasmine', 'Reem', 'Mona',
    'Shaimaa', 'Noha', 'Lamia', 'Asmaa', 'Eman', 'Samar', 'Nadia', 'Engy', 'Hoda', 'Nevine',
];

// Egyptian last names
private array $lastNames = [
    'Mahmoud', 'Elsayed', 'Hussein', 'Abdallah', 'Rashad', 'Bakr', 'Omar', 'Taher', 'Fathy', 'Kamal',
    'Saleh', 'Gaber', 'Khalil', 'Awad', 'Hassan', 'Mounir', 'Nasr', 'Aziz', 'El Sharkawy', 'Ragab',
    'Makram', 'Helmy', 'Zidan', 'Badr', 'Tawfik', 'El Mansouri', 'El Shafie', 'Mansour', 'Ibrahim', 'El Gohary',
];

    public function definition(): array
    {
        $isMale    = $this->faker->boolean;
        $firstName = $isMale
            ? $this->maleNames[array_rand($this->maleNames)]
            : $this->femaleNames[array_rand($this->femaleNames)];

        $lastName = $this->lastNames[array_rand($this->lastNames)];

        return [
            'first_name'        => $firstName,
            'last_name'         => $lastName,
            'email'             => $this->faker->unique()->safeEmail,
            'phone'             => '01' . $this->faker->randomElement(['0', '1', '2', '5']) . $this->faker->numerify('########'),
            'status'            => 'active',
            'email_verified_at' => now(),
            'password'          => bcrypt('password'),
            'remember_token'    => Str::random(10),
        ];
    }

    public function doctor()
    {
        return $this->state(fn (array $attributes) => [])->afterCreating(function (User $user) {
            $user->assignRole('doctor');

            DoctorProfile::create([
                'user_id'             => $user->id,
                'specialization_id'   => Specialization::inRandomOrder()->first()?->id,
                'clinic_id'           => Clinic::inRandomOrder()->first()?->id,
                'about'               => $this->faker->paragraph(3),
                'years_of_experience' => $this->faker->numberBetween(3, 25),
                'video_fee'           => $this->faker->randomElement([150, 200, 250, 300, 350, 400]),
                'in_person_fee'       => $this->faker->randomElement([250, 300, 400, 500, 600]),
                'languages'           => $this->faker->randomElements(['Arabic', 'English', 'French'], rand(1, 2)),
            ]);
        });
    }

    public function patient()
    {
        return $this->state([])->afterCreating(function (User $user) {
            $user->assignRole('patient');
        });
    }
}