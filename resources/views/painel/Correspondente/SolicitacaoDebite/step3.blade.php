@extends('Painel.Correspondente.Layouts.index')


@section('content')

 <div class="content-wrapper">
        <section class="content-header">
            <h1>
                [Solicitação Pagamento] - 
                <small>[Etapa 3 - Realize o anexo dos documentos.]</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Painel.Correspondente.principal') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Etapa 3</li>
            </ol>
        </section>
        <section class="content">
          @include('flash::message')
            <div class="row">
                


<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="../../../../public/dist/css/stepbystep.css"></script>
<script src="../../../../public/dist/js/stepbystep.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>


<script>
    function test() {
        $("#select_file2").show();
        $("#labelfile").show();
    }
</script>

<script>
    function test2() {
        $("#select_file2").hide();
        $("#labelfile").hide();
    }
</script>

<!------ Include the above in your HEAD tag ---------->

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

                    <li role="presentation" class="disabled">
                        <a href="#"data-toggle="tab" aria-controls="step1" role="tab" title="Formulário para preenchimento
                           do tipo de serviço. Preencha também o número fornecido na fase de contratação.">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-list-alt"></i>
                            </span>
                        </a>
                    </li>

                    <li role="presentation" class="disabled">
                        <a href="#" data-toggle="tab" aria-controls="step2" disabled="disabled" role="tab" title="Formulário para 
                           preenchimento referente ao valor dos serviços prestados">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-usd"></i>
                            </span>
                        </a>
                    </li>
                    <li role="presentation" class="active">
                        <a href="#step3" data-toggle="tab" aria-controls="step3" disabled="disabled" role="tab" title="Formulário para anexar
                           o comprovante da fatura/nota fiscal.">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-paperclip"></i>
                            </span>
                        </a>
                    </li>
                    

                    <li role="presentation" class="disabled">
                       <a href="#" data-toggle="tab" aria-controls="complete" disabled="disabled" role="tab" title="Formulário para pesquisa 
                           de satisfação.">
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
    
    <form role="form" onsubmit="btnsubmit.disabled = true; return true;" action="{{ route('Painel.Correspondente.anexar') }}" method="post"  enctype="multipart/form-data">
        {{ csrf_field() }}

         <div class="panel panel-primary setup-content" style="border-color:#965A2C;" id="step2">
            <div class="panel-heading" style="background-color:#965A2C;border-color:#965A2C;">
                 <h3 class="panel-title">Realize o anexo dos documentos.</h3>
            </div>
            <div class="panel-body">
        <div class="card card-default">
     
          <div class="card-body">
      
              
              <div class="form-group" id="teste3">
               <label>Possui comprovante da audiência/diligência ?</label>
                 <div class="form-check">
                 <label class="form-check-label">
                     <input type="radio" class="form-check-input" id="pergunta1" name="pergunta" value="SIM" checked="" onclick="test();">Sim
                  
                 <input type="radio" class="form-check-input" id="pergunta2n" name="pergunta" onclick="test2();" value="NAO">Não
                </label>
                </div>    
              </div>    
              </div>
              
              
             <div class="row">
                <div class="col-md-6">
                <div class="form-group">
                   <div class="form-group">
                    <label class="control-label" name="labelfile"  id="labelfile">Anexar comprovante da audiência/diligência:</label>
                    <input  id="select_file2" name="select_file2" type="file" class="form-control" accept=".jpg,.png,.xls,.xlsx,.pdf,.doc,.docx,.rtf,.jpeg" />
                </div>
                </div>
              </div>      
              </div>
              
              
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
            
              </div>

              <div class="row">
               <div class="col-md-12">
                <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Observação:</label>
<textarea id="observacao" rows="7" type="text" name="observacao" class="form-control" placeholder="Digite a observação" style="text-align:left; overflow:auto;"></textarea>
                </div>
                </div>
              </div>     
              </div>

          
              <div class="row">
               <div class="col-md-12">
                <div class="form-group">
                   <div class="form-group">
<textarea id="observacao2" rows="7" type="text" name="observacao2" class="form-control" placeholder="Digite a observação" style="text-align:left; overflow:auto;display: none">
Correspondente: {{$data->Correspondente}}     
Outra Parte: {{$data->OutraParte}}    Comarca/Tribunal/Vara: {{$data->DescricaoTribunal}} - {{$data->DescricaoVara}}
Número Processo: {{$data->NumPrc1_Sonumeros}}
Conta: {{$data->PRConta}} 
@if($data->TipoSolicitacao == "A")
Tipo de solicitação: Audiência
@elseif($data->TipoSolicitacao == "D")
Tipo de solicitação: Diligência
@endif
Tipo de serviço: {{$data->TipoServico}}
Data serviço: {{ date('d/m/Y', strtotime($data->data)) }}</textarea>
                </div>
                </div>
              </div>     
              </div>
         
          <button class="btn btn-primary nextBtn pull-right fa fa-arrow-right" id="btnsubmit" type="submit" style="background-color:#4B4B4B;border-color:#4B4B4B;">&nbsp;&nbsp;Avançar</button>
            </div>
        </div>
                </div>
    </form>

</div>
    </div>

            </div>
        </section>
@endsection


