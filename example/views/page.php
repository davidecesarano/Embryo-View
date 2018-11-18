@include('header', ['title' => 'Hello Worlds!'])

@if($status == 1)
    <p>Hi, status 1.</p>
@else 
    <p>Hi, other status.</p>
@endif