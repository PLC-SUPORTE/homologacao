@extends('Painel.Layouts.index')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Visualizar Categoria
                <small>Sistema {{ env('APP_NAME') }}</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Visualizar Categoria</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">

                <div class="col-xs-12">

                   <div class="content-din">
    @if( isset($errors) && count($errors) > 0 )
    <div class="alert alert-warning">
        @foreach($errors->all() as $error)
            <p>{{$error}}</p>
        @endforeach
    </div>
    @endif
    
    <h2><strong>Descrição:</strong> {{$categoria->descricao}}</h2>
    <h2><strong>Ativo:</strong> {{$categoria->ativo}}</h2>
    <h2><strong>Criado:</strong> {{$categoria->created_at}}</h2>
   
    {!! Form::open(['route' => ['Painel.TipoServico.delete', $categoria->id], 'class' => 'form form-search form-ds', 'method' => 'DELETE']) !!}
        <div class="form-group">
            {!! Form::submit("Deletar Categoria: $categoria->descricao", ['class' => 'btn btn-danger']) !!}
        </div>
    {!! Form::close() !!}

</div><!--Content DinÃ¢mico-->

            </div>
        </section>
    </div>
@endsection
