@extends('Painel.Coordenador.Layouts.index')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                [Aprovação Revisão Técnica] - 
                <small>[Formulário para aprovação de solicitação de pagamento de correspondente]</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Aprovação de pagamento correspondente</li>
            </ol>
        </section>
        
        <br><br>
        <div class="panel-body">
        <div class="card card-default">
        
        @include('flash::message')
        
         <form role="form" onsubmit="btnsubmit.disabled = true; return true;" action="{{ route('Painel.Coordenador.aprovado', $notas->NumeroDebite)}}" method="POST" role="create" >
        {{ csrf_field() }}
        
          <div class="card-body">
            <div class="row">
                 
              <div class="col-md-2">
                <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Número solicitação:</label>
                <input type="text" id="numerodebite" name="numerodebite" class="form-control" readonly="" value="{{$notas->NumeroDebite}}">
                </div>
                </div>
              </div>
                
               <div class="col-md-2">
                <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Código pasta:</label>
                <input type="text" id="pasta" name="pasta" class="form-control" readonly="" value="{{$notas->Pasta}}">
                </div>
                </div>
              </div>
                
                
               <div class="col-md-2">
               <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Status debite Advwin:</label>
                @if($notas->Status_Debite == "0")       
                <input type="text" id="inputClientCompany" readonly="" class="form-control" value="Ok para cobrança">
                @elseif($notas->Status_Debite == "2")
                <input type="text" id="inputClientCompany" readonly="" class="form-control" value="Bloqueado">
                @endif
                </div>
                </div>
              </div> 
                
              <div class="col-md-3">
               <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">SubStatus:</label>
                <input type="text" id="inputClientCompany" readonly="" class="form-control" value="{{$notas->descricao}}">
                </div>
                </div>
              </div> 
                
              <div class="col-md-2">
                <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Valor Total:</label>
                <input type="text" id="ValorT" name="ValorT" readonly="" value="{{$notas->ValorTotal}}" class="form-control" placeholder="Valor(R$)">
                </div>
                </div>
              </div> 
                
             <div class="col-md-2">
                <div class="form-group">
                   <div class="form-group">
                           <label class="control-label">Data serviço:</label>
                    <input id="dataservico2" name="dataservico2" readonly="" value="{{ date('d/m/Y', strtotime($notas->DataServico)) }}" class="form-control" placeholder="Data Serviço">
                   <input type="hidden" id="dataservico" name="dataservico" readonly="" value="{{$notas->DataServico}}" class="form-control" placeholder="Data Serviço">
                   </div>
                </div>
              </div> 
                
              <div class="col-md-2">
                <div class="form-group">
                   <div class="form-group">
                           <label class="control-label">Data Solicitação:</label>
                           <input type="text" id="datasolicitacao2" name="datasolicitacao2" readonly="" value="{{ date('d/m/Y', strtotime($notas->DataFicha)) }}" class="form-control" placeholder="Data Solicitação">
                           <input type="hidden" id="datasolicitacao" name="datasolicitacao" readonly="" value="{{$notas->DataFicha}}" class="form-control" placeholder="Data Solicitação">
                   </div>
                </div>
              </div> 
                
                <div class="col-md-1">
                <div class="form-group">
                   <div class="form-group">
                           <label class="control-label">UF:</label>
                <input type="text" id="uf" name="uf"readonly="" value="{{$notas->UF}}" class="form-control" placeholder="UF">
                </div>
                </div>
              </div> 
                
               <div class="col-md-4">
               <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Tribunal:</label>
                <input type="text" id="inputClientCompany" readonly="" class="form-control" value="{{$notas->DescricaoTribunal}}">
                </div>
                </div>
              </div> 
                
               <div class="col-md-2">
               <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Comarca:</label>
                <input type="text" id="inputClientCompany" readonly="" class="form-control" value="{{$notas->Comarca}}">
                </div>
                </div>
              </div> 
                
                
              <div class="col-md-2">
               <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Número Processo:</label>
                <input type="text" id="numeroprocesso" name="numeroprocesso" readonly="" class="form-control" value="{{$notas->NumPrc1_Sonumeros}}">
                </div>
                </div>
              </div> 
                
               <div class="col-md-2">
               <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">PRConta:</label>
                <input type="text" id="prconta" name="prconta" readonly="" class="form-control" value="{{$notas->PRConta}}">
                </div>
                </div>
              </div> 
                
              <div class="col-md-2">
               <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Contrato:</label>
                <input type="text" id="contrato" name="contrato" readonly="" class="form-control" value="{{$notas->Contrato}}">
                </div>
                </div>
              </div>
                
              <div class="col-md-2">
               <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Código setor:</label>
                <input type="text" id="setor" name="setor" readonly="" class="form-control" value="{{$notas->Setor}}">
                </div>
                </div>
              </div> 
                
               <div class="col-md-3">
               <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Setor:</label>
                <input type="text" id="inputClientCompany" readonly="" class="form-control" value="{{$notas->SetorDescricao}}">
                </div>
                </div>
              </div> 
                
               <div class="col-md-2">
               <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Unidade:</label>
                    <input type="text" id="unidade" name="unidade" readonly="" class="form-control" value="{{$notas->Unidade}}">
                </div>
                </div>
              </div> 
                
               <div class="col-md-2">
               <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Unidade descrição:</label>
                <input type="text" id="unidadedescricao" readonly="" class="form-control" value="{{$notas->UnidadeDescricao}}">
                </div>
                </div>
              </div> 
                
                
               <div class="col-md-1">
               <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Moeda:</label>
                <input type="text" id="moeda" name="moeda" readonly="" class="form-control" value="{{$notas->Moeda}}">
                </div>
                </div>
              </div> 
                
               <div class="col-md-2">
               <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Banco:</label>
                <input type="text" id="banco" name="banco" readonly="" class="form-control" value="{{$notas->Banco}}">
                </div>
                </div>
              </div> 
                
               <div class="col-md-3">
                <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">RefCliente:</label>
                <input type="text" id="refcliente" name="refcliente" class="form-control" readonly="" value="{{$notas->RefCliente}}">
                </div>
                </div>
              </div>
                
              <div class="col-md-12" hidden="">
                <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Histórico preview:</label>
                    <textarea id="hist" rows="4" type="text" name="hist" readonly="" class="form-control" style="text-align: left;margin: 0;" placeholder="Hist debite">
{{$notas->Hist}}
Ficha aprovada pelo: {{Auth::user()->name}} na Revisão Técnica - {{$dataHoraMinuto}}
                    </textarea>
                </div>
                </div>
              </div> 
                
                
              <div class="col-md-2">
                <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Status Pasta:</label>
                <input type="text" id="statuspasta" name="statuspasta" class="form-control" readonly="" value="{{$notas->PastaStatus}}">
                </div>
                </div>
              </div>  
                
              <div class="col-md-3">
                <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Descrição Pasta:</label>
                <input type="text" id="descricaopasta" name="descricaopasta" class="form-control" readonly="" value="{{$notas->PastaDescricao}}">
                </div>
                </div>
              </div>  
                
             <div class="col-md-5">
               <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Outra parte:</label>
                <input type="text" id="inputClientCompany" readonly="" class="form-control" value="{{$notas->OutraParte}}">
                </div>
                </div>
              </div>  
                
               <div class="col-md-3">
                <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Status Contrato:</label>
                <input type="text" id="contratostatus" name="contratostatus" class="form-control" readonly="" value="{{$notas->ContratoStatus}}">
                </div>
                </div>
              </div>  
                     
              <div class="col-md-12">
                <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Descrição Contrato</label>
                    <textarea id="observacao" rows="4" type="text" name="contratodescricao" readonly="" class="form-control" placeholder="Descrição Contrato">
{{$notas->ContratoDescricao}}
                    </textarea>
                </div>
                </div>
              </div> 
                
           <div class="col-md-6">
               <div class="form-group">
                  <label>Essa despesa é reembonsável pelo cliente?</label>
                  <select class="form-control select2" required="required" style="width: 100%;" id="ressalva" name="ressalva"  data-toggle="tooltip" data-placement="top" title="Selecione se esta solicitação de pagamento é reembonsável pelo cliente.">
                      <option selected="selected" value=""></option>
                      <option value="SIM">SIM</option>
                      <option value="NÃO">NÃO</option>
                  </select>
                </div>
           </div>
              </div>                          
           <br>
           <div style="margin-top: -43px;margin-left: 774px;">    
            <button class="btn btn-primary nextBtn fa fa-check"  id="btnsubmit" name="btn" type="submit" style="background-color:#4B4B4B;border-color:#4B4B4B;">&nbsp;&nbsp;Aprovar solicitação</button>
           </div>
          </div>
              </div>
              </div>  
              </form>
          <div style="margin-top: -43px;margin-left: 950px;">    
          <a href="{{route('Painel.Coordenador.reprovar', $notas->NumeroDebite)}}"><span class="btn btn-info fa fa-edit"data-toggle="tooltip" data-placement="left" title="Clique aqui para reprovar e retornar esta solicitação de pagamento para o Correspondente."  style="background-color:#4B4B4B;border-color:#4B4B4B;">&nbsp;&nbsp;Reprovar solicitação</span></a>
          <a href="{{route('Painel.Coordenador.cancelar', $notas->NumeroDebite)}}" class="delete"><span class="btn btn-danger fa fa-remove" data-toggle="tooltip" data-placement="left" title="Clique aqui para reprovar e cancelar esta solicitação de pagamento.">&nbsp;&nbsp;Cancelar solicitação</span></a>
          </div>

          <br><br>
        </div>
@endsection