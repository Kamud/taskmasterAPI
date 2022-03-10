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
        Schema::create('logs', function (Blueprint $table) {
            $table->string('id',8)->primary();
            $table->string('category')->default('logs');
            $table->enum('resource_category',['users','prospects','tasks','assignments','estimates','archives']);
            $table->string('resource_id');
            $table->string('description');
            $table->enum('action',['created','modified','login','logout','deleted']);
            $table->unsignedBigInteger('modified_by_user_id');
            $table->foreign('modified_by_user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('logs');
    }
};
