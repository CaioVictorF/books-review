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

            //$table->unsignedBigInteger('book_id'); # Essa coluna faz referência à outra tabela.

            $table->text('review');
            $table->unsignedTinyInteger('rating');
            
            $table->timestamps();
            
            $table->foreignId('book_id')->constrained()
                ->cascadeOnDelete(); # Manipulador de exclusão. A cascata de exclusão especifíca quando o livro será excluído, portanto, quando um registro de livro é excluído do BD, todas as avaliações relacionadas a ele também são excluídas.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews'); 
    }
};
