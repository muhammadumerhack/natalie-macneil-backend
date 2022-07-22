<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('add_users')->after('allow_users')->default(0);
            $table->integer('edit_users')->after('add_users')->default(0);
            $table->integer('del_users')->after('edit_users')->default(0);

            $table->integer('add_verifiers')->after('allow_verifiers')->default(0);
            $table->integer('edit_verifiers')->after('add_verifiers')->default(0);
            $table->integer('del_verifiers')->after('edit_verifiers')->default(0);

            $table->integer('add_institutes')->after('allow_institutes')->default(0);
            $table->integer('edit_institutes')->after('add_institutes')->default(0);
            $table->integer('del_institutes')->after('edit_institutes')->default(0);

            $table->integer('add_groups')->after('allow_groups')->default(0);
            $table->integer('edit_groups')->after('add_groups')->default(0);
            $table->integer('del_groups')->after('edit_groups')->default(0);

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
            $table->dropColumn(['add_users','edit_users','del_users','add_verifiers','edit_verifiers','del_verifiers','add_institutes','edit_institutes','del_institutes','add_groups','edit_groups','del_groups']);
        });
    }
}
