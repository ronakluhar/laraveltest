<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',255);
            $table->string('email',100)->unique();
            $table->string('password',255);
            $table->string('phone',15)->nullable();
            $table->string('unique_id',20)->nullable();
            $table->string('photo',255)->nullable();
            $table->bigInteger('last_activity')->nullable()->unsigned();
            $table->bigInteger('last_logged_in_at')->nullable()->unsigned();
            $table->bigInteger('current_login_time')->nullable()->unsigned();
            $table->tinyInteger('is_admin')->default(0)->unsigned();
            $table->tinyInteger('is_login')->default(0)->unsigned();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
