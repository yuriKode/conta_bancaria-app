@extends('layouts.app')

@section('title', 'Simples Hotel')

@section('content')

<div class="px-4 py-5 my-5 text-center">
    <img role="img" class="d-block mx-auto mb-4" src="images/bank2.svg" alt="" width="72" height="57">
    <h1 class="display-5 fw-bold">Sistema de Conta Bancária</h1>
    <div class="col-lg-6 mx-auto">
        <p class="lead mb-4">Desafio proposto pela empresa Simples Hotel...</p>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <a type="button" href="/cliente/create" class="btn btn-primary btn-lg px-4 gap-3">
                Cadastrar Cliente
            </a>
            <a type="button" href="/cliente" class="btn btn-outline-secondary btn-lg px-4">
                Ver Clientes
            </a>
        </div>
    </div>
</div>

@endsection