@extends('Painel.PesquisaPatrimonial.Layouts.index')

<script src="https://code.jquery.com/jquery-1.9.1.js"></script>


@section('content')


    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                [Listagem] -  
                <small>[Clientes da pesquisa patrimonial]</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Listagem</li>
            </ol>
        </section>
        <section class="content">
            @include('flash::message')
            <div class="row">

                <div class="col-xs-12">
                   
          <div class="float-right">
                      <small>
                          <a href="" class="delete" target="_blank"><span class="btn btn-success fa fa-file-excel-o"> Exportar Dados</span></a>
                          <a href="{{route('Painel.PesquisaPatrimonial.supervisao.clientes.create')}}" class="delete" target="_blank"><span class="btn btn-success fa fa-plus">Adicionar novo cliente</span></a>
                      </small>
                  </div>
                    <div class="box">
                        <div class="box-body">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped" style="font-size: 11px">
                                <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Nome fantasia</th>
                                    <th>Grupo</th>
                                    <th>UF</th>
                                    <th>Dias vencimento</th>
                                    <th>Qtd. solicitações</th>
                                    <th>Valor recebido</th>
                                    <th>Valor gasto</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($datas as $data)  
                                    <tr>
                                        <td>{{ $data->Codigo }}</td>
                                        <td>{{ $data->Descricao}}</td>
                                        <td>{{ $data->Grupo}}</td>
                                        <td>{{ $data->UF}}</td>
                                        <td>{{ $data->DiasVencimento}}</td>
                                        <td>0</td>
                                        <td>R$ 00,00</td>
                                        <td>R$ 00,00</td>
                                        <td>{{ $data->Status}} </td>
                                        <td>
                                        <a href="{{route('Painel.PesquisaPatrimonial.supervisao.clientes.edit', $data->id)}}" class="delete"><span data-toggle="tooltip" data-placement="top" title="Clique aqui para editar este cliente." class="btn btn-success fa fa-edit"style="background-color:#4B4B4B;border-color:#4B4B4B;"></span></a> 
                                        <a href="" target="_blank" class="delete"><span data-toggle="tooltip" data-placement="top" title="Clique aqui para visualizar listagem deste cliente." class="btn btn-success fa fa-search"style="background-color:#4B4B4B;border-color:#4B4B4B;"></span></a> 
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
