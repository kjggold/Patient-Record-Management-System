<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PatientFactory extends Factory
{
    public function definition(): array
    {
        $dateOfBirth = $this->faker->dateTimeBetween('-90 years', '-1 year');
        $age = Carbon::now()->diffInYears($dateOfBirth);

        // Generate Myanmar-style phone numbers
        $phoneNumber = '+9597' . $this->faker->numberBetween(9000000, 9999999);

        return [
            'full_name' => $this->faker->name(),
            'age' => $age,
            'sex_gender' => $this->faker->randomElement(['Male', 'Female']),
            'date_of_birth' => $dateOfBirth,
            'phone_number' => $phoneNumber,
            'address' => $this->faker->address(),
            'known_medical_conditions' => $this->faker->randomElement([
                'None', 'Hypertension', 'Diabetes Type 2', 'Asthma',
                'High Cholesterol', 'Migraines', 'Arthritis'
            ]),
            'allergies' => $this->faker->randomElement([
                'None', 'Penicillin', 'Dust Mites', 'Pollen',
                'Shellfish', 'Peanuts', 'Latex'
            ]),
            'blood_type' => $this->faker->randomElement(['A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-']),
            'alcohol_consumption' => $this->faker->randomElement(['None', 'Occasional', 'Social drinker']),
            'assigned_doctor' => null, // Will be set in seeder
            'registration_date' => $this->faker->dateTimeBetween('-2 years', 'now'),
            // created_by and updated_by will be set in seeder
        ];
    }
}
