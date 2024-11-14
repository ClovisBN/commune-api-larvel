<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title_article')->default('Untitled Article');
            $table->string('description_article')->default('Description of the article');
            $table->json('content_article')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('status_id')->default(1)->constrained('article_statuses');
            $table->timestamps();
        });        
    }

    public function down()
    {
        Schema::dropIfExists('articles');
    }
};
