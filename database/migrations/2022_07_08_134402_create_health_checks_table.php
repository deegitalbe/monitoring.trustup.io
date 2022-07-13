<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('health_checks', function (Blueprint $table) {
            $table->id();

            $table->dateTime('finished_at');
            $table->string('name');
            $table->string('label')->nullable();
            $table->text('notification_message')->nullable();
            $table->string('short_summary')->nullable();
            $table->string('status');
            $table->json('meta')->nullable();

            $table->foreignId('end_point_id')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('health_checks');
    }
};
