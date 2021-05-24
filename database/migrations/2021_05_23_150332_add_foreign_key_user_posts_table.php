<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyUserPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table)

        //Aggiungo colonna che sarà la mia chiave esterna
            {
            $table->unsignedBigInteger('user_id')->after('content');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table){
            $table->dropForeign('posts_user_id_foreign');
            $table->dropColumn('user_id');
        });
        
    }
}
