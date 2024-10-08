@extends('layouts.app')

@section('content')
    <h1 class="mb-10 rext-2x1">Adicionar avaliação {{ $book->title }}</h1>

<form method="POST" action="{{route('books.reviews.store', $book) }}">
    @csrf
    <label for="review">Análise</label>
    <textarea name="review" id="review" required class="input mb-4"></textarea>

    <label for="rating">Avaliar</label>

    <select name="rating" id="rating" class="input mb-4" required>
    <option value="">Selecione uma classificação</options>
    @for($i = 1; $i <= 5; $i++)
        <option value="{{ $i }}">{{ $i }}</option>
    @endfor
    </select>

    <button type="submit" class="btn">Adicionar!</button>

</form>
@endsection