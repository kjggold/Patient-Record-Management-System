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
            $table->unsignedBigInteger('patient_name');
            $table->foreign('patient_name')
                ->references('id')
                ->on('patients')
                ->onDelete('cascade');

            // $table->foreignId('patient_id')->constrained()->onDelete('cascade');


            $table->unsignedBigInteger('doctor_name');
            $table->foreign('doctor_name')
                ->references('id')
                ->on('doctors')
                ->onDelete('cascade');

            // $table->foreignId('doctor_id')->constrained()->onDelete('cascade');


            $table->unsignedBigInteger('service');//fk
            $table->foreign('service')
                ->references('id')
                ->on('services')
                ->onDelete('cascade');

            // $table->foreignId('service_id')->constrained()->onDelete('cascade');


            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->string('phone');
            // $table->foreign('phone')
            //     ->references('phone_number')
            //     ->on('patients')
            //     ->onDelete('cascade');


            $table->string('notes_optional');
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