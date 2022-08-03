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
            $table->integer('role_id');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('status')->nullable();
            $table->string('invite_code')->nullable();
            $table->enum('service_type',['funnel','webpage','pending'])->default('pending');
            $table->integer('branding')->nullable();
            $table->integer('template')->nullable();
            $table->integer('funnel_platform')->nullable();
            $table->string('funnel_emails')->nullable();
            $table->string('page_content')->nullable();
            $table->string('privacy_content')->nullable();
            $table->string('terms_content')->nullable();
            $table->enum('service_status',['pending','started','inprogress','review','completed',])->default('pending');
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
