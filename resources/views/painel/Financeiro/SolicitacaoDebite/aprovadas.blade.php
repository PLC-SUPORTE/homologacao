@extends('painel.Layout.header')
@section('title') Listagem solicitações @endsection
<!-- Titulo da pagina -->

@section('header')

@endsection
@section('header_title')
Listagem para acompanhamento de debites pelo Financeiro
@endsection
@section('submenu')
<li class="active">Solicitações Aprovadas </li>
@endsection
@section('body')
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-action">
                    <a href="{{ route('Painel.Financeiro.gerarExcelAguardandoPagamento')}}" class="delete" target="_blank"><span class="btn green waves-effect waves-light"><i class="material-icons left">file_download</i> Exportar Dados</span></a>
                </div>
                <div class="card-content">
                    <div class="table-responsive">
                        <table id="example1" class="table table-bordered table-striped" style="font-size: 11px;">
                            <thead>
                            <tr>
                            <th style="font-size: 12px">#</th>
                            <th style="font-size: 12px">Correspondente</th>
                            <th style="font-size: 12px">Valor</th>
                            <th style="font-size: 12px">Tipo Serviço</th>
                            <th style="font-size: 12px">Data</th>
                            <th style="font-size: 12px">Ação</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($notas as $categoria)
                                <tr>
                                    <td style="font-size: 12px">{{ $categoria->NumeroDebite }}</td>                  
                                    <td style="font-size: 12px">{{ $categoria->name}}</td>
                                    <td style="font-size: 12px">{{ $categoria->ValorTotal}}</td>
                                    <td style="font-size: 12px">{{ $categoria->TipoServico}} </td>
                                    <td style="font-size: 12px">{{ date('d/m/Y', strtotime($categoria->DataFicha)) }}</td>
                                    <td>
                                    <a href="{{route('Painel.Financeiro.imprimir', $categoria->NumeroDebite)}}" class="delete"><span class="btn grey waves-effect waves-light tooltipped" data-toggle="tooltip" data-position="left" data-tooltip="Clique aqui para gerar o relatório desta solicitação de pagamento."><i class="material-icons">description</i></span></a>    
                                    <a href="{{route('Painel.Financeiro.show', $categoria->NumeroDebite)}}" target="_blank" ><span class="btn blue waves-effect waves-light tooltipped" value="{{$categoria->NumeroDebite}}" data-toggle="tooltip" data-position="left" data-tooltip="Clique aqui para acompanhar o workflow desta solicitação de pagamento."><i class="material-icons">insert_chart</i></span></a>
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
