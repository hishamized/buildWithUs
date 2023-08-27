<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('jobs', function (Blueprint $table) {
        $table->id();
        $table->string('job_title');
        $table->text('job_description');
        $table->text('job_requirements');
        $table->integer('hiring_capacity');
        $table->json('site_address');
        $table->string('job_type');
        $table->date('start_date');
        $table->date('end_date');
        $table->date('expiration_date');
        $table->json('site_pictures')->nullable();
        $table->decimal('wage', 10, 2);
        $table->string('job_status')->default('active')->nullable();
        $table->integer('views')->default(0)->nullable();
        $table->integer('application_count')->default(0)->nullable();
        $table->string('currency');
        $table->json('skill_set'); // Added skillset column
        $table->foreignId('client_id')->constrained('users');
        $table->timestamps();
    });
}


    public function down()
    {
        Schema::dropIfExists('jobs');
    }
};
