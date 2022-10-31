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
        Schema::create('site_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('email')->nullable();
            $table->string('first')->nullable();
            $table->string('second')->nullable();
            $table->string('middle')->nullable();
            $table->string('phone')->nullable();
            $table->string('loyalty_uid')->nullable();
            $table->string('card_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_users');
    }
};
