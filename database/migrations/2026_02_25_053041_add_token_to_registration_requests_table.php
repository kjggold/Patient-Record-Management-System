<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddTokenToRegistrationRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('registration_requests', function (Blueprint $table) {
            $table->string('approval_token')->nullable()->unique()->after('user_agent');
            $table->string('status')->default('pending')->after('approval_token');
        });
    }

    public function down()
    {
        Schema::table('registration_requests', function (Blueprint $table) {
            $table->dropColumn(['approval_token', 'status']);
        });
    }
}