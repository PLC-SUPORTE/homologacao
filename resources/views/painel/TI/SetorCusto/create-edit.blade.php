@extends('Painel.TI.Layouts.index')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Editar Setor Custo
                <small>Sistema {{ env('APP_NAME') }}</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Editar Setor Custo</li>
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
        {!! Form::model($data, ['route' => ['setorcusto.update', $data->id], 'class' => 'form form-search form-ds', 'method' => 'PUT']) !!}
    @else
        {!! Form::open(['route' => 'setorcusto.store', 'class' => 'form form-search form-ds']) !!}
    @endif
        <div class="form-group">
            {!! Form::text('Codigo', null, ['placeholder' => 'Codigo:', 'class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::text('Descricao', null, ['placeholder' => 'Descrição:', 'class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::text('Ativo', null, ['placeholder' => 'Ativo:', 'class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::submit('Cadastrar', ['class' => 'btn-primary']) !!}
        </div>
    {!! Form::close() !!}

</div><!--Content Dinâmico-->
            </div>
    </div>
@endsection