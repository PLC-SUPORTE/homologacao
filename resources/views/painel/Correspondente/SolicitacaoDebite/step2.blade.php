@extends('Painel.Correspondente.Layouts.index')


@section('content')

 <div class="content-wrapper">
        <section class="content-header">
            <h1>
                [Solicitação Pagamento] - 
                <small>[Etapa 2 - Selecione o tipo de serviço prestado e preencha o campo valor e data serviço.]</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Painel.Correspondente.principal') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Etapa 2</li>
            </ol>
        </section>
        <section class="content">
            
          @include('flash::message')

<script src="../../../../public/dist/css/stepbystep.css"></script>
<script src="../../../../public/dist/js/stepbystep.js"></script>
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

	<section>
        <div class="wizard">
            <div class="wizard-inner">
                <div class="connecting-line"></div>
                <ul class="nav nav-tabs" role="tablist">

                    <li role="presentation" class="disabled">
                       <a href="#" data-toggle="tab" aria-controls="step1" role="tab" title="Formulário para preenchimento
                           do tipo de serviço. Preencha também o número fornecido na fase de contratação.">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-list-alt"></i>
                            </span>
                        </a>
                    </li>

                    <li role="presentation" class="active">
                        <a href="#step2" data-toggle="tab" aria-controls="step2" disabled="disabled" role="tab" title="Formulário para 
                           preenchimento referente ao valor dos serviços prestados">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-usd"></i>
                            </span>
                        </a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#" data-toggle="tab" aria-controls="step3" disabled="disabled" role="tab" title="Formulário para anexar
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
    
    <form role="form" onsubmit="btnsubmit.disabled = true; return true;" action="{{ route('Painel.Correspondente.store') }}" method="POST" role="create" >
        {{ csrf_field() }}
        @foreach($pastas as $pasta) 
       <div class="panel panel-primary setup-content" style="border-color:#965A2C;" id="step2">
            <div class="panel-heading" style="background-color:#965A2C;border-color:#965A2C;">
                 <h3 class="panel-title">Selecione o tipo de serviço prestado e preencha o campo valor e data do serviço.</h3>
            </div>
            <div class="panel-body">
        <div class="card card-default">
     
          <div class="card-body">
           <div class="row">
               
            <label hidden="" class="control-label">Código Pasta</label>
            <input maxlength="120" type="hidden" value="{{$pasta->Codigo_Comp}}" id="codigopasta" name="codigopasta" readonly="" required="required" class="form-control" placeholder="Codigo Pasta">
                    
            <input maxlength="120" type="hidden" value="{{$pasta->Status}}" id="status" name="status" readonly="" required="required" class="form-control" placeholder="Codigo Pasta">
            
            <div class="col-md-6">
               <div class="form-group">
                  <label>Selecione o tipo de serviço prestado:</label>
                  <select class="form-control select2" style="width: 100%;" id="tiposervico" name="tiposervico"  data-toggle="tooltip" data-placement="left" title="Selecione o tipo de serviço prestado.">
                   @foreach($tiposervico_descricao as $tiposervico)   
                    <option selected="selected">{{ $tiposervico->descricao }}</option>
                    @endforeach
                  </select>
                </div>
           </div>      
                             
            <div class="col-md-3">
                <div class="form-group">
                   <div class="form-group">
                  <label class="control-label">Valor serviço prestado:</label>
               <input name="ValorT" id="ValorT" type="text" maxlength="8" pattern="(?:\.|,|[0-9])*" class="form-control" placeholder="Valor(R$)" onKeyPress="return(moeda2(this,'.',',',event))" data-toggle="tooltip" data-placement="top" title="Preencha o valor da diligência conforme acordado pelo contratante" required="required">
                   </div>
                </div>
              </div>  
            
              <div class="col-md-3">
                <div class="form-group">
                   <div class="form-group">
                  <label class="control-label">Data serviço:</label>
                  <input name="data" id="data" type="date" max="{{$datahoje}}" class="form-control" data-toggle="tooltip" data-placement="top" title="Selecione a data da realização do serviço." required="required">
                   </div>
                </div>
              </div>  
           </div>
                  <button class="btn btn-primary nextBtn pull-right fa fa-arrow-right"  id="btnsubmit" name="btn" type="submit" style="background-color:#4B4B4B;border-color:#4B4B4B;">&nbsp;&nbsp;Avançar</button>

           </div>
          </div>
           </div>
          </div>
            </div>
        </div>
    @endforeach
    </form>

</div>
        </section>
@endsection



