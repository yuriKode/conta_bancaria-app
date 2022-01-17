@extends('layouts.app')

@section('title', 'Cadastro')
@section('sidebar')
    @parent
@endsection
@section('content')
@include('shared.form', ['status' => 'cadastro'])
@endsection
