<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



return new class extends Migration
{
    /* Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('national_id_passport')->unique();
            $table->integer('age');
            $table->string('sex_gender');
            $table->integer('date_of_birth_day');
            $table->integer('date_of_birth_month');
            $table->integer('date_of_birth_year');
            $table->string('phone_number')->unique();
            $table->string('address');
            $table->string('known_medical_conditions')->default('None');;
            $table->string('allergies')->default('None');
            $table->string('blood_type');
            $table->string('alcohol_consumption');
            
            // Change this line: use 'assigned_doctor' as the column name
            $table->unsignedBigInteger('assigned_doctor');
            
            // Foreign key constraint pointing to doctors.id
            $table->foreign('assigned_doctor')
                ->references('id')
                ->on('doctors')
                ->onDelete('cascade');

            $table->date('registration_date');
            $table->timestamps();
        });
    }

    /* Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};