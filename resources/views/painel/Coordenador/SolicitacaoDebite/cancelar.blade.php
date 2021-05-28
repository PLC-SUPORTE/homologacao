@extends('Painel.Coordenador.Layouts.index')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                [Cancelar Solicitação] - 
                <small>[Indique o motivo do cancelamento e preencha o campo observação]</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Cancelar Solicitação</li>
            </ol>
        </section>
        
        
       <div class="panel-heading">
                 <h3 class="panel-title">Dados da Solicitação</h3>
            </div>
            <div class="panel-body">
        <div class="card card-default">
        
        @include('flash::message')
        
         <form role="form" onsubmit="btnsubmit.disabled = true; return true;" action="{{ route('Painel.Coordenador.darbaixa', $notas->NumeroDebite)}}" method="POST" role="create" >
        {{ csrf_field() }}
        
          <div class="card-body">
            <div class="row">
              <div class="col-md-2">
                <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Número</label>
                <input type="text" id="numerodebite" name="numerodebite" class="form-control" readonly="" value="{{$notas->NumeroDebite}}">
                </div>
                </div>
              </div>
                
                
               <div class="col-md-3">
               <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Status Atual</label>
                <input type="text" id="inputClientCompany" readonly="" class="form-control" value="{{$notas->descricao}}">
                </div>
                </div>
              </div> 
                
              <div class="col-md-2">
                <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Valor Total</label>
                <input type="text" id="ValorT" name="ValorT" readonly="" value="R$ {{$notas->ValorTotal}}" class="form-control" placeholder="Valor(R$)">
                </div>
                </div>
              </div> 
                
              <div class="col-md-3">
                <div class="form-group">
                   <div class="form-group">
                           <label class="control-label">Data Solicitação</label>
                <input type="text" id="data" name="data" readonly="" value="{{ date('d/m/Y H:m:s', strtotime($notas->DataFicha)) }}" class="form-control" placeholder="Data Solicitação">
                </div>
                </div>
              </div> 

              <div class="col-md-12">
                <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Histórico preview:</label>
                    <textarea id="hist" rows="4" type="text" name="hist" readonly="" class="form-control" style="text-align: left;margin: 0;" placeholder="Hist debite">
{{$notas->Hist}}
Solicitação de debite cancelada pelo(a): {{Auth::user()->name}} no portal PL&C Advogados - {{$dataHoraMinuto}}
                    </textarea>
                </div>
                </div>
              </div> 
             
             <div class="col-md-6">
               <div class="form-group">
                  <label>Selecione o motivo do cancelamento:</label>
                  <select class="form-control select2" style="width: 100%;" id="motivo" name="motivo"  data-toggle="tooltip" data-placement="top" title="Selecione o motivo da reprovação.">
                   @foreach($motivos as $motivo)   
                    <option selected="selected">{{ $motivo->descricao }}</option>
                   @endforeach
                  </select>
                </div>
           </div>
            </div>
              
              <div class="row">
               <div class="col-md-12">
                <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Observação</label>
                    <textarea required id="observacao" rows="4" type="text" name="observacao" class="form-control" placeholder="Digite a observação" style="text-align:left; overflow:auto;">
                    </textarea>
                </div>
                </div>
              </div>     
              </div>
              
            <button class="btn btn-primary nextBtn pull-right fa fa-arrow-right"  id="btnsubmit" name="btn" type="submit" style="background-color:#4B4B4B;border-color:#4B4B4B;" onClick="this.form.submit(); this.disabled=true; this.value='Avançando…';">&nbsp;&nbsp;Cancelar Solicitação</button>

              </div>
              </div>  
              </form>
           
        </div>
    </div>
@endsection