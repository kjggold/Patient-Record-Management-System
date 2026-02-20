<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('discharges', function (Blueprint $table) {
    $table->id(); // This creates an auto-increment 'id' column
    // OR, if you had $table->integer('discharge_id'); change it to:
    // $table->id('discharge_id'); // this makes it auto-increment primary key
    $table->integer('appointment_id');
    $table->string('patient_name');
    $table->string('doctor_name');
    $table->json('services');
    $table->decimal('total', 12, 2);
    $table->decimal('discount', 12, 2)->default(0);
    $table->decimal('paid', 12, 2)->default(0);
    $table->decimal('balance', 12, 2)->default(0);
    $table->timestamps();
});


        // Set auto increment starting value (optional)
        DB::statement("ALTER TABLE discharges AUTO_INCREMENT = 1");
    }

    public function down()
    {
        Schema::dropIfExists('discharges');
    }
};