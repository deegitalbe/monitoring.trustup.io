<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('domain_ping_batches', function (Blueprint $table) {
            $table->index(['started_at'], 'started_at_index');
        });
    }

    public function down()
    {
        Schema::table('domain_ping_batches', function (Blueprint $table) {
            $table->dropIndex('started_at_index');
        });
    }
};
