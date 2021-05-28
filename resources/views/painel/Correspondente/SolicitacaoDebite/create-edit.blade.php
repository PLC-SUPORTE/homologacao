@extends('Painel.Correspondente.Layouts.index')

@section('content')

<script src="https://code.jquery.com/jquery-1.9.1.js"></script>
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
                [Solicitação de Pagamento] - 
                <small>Etapa 1 - Indique o tipo de solicitação, e insira o número fornecido na fase de contratação.</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Painel.Correspondente.principal') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Etapa 1</li>
            </ol>
        </section>
        <section class="content-header">
         @include('flash::message')
            <div class="row">

<script src="../../../../public/dist/css/stepbystep.css"></script>
<script src="../../../../public/dist/js/stepbystep.js"></script>
<script src="https://code.jquery.com/jquery-1.9.1.js"></script>

<style>.wizard {
    margin: 20px auto;
    background: #ecf0f5;
}

    .wizard .nav-tabs {
        position: relative;
        margin: 40px auto;
        margin-bottom: 0;
        border-bottom-color: #e0e0e0;
    }

    .wizard > div.wizard-inner {
        position: relative;
    }

.connecting-line {
    height: 2px;
    background: #e0e0e0;
    position: absolute;
    width: 80%;
    margin: 0 auto;
    left: 0;
    right: 0;
    top: 50%;
    z-index: 1;
}

.wizard .nav-tabs > li.active > a, .wizard .nav-tabs > li.active > a:hover, .wizard .nav-tabs > li.active > a:focus {
    color: #555555;
    cursor: default;
    border: 0;
    border-bottom-color: transparent;
}

span.round-tab {
    width: 70px;
    height: 70px;
    line-height: 70px;
    display: inline-block;
    border-radius: 100px;
    background: #fff;
    border: 2px solid #e0e0e0;
    z-index: 2;
    position: absolute;
    left: 0;
    text-align: center;
    font-size: 25px;
}
span.round-tab i{
    color:#555555;
}
.wizard li.active span.round-tab {
    background: #fff;
    border: 2px solid #965A2C;
    
}
.wizard li.active span.round-tab i{
    color: #965A2C;
}

span.round-tab:hover {
    color: #333;
    border: 2px solid #333;
}

.wizard .nav-tabs > li {
    width: 25%;
}

.wizard li:after {
    content: " ";
    position: absolute;
    left: 46%;
    opacity: 0;
    margin: 0 auto;
    bottom: 0px;
    border: 5px solid transparent;
    border-bottom-color: #965A2C;
    transition: 0.1s ease-in-out;
}

.wizard li.active:after {
    content: " ";
    position: absolute;
    left: 46%;
    opacity: 1;
    margin: 0 auto;
    bottom: 0px;
    border: 10px solid transparent;
    border-bottom-color: #965A2C;
}

.wizard .nav-tabs > li a {
    width: 70px;
    height: 70px;
    margin: 20px auto;
    border-radius: 100%;
    padding: 0;
}

    .wizard .nav-tabs > li a:hover {
        background: transparent;
    }

.wizard .tab-pane {
    position: relative;
    padding-top: 50px;
}

.wizard h3 {
    margin-top: 0;
}

@media( max-width : 585px ) {

    .wizard {
        width: 90%;
        height: auto !important;
    }

    span.round-tab {
        font-size: 16px;
        width: 50px;
        height: 50px;
        line-height: 50px;
    }

    .wizard .nav-tabs > li a {
        width: 50px;
        height: 50px;
        line-height: 50px;
    }

    .wizard li.active:after {
        content: " ";
        position: absolute;
        left: 35%;
    }
}
</style>

<div class="row">
	<section>
        <div class="wizard">
            <div class="wizard-inner">
                <div class="connecting-line"></div>
                <ul class="nav nav-tabs" role="tablist">

                    <li role="presentation" class="active">
                        <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="Cadastro Solicitação para preenchimento do tipo de solicitação. Preencha também o número fornecido na fase de contratação.">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-list-alt"></i>
                            </span>
                        </a>
                    </li>

                    <li role="presentation" class="disabled">
                        <a href="#" data-toggle="tab" aria-controls="step2" disabled="disabled" role="tab" title="Cadastro Solicitação para preenchimento referente ao valor dos serviços prestados">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-usd"></i>
                            </span>
                        </a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#" data-toggle="tab" aria-controls="step3" disabled="disabled" role="tab" title="Cadastro Solicitação para anexar o comprovante da fatura/nota fiscal.">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-paperclip"></i>
                            </span>
                        </a>
                    </li>
                    

                    <li role="presentation" class="disabled">
                        <a href="#" data-toggle="tab" aria-controls="complete" disabled="disabled" role="tab" title="Cadastro Solicitação para pesquisa de satisfação.">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-ok"></i>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </section>
   </div>
</div>
       
    <form role="form" onsubmit="btnsubmit.disabled = true; return true;" action="{{ route('Painel.Correspondente.step2') }}" method="POST" role="search" >
        {{ csrf_field() }}
        <div class="panel panel-primary setup-content" style="border-color:#965A2C;" id="step-1">
            <div class="panel-heading" style="background-color:#965A2C;border-color:#965A2C;">
                 <h3 class="panel-title">Indique o tipo de solicitação, e insira o número fornecido na fase de contratação.</h3>
            </div>
            <div class="panel-body">
                
                <div class="form-group">
                  <label>Indique o tipo de solicitação:</label>
                  <br>
                  <input class="form-check" type="radio" name="tiposervico" id="tiposervico" value="A" checked> Audiência
                  <input class="form-check" type="radio" name="tiposervico" id="tiposervico" value="D"> Diligência<br>
                </div>

                <div class="form-group">
                    <label class="control-label">Preencha o número fornecido na fase de contratação:</label>
                    <input name="numeroprocesso" id="numeroprocesso"  onchange="verificainput();"  type="text" required="required" maxlength="50" class="form-control" placeholder="Informe o Nª Processo ou Codigo Pasta" data-toggle="tooltip" data-placement="left" title="Informe o número fornecido na fase de contratação, conforme informado pela contratante." onkeyup="this.value=this.value.replace(/[' ']/g,'')"/>
                </div>
                <button class="btn btn-primary nextBtn pull-right fa fa-arrow-right" id="btnsubmit" type="submit" style="background-color:#4B4B4B;border-color:#4B4B4B;">&nbsp;&nbsp;Avançar</button>
            </div>
        </div>
        </div>
    </form>
                
        </section>

<script language="javascript">   
 function verificainput() {

  var caracteresDigitados = $("#numeroprocesso").val().length;

  if(caracteresDigitados >= 15) {

    var numeroprocesso = $("#numeroprocesso").val().replace(/[^0-9]/g,'');
    document.getElementById('numeroprocesso').value=(numeroprocesso);
  }

    
}
</script>

@endsection