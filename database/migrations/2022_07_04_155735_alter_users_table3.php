<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTable3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('allow_new_verification')->after('del_groups')->default(0);
            $table->integer('allow_verifications')->after('allow_new_verification')->default(0);
            $table->integer('parent_verifier_id')->after('allow_verifications')->default(0);
        });    

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['allow_new_verification','allow_verifications','parent_verifier_id']);
        });
    }
}
