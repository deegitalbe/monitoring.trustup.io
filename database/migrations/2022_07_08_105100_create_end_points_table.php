<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('end_points', function (Blueprint $table) {
            $table->id();

            $table->dateTime('last_health_check_timestamp')->nullable();

            $table->boolean('is_monitored')->default(true);
            $table->boolean('is_staging')->default(false);
            $table->string('name');
            $table->string('url');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('end_points');
    }
};
