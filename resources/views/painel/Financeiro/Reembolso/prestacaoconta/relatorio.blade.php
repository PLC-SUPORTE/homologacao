
<!DOCTYPE html>

<html class="loading" lang="en" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="ThemeSelect">
    <title>Prestação de conta das solicitações de reembolso do cliente {{$datas->ClienteRazao}}</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../../app-assets/images/favicon/favicon-32x32.png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/app-invoice.min.css') }}">


    <style>
    .html{
       color: black;
    }
    article, aside, details, figcaption, figure, 
footer, header, hgroup, menu, nav, section, main {
	display: block;
}
ol, ul {
	list-style: none;
}
blockquote, q {
	quotes: none;
}
blockquote:before, blockquote:after,
q:before, q:after {
	content: '';
	content: none;
}
table {
	border-collapse: collapse;
	border-spacing: 0;
}

.img-replace {
  /* replace text with an image */
  display: inline-block;
  overflow: hidden;
  text-indent: 100%;
  color: transparent;
  white-space: nowrap;
}


.cd-nugget-info {
  text-align: center;
  position: absolute;
  width: 100%;
  height: 50px;
  line-height: 50px;
  bottom: 0;
  left: 0;
}
.cd-nugget-info a {
  position: relative;
  font-size: 14px;
  color: #5e6e8d;
  -webkit-transition: all 0.2s;
  -moz-transition: all 0.2s;
  transition: all 0.2s;
}
.no-touch .cd-nugget-info a:hover {
  opacity: .8;
}
.cd-nugget-info span {
  vertical-align: middle;
  display: inline-block;
}
.cd-nugget-info span svg {
  display: block;
}
.cd-nugget-info .cd-nugget-info-arrow {
  fill: #5e6e8d;
}


.cd-popup-trigger {
  display: block;
  width: 170px;
  height: 50px;
  line-height: 50px;
  margin: 3em auto;
  text-align: center;
  color: #FFF;
  font-size: 14px;
  font-size: 0.875rem;
  font-weight: bold;
  text-transform: uppercase;
  border-radius: 50em;
  background: #35a785;
  box-shadow: 0 3px 0 rgba(0, 0, 0, 0.07);
}
@media only screen and (min-width: 1170px) {
  .cd-popup-trigger {
    margin: 6em auto;
  }
}

.cd-popup {
  position: fixed;
  left: 0;
  top: 0;
  height: 100%;
  width: 100%;
  opacity: 0;
  visibility: hidden;
  -webkit-transition: opacity 0.3s 0s, visibility 0s 0.3s;
  -moz-transition: opacity 0.3s 0s, visibility 0s 0.3s;
  transition: opacity 0.3s 0s, visibility 0s 0.3s;
}
.cd-popup.is-visible {
  opacity: 1;
  visibility: visible;
  -webkit-transition: opacity 0.3s 0s, visibility 0s 0s;
  -moz-transition: opacity 0.3s 0s, visibility 0s 0s;
  transition: opacity 0.3s 0s, visibility 0s 0s;
}

