@extends('Painel.DPRH.Layouts.index')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                [Formulário] -
                <small>[Listagem de colaboradores inativos na PL&C]</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Painel.DPRH.principal') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Listagem de colaboradores inativos na PL&C </li>
            </ol>
        </section>
        <section class="content">
            @include('flash::message')
            <div class="row">
                <div class="col-xs-12">
                  <div class="float-right">
                      <small>
                          <a href="{{ route('Painel.DPRH.Colaboradores.gerarExcelInativos')}}" class="delete" target="_blank"><span class="btn btn-success fa fa-file-excel-o"> Exportar Dados</span></a>
                      </small>
                  </div>
                    <div class="box ">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>CPF</th>
                                    <th>Nome</th>
                                    <th>Data Admissão</th>
                                    <th>Data Saída</th>
                                    <th>Setor</th>
                                    <th>Descrição</th>
                                    <th>UF</th>
                                    <th>Ação</th>
                                </tr>
                                </thead>
                                <tbody>
                                
                                @foreach($datas as $data)
                                    <tr>
                                        <td>{{ $data->CPF }}</td>                  
                                        <td>{{ $data->Nome}}</td>
                                        <td>{{ date('d/m/Y', strtotime($data->dt_entrada)) }}</td>
                                        <td>{{ date('d/m/Y', strtotime($data->dt_saida)) }}</td>
                                        <td>{{ $data->Setor}} </td>
                                        <td>{{ $data->SetorDescricao}} </td>
                                        <td>{{ $data->UF}}</td>
                                        <td>
                                       <a href="" class="delete"><span class="btn btn-primary fa fa-eye"data-toggle="tooltip" data-placement="left" title="Clique aqui para visualizar o perfíl deste usuário."></span></a>
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


