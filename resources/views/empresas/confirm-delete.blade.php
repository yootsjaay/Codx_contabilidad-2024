@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Confirmar Eliminación</h2>
        <p>¿Estás seguro de que deseas eliminar la empresa "{{ $empresa->nombre }}"?</p>
        <form method="POST" action="{{ route('empresas.destroy', $empresa->id) }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Sí, eliminar</button>
            <a href="{{ route('empresas.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
@endsection
