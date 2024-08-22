@extends('layouts.app')

@section('content')
    
<h1 class="mb-10 text-2x1">Livros</h1>

<form method ="GET" action="{{ route('books.index') }}" class="mb-4 flex items-center space-x-2"> {{--Este formulário está sendo enviado para a mesma rota que renderiza a página que tem o indice dos livros--}}
  <input type="text" name="title" placeholder="Pesquisar por título" 
    value="{{ request('title') }}" class="input h-10"/> {{-- O input pegará o nome do livro--}}
    {{-- o value recupera o valor antigo se ele estiver sido enviado antes, solicitando com o request--}}
  <input type="hidden" name="filter" value="{{ request('filter') }}"/>
  <button type="submit" class="btn h-10">Pesquisar</button>
  <a href="{{ route('books.index') }}" class="btn h-10">limpar</a>
</form>

<div class="filter-container mb-4 flex">
  @php  
    $filters = [
      '' => 'Recentes',
      'popular_last_month' => 'Mês passado',
      'popular_last_6months' => 'Últimos 6 meses',
      'highest_rated_last_month' => 'Melhor avaliação do mês passado',
      'highest_rated_last_6months' => 'Melho classificação dos últimos 6 meses'
    ];
  @endphp

  {{--Usando foreach para iterar os filtros--}}
  @foreach($filters as $key => $label) {{--Filtro está usando o valor da chave e a chave, que basicamente é uma label--}}
    <a href="{{ route('books.index', [...request()->query(),'filter' => $key]) }}" 
    class="{{ request('filter') === $key || (request('fiter') === null && $key === '')? 'filter-item-active' : 'filter-item'}}"> {{--Filter item mostra os elementos na label--}}
      {{ $label }}  
  @endforeach
</div>

<ul>
  @forelse ($books as $book)
    <li class="mb-4">
      <div class="book-item">
        <div
          class="flex flex-wrap items-center justify-between">
          <div class="w-full flex-grow sm:w-auto">
            <a href="{{ route('books.show', $book) }}" class="book-title">{{ $book->title }}</a>
            <span class="book-author">{{ $book->author }}</span>
          </div>
          <div>
            <div class="book-rating">
              {{ number_format($book->reviews_avg_rating, 1) }}
            </div>
            <div class="book-review-count">
              out of {{ $book->reviews_count}} {{ Str::plural('review', $book->reviews_count) }}
            </div>
      </div>
    </div>
  </div>
</li>
    @empty
      <li class=""mb-4>
        <div class="empty-book-item">
          <p class="empty-text">No books found</p>
          <a href="{{ route('books.index') }}" class="reset-link">Reset Criteria</a>
        </div>
      </li>
    @endforelse
</ul>

@endsection