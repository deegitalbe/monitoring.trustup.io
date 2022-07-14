<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('end_points', function (Blueprint $table) {
            $table->boolean('ping_default_url')->default(false);
        });
    }

    public function down()
    {
        Schema::table('end_points', function (Blueprint $table) {
            $table->dropColumn('ping_default_url');
        });
    }
};
