<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_configs', function (Blueprint $table) {
            $table->string('lenta_host')->nullable();
            $table->string('lenta_agent')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_configs', function (Blueprint $table) {
            $table->dropColumn('lenta_host');
        });
        Schema::table('project_configs', function (Blueprint $table) {
            $table->dropColumn('lenta_agent');
        });

    }
};
