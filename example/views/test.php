@include('header', ['b' => 'dddd'])

<p>Ciao {{ $message }}</p>

@if($a == 1)
    <p>Ciao come stai?</p>
@else 
    <p>Noooo</p>
@endif