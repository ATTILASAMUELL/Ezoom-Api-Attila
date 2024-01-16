<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lists', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->enum('check', ["pendente", "concluida", "cancelada"])->default("pendente")->nullable();
            $table->unsignedBigInteger('user_id'); // Chave estrangeira para a tabela de usuários
            $table->timestamps();

            // Definindo a chave estrangeira
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list');
    }
};
