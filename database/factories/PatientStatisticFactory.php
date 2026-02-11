<?php
// database/factories/PatientStatisticFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use App\Models\Patient;

class PatientStatisticFactory extends Factory
{
    public function definition(): array
    {
        // Get the latest actual patient data
        $patients = Patient::all();

        if ($patients->count() > 0) {
            // Use real patient data if available
            $date = $this->faker->dateTimeBetween('-30 days', 'now');
            $targetDate = Carbon::parse($date);

            $patientsUpToDate = Patient::whereDate('registration_date', '<=', $targetDate)->get();

            $child = $patientsUpToDate->where('age', '<', 18)->count();
            $adult = $patientsUpToDate->whereBetween('age', [18, 64])->count();
            $elderly = $patientsUpToDate->where('age', '>=', 65)->count();
            $total = $patientsUpToDate->count();
        } else {
            // Fallback to generated data if no patients exist
            $child = $this->faker->numberBetween(2, 15);
            $adult = $this->faker->numberBetween(10, 40);
            $elderly = $this->faker->numberBetween(2, 20);
            $total = $child + $adult + $elderly;
            $date = $this->faker->dateTimeBetween('-30 days', 'now');
        }

        return [
            'date' => Carbon::parse($date)->format('Y-m-d'),
            'child_count' => $child,
            'adult_count' => $adult,
            'elderly_count' => $elderly,
            'total_patients' => $total,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Generate statistics for specific dates
     */
    public function forDateRange(Carbon $startDate, Carbon $endDate): Factory
    {
        return $this->state(function (array $attributes) use ($startDate, $endDate) {
            $date = $this->faker->dateTimeBetween($startDate, $endDate);
            $targetDate = Carbon::parse($date);

            // Get patients registered up to this date
            $patients = Patient::whereDate('registration_date', '<=', $targetDate)->get();

            $child = $patients->where('age', '<', 18)->count();
            $adult = $patients->whereBetween('age', [18, 64])->count();
            $elderly = $patients->where('age', '>=', 65)->count();
            $total = $patients->count();

            return [
                'date' => $targetDate->format('Y-m-d'),
                'child_count' => max(0, $child), // Ensure non-negative
                'adult_count' => max(0, $adult),
                'elderly_count' => max(0, $elderly),
                'total_patients' => max(0, $total),
            ];
        });
    }
}
