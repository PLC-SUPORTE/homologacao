@extends('Painel.TI.Layouts.index')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Lista de Permissões
                <small>Sistema {{ env('APP_NAME') }}</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Lista de Permissões</li>
            </ol>
            <div class="title-pg">
    <h1 class="title-pg">Permissões: <b>{{$permission->name}}</b></h1>
</div>

    <div class="content-din bg-white">

<div class="bred">
    <a href="{{url('/painel')}}" class="bred">Home  > </a>
    <a href="{{url('/painel/permissoes')}}" class="bred">Perfis > Gestão de Perfil</a>
    <a href="{{route('permissao.perfis', $permission->id)}}" class="bred"> > {{$permission->name}}</a>
</div>

<div class="title-pg">
    <h1 class="title-pg">Adicionar Novos Perfil a Permissão: <b>{{$permission->name}}</b></h1>
</div>

<div class="content-din">
    @if( isset($errors) && count($errors) > 0 )
    <div class="alert alert-warning">
        @foreach($errors->all() as $error)
            <p>{{$error}}</p>
        @endforeach
    </div>
    @endif
    
   {!! Form::open(['route' => ['permissao.profiles.add', $permission->id], 'class' => 'form form-search form-ds']) !!}
        @foreach( $profiles as $profile )
        <div class="form-group">
            <label>
                {!! Form::checkbox('profiles[]', $profile->id) !!}
                {{ $profile->name }}
            </label>
        </div>
        @endforeach

        <div class="form-group">
            {!! Form::submit('Enviar', ['class' => 'btn']) !!}
        </div>
    {!! Form::close() !!}

</div><!--Content Dinâmico-->
    </div>
        </section>
    </div>

@endsection