<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Add patient_id column if it doesn't exist
            if (!Schema::hasColumn('appointments', 'patient_id')) {
                $table->foreignId('patient_id')->nullable()->after('id');
            }

            // Add patient_name column if it doesn't exist
            if (!Schema::hasColumn('appointments', 'patient_name')) {
                $table->string('patient_name')->nullable()->after('patient_id');
            }
        });
    }

    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['patient_id', 'patient_name']);
        });
    }
};