@include('header', ['title' => $message]);

@if($status == 1)
    <p>Hi, status is 1.</p>
@else 
    <p>Hi, other status.</p>
@endif