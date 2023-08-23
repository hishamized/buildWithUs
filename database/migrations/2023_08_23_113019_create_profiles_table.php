<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('profile_picture')->nullable();
            $table->string('street_address')->nullable(false);
            $table->string('country')->nullable(false);
            $table->string('state')->nullable(false);
            $table->string('city')->nullable(false);
            $table->string('pin_code')->nullable(false);
            $table->string('contact_no')->nullable(false);
            $table->string('alternate_contact_no')->nullable();
            $table->date('date_of_birth')->nullable(false);
            $table->string('adhaar_card', 12)->nullable();
            $table->text('skill_set')->nullable();
            $table->string('resume')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}

