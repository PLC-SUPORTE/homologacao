@extends('Painel.Correspondente.Layouts.index')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                [Corrigir Solicitação]
                <small>[Edição da solicitação de pagamento]</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Painel.Correspondente.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Edição da solicitação de pagamento</li>
            </ol>
        </section>
        
       
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script> 


<script language="javascript">   
function moeda2(a, e, r, t) {
    let n = ""
      , h = j = 0
      , u = tamanho2 = 0
      , l = ajd2 = ""
      , o = window.Event ? t.which : t.keyCode;
    if (13 == o || 8 == o)
        return !0;
    if (n = String.fromCharCode(o),
    -1 == "0123456789".indexOf(n))
        return !1;
    for (u = a.value.length,
    h = 0; h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r); h++)
        ;
    for (l = ""; h < u; h++)
        -1 != "0123456789".indexOf(a.value.charAt(h)) && (l += a.value.charAt(h));
    if (l += n,
    0 == (u = l.length) && (a.value = ""),
    1 == u && (a.value = "0" + r + "0" + l),
    2 == u && (a.value = "0" + r + l),
    u > 2) {
        for (ajd2 = "",
        j = 0,
        h = u - 3; h >= 0; h--)
            3 == j && (ajd2 += e,
            j = 0),
            ajd2 += l.charAt(h),
            j++;
        for (a.value = "",
        tamanho2 = ajd2.length,
        h = tamanho2 - 1; h >= 0; h--)
            a.value += ajd2.charAt(h);
        a.value += r + l.substr(u - 2, u)
    }
    return !1
}

 </script>   
        
        
       <div class="panel-heading">
                 <h3 class="panel-title">Dados da Solicitação</h3>
            </div>
            <div class="panel-body">
        <div class="card card-default">
        
        @include('flash::message')
        
         <form role="form" onsubmit="btnsubmit.disabled = true; return true;" action="{{ route('Painel.Correspondente.corrigido', $notas->NumeroDebite)}}" method="POST" role="create" enctype="multipart/form-data" >
        {{ csrf_field() }}
        
          <div class="card-body">
            <div class="row">
              <div class="col-md-2">
                <div class="form-group">
                   <div class="form-group">
                       <center>
                    <label class="control-label">Número Processo</label>
                       </center>
                <input type="text" id="numerodebite" name="numerodebite" class="form-control" readonly="" value="{{$notas->NumeroDebite}}">
                </div>
                </div>
              </div>
                
                
               <div class="col-md-3">
               <div class="form-group">
                   <div class="form-group">
                       <center>
                    <label class="control-label">Status Atual</label>
                       </center>
                <input type="text" id="inputClientCompany" readonly="" class="form-control" value="{{$notas->descricao}}">
                </div>
                </div>
              </div> 
                
              <div class="col-md-2">
                <div class="form-group">
                   <div class="form-group">
                       <center>
                    <label class="control-label">Valor Total</label>
                       </center>
                       <input name="ValorT" id="ValorT" value="{{$notas->ValorTotal}}"  type="text" required="required" maxlength="200" pattern="(?:\.|,|[0-9])*" class="form-control" placeholder="Valor(R$)" onKeyPress="return(moeda2(this,'.',',',event))" data-toggle="tooltip" data-placement="top" title="Preencha o valor da diligência conforme acordado pelo contratante">
                </div>
                </div>
              </div> 
                
              <div class="col-md-3">
                <div class="form-group">
                   <div class="form-group">
                       <center> 
                           <label class="control-label">Data Solicitação</label>
                       </center>    
                <input type="text" id="data" name="data" readonly="" value="{{ date('d/m/Y H:m:s', strtotime($notas->DataFicha)) }}" class="form-control" placeholder="Data Solicitação">
                </div>
                </div>
              </div> 
              </div>
              
              <div class="row">
               <div class="col-md-12">
                <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Observação:</label>
                    <textarea id="observacao" rows="4" type="text" name="observacao" class="form-control" placeholder="Digite a observação" style="text-align:left; overflow:auto;" required />
                    {{$notas->observacao}}</textarea>
                </div>
                </div>
              </div>     
              </div>
              
              <div class="col-md-12" hidden="">
                <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Híst:</label>
                    <textarea id="hist" rows="4" type="text" name="hist" readonly="" class="form-control" placeholder="Hist debite">
{{$notas->Hist}}
Editada pelo(a): {{Auth::user()->name}} na solicitação de pagamento - {{$dataHoraMinuto}}
                    </textarea>
                </div>
                </div>
              </div> 
              
              <input type="hidden" id="anexo" name="anexo"class="form-control" value="{{$notas->anexo}}">
              
             <div class="row">
                <div class="col-md-6">
                <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Anexar Nota Fiscal/Fatura/Recibo:</label>
                    <input  id="select_file" name="select_file" type='file' class="form-control" accept=".jpg,.png,.xls,.xlsx,.pdf,.doc,.docx,.rtf,.jpeg" required />
                </div>
                </div>
              </div>      
              </div>
                            
              <div class="row">
               <div class="col-md-12">
                <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Motivo Reprovação:</label>
                    <textarea id="observacao" rows="4" type="text" name="observacaomotivo"  readonly="" class="form-control" placeholder="Motivo da reprovação" style="text-align:left; overflow:auto;">
                  {{$notas->motivo}} -  {{$notas->observacaomotivo}}</textarea>
                </div>
                </div>
              </div>     
              </div>
              
              <button class="btn btn-primary nextBtn pull-right fa fa-arrow-right" id="btnsubmit" type="submit" style="background-color:#4B4B4B;border-color:#4B4B4B;">&nbsp;&nbsp;Corrigir solicitação</button>

              </div>
              </div>  
              </form>
           
        </div>
    </div>
@endsection