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
    <h1 class="title-pg">Perfis incluidos na Permissão: <b>{{$permission->name}}</b></h1>
</div>

    <div class="content-din bg-white">

    <div class="form-search">
        {!! Form::open(['route' => ['permissao.profiles.search', $permission->id], 'class' => 'form form-inline']) !!}
            {!! Form::text('key-search', null, ['class' => 'form-control', 'placeholder' => 'Nome:']) !!}
            {!! Form::submit('Filtrar', ['class' => 'btn']) !!}
        {!! Form::close() !!}
    </div>

    <div class="class-btn-insert">
        <a href="{{route('permissao.profiles.add', $permission->id)}}" class="btn-insert">
            <span class="glyphicon glyphicon-plus"></span>
            Cadastrar
        </a>
    </div>
    
    @if( Session::has('success') )
    <div class="alert alert-success hide-msg" style="float: left; width: 100%; margin: 10px 0px;">
        {!! Session::get('success') !!}
    </div>
    @endif

    <table class="table table-striped">
        <tr>
            <th>Nome</th>
            <th>Descrição</th>
            <th width="120">Ações</th>
        </tr>

        @forelse($profiles as $profile)
            <tr>
                <td>{{$profile->name}}</td>
                <td>{{$profile->label}}</td>
                <td>
                    <a href="{{route('permissao.profile.delete', [$permission->id, $profile->id])}}" class="delete"><span class="glyphicon glyphicon-trash"></span> Delete</a>
                </td>
            </tr>
        @empty
            <p>Nenhum Usuário Vinculado ao Perfil.</p>
        @endforelse
    </table>



</div><!--Content Dinâmico-->
    </div>

@endsection