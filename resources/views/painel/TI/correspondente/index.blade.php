@extends('painel.Layout.header')
@section('title') Listagem solicitações @endsection <!-- Titulo da pagina -->

@section('header') 
<script src="https://code.jquery.com/jquery-1.9.1.js"></script>
@endsection

@section('header_title')
Listagem de solicitações de pagamento
@endsection

@section('submenu')
<li class="breadcrumb-item active" style="color: black">Formulário
</li>
@endsection

@section('body')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-action">
                <a href="{{ route('Painel.TI.correspondente.gerarExcelAbertas')}}" class="delete" target="_blank"><span class="waves-effect green waves-light btn fa fa-file-excel-o"> Exportar Dados</span></a>
            </div>
            <div class="card-content">
                <div class="responsive-table">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="font-size: 11px">#</th>
                                <th style="font-size: 11px">Pasta</th>
                                <th style="font-size: 11px">Correspondente</th>
                                <th style="font-size: 11px">Solicitante PL&C</th>
                                <th style="font-size: 11px">Setor PL&C</th>
                                <th style="font-size: 11px">Serviço</th>
                                <th style="font-size: 11px">Valor</th>
                                <th style="font-size: 10px">SubStatus</th>
                                <th style="font-size: 10px">Data Serviço</th>
                                <th style="font-size: 10px">Data Solicitação</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notas as $categoria)  
                                <tr>
                                    <td style="font-size: 11px">{{ $categoria->NumeroDebite }}</td>                  
                                    <td style="font-size: 11px">{{ $categoria->Pasta }}</td>
                                    <td style="font-size: 11px">{{ $categoria->Correspondente }}</td>
                                    <td style="font-size: 11px">{{ $categoria->Solicitante}}</td>
                                    <td style="font-size: 11px">{{ $categoria->Setor}}</td>
                                    <td style="font-size: 11px">{{ $categoria->TipoServicoDescricao}} </td>
                                    <td style="font-size: 11px">R$ <?php echo number_format($categoria->ValorTotal,2,",",".") ?></td>
                                    <td style="font-size: 10px">{{$categoria->descricao}}</td>
                                    <td style="font-size: 10px">{{ date('d/m/Y', strtotime($categoria->DataServico)) }}</td>
                                    <td style="font-size: 10px">{{ \Carbon\Carbon::parse($categoria->DataFicha)->diffForHumans() }}</td>
                                    <td>
                                    <!-- <a href="{{route('Painel.Correspondente.show', $categoria->NumeroDebite)}}" target="_blank" ><span class="btn btn-primary fa fa-book" value="{{$categoria->NumeroDebite}}" data-toggle="tooltip" data-placement="left" title="Clique aqui para acompanhar o workflow desta solicitação de pagamento." style="background-color:#4B4B4B;border-color:#4B4B4B;"></span></a> -->
                                    <a href="{{route('Painel.TI.correspondente.notificacoes', $categoria->NumeroDebite)}}" target="_blank" ><span class="waves-effect waves-light btn blue" value="{{$categoria->NumeroDebite}}" data-toggle="tooltip" data-placement="left" title="Clique aqui para acompanhar as notificações desta solicitação de pagamento." style="background-color:#4B4B4B;border-color:#4B4B4B;"><i class="material-icons">notification_add</i></span></a>
                                    </td>
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
