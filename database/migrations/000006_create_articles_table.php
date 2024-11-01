<?php
// database/migrations/000006_create_articles_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title')->default('Untitled Article');
            $table->string('description')->default('Description of the article');
            $table->json('components')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');  // Lien avec la table users
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('articles');
    }
};
