<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

# Tabela Filha
class Review extends Model
{
    use HasFactory;

    protected $fillable = ['review', 'rating'];

    public function book()
    {
        return $this->belongsTo(book::class);
    }
}
