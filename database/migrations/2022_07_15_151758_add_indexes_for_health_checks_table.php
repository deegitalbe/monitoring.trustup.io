<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('health_checks', function (Blueprint $table) {
            $table->index(['finished_at', 'end_point_id']);
            $table->index(['name', 'finished_at', 'end_point_id']);
        });
    }

    public function down()
    {
        Schema::table('health_checks', function (Blueprint $table) {
            $table->dropIndex('health_checks_finished_at_end_point_id_index');
            $table->dropIndex('health_checks_name_finished_at_end_point_id_index');
        });
    }
};
