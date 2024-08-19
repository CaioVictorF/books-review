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

    public function scopePopular(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query->withCount([
            'reviews' => fn(Builder $q) => $this->dateRangerFilter($q, $from, $to)
        ])
            ->orderBy('reviews_count','desc');
            
    }

    public function scopeHighestRated(Builder $query): Builder|QueryBuilder
    {
        return $query->withAvg([
            'reviews' => fn(Builder $q) => $this->dateRangerFilter($q, $from, $to)
        ], 'rating')
            ->orderBy('reviews_avg_rating', 'desc');
    }

    public function scopeMinReviews(Builder $query, int $minReviews): Builder|QueryBuilder
    {
        return $query->having('Review_count', '>=', $minReviews);
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
}
