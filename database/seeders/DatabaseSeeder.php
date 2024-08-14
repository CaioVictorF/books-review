<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use App\Models\Review;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    
    public function run(): void
    {
        Book::factory(33)->create()->each(function ($book) { #Cria 33 livros
            $numReviews = random_int(5, 15); # Para cada livro, ele decidirá aleatóriamente quantas avaliações serão geradas

            Review::factory()->count($numReviews) # Ele irá  gerar as revisões, criará os modelos e escreverá
                ->good() # Em seguida ele executará este meétodo de substituição chamado good, que apenas define a classificação do livro
                ->for($book) # Cria uma associação com o livro definindo a colunaa de ID do livro.
                ->create(); # Em seguida, cria o modelo e salva na mesma hora.
        });

        Book::factory(33)->create()->each(function ($book) {
            $numReviews = random_int(5, 15);

            Review::factory()->count($numReviews)
                ->average()
                ->for($book)
                ->create();
        });

        Book::factory(34)->create()->each(function ($book) {
            $numReviews = random_int(5, 15);

            Review::factory()->count($numReviews)
                ->bad()
                ->for($book)
                ->create();
        });

    }
}
