@extends('Painel.Coordenador.Layouts.index')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                [Aprovação solicitação] -
                <small>[Listagem para aprovação de debites pela revisão técnica]</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Listagem para aprovação de debites pela revisão técnica </li>
            </ol>
        </section>
        <section class="content">
            @include('flash::message')
            <div class="row">
                <div class="col-xs-12">
          <div class="float-right">
                      <small>
                          <a href="{{ route('Painel.Coordenador.gerarExcelAbertas')}}" class="delete" target="_blank"><span class="btn btn-success fa fa-file-excel-o"> Exportar Dados</span></a>
                      </small>
                  </div>
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped" style="font-size: 12px">
                                <thead>
                                <tr>
                                <th style="font-size: 12px">#</th>
                                <th style="font-size: 12px">Pasta</th>
                                <th style="font-size: 12px">Número processo</th>
                                <th style="font-size: 12px">Correspondente</th>
                                <th style="font-size: 12px">Solicitante PL&C</th>
                                <th style="font-size: 12px">Setor PL&C</th>
                                <th style="font-size: 12px">Tipo serviço</th>
                                <th style="font-size: 12px">Valor(R$)</th>
                                <th style="font-size: 12px">Data serviço</th>
                                <th style="font-size: 12px">Data solicitação</th>
                                <th style="font-size: 12px">Ação</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($notas as $categoria)
                                    <tr>                                                                 
                                        <td style="font-size: 12px">{{ $categoria->NumeroDebite }}</td> 
                                        <td style="font-size: 12px">{{ $categoria->Pasta }}</td>  
                                        <td style="font-size: 12px">{{ $categoria->NumeroProcesso }} </td>
                                        <td style="font-size: 12px">{{ $categoria->name}}</td>
                                        <td style="font-size: 12px">{{ $categoria->Solicitante}} </td>
                                        <td style="font-size: 12px">{{ $categoria->Setor}} </td>
                                        <td style="font-size: 12px">{{ $categoria->Tiposervico}}</td>
                                        <td style="font-size: 12px">R$ {{ $categoria->ValorTotal}}</td>
                                        <td style="font-size: 12px">{{ date('d/m/Y', strtotime($categoria->DataServico)) }}</td>
                                        <td style="font-size: 12px">{{ date('d/m/Y', strtotime($categoria->DataFicha)) }}</td>
                                        <td> 
                                       <a href="{{route('Painel.Coordenador.aprovar', $categoria->NumeroDebite)}}"><span class="btn btn-info fa fa-eye"data-toggle="tooltip" data-placement="left" title="Clique aqui para vizualizar a solicitação de pagamento."  style="background-color:#4B4B4B;border-color:#4B4B4B;"></span></a>
                                       <a href="{{route('Painel.Coordenador.imprimir', $categoria->NumeroDebite)}}" target="_blank"><span class="btn btn-info fa fa-file" data-toggle="tooltip" data-placement="left" title="Clique aqui para gerar o relatório desta solicitação de pagamento."  style="background-color:#4B4B4B;border-color:#4B4B4B;"></span></a>
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
