@extends('layouts.app')

@section('title', 'Report Edicao')

@section('content')
@include('shared.report', ['status' => 'edicao'])

@endsection