.cd-popup-container {
  position: relative;
  width: 90%;
  max-width: 400px;
  margin: 4em auto;
  background: #FFF;
  border-radius: .25em .25em .4em .4em;
  text-align: center;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
  -webkit-transform: translateY(-40px);
  -moz-transform: translateY(-40px);
  -ms-transform: translateY(-40px);
  -o-transform: translateY(-40px);
  transform: translateY(-40px);
  /* Force Hardware Acceleration in WebKit */
  -webkit-backface-visibility: hidden;
  -webkit-transition-property: -webkit-transform;
  -moz-transition-property: -moz-transform;
  transition-property: transform;
  -webkit-transition-duration: 0.3s;
  -moz-transition-duration: 0.3s;
  transition-duration: 0.3s;
}
.cd-popup-container p {
  padding: 1em  1em;
}
.cd-popup-container .cd-buttons:after {
  content: "";
  display: table;
  clear: both;
}
.cd-popup-container .cd-buttons li {
  float: left;
  width: 50%;
  list-style: none;
}
.cd-popup-container .cd-buttons a {
  display: block;
  height: 60px;
  line-height: 60px;
  text-transform: uppercase;
  color: #FFF;
  -webkit-transition: background-color 0.2s;
  -moz-transition: background-color 0.2s;
  transition: background-color 0.2s;
}
.cd-popup-container .cd-buttons li:first-child a {
  background: #b6bece;
  border-radius: 0 0 0 .25em;
}
.no-touch .cd-popup-container .cd-buttons li:first-child a:hover {
  background-color: #fc8982;
}
.cd-popup-container .cd-buttons li:last-child a {
  background: #52ca52;
  border-radius: 0 0 .25em 0;
}
.no-touch .cd-popup-container .cd-buttons li:last-child a:hover {
  background-color: #c5ccd8;
}
.cd-popup-container .cd-popup-close {
  position: absolute;
  top: 8px;
  right: 8px;
  width: 30px;
  height: 30px;
}
.cd-popup-container .cd-popup-close::before, .cd-popup-container .cd-popup-close::after {
  content: '';
  position: absolute;
  top: 12px;
  width: 14px;
  height: 3px;
  background-color: #8f9cb5;
}
.cd-popup-container .cd-popup-close::before {
  -webkit-transform: rotate(45deg);
  -moz-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  -o-transform: rotate(45deg);
  transform: rotate(45deg);
  left: 8px;
}
.cd-popup-container .cd-popup-close::after {
  -webkit-transform: rotate(-45deg);
  -moz-transform: rotate(-45deg);
  -ms-transform: rotate(-45deg);
  -o-transform: rotate(-45deg);
  transform: rotate(-45deg);
  right: 8px;
}
.is-visible .cd-popup-container {
  -webkit-transform: translateY(0);
  -moz-transform: translateY(0);
  -ms-transform: translateY(0);
  -o-transform: translateY(0);
  transform: translateY(0);
}
@media only screen and (min-width: 1170px) {
  .cd-popup-container {
    margin: 8em auto;
  }
}
    </style>
    <style type="text/css" media="print">
   .hideonprint { 
       display:none; 
   }
   .iframeprint {
       width:600px;
   }
   </style>

  </head>
  <!-- END: Head-->
  <body class="vertical-layout page-header-light vertical-menu-collapsible vertical-menu-nav-dark preload-transitions 2-columns  app-page " data-open="click" data-menu="vertical-menu-nav-dark" data-col="2-columns">


    <!-- BEGIN: Page Main-->
    <div id="main">

    <div id="loadingdiv" style="display:none;margin-top: 200px; margin-left: 570px;">
      <img style="width: 30px; margin-top: -100px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
      <h6 style="font-size: 20px;margin-left:-210px;">Aguarde, estamos gerando o boleto para o cliente...</h6>
      </div>

      <div class="row" id="corpodiv">


        <div class="content-wrapper-before blue-grey lighten-5"></div>
        <div class="col s12">
          <div class="container">

