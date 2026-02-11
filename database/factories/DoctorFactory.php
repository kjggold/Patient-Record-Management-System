<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorFactory extends Factory
{
    public function definition(): array
    {
        // Generate Myanmar-style phone numbers
        $phoneNumber = '+9595' . $this->faker->numberBetween(4000000, 4999999);

        return [
            'full_name' => 'Dr. ' . $this->faker->firstName() . ' ' . $this->faker->lastName(),
            'speciality' => $this->faker->randomElement([
                'Cardiology', 'Neurology', 'Pediatrics', 'Orthopedics',
                'Dermatology', 'Oncology', 'Gynecology', 'Psychiatry',
                'Endocrinology', 'Gastroenterology'
            ]),
            'phone_number' => $phoneNumber,
            'email' => $this->faker->unique()->safeEmail(),
            'status' => 'active',
            'max_patients' => $this->faker->numberBetween(50, 150),
            // created_by and updated_by will be set in seeder
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    // Add methods for specific specialties
    public function cardiologist(): static
    {
        return $this->state(fn (array $attributes) => [
            'speciality' => 'Cardiology',
        ]);
    }

    public function neurologist(): static
    {
        return $this->state(fn (array $attributes) => [
            'speciality' => 'Neurology',
        ]);
    }

    public function pediatrician(): static
    {
        return $this->state(fn (array $attributes) => [
            'speciality' => 'Pediatrics',
        ]);
    }
}
