@extends('Painel.Layouts.index')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Lista de Status Ficha
                <small>Sistema {{ env('APP_NAME') }}</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Lista Status Ficha</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">

                <div class="col-xs-12">

                    <div class="box ">
                        <div class="box-header">
                            <h3 class="box-title">Adicionar : <a href="{{ route('Painel.StatusFicha.create') }}" class="btn btn-success fa fa-plus"></a></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Descrição</th>
                                    <th>Ação</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($datas as $data)
                                    <tr>
                                        <td>{{ $data->id }}</td>                  
                                        <td>{{ $data->descricao }}</td>
                                        <td>
                                            <a href="{{route('Painel.StatusFicha.show', $data->Id)}}" class="delete"><span class="btn btn-success fa fa-eye">Visualizar</span></a>
                                            <a href="{{ route('Painel.StatusFicha.edit', $data->Id)}}" class="btn btn-success fa fa-edit"</a>Editar
                                            <a href="{{ route('Painel.StatusFicha.delete', $data->Id)}})}}" class="btn btn-danger fa fa-trash"</a>Excluir
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
