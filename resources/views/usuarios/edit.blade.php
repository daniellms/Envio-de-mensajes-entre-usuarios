@extends('layout')
@section('contenido')
<h1> Editar Usuario de {{$user->name}}</h1>
    @if(session()->has('info'))
    <div class="alert-dark">{{session('info')}}</div>
    @endif
    <form class="" method="POST" action="{{ route('usuarios.update', $user->id) }}">
        {!!method_field('PUT')!!} {{-- esto es para q me reconozca el navegador solo para eso --}}
        
        @csrf <!-- token formulario valido -->
     
        <label for="nombre">  
        Nombre
        <input class="form-control" type="text" name="name" value="{{$user->name}}"> 
        {!!$errors->first('name','<span class=error>:message</span>')!!} <!--validacion de form con el metodo users de controlador-->
        </label><br>
        <label for="email">
        Email
        <input class="form-control" type="text" name="email" value="{{$user->email}}">
        {!!$errors->first('email','<span class=error>:message</span>')!!}
         </label><br>  
         Nro Documento
        <input class="form-control" type="text" name="dni" value="{{$user->dni}}">
            {!!$errors->first('dni','<span class=error>:message</span>')!!}
        </label><br>

         <div class="form-group">
                Tipo de Dni
                <select  class="select" name="tipo" class="form-control">
                    @foreach($tipos as $tipo)
                    @if($user->tipo_dni === $tipo->id)
                        <option value="{{$tipo->id}}" selected="true" disabled="disabled">{{$tipo->nombre}}</option> {{-- //corregido --}}
                    @endif
                     <option value="{{$tipo->id}}">{{$tipo->nombre}} </option>
                    @endforeach
                </select>
            </div>
        <input class="btn btn-primary" type="submit" value="Enviar"> 
    </form>

@stop