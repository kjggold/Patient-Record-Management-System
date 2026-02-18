<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add to doctors table
        Schema::table('doctors', function (Blueprint $table) {
            if (!Schema::hasColumn('doctors', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('status')->constrained('users')->onDelete('set null');
                $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->onDelete('set null');
            }
        });

        // Add to patients table
        Schema::table('patients', function (Blueprint $table) {
            if (!Schema::hasColumn('patients', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('registration_date')->constrained('users')->onDelete('set null');
                $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->onDelete('set null');
            }
        });

        // Add to services table
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('description')->constrained('users')->onDelete('set null');
                $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->onDelete('set null');
            }
        });

        // Add to appointments table - REMOVE THE RENAMECOLUMN LINES
        Schema::table('appointments', function (Blueprint $table) {
            if (!Schema::hasColumn('appointments', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('appointment_date')->constrained('users')->onDelete('set null');
                $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->onDelete('set null');
                // REMOVE THESE LINES - columns already exist as foreign keys
                // $table->renameColumn('patient_name', 'patient_id');
                // $table->renameColumn('doctor_name', 'doctor_id');
                // $table->renameColumn('service', 'service_id');
            }
        });

        // Add to discharges table
        Schema::table('discharges', function (Blueprint $table) {
            if (!Schema::hasColumn('discharges', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('balance')->constrained('users')->onDelete('set null');
                $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        // Remove from discharges table
        Schema::table('discharges', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['created_by', 'updated_by']);
        });

        // Remove from services table
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['created_by', 'updated_by']);
        });

        // Remove from patients table
        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['created_by', 'updated_by']);
        });

        // Remove from doctors table
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['created_by', 'updated_by']);
        });
    }
};
