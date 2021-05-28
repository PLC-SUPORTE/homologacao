@extends('Painel.Layouts.index')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Lista de Tipos de Serviço
                <small>Sistema {{ env('APP_NAME') }}</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Lista de Tipos de Serviço</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">

                <div class="col-xs-12">

                    <div class="box ">
                        <div class="box-header">
                            <h3 class="box-title">Adicionar : <a href="{{ route('Painel.TipoServico.create') }}" class="btn btn-success fa fa-plus"></a></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Descrição</th>
                                    <th>Ativo</th>
                                    <th>Criado</th>
                                    <th>Ação</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($categorias as $categoria)
                                    <tr>
                                        <td>{{ $categoria->id }}</td>                  
                                        <td>{{ $categoria->descricao }}</td>
                                        <td>{{ $categoria->ativo }}</td>
                                        <td>{{ $categoria->created_at->diffForHumans() }}</td>
                                        <td>
                                            <a href="{{route('Painel.TipoServico.show', $categoria->id)}}" class="delete"><span class="btn btn-success fa fa-eye">Visualizar</span></a>
                                            <a href="{{ route('Painel.TipoServico.edit', $categoria->id)}}" class="btn btn-success fa fa-edit"</a>Editar
                                            <a href="{{ route('Painel.TipoServico.delete', $categoria->id)}}" class="btn btn-danger fa fa-trash"</a>Excluir
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
