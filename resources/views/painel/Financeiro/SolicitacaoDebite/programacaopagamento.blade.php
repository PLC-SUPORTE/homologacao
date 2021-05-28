@extends('painel.Layout.header')
@section('title') Solicitações - Listagem para acompanhamento de debites pelo Financeiro @endsection <!-- Titulo da pagina -->

@section('header') 
    <style>
        body::-webkit-scrollbar-track {
            background-color: #F4F4F4;
        }       
        body::-webkit-scrollbar {
            width: 6px;
            background: #F4F4F4;
        }
        body::-webkit-scrollbar-thumb {
            background: #dad7d7;
        }
    </style>
@endsection

@section('header_title')
Listagem para acompanhamento de debites pelo Financeiro
@endsection

@section('submenu')
<li class="breadcrumb-item active" style="color: black">Solicitações
</li>
@endsection

@section('body')
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-action">
                    <a href="{{ route('Painel.Financeiro.gerarExcelAguardandoPagamento')}}" class="delete" target="_blank"><span class="btn waves-effect waves-light green fa fa-file-excel-o"> Exportar Dados</span></a>
                </div>
                <div class="card-content">
                    <div class="table-responsive">
                        <table id="example1" class="table table-bordered table-striped" style="font-size: 11px;">
                            <thead>
                                <tr>
                                    <th style="font-size: 12px">#</th>
                                    <th style="font-size: 12px">Pasta</th>
                                    <th style="font-size: 12px">Correspondente</th>
                                    <th style="font-size: 12px">Solicitante</th>
                                    <th style="font-size: 12px">Valor</th>
                                    <th style="font-size: 12px">Tipo Serviço</th>
                                    <th style="font-size: 12px">Data Serviço</th>
                                    <th style="font-size: 12px">Data Programada</th>
                                    <th style="font-size: 12px">Data Vencimento</th>
                                    <th style="font-size: 12px">Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notas as $categoria)
                                    <tr>
                                        <td style="font-size: 12px">{{ $categoria->NumeroDebite }}</td>
                                        <td style="font-size: 12px">{{ $categoria->Pasta}}</td>
                                        <td style="font-size: 12px">{{ $categoria->name}}</td>
                                        <td style="font-size: 12px">{{ $categoria->Solicitante}}</td>
                                        <td style="font-size: 12px">R$ {{ $categoria->ValorTotal}}</td>
                                        <td style="font-size: 12px">{{ $categoria->TipoServico}} </td>
                                        <td style="font-size: 12px">{{ date('d/m/Y', strtotime($categoria->DataServico)) }}</td>
                                        <td style="font-size: 12px">{{ date('d/m/Y', strtotime($categoria->DataProgramacao)) }}</td>
                                        <td style="font-size: 12px">{{ date('d/m/Y', strtotime($categoria->DataVencimento)) }}</td>
                                        <td>
                                        <a href="{{route('Painel.Financeiro.baixar', $categoria->NumeroDebite)}}" class="delete"><span class="btn btn-small waves-effect waves-light green tooltipped" data-toggle="tooltip" data-position="left" data-tooltip="Clique aqui para realizar a conciliação bancária."><i class="material-icons">done</i></span></a>    
                                        <a href="{{route('Painel.Financeiro.imprimir', $categoria->NumeroDebite)}}" class="delete" target="_blank"><span class="btn btn-small waves-effect waves-light blue tooltipped" data-toggle="tooltip" data-position="left" data-tooltip="Clique aqui para gerar o relatório desta solicitação de pagamento."><i class="material-icons">description</i></span></a>    
                                        <a href="{{route('Painel.Financeiro.show', $categoria->NumeroDebite)}}" target="_blank" ><span class="btn btn-small waves-effect waves-light blue tooltipped" value="{{$categoria->NumeroDebite}}" data-toggle="tooltip" data-position="left" data-tooltip="Clique aqui para acompanhar o workflow desta solicitação de pagamento." ><i class="material-icons">book</i></span></a>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection