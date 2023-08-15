@extends('layouts.auth.auth')

@section('content')
<div>
    <h1>Ввести другий номер телефону</h1>

    <form action="{{route('auth.add-phone')}}" method="POST">
        @csrf
        <input type="text" name="phone" placeholder="+38(XXX) XXX-XX-XX">
        <input type="hidden" name="another" value="1">
        <input type="submit" value="Підтвердити номер">
    </form>
</div>
@endsection
