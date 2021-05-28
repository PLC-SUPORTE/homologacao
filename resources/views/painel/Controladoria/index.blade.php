@extends('Painel.Layouts.index')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Lista de Fichas para Aprovação
                <small>Sistema {{ env('APP_NAME') }}</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Lista de Fichas para aprovação</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">

                <div class="col-xs-12">

                    <div class="box ">
                        <div class="box-header">
                            <h3 class="box-title">Criar : <a href="{{ route('Painel.Notas.create') }}" class="btn btn-success fa fa-plus"></a></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Numero Debite</th>
                                    <th>Pasta</th>
                                    <th>Solicitante</th>
                                    <th>Quantidade</th>
                                    <th>Valor Total (R$)</th>
                                    <th>Data Criação</th>
                                    <th>Status </th>
                                    <th width="300">Ação</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($notas as $categoria)
                                    <tr>
                                        <td>{{ $categoria->NumeroDebite }}</td>                  
                                        <td>{{ $categoria->Pasta }}</td>
                                        <td>{{ $categoria->name }}</td>
                                        <td>{{ $categoria->Quantidade}}</td>
                                        <td>{{ $categoria->ValorTotal}}</td>
                                        <td>{{ $categoria->DataFicha}}</td>
                                        <td>{{ $categoria->descricao}}</td>
                                        <td>
                                       <a href="{{ route('Painel.Controladoria.aprovar', $categoria->NumeroDebite)}}" class="delete"><span class="btn btn-success fa fa-check"> Aprovar</span></a>
                                       <a href="{{ route('Painel.Controladoria.reprovar', $categoria->NumeroDebite)}}" class="btn btn-danger fa fa-edit"</a> Reprovar
                                       <a href="{{ route('Painel.Controladoria.show', $categoria->NumeroDebite)}}" class="btn btn-success fa fa-file"</a> Visualizar </td>
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
