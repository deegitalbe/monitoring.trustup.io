<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('domain_pings', function (Blueprint $table) {
            $table->longText('status_reason')->nullable();
        });
    }

    public function down()
    {
        Schema::table('domain_pings', function (Blueprint $table) {
            $table->dropColumn('status_reason');
        });
    }
};
