@if($rating)
    @for($1 = 1; $1 <= 5; $i++)
        {{ $1<= round($rating) ? '*' : '☆'}}

    @endfor
@else
    No rating yet
@endif