@extends('layouts.app')

@section('title', 'Report Cadastro')

@section('content')
@include('shared.report', ['status' => 'cadastro'])

@endsection