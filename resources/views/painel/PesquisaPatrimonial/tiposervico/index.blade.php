@extends('Painel.PesquisaPatrimonial.Layouts.index')

<script src="https://code.jquery.com/jquery-1.9.1.js"></script>


@section('content')


    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                [Listagem tipos de serviço] -  
                <small>[Listagem de tipos de serviço]</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Listagem tipos de serviço</li>
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
                        <div class="box-header">
                            
                            <small>Criar novo tipo de serviço
                                <a type="button" href="{{ route('Painel.PesquisaPatrimonial.tiposdeservico.create') }}" class="btn btn-sm  btn-success fa fa-plus" data-toggle="tooltip" data-placement="top" title="Adicionar novo tipo de serviço para a pesquisa patrimonial.">&nbsp;&nbsp;Novo</a>
                            </small>
                        </div>
                        
                        <div class="box-body">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th style="font-size: 12px">#</th>
                                    <th style="font-size: 12px">Estado</th>
                                    <th style="font-size: 12px">Descrição</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($datas as $data)  
                                    <tr>
                                        <td id="myInputTextField" style="font-size: 12px">{{ $data->Id}}</td>                  
                                        <td style="font-size: 12px">{{ $data->Estado }}</td>
                                        <td style="font-size: 12px">{{ $data->Descricao}}</td>
                                        <td>
        
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
