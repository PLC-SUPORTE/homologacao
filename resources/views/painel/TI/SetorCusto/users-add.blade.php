@extends('Painel.TI.Layouts.index')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Relacionar Usuario ao Setor Custo
                <small>Sistema {{ env('APP_NAME') }}</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Relacionar Usuario ao Setor Custo</li>
            </ol>
         

    <div class="content-din bg-white">

<div class="bred">
    <a href="{{url('/painel')}}" class="bred">Home  > </a>
    <a href="{{url('/painel/setorcustos')}}" class="bred">Perfis > Gestão de Perfil</a>
    <a href="{{route('setorcusto.users', $setorcusto->Id)}}" class="bred"> > {{$setorcusto->Descricao}}</a>
</div>

<div class="title-pg">
    <h1 class="title-pg">Adicionar Novos Usuários ao Setor Custo: <b>{{$setorcusto->Descricao}}</b></h1>
</div>

<div class="content-din">
    @if( isset($errors) && count($errors) > 0 )
    <div class="alert alert-warning">
        @foreach($errors->all() as $error)
            <p>{{$error}}</p>
        @endforeach
    </div>
    @endif
    
    {!! Form::open(['route' => ['setorcusto.users.add', $setorcusto->Id], 'class' => 'form form-search form-ds']) !!}
        @foreach( $users as $user )
        <div class="form-group">
            <label>
                {!! Form::checkbox('users[]', $user->id) !!}
                {{ $user->name }}
            </label>
        </div>
        @endforeach

        <div class="form-group">
            {!! Form::submit('Relacionar Usuario', ['class' => 'btn-primary']) !!}
        </div>
    {!! Form::close() !!}

</div><!--Content Dinâmico-->
    </div>
        </section>
    </div>

@endsection