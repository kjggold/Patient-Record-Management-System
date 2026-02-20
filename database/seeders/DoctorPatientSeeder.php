<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DoctorPatientSeeder extends Seeder
{
    private $usedPatientPhoneNumbers = [];
    private $usedDoctorPhoneNumbers = [];

    public function run(): void
    {

        // Get all admin users
        $admins = User::all();

        // All specialties from your sample data
        $specialties = [
            'Cardiology', 'Neurology', 'Pediatrics', 'Orthopedics', 'Dermatology',
            'Oncology', 'Gynecology', 'Psychiatry', 'Endocrinology', 'Gastroenterology',
            'Rheumatology', 'Urology', 'Ophthalmology', 'ENT', 'Pulmonology',
            'Nephrology', 'Hematology', 'Infectious Disease', 'Allergy & Immunology',
            'Cardiothoracic Surgery', 'Plastic Surgery', 'Neurosurgery', 'Geriatrics',
            'Sports Medicine', 'Family Medicine', 'Emergency Medicine', 'Physical Medicine',
            'Pain Management', 'Sleep Medicine', 'Preventive Medicine',
        ];

        // Create 100 doctors (created some time ago)
        $doctors = [];
        for ($i = 1; $i <= 100; $i++) {
            $specialtyIndex = ($i - 1) % count($specialties);
            $randomAdmin = $admins->random();
            $doctorPhone = $this->generateUniqueDoctorPhone();

            // Doctors created in the past (1-6 months ago)
            $doctorCreatedAt = Carbon::now()->subMonths(rand(1, 6))->subDays(rand(0, 30));

            $doctor = Doctor::create([
                'full_name' => 'Dr. ' . $this->generateRandomName(),
                'speciality' => $specialties[$specialtyIndex],
                'phone_number' => $doctorPhone,
                'email' => 'doctor' . $i . '@medical.com',
                'status' => rand(0, 1) ? 'Active' : 'On leave',
                'max_patients' => rand(10, 20),
                'created_by' => $randomAdmin->id,
                'updated_by' => null,
                'created_at' => $doctorCreatedAt,
                'updated_at' => $doctorCreatedAt,
            ]);

            $doctors[] = $doctor;
            $this->usedDoctorPhoneNumbers[] = $doctorPhone;
        }

        // Create patients for each doctor
        $totalPatients = 0;
        foreach ($doctors as $doctor) {
            // Random number of patients for each doctor (1-5)
            $patientCount = rand(1, 5);

                        $ageGroups = [
                        ['min' => 0, 'max' => 17, 'label' => 'child'],    // 0-17 years old
                        ['min' => 18, 'max' => 64, 'label' => 'adult'],   // 18-64 years old
                        ['min' => 65, 'max' => 90, 'label' => 'senior'],  // 65+ years old
                    ];
                    foreach ($ageGroups as $ageGroup) {
                        $randomAdmin = $admins->random();
                        $patientPhone = $this->generateUniquePatientPhone();

                        // Generate date of birth based on age group
                        $age = rand($ageGroup['min'], $ageGroup['max']);
                        $dateOfBirth = Carbon::now()->subYears($age)->subMonths(rand(0, 11))->subDays(rand(0, 30));

                        // BOTH registration_date AND created_at should be recent (last 10 days)
                        $recentDate = $this->generateRecentDate();

                        Patient::create([
                            'full_name' => $this->generateRandomName(),
                            'age' => $age,
                            'sex_gender' => rand(0, 1) ? 'Male' : 'Female',
                            'date_of_birth' => $dateOfBirth,
                            'phone_number' => $patientPhone,
                            'address' => $this->generateRandomAddress(),
                            'known_medical_conditions' => $this->randomMedicalCondition(),
                            'allergies' => $this->randomAllergy(),
                            'blood_type' => $this->randomBloodType(),
                            'alcohol_consumption' => $age < 18 ? 'None' : $this->randomAlcoholConsumption(),
                            'registration_date' => $recentDate, // Same as created_at
                            'created_by' => $randomAdmin->id,
                            'updated_by' => null,
                            'created_at' => $recentDate,
                            'updated_at' => $recentDate,
                        ]);

                        $totalPatients++;
                        $this->usedPatientPhoneNumbers[] = $patientPhone;
                    }
            }

            // Output success message
            $this->command->info('Seeded: ' . count($doctors) . ' doctors');
            $this->command->info('Total patients created: ' . $totalPatients);
            $this->command->info('Patient created_at dates: Within last 10 days');
        }

        private function generateUniqueDoctorPhone(): string
        {
            do {
                // Myanmar doctor phone format: +95955XXXXXX
                $prefixes = ['+9597', '+9599', '+9594'];
                $prefix = $prefixes[array_rand($prefixes)];
                $number = str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
                $phone = $prefix . $number;
            } while (in_array($phone, $this->usedDoctorPhoneNumbers));

            return $phone;
        }

        private function generateUniquePatientPhone(): string
        {
            do {
                // Myanmar patient phone format: +9597XXXXXXX or +9599XXXXXXX
                $prefixes = ['+9597', '+9599', '+9594'];
                $prefix = $prefixes[array_rand($prefixes)];
                $number = str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
                $phone = $prefix . $number;
            } while (in_array($phone, $this->usedPatientPhoneNumbers));

            return $phone;
        }
        private function generateRandomName(): string
    {
        $firstNames = [
            // Male
            'Aung', 'Kyaw', 'Zaw', 'Myint', 'Soe', 'Htun', 'Min', 'Lin', 'Win', 'Thant',
            'Tun', 'Naing', 'Moe', 'Thet', 'Kaung', 'Myo', 'Ye', 'Hla', 'Than', 'Ko',

            // Female
            'Hla', 'Khin', 'Thandar', 'Su', 'Nwe', 'Aye', 'Mya', 'Hnin', 'Yu', 'Pan',
            'Thazin', 'May', 'Myat', 'Phyu', 'Ei', 'Sandar', 'Wai', 'Thu', 'Zin', 'Cho'
        ];

        $lastNames = [
            // Common Burmese names (no family names traditionally, but these are used as surnames)
            'Aung', 'Kyaw', 'Win', 'Hlaing', 'Soe', 'Myint', 'Zaw', 'Moe', 'Thant', 'Naing',
            'Tun', 'Min', 'Htun', 'Lin', 'Thein', 'Than', 'Ye', 'Htet', 'Phyo', 'Sithu',

            // Some family names (less common but exist)
            'Maung', 'Bo', 'U', 'Daw', 'Saw', 'Mya', 'Khin', 'Nwe', 'Hnin', 'Mar'
        ];

        return $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
    }

    private function generateRandomAddress(): string
    {
        $streets = ['Main St', 'First Ave', 'Oak Rd', 'Maple Dr', 'Elm St', 'Park Ave', 'Cedar Ln'];
        $cities = ['Yangon', 'Mandalay', 'Naypyidaw', 'Bago', 'Mawlamyine', 'Taunggyi', 'Monywa'];

        return rand(100, 9999) . ' ' . $streets[array_rand($streets)] . ', ' .
               $cities[array_rand($cities)] . ', Myanmar';
    }

    private function randomMedicalCondition(): string
    {
        $conditions = [
            'None',
            'Hypertension',
            'Diabetes Type 2',
            'Asthma',
            'High Cholesterol',
            'Migraines',
            'Arthritis',
            'GERD',
            'Depression',
            'Anxiety'
        ];

        return $conditions[array_rand($conditions)];
    }

    private function randomAllergy(): string
    {
        $allergies = [
            'None',
            'Penicillin',
            'Dust Mites',
            'Pollen',
            'Shellfish',
            'Peanuts',
            'Latex',
            'Sulfa Drugs',
            'Eggs',
            'Milk'
        ];

        return $allergies[array_rand($allergies)];
    }

    private function randomBloodType(): string
    {
        $bloodTypes = ['A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-'];
        return $bloodTypes[array_rand($bloodTypes)];
    }

    private function randomAlcoholConsumption(): string
    {
        $consumption = ['None', 'Occasional','Regular'];
        return $consumption[array_rand($consumption)];
    }

    /**
     * Generate a recent date within the last 10 days (including today)
     * Random time between 8 AM and 6 PM
     */
    private function generateRecentDate(): Carbon
    {
        // Random day offset (0-10 days ago, where 0 is today)
        $daysAgo = rand(0, 10);

        // Start with today and subtract days
        $date = Carbon::now()->subDays($daysAgo);

        // Random time between 8:00 AM and 6:00 PM (clinic hours)
        $hour = rand(8, 17); // 8 AM to 5 PM (17 = 5 PM)
        $minute = rand(0, 59);
        $second = rand(0, 59);

        return $date->setTime($hour, $minute, $second);
    }
}