@extends('Painel.TI.Layouts.index')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Lista de Setores de Custo
                <small>Sistema {{ env('APP_NAME') }}</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Lista de Setores de Custo</li>
            </ol>
        </section>
        <section class="content">
            @include('flash::message')
            <div class="row">

                <div class="col-xs-12">

                    <div class="box ">
                        <div class="box-header">
                            <h3 class="box-title">Adicionar : <a href="{{ route('setorcusto.create') }}" class="btn btn-success fa fa-plus"></a></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Codigo</th>
                                    <th>Descrição</th>
                                    <th>Ação</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($data as $setorcusto)
                                    <tr>
                                        <td>{{ $setorcusto->Id }}</td>                  
                                        <td>{{ $setorcusto->Codigo }}</td>
                                        <td>{{ $setorcusto->Descricao }}</td>
                                        <td>
                                            <a href="{{route('setorcusto.show', $setorcusto->Id)}}" class="delete"><span class="btn btn-success fa fa-eye">Visualizar</span></a>
                                            <a href="{{route('setorcusto.edit', $setorcusto->Id)}}" class="btn btn-success fa fa-edit"</a>Editar
                                            <a href="{{route('setorcusto.users', $setorcusto->Id)}}" class="btn btn-success fa fa-user"</a>Usuarios
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
