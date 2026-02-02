<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Doctor;


class ServiceController extends Controller
{
    public function index()
    {
        // Get services from database for the table
        $services = Service::all();

        // Get clinic services data without relationships
        $clinicServices = $this->getClinicServicesData();

        return view('services', compact('services', 'clinicServices'));
    }

    private function getClinicServicesData()
    {
        // Get all doctors
        $doctors = Doctor::all();

        // Define clinic services with static illness data
        // You can fetch this from a separate table if you have one
        // For now, we'll use static data
        $clinicServices = [
            [
                'name' => 'General Consultation',
                'description' => 'Comprehensive general medical consultations for common health concerns',
                'illnesses' => ['Fever & Flu', 'Headache', 'Body Pain', 'Cold & Cough', 'Stomach Issues', 'Allergies', 'Skin Issues', 'Fatigue'],
                // Filter doctors by speciality or get all general medicine doctors
                'doctors' => $this->getDoctorsBySpeciality($doctors, ['General Medicine', 'Family Medicine', 'Internal Medicine'])
            ],
            [
                'name' => 'Heart & Cardiology',
                'description' => 'Cardiac care and heart-related health services',
                'illnesses' => ['Hypertension', 'Heart Disease', 'Cholesterol', 'Arrhythmia', 'Chest Pain', 'Palpitations', 'Edema', 'Shortness of Breath'],
                'doctors' => $this->getDoctorsBySpeciality($doctors, ['Cardiology', 'Cardiothoracic'])
            ],
            [
                'name' => 'Child Health (Paediatrics)',
                'description' => 'Specialized care for children\'s health and development',
                'illnesses' => ['Child Fever', 'Cough & Cold', 'Vaccination', 'Growth Issues', 'Nutrition', 'Allergies', 'Skin Rashes', 'Behavioral Issues'],
                'doctors' => $this->getDoctorsBySpeciality($doctors, ['Pediatrics', 'Child Health'])
            ],
            [
                'name' => 'Women\'s Health',
                'description' => 'Comprehensive women\'s health and obstetric care',
                'illnesses' => ['Pregnancy Care', 'Menstrual Disorders', 'Menopause', 'Fertility Issues', 'PCOS', 'Breast Health', 'Contraception', 'Pap Smear'],
                'doctors' => $this->getDoctorsBySpeciality($doctors, ['Gynecology', 'Obstetrics', 'Women\'s Health'])
            ],
            [
                'name' => 'Diabetes & Endocrinology',
                'description' => 'Diabetes management and hormonal health',
                'illnesses' => ['Type 2 Diabetes', 'Blood Sugar Control', 'Thyroid Disorders', 'Hormonal Imbalance', 'Obesity', 'Metabolic Syndrome'],
                'doctors' => $this->getDoctorsBySpeciality($doctors, ['Endocrinology', 'Diabetes Care'])
            ],
            [
                'name' => 'Neurology',
                'description' => 'Brain and nervous system related consultations',
                'illnesses' => ['Migraine', 'Epilepsy', 'Stroke', 'Parkinson\'s', 'Neuropathy', 'Memory Issues', 'Dizziness', 'Sleep Disorders'],
                'doctors' => $this->getDoctorsBySpeciality($doctors, ['Neurology', 'Neurosurgery'])
            ],
            [
                'name' => 'Orthopedics',
                'description' => 'Bone, joint and muscle related treatments',
                'illnesses' => ['Joint Pain', 'Back Pain', 'Fractures', 'Arthritis', 'Sports Injuries', 'Osteoporosis', 'Muscle Pain', 'Sprains'],
                'doctors' => $this->getDoctorsBySpeciality($doctors, ['Orthopedics', 'Sports Medicine'])
            ],
            [
                'name' => 'Dermatology',
                'description' => 'Skin, hair and nail related health services',
                'illnesses' => ['Eczema', 'Psoriasis', 'Acne', 'Skin Infections', 'Allergic Reactions', 'Hair Loss', 'Nail Issues', 'Skin Cancer'],
                'doctors' => $this->getDoctorsBySpeciality($doctors, ['Dermatology'])
            ],
            [
                'name' => 'Mental Health',
                'description' => 'Psychological and psychiatric consultations',
                'illnesses' => ['Anxiety', 'Depression', 'Stress', 'Bipolar Disorder', 'OCD', 'PTSD', 'Eating Disorders', 'Addiction'],
                'doctors' => $this->getDoctorsBySpeciality($doctors, ['Psychiatry', 'Psychology', 'Mental Health'])
            ],
            [
                'name' => 'Gastroenterology',
                'description' => 'Digestive system and gastrointestinal care',
                'illnesses' => ['Stomach Pain', 'Acid Reflux', 'Ulcers', 'IBD', 'Liver Issues', 'Constipation', 'Diarrhea', 'Gallstones'],
                'doctors' => $this->getDoctorsBySpeciality($doctors, ['Gastroenterology'])
            ]
        ];

        return $clinicServices;
    }

    private function getDoctorsBySpeciality($doctors, array $specialities)
    {
        return $doctors
            ->filter(function($doctor) use ($specialities) {
                // Check if doctor's speciality matches any in the array
                foreach ($specialities as $speciality) {
                    if (stripos($doctor->speciality, $speciality) !== false) {
                        return true;
                    }
                }
                return false;
            })
            ->take(3) // Limit to 3 doctors per service
            ->map(function($doctor) {
                return [
                    'id' => $doctor->id,
                    'name' => $doctor->name,
                    'speciality' => $doctor->speciality,
                    'qualification' => $doctor->qualification ?? 'MD Specialist',
                    'fee' => $doctor->consultation_fee ? $doctor->consultation_fee . ' MMK' : 'Contact for price',
                    'experience' => $doctor->experience ?? 'Not specified',
                    'availability' => $this->getAvailability($doctor->speciality),
                    'rating' => $this->getRating($doctor->speciality)
                ];
            })
            ->values()
            ->toArray();
    }

    private function getAvailability($speciality)
    {
        // You can store availability in the doctors table or use this mapping
        $availabilityMap = [
            'Cardiology' => 'Mon-Fri: 10AM-6PM',
            'Pediatrics' => 'Mon-Sat: 8AM-4PM',
            'Gynecology' => 'Mon-Fri: 8AM-4PM',
            'Emergency' => '24/7',
            'default' => 'Mon-Fri: 9AM-5PM'
        ];

        foreach ($availabilityMap as $key => $value) {
            if (stripos($speciality, $key) !== false) {
                return $value;
            }
        }

        return $availabilityMap['default'];
    }

    private function getRating($speciality)
    {
        // You can add ratings to your doctors table or use this
        // For now, return a static rating based on speciality
        $ratingMap = [
            'Pediatrics' => '4.9',
            'Cardiology' => '4.8',
            'Gynecology' => '4.8',
            'Neurology' => '4.8',
            'default' => '4.7'
        ];

        foreach ($ratingMap as $key => $value) {
            if (stripos($speciality, $key) !== false) {
                return $value;
            }
        }

        return $ratingMap['default'];
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_name' => 'required|string|max:255',
            'service_fee' => 'required|string|max:100',
            'description' => 'nullable|string'
        ]);

        Service::create($validated);

        return redirect()->route('services.index')
            ->with('success', 'Service added successfully!');
    }
}
