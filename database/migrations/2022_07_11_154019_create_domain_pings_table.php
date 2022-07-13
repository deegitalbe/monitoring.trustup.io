<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('domain_pings', function (Blueprint $table) {
            $table->id();

            $table->string('url');
            $table->integer('status');
            $table->float('answer_time_ms')->nullable();
            $table->string('dns_a')->nullable();
            $table->foreignId('domain_ping_batch_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('domain_pings');
    }
};
