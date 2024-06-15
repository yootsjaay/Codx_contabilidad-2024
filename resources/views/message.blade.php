<!-- resources/views/message.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="alert alert-success" role="alert">
            {{ $mensaje }}
        </div>
    </div>
@endsection
