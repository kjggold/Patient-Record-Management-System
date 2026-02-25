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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('service_name');
            $table->integer('service_fee');
            $table->string('description');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE services AUTO_INCREMENT = 4001;');
    }

    /* Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
