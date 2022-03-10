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
        Schema::create('estimates', function (Blueprint $table) {
            $table->string('id',8)->primary();
            $table->foreignId('assignment_id')->constrained()->onDelete('cascade');
            $table->string('category')->default('estimates');
            $table->decimal('quote_price');
            $table->enum('quote_currency',['USD','ZWL'])->default('ZWL');
            $table->string('quote_ref');
            $table->string('slug');
            $table->enum('status',['pending','completed','failed']);
            $table->string('status_description');
            $table->dateTime('submission_date');
            $table->dateTime('closing_date');
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
        Schema::dropIfExists('estimates');
    }
};
