@extends('layout')
@section('contenido')
<h1>Mensaje Enviado</h1>
<br><br>
{{-- {{dd($mismsjs)}} --}}
<div class="list-group">
        <a href="#" class="list-group-item list-group-item-action active">{{$mensaje -> motivo}}</a>
        <a href="#" class="list-group-item list-group-item-action">{{$mensaje -> mensaje}}</a>
        <a href="#" class="list-group-item list-group-item-action disabled">Enviado a: 
            {{$destino->email}}</a>
        <a href="#" class="list-group-item list-group-item-action disabled">Fecha: {{$mensaje -> updated_at}} </a>
    </div>
    <br><br>

@stop