<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 60);
            $table->string('label', 200);
            $table->timestamps();
        });
        
        Schema::create('profile_user', function(Blueprint $table){
            $table->increments('id');
            $table->integer('profile_id')->unsigned();
            $table->integer('user_id')->unsigned();
            
            $table->foreign('profile_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_user');
        Schema::dropIfExists('profiles');
    }
}
