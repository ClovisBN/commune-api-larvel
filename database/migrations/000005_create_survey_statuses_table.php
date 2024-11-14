<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('survey_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'unpublished', 'published', 'closed'
        });
    }

    public function down()
    {
        Schema::dropIfExists('survey_statuses');
    }
};
