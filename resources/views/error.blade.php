<!-- resources/views/error.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="alert alert-danger" role="alert">
            {{ $mensaje }}
        </div>
    </div>
@endsection
