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
        $user_model_fqn = config('backpack.base.user_model_fqn');
        /** @var Eloquent $user */
        $user = new $user_model_fqn();

        try {
            $user->create([
                'name' => 'Admin',
                backpack_authentication_column() => 'admin@mail.ru',
                'password' => bcrypt('admin'),
            ]);
        } catch (Exception $exception) {
            var_dump($exception->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
