<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('domain_ping_batches', function (Blueprint $table) {
            $table->longText('failed_reason')->nullable();
        });
    }

    public function down()
    {
        Schema::table('domain_ping_batches', function (Blueprint $table) {
            $table->dropColumn('failed_reason');
        });
    }
};
