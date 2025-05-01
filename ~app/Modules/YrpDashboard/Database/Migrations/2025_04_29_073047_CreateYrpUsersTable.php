<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYrpUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yrp_users', function (Blueprint $table) {

            $table->bigInteger('id',)->unsigned();
            $table->string('name',191);
            $table->enum('prefix_code',['นาย','นาง','นางสาว',''])->nullable()->default('NULL');
            $table->text('first_name');
            $table->text('last_name');
            $table->enum('role',['admin','department_staff']);
            $table->text('position')->nullable();
            $table->text('department')->nullable();
            $table->string('email',191);
            $table->timestamp('email_verified_at')->nullable()->default('NULL');
            $table->string('password',191);
            $table->string('remember_token',100)->nullable()->default('NULL');
            $table->timestamp('created_at')->nullable()->default('NULL');
            $table->timestamp('updated_at')->nullable()->default('NULL');
    
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('yrp_users');
    }
}