@extends('Painel.TI.Layouts.index')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Editar Permissão
                <small>Sistema {{ env('APP_NAME') }}</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Editar Permissão</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">

               
<div class="content-din">
    @if( isset($errors) && count($errors) > 0 )
    <div class="alert alert-warning">
        @foreach($errors->all() as $error)
            <p>{{$error}}</p>
        @endforeach
    </div>
    @endif
   @if( isset($data) )
        {!! Form::model($data, ['route' => ['permissoes.update', $data->id], 'class' => 'form form-search form-ds', 'method' => 'PUT']) !!}
    @else
        {!! Form::open(['route' => 'permissoes.store', 'class' => 'form form-search form-ds']) !!}
    @endif
        <div class="form-group">
            {!! Form::text('name', null, ['placeholder' => 'Nome:', 'class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::text('label', null, ['placeholder' => 'Descrição:', 'class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::submit('Enviar', ['class' => 'btn']) !!}
        </div>
    {!! Form::close() !!}

</div><!--Content Dinâmico-->
            </div>
    </div>
@endsection