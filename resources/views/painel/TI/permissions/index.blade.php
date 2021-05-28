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
        </section>
        <section class="content">
            @include('flash::message')
            <div class="row">

                <div class="col-xs-12">

                    <div class="box ">
                        <div class="box-header">
                            <h3 class="box-title">Adicionar : <a href="{{ route('permissoes.create') }}" class="btn btn-success fa fa-plus"></a></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nome</th>
                                    <th>Descrição</th>
                                    <th>Ação</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($data as $permission)
                                    <tr>
                                        <td>{{ $permission->id }}</td>                  
                                        <td>{{ $permission->name }}</td>
                                        <td>{{ $permission->label }}</td>
                                        <td>
                                            <a href="{{route('permissoes.show', $permission->id)}}" class="delete"><span class="btn btn-success fa fa-eye">Visualizar</span></a>
                                            <a href="{{route('permissoes.edit', $permission->id)}}" class="btn btn-success fa fa-edit"</a>Editar
                                            <a href="{{route('permissao.perfis', $permission->id)}}" class="btn btn-success fa fa-user"</a>Relacionar Perfil
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </section>
    </div>
@endsection
