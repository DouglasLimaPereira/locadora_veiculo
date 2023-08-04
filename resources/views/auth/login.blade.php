@extends('layouts.app')

@section('content')
    
    <login-component 
        csrf_token="{{ @csrf_token() }}" 
        action="{{ route('login') }}" 
        submit="{{ route('login') }}"
        url="{{ url('/') }}">
    </login-component>

@endsection
