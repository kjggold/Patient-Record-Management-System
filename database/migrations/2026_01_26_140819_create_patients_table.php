<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;



return new class extends Migration
{
    /* Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->integer('age');
            $table->string('sex_gender');
            $table->date('date_of_birth');
            $table->string('phone_number')->unique();
            $table->string('address');
            $table->string('known_medical_conditions')->default('None');;
            $table->string('allergies')->default('None');
            $table->string('blood_type');
            $table->string('alcohol_consumption');

            $table->date('registration_date');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE patients AUTO_INCREMENT = 2001;');
    }

    /* Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
