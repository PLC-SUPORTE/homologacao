@extends('Painel.Layouts.index')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Editar Moeda
                <small>Sistema {{ env('APP_NAME') }}</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Editar Moeda</li>
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
    @if( isset($moedas) )
        {!! Form::model($moedas, ['route' => ['Painel.Moeda.update', $moedas->id], 'class' => 'form form-search form-ds', 'method' => 'PUT']) !!}
    @else
        {!! Form::open(['route' => 'Painel.Moedas.store', 'class' => 'form form-search form-ds']) !!}
    @endif
        <div class="form-group">
            {!! Form::text('Descricao', null, ['placeholder' => 'Descrição:', 'class' => 'form-control']) !!}
        </div>
       <div class="form-group">
            {!! Form::text('Simbolo', null, ['placeholder' => 'Simbolo:', 'class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::submit('Salvar Edição', ['class' => 'btn']) !!}
        </div>
    {!! Form::close() !!}

</div><!--Content Dinâmico-->

            </div>
        </section>
    </div>
@endsection