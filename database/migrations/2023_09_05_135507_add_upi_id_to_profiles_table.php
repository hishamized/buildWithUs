<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('profiles', function (Blueprint $table) {
        $table->string('upi_id')->nullable(); // You can adjust the column type and options as needed
    });
}


    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('profiles', function (Blueprint $table) {
        $table->dropColumn('upi_id');
    });
}

};
