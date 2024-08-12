<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('book_id'); # Essa coluna faz referência à outra tabela. Isso acontecerá com o método unsignedBigInterger que tem 

            $table->text('review');
            $table->unsignedTinyInteger('rating');
            $table->timestamps();

        /* A chave estrangeira 'foreign', é chamada a tabela do ID de livro estrangeiro e em seguida, chama outro método chamado references, 
           que especificará qual coluna na outra tabela essa chave estrangeira está referenciando o ID da outra tabela de livros. Também é preciso especificar esse uso nos livros com o método 'on' 
            $table->foreign('book_id')->references('id')->on('books') 
                ->onDelete('cascade'); # A cascata de exclusão especifica, apaga todas as avaliações relacionadas a um livros quando ele é excluído do banco de dados*/
        
            $table->foreginId('Book_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews'); 
    }
};
