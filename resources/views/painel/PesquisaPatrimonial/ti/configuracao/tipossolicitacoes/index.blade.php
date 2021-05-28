@extends('Painel.PesquisaPatrimonial.Layouts.index')

<script src="https://code.jquery.com/jquery-1.9.1.js"></script>


@section('content')


    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                [Configuração] -  
                <small>[Configurar tipos de solicitação da pesquisa patrimonial]</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Configuração</li>
            </ol>
        </section>
        <section class="content">
            @include('flash::message')
            <div class="row">

                <div class="col-xs-12">
                   
          <div class="float-right">
                      <small>
                          <a href="" class="delete" target="_blank"><span class="btn btn-success fa fa-file-excel-o"> Exportar Dados</span></a>
                      </small>
                  </div>
                    <div class="box">
                        <div class="box-body">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped" style="font-size: 11px">
                                <thead>
                                <tr>
                                    <th style="font-size: 12px">#</th>
                                    <th style="font-size: 12px">Descricao</th>
                                    <th style="font-size: 12px">Gera debite</th>
                                    <th style="font-size: 12px">Tipo debite</th>
                                    <th style="font-size: 12px">Status</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($datas as $data)  
                                    <tr>
                                        <td style="font-size: 10px">{{ $data->id }}</td>
                                        <td style="font-size: 10px">{{ $data->descricao}}</td>
                                        <td style="font-size: 10px">{{ $data->geradebite}}</td>
                                        <td style="font-size: 10px">{{ $data->tipodebite}}</td>
                                        <td style="font-size: 10px">{{ $data->status}}</td>
                                        <td>
                                        <a href="{{route('Painel.PesquisaPatrimonial.ti.configurar.tipossolicitacoes.editar', $data->id)}}" class="delete"><span data-toggle="tooltip" data-placement="top" title="Clique aqui para editar este tipo de solicitação." class="btn btn-success fa fa-edit"style="background-color:#4B4B4B;border-color:#4B4B4B;"></span></a> 
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
