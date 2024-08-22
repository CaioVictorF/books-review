<?php

namespace App\Http\Controllers;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{

    public function index(Request $request) # $request é o objeto instanciado da classe Request, que vai fazer a solicitação.
    {
        $title = $request->input('title'); # a variavel titulo recebe os objeto request que chama o metodo de entrada input que tem como parâmetro o título.
        $filter = $request->input('filter', ''); #o filtro recebe o metodo de solicitação request para verificar se ele foi especificado chamando o metodo de entrada input, passando filter como parametero.

        #o método when recebe algo como primeiro argumento. Neste caso, o title. Como segundo argumento ele tem uma função.
        $books = Book::when( #Se o titulo Não for nulo ou não estiver vazio, ele executa a função passada como segundo argumento.
            $title,  
            fn($query, $title) => $query->title($title)  
        );

        $books = match($filter) 
        {
            'popular_last_month' => $books->popularLastMonth(), 
            'popular_last_6months' => $books->popularLast6Months(),
            'highest_rated_last_month' => $books->highestRatedLastMonth(),
            'highest_rated_last_6months' => $books->highestRatedLast6Months(),
            default => $books->latest()
        };

        $books = $books->get();  #executa consultas separadamente
            return view('books.index', ['books' => $books]);
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
