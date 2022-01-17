@extends('layouts.app')

@section('title', 'Edição')
@section('sidebar')
    @parent
@endsection
@section('content')
@include('shared.form', ['status' => 'edicao'])
@endsection
