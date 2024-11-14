<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('grievance_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'open', 'closed'
        });
    }

    public function down()
    {
        Schema::dropIfExists('grievance_statuses');
    }
};
