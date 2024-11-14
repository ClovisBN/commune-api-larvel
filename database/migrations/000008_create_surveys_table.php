<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title_survey')->default('untitled-form');
            $table->string('description_survey')->default('Add Description');
            $table->json('content_survey')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('status_id')->default(1)->constrained('survey_statuses');
            $table->timestamps();
        });        
    }

    public function down()
    {
        Schema::dropIfExists('surveys');
    }
};
