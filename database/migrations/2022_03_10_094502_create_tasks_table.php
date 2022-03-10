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
        Schema::create('tasks', function (Blueprint $table) {
            $table->string('id',8)->primary();
            $table->string('description');
            $table->string('category')->default('tasks');
            $table->enum('status',['pending','completed','failed']);
            $table->string('status_description');
            $table->unsignedBigInteger('assigned_user_id');
            $table->foreign('assigned_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('modified_by_user_id');
            $table->foreign('modified_by_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('slug')->unique();
            $table->dateTime('closing_date');
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
        Schema::dropIfExists('tasks');
    }
};
