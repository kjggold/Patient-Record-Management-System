<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition()
    {
        // Static variable to cache user IDs
        static $userIds = null;

        // Load all user IDs once
        if ($userIds === null) {
            $userIds = User::pluck('id')->toArray();

            // If no users exist, create one
            if (empty($userIds)) {
                $user = User::factory()->create();
                $userIds = [$user->id];
            }
        }

        $serviceTypes = ['Consultation', 'Procedure', 'Test', 'Therapy', 'Surgery', 'Checkup'];
        $serviceType = $this->faker->randomElement($serviceTypes);

        $serviceName = match($serviceType) {
            'Consultation' => $this->faker->randomElement(['General Consultation', 'Specialist Consultation', 'Follow-up Visit', 'Emergency Consultation']),
            'Procedure' => $this->faker->randomElement(['Minor Surgery', 'Endoscopy', 'Colonoscopy', 'Biopsy', 'Suturing']),
            'Test' => $this->faker->randomElement(['Blood Test', 'X-Ray', 'MRI Scan', 'CT Scan', 'Ultrasound', 'ECG', 'Urine Test']),
            'Therapy' => $this->faker->randomElement(['Physiotherapy', 'Occupational Therapy', 'Speech Therapy', 'Counseling Session']),
            'Surgery' => $this->faker->randomElement(['Appendectomy', 'Hernia Repair', 'Gallbladder Removal', 'Cataract Surgery']),
            'Checkup' => $this->faker->randomElement(['Annual Checkup', 'Pre-employment Checkup', 'School Checkup', 'Insurance Checkup']),
        };

        // Generate fee ending with 000 or 0000
        $fee = $this->generateRoundFee($serviceType);

        return [
            'service_name' => $serviceName,
            'service_fee' => $fee,
            'description' => $this->faker->sentence(10),
            'created_by' => $this->faker->randomElement($userIds),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Generate fees that end with 000 or 0000 based on service type
     */
    private function generateRoundFee(string $serviceType): string
    {
        // Define fee ranges for each service type
        $ranges = match($serviceType) {
            'Consultation' => [
                'min' => 5,
                'max' => 50,
                'multiplier' => 1000, // Ends with 000
            ],
            'Procedure' => [
                'min' => 20,
                'max' => 200,
                'multiplier' => 1000, // Ends with 000
            ],
            'Test' => [
                'min' => 10,
                'max' => 100,
                'multiplier' => 1000, // Ends with 000
            ],
            'Therapy' => [
                'min' => 15,
                'max' => 80,
                'multiplier' => 1000, // Ends with 000
            ],
            'Surgery' => [
                'min' => 100,
                'max' => 1000,
                'multiplier' => 1000, // Ends with 000
            ],
            'Checkup' => [
                'min' => 10,
                'max' => 50,
                'multiplier' => 1000, // Ends with 000
            ],
            default => [
                'min' => 5,
                'max' => 100,
                'multiplier' => 1000,
            ],
        };

        // 30% chance to end with 0000 instead of 000
        $multiplier = $this->faker->boolean(30) ? 10000 : $ranges['multiplier'];

        // Generate random amount within range
        $amount = $this->faker->numberBetween($ranges['min'], $ranges['max']);

        // Make sure the amount looks nice (e.g., 15, 20, 25, 30, etc.)
        if ($multiplier === 1000) {
            // For 000 endings, use multiples of 5 for nicer numbers
            $amount = round($amount / 5) * 5;
        } else {
            // For 0000 endings, use multiples of 10
            $amount = round($amount / 10) * 10;
        }

        return number_format($amount * $multiplier, 0, '', ',');
    }

    /**
     * Alternative: Generate only specific nice round numbers
     */
    private function generateNiceFee(string $serviceType): string
    {
        // Define nice round number options for each service type
        $niceNumbers = match($serviceType) {
            'Consultation' => [
                10000, 15000, 20000, 25000, 30000, 35000, 40000, 50000,
                80000, 100000, 150000, 200000 // Some 0000 endings
            ],
            'Procedure' => [
                20000, 25000, 30000, 35000, 40000, 50000, 60000, 75000, 80000, 100000,
                150000, 200000, 250000, 300000, 500000, 1000000 // Some 0000 endings
            ],
            'Test' => [
                10000, 15000, 20000, 25000, 30000, 35000, 40000, 50000,
                80000, 100000 // Some 0000 endings
            ],
            'Therapy' => [
                15000, 20000, 25000, 30000, 35000, 40000, 50000, 60000, 75000, 80000
            ],
            'Surgery' => [
                100000, 150000, 200000, 250000, 300000, 350000, 400000, 500000,
                750000, 1000000, 1500000, 2000000 // 0000 endings
            ],
            'Checkup' => [
                10000, 15000, 20000, 25000, 30000, 35000, 40000, 50000
            ],
            default => [
                10000, 15000, 20000, 25000, 30000, 35000, 40000, 50000,
                100000, 150000, 200000, 500000, 1000000
            ],
        };

        $fee = $this->faker->randomElement($niceNumbers);
        return number_format($fee, 0, '', ',');
    }

    // You can use either method by calling:
    // $fee = $this->generateRoundFee($serviceType); // For dynamic ranges
    // OR
    // $fee = $this->generateNiceFee($serviceType); // For predefined nice numbers
}
