@extends('painel.Layout.header')
@section('title') Solicitações pagas - Listagem de solicitações de pagamento pagas @endsection <!-- Titulo da pagina -->

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

      @import url(https://fonts.googleapis.com/css?family=Roboto+Condensed:300,400);

      .font-roboto {
        font-family: 'roboto condensed';
      }

      * {
        box-sizing: border-box;
      }

      .modal {
      text-align: center;
      padding: 0!important;
      top: 60px;
      }

      .modal-dialog {
        position: static;
        margin: 0;
        width: 55%;
        height: 75%;
        padding: 0;
        border: 10px solid #965A2C;
        border-radius: 10px;
        display: inline-block;
        text-align: left;
        vertical-align: middle;
        
      }

      .modal-content {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        border: 2px solid #965A2C;
        border-radius: 0;
        box-shadow: none;
      }

      .modal-header {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        height: 50px;
        padding: 10px;
        background: #965A2C;
        border: 0;
      }

      .modal-title {
        font-weight: 300;
        font-size: 2em;
        color: #fff;
        line-height: 30px;
      }

      .modal-body {
        position: absolute;
        top: 50px;
        bottom: 60px;
        width: 100%;
        font-weight: 300;
        overflow: auto;
      }

      .modal-footer {
        position: absolute;
        right: 0;
        bottom: 0;
        left: 0;
        height: 60px;
        padding: 10px;
        background: #f1f3f5;
      }
    </style>
@endsection

@section('header_title')
Listagem de solicitações de pagamento pagas
@endsection

@section('submenu')
<li class="breadcrumb-item active" style="color: black">Solicitações pagas
</li>
@endsection

@section('body')
  <div class="row">
    <div class="col s12">
      <div class="card">
        <div class="card-action">
          <a href="{{ route('Painel.Financeiro.gerarExcelPagas')}}" class="delete" target="_blank"><span class="btn green waves-effect waves-light"><i class="material-icons left">file_download</i> Exportar Dados</span></a>
        </div>
        <div class="card-content">
          <div class="table-responsive">
            <table id="example1" class="table table-bordered table-striped" style="font-size: 12px;">
              <thead>
                <tr>

                    <th>#</th>
                    <th>Ressalva</th>
                    <th>Pasta</th>
                    <th>Cliente</th>
                    <th>Setor</th>
                    <th>Tipo Serviço</th>
                    <th>Valor</th>
                    <th>Status</th>
                    <th>Data serviço</th>
                    <th>Data pagamento</th>
                    <th>Ação</th>
                </tr>
              </thead>
              <tbody>
                @foreach($notas as $categoria)
                  <tr>
                    <td>{{ $categoria->NumeroDebite }}</td>
                    <td>{{ $categoria->Ressalva}}</td>
                    <td>{{ $categoria->Pasta}}</td>
                    <td>{{ $categoria->Cliente}}</td>
                    <td>{{ $categoria->Setor}}</td>
                    <td>{{ $categoria->TipoServico}}</td>
                    <td>R$ {{ $categoria->ValorTotal}}</td>
                    @if($categoria->StatusID == "9")
                    <td>Aguardando comprovante</td>
                    @else
                    <td>Finalizada</td>
                    @endif
                    <td>{{ date('d/m/Y', strtotime($categoria->DataServico)) }}</td>
                    <td>{{ date('d/m/Y', strtotime($categoria->DataPagamento)) }}</td>
                    <td>     
                      @if($categoria->StatusID == "9")
                      <a href="" data-toggle="modal" data-target="#fsModal{{$categoria->NumeroDebite}}"><span class="btn btn-small waves-light waves-effect blue tooltipped" value="{{$categoria->NumeroDebite}}" data-toggle="tooltip" data-position="left" data-tooltip="Clique aqui para anexar o comprovante de pagamento."><i class="material-icons">upload_file</i></span></a>
                      @endif
                      <a href="{{route('Painel.Financeiro.imprimir', $categoria->NumeroDebite)}}" target="_blank" class="delete"><span class="btn btn-small waves-light waves-effect blue tooltipped"data-toggle="tooltip" data-position="left" data-tooltip="Clique aqui para gerar o relatório desta solicitação de pagamento."><i class="material-icons">article</i></span></a>
                      <a href="{{route('Painel.Financeiro.show', $categoria->NumeroDebite)}}" target="_blank" ><span class="btn btn-small waves-light waves-effect blue tooltipped" value="{{$categoria->NumeroDebite}}" data-toggle="tooltip" data-position="left" data-tooltip="Clique aqui para acompanhar o workflow desta solicitação de pagamento."><i class="material-icons">book</i></span></a>
                      <a href="{{route('Painel.Financeiro.recibo', $categoria->NumeroDebite)}}" target="_blank" class="delete"><span class="btn btn-small waves-light waves-effect blue tooltipped" data-toggle="tooltip" data-position="left" data-tooltip="Clique aqui para gerar o recibo desta solicitação de pagamento."><i class="material-icons">request_quote</i></span></a>
                    </td>
                  </tr>

                  <div id="fsModal{{$categoria->NumeroDebite}}" class="modal animated bounceIn" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <center> <h1 id="myModalLabel" class="modal-title">Anexar comprovante da solicitação: {{ $categoria->NumeroDebite}}</h1></center>
                        </div>
                        <div class="modal-body">
                          <div class="panel-body">
                            <div class="card card-default">
                              <form role="form" action="{{ route('Painel.Financeiro.comprovantepagamento')}}" method="POST" role="create" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="card-body">
                                  <div class="row">
                                    <div class="col s12">
                                      <div class="form-group">
                                        <label class="control-label">Número solicitação:</label>
                                        <input type="text" id="numerodebite" name="numerodebite" class="form-control" readonly="" value="{{ $categoria->NumeroDebite}}">
                                      </div>
                                    </div>
                                  </div>
            
                                  <div class="row">
                                    <div class="col-md-12">
                                      <div class="form-group">
                                        <label class="control-label">Anexar comprovante de pagamento:</label>
                                        <input  id="select_file" name="select_file" type='file' class="form-control" accept=".pdf" required />
                                      </div>
                                    </div>      
                                  </div>

                                  <button class="btn btn-primary nextBtn pull-right fa fa-arrow-right"  id="btn" name="btn" type="submit" style="background-color:#4B4B4B;border-color:#4B4B4B;">&nbsp;&nbsp;Anexar comprovante</button>
                                </div>  
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- modal -->   
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