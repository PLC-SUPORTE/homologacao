@extends('Painel.Layouts.index')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Lista de Moedas
                <small>Sistema {{ env('APP_NAME') }}</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Lista de Moedas</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">

                <div class="col-xs-12">

                    <div class="box ">
                        <div class="box-header">
                            <h3 class="box-title">Adicionar : <a href="{{ route('Painel.Moeda.create') }}" class="btn btn-success fa fa-plus"></a></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Descrição</th>
                                    <th>Ativo</th>
                                    <th>Ação</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($moedas as $moeda)
                                    <tr>
                                        <td>{{ $moeda->Codigo }}</td>                  
                                        <td>{{ $moeda->Descricao }}</td>
                                        <td>{{ $moeda->Simbolo }}</td>

                                        <td>
                                            <a href="{{route('Painel.Moeda.show', $moeda->Codigo)}}" class="delete"><span class="btn btn-success fa fa-eye">Visualizar</span></a>
                                            <a href="{{ route('Painel.Moeda.edit', $moeda->Codigo)}}" class="btn btn-success fa fa-edit"</a>Editar
                                            <a href="{{ route('Painel.Moeda.delete', $moeda->Codigo)}})}}" class="btn btn-danger fa fa-trash"</a>Excluir
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
