@extends('Painel.TI.Layouts.index')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Lista de Setor Custo
                <small>Sistema {{ env('APP_NAME') }}</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Lista de Setor Custo</li>
            </ol>
            <div class="title-pg">
    <h1 class="title-pg">Usuários do Setor Custo: <b>{{$setorcusto->Descricao}}</b></h1>
</div>

    <div class="content-din bg-white">

    <div class="form-search">
        {!! Form::open(['route' => ['setorcusto.users.search', $setorcusto->Id], 'class' => 'form form-inline']) !!}
            {!! Form::text('key-search', null, ['class' => 'form-control', 'placeholder' => 'Nome:']) !!}
            {!! Form::submit('Filtrar', ['class' => 'btn-primary']) !!}
        {!! Form::close() !!}
    </div>

    <div class="class-btn-insert">
        <a href="{{route('setorcusto.users.add', $setorcusto->Id)}}" class="btn-insert">
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
            <th>email</th>
            <th width="120">Ações</th>
        </tr>

        @forelse($users as $user)
            <tr>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td>
                    <a href="{{route('setorcusto.user.delete', [$setorcusto->Id, $user->id])}}" class="delete"><span class="glyphicon glyphicon-trash"></span> Delete</a>
                </td>
            </tr>
        @empty
            <p>Nenhum Usuário Vinculado ao Setor Custo.</p>
        @endforelse
    </table>


</div><!--Content Dinâmico-->
    </div>

@endsection