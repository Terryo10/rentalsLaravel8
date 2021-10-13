<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('categories_id');
            $table->integer('type')->default(1);
            $table->boolean('taken')->default(0);
            $table->string('city');
            $table->string('title');
            $table->string('province');
            $table->string('country');
            $table->string('yard_size')->nullable();
            $table->string('bedroom_number')->nullable();
            $table->string('toilet_number')->nullable();
            $table->string('bathroom_number')->nullable();
            $table->string('garage_number')->nullable();
            $table->double('price');
            $table->string('day_or_month');
            $table->string('imagePath');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('categories_id')->references('id')->on('categories')->onDelete('cascade');
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
        Schema::dropIfExists('properties');
    }
}
