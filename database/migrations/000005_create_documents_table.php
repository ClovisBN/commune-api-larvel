<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->default('untitled-form');
            $table->string('description')->default('Add Description');
            $table->json('questions')->nullable();
            $table->string('screenshot_path')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');  // Lien avec la table users
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
};