<section class="invoice-view-wrapper section">
  <div class="row">

  <form role="form" id="form" onsubmit="btnsubmit.disabled = true; return true;" action="{{ route('Painel.Financeiro.Reembolso.PrestacaoConta.gerarboleto') }}" method="POST" role="search"  enctype="multipart/form-data">
  {{ csrf_field() }}

  <input type="hidden" name="fatura" id="fatura" value="{{$fatura}}">
  <input type="hidden" name="datavencimento" id="datavencimento" value="{{$DataVencimento}}">
  <input type="hidden" name="datavencimentobr" id="datavencimentobr" value="{{ date('d-m-Y', strtotime($DataVencimento)) }}">
  <input type="hidden" name="clientecodigo" id="clientecodigo" value="{{$datas->ClienteCodigo}}">
  <input type="hidden" name="clienterazao" id="clienterazao" value="{{$datas->ClienteRazao}}">
  <input type="hidden" name="clienteendereco" id="clienteendereco" value="{{$datas->ClienteEndereco}}">
  <input type="hidden" name="clientebairro" id="clientebairro" value="{{$datas->ClienteBairro}}">
  <input type="hidden" name="clientecidade" id="clientecidade" value="{{$datas->ClienteCidade}}">
  <input type="hidden" name="clientecep" id="clientecep" value="{{$datas->ClienteCEP}}">
  <input type="hidden" name="clienteuf" id="clienteuf" value="{{$datas->ClienteUF}}">
  <input type="hidden" name="clienteunidade" id="clienteunidade" value="{{$datas->ClienteUnidade}}">
  <input type="hidden" name="codigobanco" id="codigobanco" value="{{$banco}}">
  <input type="hidden" name="valor" id="valor" value="{{$valortotal}}">


    <div class="col xl9 m8 s12">
      <div class="card">
        <div class="card-content invoice-print-area">

          <div class="row invoice-date-number">

            <div class="col xl4 s12">
              <span class="invoice-number mr-1">Fatura#</span>
              <span>{{$fatura}}</span>
            </div>

            <div class="col xl8 s12">
              <div class="invoice-date display-flex align-items-center flex-wrap">
                <!-- <div class="mr-3">
                  <small>Data ínicio:</small>
                  <span>{{ date('d/m/Y', strtotime($carbon)) }}</span>
                </div>
                <div>
                  <small>Date fim:</small>
                  <span>{{ date('d/m/Y', strtotime($carbon)) }}</span>
                </div> -->

              </div>
            </div>
          </div>

          <div class="row mt-3 invoice-logo-title">

            <div class="col m6 s12 display-flex invoice-logo mt-1 push-m6">
            </div>

            <div class="col m6 s12 pull-m6">
            <img src="{{url('./public/imgs/logo.png')}}" alt="Logo PLC" height="116" width="200">
            </div>

          </div>

          <div class="divider mb-3 mt-3"></div>

          <div class="row invoice-info">
            <div class="col m12 s12">

            <p>Belo Horizonte, {{ date('d', strtotime($carbon)) }} de Maio de {{ date('Y', strtotime($carbon)) }}.
            <br><br>

                     
            <strong>
            <p>Á</p>
            <p>{{$datas->ClienteRazao}} </p>
            <p>CNPJ: {{$datas->ClienteCodigo}} </p>
                     
            <br><br>
            <u><p>REF: SOLICITAÇÃO DE REEMBOLSO </p></u>
            </strong>

            <br><br>
            <p>Segue anexo despesas adiantadas pelo nosso escritório no valor de <strong> R$ <?php echo number_format($valortotal,2,",",".") ?> ({{$extenso}}) </strong>  para o devido reembolso.</strong> 
            </p>

            <br>

            <p>Assim aguardamos o reembolso das despesas antecipadas a ser efetuada em <strong>Boleto/Deposito {{$banco_descricao}} – vencimento {{ date('d/m/Y', strtotime($DataVencimento)) }} </strong>, de titularidade da Portela, Lima, Lobato & Colen Sociedade de Advogados, inscrita no CNPJ/MF sob o nº {{$banco_cpfcnpj}}
            </p>

            <br>

            <p>Sem mais para o momento colocamo-nos à disposição para quaisquer esclarecimentos.</p>

            <br>

            <p>Atenciosamente,</p>

            <br>

            <p>PORTELA, LIMA, LOBATO & COLEN SOCIEDADE DE ADVOGADOS</p>

            </div>
           
          </div>
          <div class="divider mb-3 mt-3"></div>

          <div style='page-break-after:always'></div>
          <br><br><br><br><br>

          <div class="invoice-product-details">
            <table class="striped responsive-table">
              <thead>
                <tr>
                <th style="font-size: 10px">Número</th>
                <th style="font-size: 10px">Cliente</th>
                <th style="font-size: 10px">Data</th>
                <th style="font-size: 10px">Tipo de debite</th>
                <th style="font-size: 10px">Valor</th>
                <th style="font-size: 10px">Advogado nome</th>
                <th style="font-size: 10px">Nº Pasta</th>
                <th style="font-size: 10px">Número do processo</th>
                </tr>
              </thead>
              <tbody>
              @foreach($debites as $debite)
                   <tr>
                   <td style="font-size: 9px">{{$debite->Numero}} </td>
                   <td style="font-size: 9px"><?php echo mb_convert_case($debite->ClienteRazao, MB_CASE_TITLE, "UTF-8")?> </td>
                   <td style="font-size: 9px">{{ date('d/m/Y', strtotime($debite->Data)) }}</td>
                   <td style="font-size: 9px">{{$debite->TipoDebite}} </td>
                   <td style="font-size: 9px">R$ <?php echo number_format($debite->Valor,2,",",".") ?> </td>
                   <td style="font-size: 9px"><?php echo mb_convert_case($debite->AdvogadoNome, MB_CASE_TITLE, "UTF-8")?></td>
                   <td style="font-size: 9px">{{$debite->Pasta}}</td>
                   <td style="font-size: 9px">{{$debite->NumeroProcesso}}</td>
                   </tr> 
              @endforeach
               
              </tbody>
            </table>
          </div>

    
          <div style='page-break-after:always'></div>

          <br><br><br><br><br>

          <!--Relatório do Debite --> 
          @foreach($debites as $debite)
          <div style=" border: 1px solid;border-color:black;">
          <p>Debito: <b style="color: black;"><?php echo mb_convert_case($debite->ClienteRazao, MB_CASE_TITLE, "UTF-8")?></b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;                   Número: <b style="color: black;">{{$debite->Numero}} </b></p>
          <br>
          <p>Tipo de despesa: <b style="color: black;"> {{$debite->TipoDebite}}</b> </p>
          <br>
          <p>Descrição: <b style="color: black;"> {{$debite->Observacao}}</b></p>
          <br>
          <p>Advogado a Creditar: <b style="color: black;"> <?php echo mb_convert_case($debite->AdvogadoNome, MB_CASE_TITLE, "UTF-8")?></b></p>
          <br>
          <p>Advogado: </p>
          <br>
          <p>Solicitante: <b style="color: black;"> <?php echo mb_convert_case($debite->AdvogadoNome, MB_CASE_TITLE, "UTF-8")?></b></p>
          <br>
          <p>Data:  <b style="color: black;"> {{ date('d/m/Y', strtotime($debite->Data)) }}</b> &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;                   Setor:    <b style="color: black;">{{$debite->SetorDescricao}}</b>       &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;       Valor: <b style="color:black;">R$ <?php echo number_format($debite->Valor,2,",",".") ?></b> </p>

          <br><br>
          <center><p style="font-size: 8.5px;">Rodovia Stael Mary Bicalho Motta Magalhães, 521, 8º Andar - 30441194 - Belo Horizonte - MG - Brasil - Tel 55 (31) 2513-6550 www.plcadvogados.com.br </p></center>
          </div>

          <div style='page-break-after:always'></div>


          <div class="masonry-gallery-wrapper">
      <div class="popup-gallery">
        <div class="gallery-sizer"></div>

        @foreach($arquivos as $arquivo)
          <div class="row">
          <div class="col s12 m6 l4 xl2">
            <div>
            @if($arquivo->Codigo_OR == $debite->Numero)
             @if($arquivo->Arq_tipo == "pdf") 

               <iframe src="https://docs.google.com/gview?url=https://portal.plcadvogados.com.br/portal/public/imgs/reembolso/{{$arquivo->Arq_nick}}&embedded=true" style="width:900px;height:1200px;" scrolling="no" frameborder="0" allowfullscreen></iframe> 

               @elseif($arquivo->Arq_tipo == "png" || $arquivo->Arq_tipo == "jpg" || $arquivo->Arq_tipo == "jpeg" || $arquivo->Arq_tipo == "eml") 
               <a href="{{ url('public/imgs/reembolso/'.$arquivo->Arq_nick) }}" title="{{$arquivo->Arq_nick}}"> 
               <img src="{{ url('public/imgs/reembolso/'.$arquivo->Arq_nick) }}" style="width:860px; height:900px;" alt="" title="{{$arquivo->Arq_nick}}"> 
               </a> 
              @endif 

              @endif
            </div>
          </div>
          </div>
          @endforeach

          </div>
       </div>
          <div style='page-break-after:always'></div>

          @endforeach

          </div>

          </div>

          </div>

    



    <div class="col xl3 m4 s12">
      <div class="card invoice-action-wrapper">
        <div class="card-content">

          <div class="invoice-action-btn">
            <a href="#" class="btn-block btn waves-effect waves-light invoice-print" style="background-color:gray;font-size:11px;">
              <span>Imprimir</span>
            </a>

            <br>

            @if($codigo_boleto == null || $codigo_boleto == '')
            <a href="#" onClick="abreconfirmacaoboleto();" class="btn-block btn waves-effect waves-light" style="background-color:gray;font-size:11px;">
              <span>Gerar boleto</span>
            </a>
            @else 
            <a href="{{route('Painel.Financeiro.Reembolso.PrestacaoConta.baixarboleto', ['codigo' => $datas->ClienteCodigo, 'fatura' => $fatura])}}" class="btn-block btn waves-effect waves-light" style="background-color:gray;font-size:11px;">
              <span>Imprimir boleto</span>
            </a>
            @endif

          </div>
          
        </div>
      </div>
    </div>
  </div>

  </form>
</section>


<div id="alertaconfirmacao" name="alertaconfirmacao" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Deseja gerar boleto para o cliente: {{$datas->ClienteRazao}} no valor de: R$ <?php echo number_format($valortotal,2,",",".") ?> com o vencimento para: {{ date('d/m/Y', strtotime($DataVencimento)) }} </p>
		<ul class="cd-buttons">
			<li><a href="#" onClick="nao();" style="font-weight: bold;">Não</a></li>
			<li><a href="#" onClick="sim();" style="font-weight: bold;">Sim</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div>



    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>

    <script>
    function abreconfirmacaoboleto() {
      $('#alertaconfirmacao').addClass('is-visible');
    }
    </script>

    <script>
    function sim() {
    $('.modal').css('display', 'none');
    document.getElementById("loadingdiv").style.display = "";
    document.getElementById("corpodiv").style.display = "none";
    $('.cd-popup').removeClass('is-visible');
    document.getElementById("form").submit();
    }
    </script>

    <script>
    function nao() {
      $('.cd-popup').removeClass('is-visible');
    }
    </script>


  </body>
</html>