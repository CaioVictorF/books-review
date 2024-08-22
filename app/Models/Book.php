<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;


# Tabela pai
class Book extends Model
{
    use HasFactory;

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    # Função escopo de consulta que precisa ser exatamente 'scope' e em seguida deve ser chamado um nome que pode ser 'title'.
    public function scopeTitle(Builder $query, string $title): Builder  # Este Builder fora dos argumentos, é um retorno construtor.
    # $query é o argumento para consulta no banco de dados.
    {
        return $query->where('title', 'LIKE', '%' . $title . '%');
        # Retorna o objeto $query que chama o método where para verificar as colunas de Titulo usando o operador Like(Retorna o titulo com alguma string a mais).
    }

    public function scopesWithReviewsCount(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query->withCount([
            'reviews' => fn(Builder $q) => $this->dateRangerFilter($q, $from, $to)
        ]);
    }

    public function scopesWithAvgRating(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query->withAvg([
            'reviews' => fn(Builder $q) => $this->dateRangerFilter($q, $from, $to)
        ], 'rating');
    }

    public function scopePopular(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query->withReviewsCount()
            ->orderBy('reviews_count','desc');
            
    }

    public function scopeHighestRated(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query->withAvgRating()
            ->orderBy('reviews_avg_rating', 'desc');
    }

    public function scopeMinReviews(Builder $query, int $minReviews): Builder|QueryBuilder
    {
        return $query->having('reviews_count', '>=', $minReviews);
    }

    private function dateRangerFilter(Builder $query, $from = null, $to = null) 
    {
        if ($from && !$to){
            $query->where('created_at', '>=', $from);
        }elseif (!$from && $to) {
            $query->where('created_at', '<=', $to);
        }elseif ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }
    }

    public function scopePopularLastMonth(Builder $query): Builder|QueryBuilder
    {   
        #retorna uma consulta que chama o metodo popular no mês passado
        return $query->popular(now()->subMonth(), now()) #para obter todos os livros populares do ultimo mês até o momento, é passado como parametro uma nova data e subtraído 1 mês dela usando o método 
            ->highestRated(now()->subMonth(), now()) #trará a classificação mais alta.
            ->minReviews(2); #para não incluir os livros que tiveram apenas uma resenha.
    }

    public function scopePopularLast6Months(Builder $query): Builder|QueryBuilder
    {   
        #retorna uma consulta que chama o metodo popular dos ultimos 6 meses
        return $query->popular(now()->subMonths(6), now()) #para obter todos os livros populares dos ultimos 6 meses até o momento, é passado como parametro uma nova data e subtraído 6 meses dela usando o método 
            ->highestRated(now()->subMonths(6), now()) #trará a classificação mais alta.
            ->minReviews(5); 
    }

    public function scopeHighestRatedLastMonth(Builder $query): Builder|QueryBuilder
    {   
        #retorna uma consulta que chama o metodo mais bem avaliado do mês passado
        return $query->highestRated(now()->subMonth(), now()) #para obter todos os livros mais bem avaliados do ultimo mês até o momento, é passado como parametro uma nova data e subtraído 1 mês dela usando o método 
            ->popular(now()->subMonth(), now()) #trará a classificação mais alta.
            ->minReviews(2); #para não incluir os livros que tiveram apenas uma resenha.
    }

    public function scopeHighestRatedLast6Months(Builder $query): Builder|QueryBuilder
    {   
        #retorna uma consulta que chama o metodo mai bem avaliado dos ultimos 6 meses passado
        return $query->highestRated(now()->subMonths(6), now()) #para obter todos os livros mais bem avaliados dos ultimos 6 meses até o momento, é passado como parametro uma nova data e subtraído 1 mês dela usando o método 
            ->popular(now()->subMonths(6), now()) #trará a classificação mais alta.
            ->minReviews(5); #para não incluir os livros que tiveram apenas uma resenha.
    }

    protected static function booted()
    {
        static::updated(
            fn(Book $book) => cache()->forget('book:' . $book->id)
        );
        static::deleted(
            fn(Book $book) => cache()->forget('book:' . $book->id)
        );
    }
}
























