@extends('Painel.Layouts.index')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Lista de Pastas
                <small>Sistema {{ env('APP_NAME') }}</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Lista de Pastas</li>
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
                                    <th>Codigo Comp</th>
                                    <th>Cliente</th>
                                    <th>Descrição</th>
                                    <th>UF</th>
                                    <th>Numero Processo</th>
                                    <th>Status</th>
                                    <th>Ação</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($pastas as $pasta)
                                    <tr>
                                        <td>{{ $pasta->Codigo_Comp }}</td>                  
                                        <td>{{ $pasta->Cliente }}</td>
                                        <td>{{ $pasta->Descricao }}</td>
                                        <td>{{ $pasta->UF }}</td>
                                        <td>{{ $pasta->NumPrc1_Sonumeros }}</td>
                                        <td>{{ $pasta->Status }}</td>
                                        <td>
                                            <a href="{{route('Painel.Pasta.show', $pasta->Codigo_Comp)}}" class="delete"><span class="btn btn-success fa fa-eye">Visualizar</span></a>
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
