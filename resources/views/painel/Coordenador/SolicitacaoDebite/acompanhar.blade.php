@extends('Painel.Coordenador.Layouts.index')

@section('content')


    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                [Acompanhamento solicitações] -
                <small>[Listagem para acompanhamento de debites]</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Listagem para acompanhamento de debites </li>
            </ol>
        </section>
        <section class="content">
            @include('flash::message')
            <div class="row">

                <div class="col-xs-12">
          <div class="float-right">
                      <small>
                          <a href="{{ route('Painel.Coordenador.gerarExcelAcompanhamento')}}" class="delete" target="_blank"><span class="btn btn-success fa fa-file-excel-o"> Exportar Dados</span></a>
                      </small>
                  </div>
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                <th style="font-size: 11px">#</th>
                                <th style="font-size: 11px">Pasta</th>
                                <th style="font-size: 11px">Número processo</th>
                                <th style="font-size: 11px">Correspondente</th>
                                <th style="font-size: 11px">Solicitante PL&C</th>
                                <th style="font-size: 11px">Setor PL&C</th>
                                <th style="font-size: 11px">Tipo serviço </th>
                                <th style="font-size: 11px">Valor(R$)</th>
                                <th style="font-size: 11px">Data serviço</th>
                                <th style="font-size: 11px">Data solicitação</th>
                                <th style="font-size: 11px">Status</th>
                                <th style="font-size: 11px">Ação</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($notas as $categoria)
                                    <tr>
                                        <td style="font-size: 11px">{{ $categoria->NumeroDebite }}</td>    
                                        <td style="font-size: 11px">{{ $categoria->Pasta}} </td>
                                        <td style="font-size: 11px">{{ $categoria->NumeroProcesso}} </td>              
                                        <td style="font-size: 11px">{{ $categoria->name}}</td>
                                        <td style="font-size: 11px">{{ $categoria->Solicitante}} </td>
                                        <td style="font-size: 11px">{{ $categoria->Setor}} </td>
                                        <td style="font-size: 11px">{{ $categoria->Tiposervico}} </td>
                                        <td style="font-size: 11px">R$ {{ $categoria->ValorTotal}}</td>
                                        <td style="font-size: 11px">{{ date('d/m/Y', strtotime($categoria->DataServico)) }}</td>
                                        <td style="font-size: 11px">{{ date('d/m/Y', strtotime($categoria->DataFicha)) }}</td>
                                        <td style="font-size: 11px">{{ $categoria->descricao}}</td>
                                        <td>
                                       <a href="{{route('Painel.Coordenador.imprimir', $categoria->NumeroDebite)}}" target="_blank" class="delete"><span class="btn btn-primary fa fa-file"data-toggle="tooltip" data-placement="left" title="Clique aqui para gerar o relatório desta solicitação de pagamento." style="background-color:#4B4B4B;border-color:#4B4B4B;"></span></a>
                                       <a href="{{route('Painel.Coordenador.show', $categoria->NumeroDebite)}}" target="_blank" ><span class="btn btn-primary fa fa-book" value="{{$categoria->NumeroDebite}}" data-toggle="tooltip" data-placement="left" title="Clique aqui para acompanhar o workflow desta solicitação de pagamento." style="background-color:#4B4B4B;border-color:#4B4B4B;"></span></a>
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
