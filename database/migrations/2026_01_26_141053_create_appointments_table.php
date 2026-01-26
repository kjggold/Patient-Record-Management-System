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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('patient_name');//fk
            $table->foreign('patient_name')
                ->references('full_name')
                ->on('patients')
                ->onDelete('cascade');

            $table->string('doctor_name');//fk
            $table->foreign('doctor_name')
                ->references('full_name')
                ->on('doctors')
                ->onDelete('cascade');

            $table->string('service');//fk
            $table->foreign('service')
                ->references('service_name')
                ->on('services')
                ->onDelete('cascade');

            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->string('phone');
            $table->foreign('phone')
                ->references('phone_number')
                ->on('patients')
                ->onDelete('cascade');

            $table->string('notes_optional');
            $table->string('status');
            $table->timestamps();
        });
    }

    /* Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};