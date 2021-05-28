@extends('Painel.Correspondente.Layouts.index')

<script src="https://code.jquery.com/jquery-1.9.1.js"></script>


@section('content')


@if($totalPendencias >= '1')
 <script>
    $(document).ready(function(){
        $('#exampleModalCenter').modal({
  backdrop: 'static',
  keyboard: false
});

    });
</script>  
@endif

    

<div class="modal modal-danger fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLongTitle">*****[PLC][Notificação][Alerta]*****</h5>
      </div>
      <div class="modal-body text-center">
      Existe {{$totalPendencias}} registro(s) pendente(s) para edição
      </div>
      <div class="modal-footer">
         <a href="{{ route('Painel.Correspondente.step3')}}" class="btn btn-success"> Continuar Registro </a>
         <a href="{{ route('Painel.Correspondente.DeletarRegistro')}}" class="btn btn-warning"> Deletar Registro </a>
      </div>
    </div>
  </div>
</div>


    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                [Listagem solicitações] -  
                <small>[Listagem de solicitações de pagamento - Status principal/Substatus]</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Painel.Correspondente.principal') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Formulário</li>
            </ol>
        </section>
        <section class="content">
            @include('flash::message')
            <div class="row">

                <div class="col-xs-12">
                   
          <div class="float-right">
                      <small>
                          <a href="{{ route('Painel.Correspondente.gerarExcelAbertas')}}" class="delete" target="_blank"><span class="btn btn-success fa fa-file-excel-o"> Exportar Dados</span></a>
                      </small>
                  </div>
                    <div class="box">
                        <div class="box-header">
                            
                            <small>Adicionar nova solicitação de pagamento:
                                <a type="button" href="{{ route('Painel.Correspondente.create') }}" class="btn btn-sm  btn-success fa fa-plus" data-toggle="tooltip" data-placement="top" title="Adicionar nova solicitação de pagamento para Correspondente.">&nbsp;&nbsp;Novo</a>
                            </small>
                        </div>
                        
                        <div class="box-body">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th style="font-size: 12px">#</th>
                                    <th style="font-size: 12px">Pasta</th>
                                    <th style="font-size: 12px">Data Solicitação</th>
                                    <th style="font-size: 12px">Solicitante PL&C</th>
                                    <th style="font-size: 12px">Serviço</th>
                                    <th style="font-size: 12px">Valor</th>
                                    <th style="font-size: 12px">Status</th>
                                    <th style="font-size: 12px">SubStatus</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($notas as $categoria)  
                                    <tr>

                        <!--Inicio Modal Anexos --> 
                        <div class="modal fade" id="anexo{{$categoria->NumeroDebite}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <button type="button" class="btn  mr-sm-1 mr-2 modal-close red" data-dismiss="modal" style="margin-left: 1255px; margin-top: 5px;">
                        <i class="material-icons">close</i> 
                        </button>
                        <iframe style=" position:absolute;
                           top:60;
                           left:0;
                           width:100%;
                           height:100%;" src="{{ route('Painel.Correspondente.anexo', $categoria->NumeroDebite) }}"></iframe>
                        </div>
                        <!--Fim Modal Anexos --> 

                                        <td id="myInputTextField" style="font-size: 12px">{{ $categoria->NumeroDebite }}</td>                  
                                        <td style="font-size: 12px">{{ $categoria->Pasta }}</td>
                                        <td style="font-size: 12px">{{ date('d/m/Y', strtotime($categoria->DataFicha)) }}</td>
                                        <td style="font-size: 12px">{{ $categoria->Solicitante}}</td>
                                        <td style="font-size: 12px">{{ $categoria->TipoServicoDescricao}} </td>
                                        <td style="font-size: 12px">R$ {{ $categoria->ValorTotal}}</td>
                                        <td style="font-size: 12px">Em Andamento</td>
                                        <td style="font-size: 12px">{{$categoria->descricao}}</td>
                                        <td>
                                        <a href="{{route('Painel.Correspondente.show', $categoria->NumeroDebite)}}" target="_blank" ><span class="btn btn-primary fa fa-book" value="{{$categoria->NumeroDebite}}" data-toggle="tooltip" data-placement="left" title="Clique aqui para acompanhar o workflow desta solicitação de pagamento." style="background-color:#4B4B4B;border-color:#4B4B4B;"></span></a>
                                         @if($categoria->StatusID == "8")
                                        <a href="{{route('Painel.Correspondente.corrigir', $categoria->NumeroDebite)}}" class="delete"><span class="btn btn-primary fa fa-edit"data-toggle="tooltip" data-placement="left" title="Clique aqui para corrigir está solicitação de pagamento." style="background-color:#4B4B4B;border-color:#4B4B4B;"></span></a>
                                         @elseif($categoria->StatusID == "10")
                                        <a href="{{route('Painel.Correspondente.corrigir', $categoria->NumeroDebite)}}" class="delete"><span class="btn btn-primary fa fa-edit"data-toggle="tooltip" data-placement="left" title="Clique aqui para corrigir está solicitação de pagamento."style="background-color:#4B4B4B;border-color:#4B4B4B;"></span></a>
                                        @elseif($categoria->StatusID != "7" && $categoria->StatusID != "12" && $categoria->StatusID != "14")
                                        <a href="{{route('Painel.Correspondente.cancelar', $categoria->NumeroDebite)}}" class="delete"><span class="btn btn-danger fa fa-remove"data-toggle="tooltip" data-placement="left" title="Clique aqui para cancelar está solicitação de pagamento."></span></a>
                                        @endif

                                        <a href="#" data-toggle="modal" data-target="#anexo{{$categoria->NumeroDebite}}" ><span class="btn btn-primary fa fa-file-pdf-o" value="{{$categoria->NumeroDebite}}" data-toggle="tooltip" data-placement="left" title="Clique aqui para acompanhar os anexos desta solicitação de pagamento." style="background-color:#4B4B4B;border-color:#4B4B4B;"></span></a>

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
