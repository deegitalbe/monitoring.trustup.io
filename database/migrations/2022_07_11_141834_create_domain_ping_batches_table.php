<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('domain_ping_batches', function (Blueprint $table) {
            $table->id();

            $table->boolean('failed')->default(0);
            $table->dateTime('started_at');
            $table->dateTime('finished_at')->nullable();
            $table->integer('domain_count');

            $table->string('job_batches_id')->nullable()->unique();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('domain_ping_batches');
    }
};
