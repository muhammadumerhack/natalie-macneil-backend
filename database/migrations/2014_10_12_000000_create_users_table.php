<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('name');
            $table->string('country')->nullable();
            $table->string('phone')->nullable();
            $table->integer('account_id')->nullable();
            $table->integer('group_id')->nullable();
            $table->integer('fixed_fees')->nullable();
            $table->integer('role_id');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('status')->nullable();
            $table->boolean('allow_users')->default(0);
            $table->boolean('allow_verifiers')->default(0);
            $table->boolean('allow_institutes')->default(0);
            $table->boolean('allow_groups')->default(0);
            $table->boolean('allow_reports')->default(0);
            $table->boolean('allow_settings')->default(0);
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
