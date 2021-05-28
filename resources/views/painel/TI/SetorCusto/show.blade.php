@extends('Painel.TI.Layouts.index')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Visualizar Setor Custo
                <small>Sistema {{ env('APP_NAME') }}</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Visualizar Setor Custo</li>
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
    
    <h2><strong>Codigo:</strong> {{$data->Codigo}}</h2>
    <h2><strong>Descrição:</strong> {{$data->Descricao}}</h2>
   
      {!! Form::open(['route' => ['setorcusto.destroy', $data->Id], 'class' => 'form form-search form-ds', 'method' => 'DELETE']) !!}
        <div class="form-group">
            {!! Form::submit("Deletar Setor Custo: $data->Descricao", ['class' => 'btn btn-danger']) !!}
        </div>
    {!! Form::close() !!}

</div><!--Content DinÃ¢mico-->

            </div>
        </section>
    </div>
@endsection
