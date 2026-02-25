<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceColumnsToDischargesTable extends Migration
{
    public function up()
    {
        Schema::table('discharges', function (Blueprint $table) {
            $table->unsignedBigInteger('service_id')->nullable()->after('doctor_name');
            $table->string('service_name')->nullable()->after('service_id');
            $table->decimal('service_price', 10, 2)->default(0)->after('service_name');
        });
    }

    public function down()
    {
        Schema::table('discharges', function (Blueprint $table) {
            $table->dropColumn(['service_id', 'service_name', 'service_price']);
        });
    }
}
