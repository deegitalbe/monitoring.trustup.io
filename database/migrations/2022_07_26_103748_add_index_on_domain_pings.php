<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('domain_pings', function (Blueprint $table) {
            $table->index(['domain_ping_batch_id', 'status'], 'domain_ping_batch_id_status');
        });
    }

    public function down()
    {
        Schema::table('domain_pings', function (Blueprint $table) {
            $table->dropIndex('domain_ping_batch_id_status');
        });
    }
};
