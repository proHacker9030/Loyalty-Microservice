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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->smallInteger('status_id');
            $table->float('amount');
            $table->float('discount_amount')->default(0);
            $table->float('bonuses')->nullable();
            $table->string('promocode')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('site_users')->onDelete('set null');
            $table->text('error_text')->nullable();
            $table->string('loyalty_operation_id')->nullable();
            $table->string('lenta_host')->nullable();
            $table->string('lenta_agent')->nullable();
            $table->foreignIdFor(\App\Models\Project::class)->nullable()->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('orders');
    }
};
