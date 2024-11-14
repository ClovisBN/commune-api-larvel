<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grievance_id')->constrained()->onDelete('cascade'); // Lié à une doléance
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Utilisateur ou admin qui envoie le message
            $table->text('content_message'); // Contenu du message
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
