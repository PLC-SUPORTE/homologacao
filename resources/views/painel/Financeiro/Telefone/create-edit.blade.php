@extends('Painel.Financeiro.Layouts.index')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Gestão telefone corporativos
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Gestão telefone corporativos</li>
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
    @if( isset($telefones) )
        {!! Form::model($user, ['route' => ['Painel.Financeiro.Telefones.update', $telefones->id], 'class' => 'form form-search form-ds', 'files' => true, 'method' => 'PUT']) !!}
    @else
        {!! Form::open(['route' => 'Painel.Financeiro.Telefones.criado', 'class' => 'form form-search form-ds', 'files' => true]) !!}
    @endif
    
    <br>
       <div class="panel-body">
        <div class="form-group">
           <label>Digite o número do telefone:</label>
            {!! Form::text('telefone', null, ['placeholder' => 'Digite o número telefone:', 'required' => '', 'class' => 'form-control']) !!}
         
            <label>Selecione o responsável:</label>
                <select class="form-control select2" style="width: 100%;" id="usuario" name="usuario"  data-toggle="tooltip" data-placement="left" title="Selecione a unidade.">
                @foreach($users as $user)   
                <option selected="selected" >{{ $user->name }}</option>
                @endforeach
                </select>
            
           <label>Selecione a operadora:</label>
                <select class="form-control select2" style="width: 100%;" id="operadora" name="operadora"  data-toggle="tooltip" data-placement="left" title="Selecione a operadora">
                  <option selected="selected">VIVO</option>
                 </select>  
           
           <label>Selecione a unidade:</label>
                <select class="form-control select2" style="width: 100%;" id="unidade" name="unidade"  data-toggle="tooltip" data-placement="left" title="Selecione a unidade.">
                @foreach($unidades as $unidade)   
                <option selected="selected">{{ $unidade->Descricao }}</option>
                @endforeach
                </select>   
           
           <label>Selecione o setor:</label>
                <select class="form-control select2" style="width: 100%;" id="setor" name="setor"  data-toggle="tooltip" data-placement="left" title="Selecione a unidade.">
                @foreach($setores as $setor)   
                <option selected="selected">{{ $setor->Descricao }}</option>
                @endforeach
                </select>   
           
           <label>Digite o número da conta:</label>
           {!! Form::text('conta', null, ['placeholder' => 'Digite a conta:', 'required' => '', 'class' => 'form-control']) !!}

            <label>Selecione o status:</label>
                <select class="form-control select2" style="width: 100%;" id="status" name="status"  data-toggle="tooltip" data-placement="left" title="Selecione a operadora">
                  <option selected="selected">ATIVO</option>
                  <option selected="selected">INATÍVO</option>
                 </select>       
        </div>
            {!! Form::submit('Cadastrar', ['class' => 'btn btn-block btn-success']) !!}
        </div>
    {!! Form::close() !!}
</div>
</div>
</div>


@endsection