<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prospects', function (Blueprint $table) {
            $table->string('id',8)->primary();
            $table->string('description');
            $table->string('resource')->default('prospects');
            $table->string('category')->default('general');
            $table->string('organisation');
            $table->string('client_ref')->nullable();
            $table->string('location')->nullable();
            $table->enum('type',['RFQ','Tender','General'])->default('General');
            $table->string('slug')->unique();
            $table->date('publish_date')->nullable();
            $table->dateTime('closing_date');
            $table->string('source')->nullable();
            $table->string('source_url')->nullable();
            $table->string('document_fees')->nullable();
            $table->string('bid_bond')->nullable();
            $table->enum('status',['new','pending','assigned','declined'])->default('new');
            $table->string('status_description')->nullable();
            $table->foreignIdFor(User::class,'created_by');
            $table->foreignIdFor(User::class,'updated_by');
            $table->softDeletes();
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
        Schema::dropIfExists('prospects');
    }
};
