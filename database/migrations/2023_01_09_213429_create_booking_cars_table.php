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
        Schema::create('booking_cars', function (Blueprint $table) {
            $table->id();
            $table->string('booking_id');
            $table->string('driver')->nullable();
            $table->string('car')->nullable();
            $table->string('destination')->nullable();
            $table->string('purpose')->nullable();
            $table->string('timedepature')->nullable();
            $table->string('timearrive')->nullable();
            $table->string('datedepature')->nullable();
            $table->string('user')->nullable();
            $table->integer('qty')->nullable();
            $table->string('rating')->nullable();
            $table->string('note')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('booking_cars');
    }
};
