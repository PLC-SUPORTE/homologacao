@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Prestação de conta da solicitação de adiantamento @endsection <!-- Titulo da pagina -->

@section('header') 
   	<link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/data-tables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/app-invoice.min.css') }}">

    <style>
     .span{
        font-weight: bold;
     }
/* HTML5 display-role reset for older browsers */
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
  padding: 3em 1em;
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
    
@endsection

@section('header_title')
Adiantamento/Prestação de conta
@endsection

@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.Financeiro.AdiantamentoPrestacao.Solicitante.index') }}">Solicitações de adiantamento</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Prestação de conta
</li>
@endsection

@section('body')
   <div>

<div id="loadingdiv" style="display:none;margin-top: 200px; margin-left: 570px;">
<img style="width: 30px; margin-top: -100px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
<h6 style="font-size: 20px;margin-left:-200px;">Aguarde, estamos atualizando a solicitação de adiantamento...</h6>
</div>

  <div class="row" id="div_all">

        <div class="content-wrapper-before blue-grey lighten-5"></div>
        <div class="col s12">
          <div class="container">
			
<section class="invoice-edit-wrapper section">


<form role="form" id="form" action="{{ route('Painel.Financeiro.AdiantamentoPrestacao.Solicitante.realizarprestacao_store') }}" method="POST" role="search" enctype="multipart/form-data" >
  {{ csrf_field() }}

  <input type="hidden" name="solicitanteemail" id="solicitanteemail" value="{{$datas->solicitante_email}}">
  <input type="hidden" name="solicitantecpf" id="solicitantecpf" value="{{$datas->solicitante_cpf}}">
  <input type="hidden" name="solicitanteid" id="solicitanteid" value="{{$datas->solicitante_id}}">
  <input type="hidden" name="solicitantenome" id="solicitantenome" value="{{$datas->solicitante_nome}}">
  <input type="hidden" name="tiposervicodescricao" id="tiposervicodescricao" value="">
  <input type="hidden" name="id_matrix" id="id" value="{{$datas->id}}">
  <input type="hidden" name="valor_pendente" id="valor_pendente" value="<?php echo number_format($datas->valor_atual,2,",",".") ?>">
  <input type="hidden" name="numdoc" id="numdoc" value="{{$datas->Numdoc}}">
  <input type="hidden" name="valor_prestado" id="valor_prestado" value="">

    <!--Inicio Modal Anexos --> 
<div id="anexos{{$datas->id}}" class="modal modal-fixed-footer" style="width: 100%;height:100%;overflow:hidden;">

<button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 1255px; margin-top: 5px;">
  <i class="material-icons">close</i> 
</button>

<iframe style=" position:absolute;
top:60;
left:0;
width:100%;
height:100%;" src="{{ route('Painel.Financeiro.AdiantamentoPrestacao.anexos', $datas->id) }}"></iframe>

</div>
<!--Fim Modal Anexos --> 


  <div class="row"  style="padding: 5px;">

    <div class="col xl9 m8 s12">
      <div class="card">
      <a href="#anexos{{$datas->id}}" class="btn-floating btn-mini waves-effect waves-light tooltipped modal-trigger"data-position="left" data-tooltip="Clique aqui para visualizar os anexos."  style="margin-left: 885px;margin-top:-10px;background-color: gray;"><i class="material-icons">attach_file</i></a>

        <div class="card-content px-36">

        <div class="col m6 s12">
            <div class="divider show-on-small hide-on-med-and-up mb-3"></div>
            <h6 class="invoice-to">Dados da solicitação</h6>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Solicitante:  <?php echo mb_convert_case($datas->solicitante_nome, MB_CASE_TITLE, "UTF-8")?></p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Data da solicitação: {{ date('d/m/Y', strtotime($datas->DataSolicitacao)) }}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Data da transferência: {{ date('d/m/Y', strtotime($datas->DataPagamento)) }}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;"> 
            <p style="font-weight: bold;color:black;">Valor transferido: R$ <?php echo number_format($datas->valor_original,2,",",".") ?></p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Valor já prestado conta: R$ <?php echo number_format($datas->valor_prestado,2,",",".") ?></p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Valor pendente para prestação de conta: R$ <?php echo number_format($datas->valor_atual,2,",",".") ?></p>
            </div>
           </div>


    
          <div class="col m6 s12">
            <div class="divider show-on-small hide-on-med-and-up mb-3"></div>
            <h6 class="invoice-to">Meus dados bancários</h6>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">CPF: {{$meubanco->CodigoBanco}}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Banco: {{$meubanco->BancoDescricao}}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Agência: {{$meubanco->Agencia}}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Conta: {{$meubanco->Conta}} </p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Moeda: {{$meubanco->Moeda}}</p>
            </div>


          </div>  

          <br><br>   

          <div class="divider mb-3 mt-3"></div>
          
          <br><br>

        



          <div id="teste1">
          <div class="invoice-item display-flex mb-1">
            <div class="invoice-item-filed row pt-1">

            <a href="#" onClick="adicionanovasolicitacao();" class="btn-floating btn-mini waves-effect waves-light tooltipped modal-trigger"data-position="left" data-tooltip="Clique aqui para adicionar uma nova prestação de conta."  style="margin-left: 805px;margin-top:-20px;background-color: green;"><i class="material-icons">add</i></a>


            <div class="col m5 s12 input-field">
            <span style="font-size: 11px;">Informe o número da pasta ou número do processo:</span>
            <input type="text" required placeholder="Informe o número da pasta ou número do processo.." style="font-size: 10px;" name="pasta[]" id="pasta">
            </div>

            <div class="col m4 s12 input-field">
            <span style="font-size: 11px;">Tipo de debite:</span>
            <select class="invoice-item-select browser-default" required style="font-size: 10px;" name="tiposervico[]" id="tiposervico">
            <option value="" selected>Selecione abaixo</option>
            @foreach($tiposdebite as $tipodebite)
            <option value="{{$tipodebite->Codigo}}">{{$tipodebite->Descricao}}</option>
            @endforeach
            </select>
            </div>

            <div class="col m2 s12 input-field" id="quantidadediv" style="display: none;">
              <span style="font-size: 11px;">Km rodados:</span>
              <input type="text" value="1" id="quantidade" name="quantidade[]" placeholder="Quantidade KM rodado" required style="font-size:10px;">
            </div>

            
            <div class="col m2 s12 input-field">
              <span id="labelvalor" name="labelvalor" style="font-size: 11px;">Valor:</span>
              <input type="text" value="00,00" id="valor_unitario" name="valor_unitario[]" placeholder="Valor únitario..." onKeyPress="return(moeda2(this,'.',',',event))" style="font-size:10px;"required >
            </div>

            <div class="col m2 s12 input-field">
              <span style="font-size: 11px;">Valor total:</span>
              <input type="text" readonly value="00,00" name="valor_total[]" id="valor_total" style="font-size:10px;"required >
            </div>


            <div class="col m2 s12 input-field">
            <span style="font-size: 11px;">Data da diligência:</span>
            <input style="font-size: 10px;width:100px;" value="{{$datahoje}}" type="date" min="{{$dataminimo}}" max="{{$datahoje}}" name="data[]" id="data" class="form-control"  required="required">
            </div>

            <div class="row" id="deslocamentodiv" style="display:none;">
            <div class="col m3 s12 input-field">
              <span style="font-size: 11px;">Comarca origem:</span>
              <select class="invoice-item-select browser-default"  id="comarcaorigem" name="comarcaorigem[]" style="font-size:10px;">
              <option value="" selected>Selecione abaixo</option>
              @foreach($comarcas as $comarca)
              <option value="{{$comarca->municipio}}">{{$comarca->municipio}}</option>
              @endforeach
              </select>
            </div>

            <div class="col m3 s12 input-field">
              <span style="font-size: 11px;">Comarca destino:</span>
              <select class="invoice-item-select browser-default"  id="comarcadestino" name="comarcadestino[]" style="font-size:10px;">
              @foreach($comarcas as $comarca)
              <option value="{{$comarca->municipio}}">{{$comarca->municipio}}</option>
              @endforeach
              </select>
            </div>

            </div>

 


          <div class="row">
          <div class="input-field col s12" style="margin-top: -15px;">
          <div class="form-group">
          <div class="form-group">
          <label class="control-label" style="font-size: 11px;">Descrição do debite:</label>
          <textarea rows="3" type="text" name="observacao[]" id="observacao" class="form-control" placeholder="Insira a observação abaixo." style="height: 5rem;text-align:left; overflow:auto;font-size: 10px;"></textarea>
          </div>
          </div>
          </div>   
          </div>  

            <div class="row">

            <div class="col m12 s12 input-field">
            <span style="font-size: 11px;">Anexar comprovante do debite:</span>
            <input style="font-size: 10px;"  type="file" id="select_file"  name="select_file[]" accept=".jpg,.png,.xls,.xlsx,.pdf,.doc,.docx,.rtf,.jpeg" required class="dropify" data-default-file="" />
            </div>

            </div>


            </div>    
          </div>

          </div>	


          <div class="divider mt-3 mb-3"></div>

          <div class="invoice-subtotal">

          <div class="row">
          <div class="input-field col s12" style="margin-top: -15px;">
          <div class="form-group">
          <div class="form-group">
          <label class="control-label" style="font-size: 11px;">Informações da solicitação:</label>
          <textarea readonly rows="3" type="text" class="form-control" placeholder="Insira a observação abaixo." style="height: 5rem;text-align:left; overflow:auto;font-size: 10px;">{{$datas->Observacao}}</textarea>
          </div>
          </div>
          </div>   
          </div>  

          </div>

        </div>
      </div>
    </div>
    <!-- invoice action  -->


    <div class="col xl3 m4 s12">
      <div class="card invoice-action-wrapper mb-10">
        <div class="card-content">
  
        <div class="invoice-action-btn">
            <a onClick="abreconfirmacao();" style="background-color: gray;color:white;font-size:11px;" class="btn waves-effect waves-light display-flex align-items-center justify-content-center">
              <i class="material-icons mr-3">save</i>
              <span class="responsive-text" id="btngravar">Gerar prestação de conta</span>
            </a>
        </div> 

        </div>

        </div>
      </div>
      </div>


      </div>



  <div id="alertaconfirmacao" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Deseja enviar a prestação de conta para revisão do financeiro?</p>
		<ul class="cd-buttons">
			<li><a href="#" onClick="nao();" style="font-weight: bold;">Não</a></li>
			<li><a href="#" onClick="sim();" style="font-weight: bold;">Sim</a></li>
		</ul>
		<a href="#" onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div> 

<div id="alertacamposfaltantes" name="alertacamposfaltantes" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Preencha todos os campos e anexe ao menos um arquivo.</p>
		<ul class="cd-buttons" style="width: 800px">
			<li><a href="#" onClick="nao();" style="font-weight: bold;background-color:#b6bece">Fechar</a></li>
		</ul>
		<a href="#" onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div> 

<div id="alertavalordevolutiva" name="alertavalordevolutiva" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;" id="labeldevolutiva"></p>
		<ul class="cd-buttons" style="width: 800px">
    <li><a href="#" onClick="nao();" style="font-weight: bold;background-color:#52ca52">OK</a></li>
		</ul>
		<a href="#" onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div> 




</form>
</section>

@endsection

@section('scripts')

    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>
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



<script>
function adicionanovasolicitacao() {

  var html = '';
  html += '<div class=invoice-item display-flex mb-1>';
  html += '<div class="invoice-item-filed row pt-1">';
  html += '<div class="row">';

  html += '<div class="col m5 s12 input-field">';
  html += '<span style="font-size: 11px;">Informe o número da pasta:</span>';
  html += '<input type="text" required placeholder="Informe o número da pasta ou número do processo.." style="font-size: 10px;" name="pasta[]" id="pasta">'
  html += '</div>';

  html += '<div class="col m3 s12 input-field">';
  html += '<span style="font-size: 11px;">Tipo de debite:</span>';
  html += '<select class="invoice-item-select browser-default" required style="font-size: 10px;" name="tiposervico[]" id="tiposervico">'
  html += '<option value="" selected>Selecione abaixo</option>';
  @foreach($tiposdebite as $tipodebite)
  html += '<option value="{{$tipodebite->Codigo}}">{{$tipodebite->Descricao}}</option>';
  @endforeach
  html += '</select>';
  html += '</div>';


  html += '<div class="col m2 s12 input-field">';
  html += '<span style="font-size: 11px;">Data da diligência:</span>';
  html += '<input style="font-size: 10px;width:100px;" value="{{$datahoje}}" type="date"  min="{{$dataminimo}}" max="{{$datahoje}}" name="data[]" id="data" class="form-control"  required="required">'
  html += '</div>';

  html += '<div class="col m2 s12 input-field">';
  html += '<span style="font-size: 11px;">Valor:</span>';
  html += '<input type="text" value="00,00" name="valor[]" id="valor"  placeholder="Valor únitario..." onKeyPress="javascript:return(moeda2(this,event))" pattern="(?:\.|,|[0-9])*" style="font-size:10px;"required >'
  html += '</div>';

  html += '</div>';

  html += '<div class="row">';
  html += '<div class="input-field col s12" style="margin-top: -15px;">';
  html += '<div class="form-group">';
  html += '<div class="form-group">';
  html += '<label class="control-label" style="font-size: 11px;">Descrição do debite:</label>';
  html += '<textarea rows="3" type="text" name="observacao[]" class="form-control" placeholder="Insira a observação abaixo." style="height: 5rem;text-align:left; overflow:auto;font-size: 10px;"></textarea>'
  html += '</div>';
  html += '</div>';
  html += '</div>';
  html += '</div>';

  html += '<div class="row">';
  html += '<div class="col m12 s12 input-field">';
  html += '<span style="font-size: 11px;">Anexar comprovante do debite:</span>';
  html += '<input style="font-size: 10px;"  type="file" id="select_file"  name="select_file[]" accept=".jpg,.png,.xls,.xlsx,.pdf,.doc,.docx,.rtf,.jpeg" required class="dropify" data-default-file="" />'
  html += '</div>';
  html += '</div>';

  html += '</div>';

  html += '</div>';
  html += '</div>';


  $('#teste1').append(html);

}
</script>


<script>
$('#tiposervico').on('change', function() {

  var tiposervico = $("#tiposervico").val();
  var quantidade = $("#quantidade").val();
  var valorunitario = $("#valor_unitario").val();
  var valortotal = $("#valor_total").val();

  var tiposervicodescricao = $('#tiposervico option:selected').text();
  var _token = $('input[name="_token"]').val();
  var comarcaorigem = $('#comarcaorigem option:selected').text();
  var comarcadestino = $('#comarcadestino option:selected').text();


    //Se for despesas livres ele ignora o processo de verificação das regras do contrato

  if(tiposervico == '011' || tiposervico == '025') {

    document.getElementById("deslocamentodiv").style.display = "";
    document.getElementById("quantidadediv").style.display = "";
    document.getElementById('observacao').value=(' Tarefa: ' + tiposervicodescricao +  ' Deslocamento origem: '  + comarcaorigem + ' para a comarca de destino: ' + comarcadestino + ' em km = Total ida e volta: ' + quantidade + ' - ' + quantidade + ' x ' + valor_unitario + ' = R$ ' + valor_total);


    $("#valor_unitario").val(parseFloat({{$valor_unitario}}).toFixed(2));

    $('#labelvalor').html('Valor únitario:');
  } else {
    
    document.getElementById("deslocamentodiv").style.display = "none";
    document.getElementById("quantidadediv").style.display = "none";
    document.getElementById('observacao').value=(' Solicitante: ' + "{{Auth::user()->name}}" + ' - Data da solicitação: ' +  "{{$dataehora}}" + ' - Tipo de debite: ' + tiposervicodescricao);
    $("#quantidade").val("1");
    $('#labelvalor').html('Valor:');
  }

});
</script>

<script>
$('#valor_unitario').blur(function(){
  var quantidade = parseFloat($("#quantidade").val().replace(',', '.'));
    var valor_unitario = parseFloat($("#valor_unitario").val().replace('.', '.').replace(',','.'));
    var valor_total = quantidade * valor_unitario;
    var tiposervico = $("#tiposervico").val();
    var tiposervicodescricao = $('#tiposervico option:selected').text();
    var comarcaorigem = $('#comarcaorigem option:selected').text();
    var comarcadestino = $('#comarcadestino option:selected').text();
    var valor_total = valor_total.toFixed(2);

    var valorpermitido = $("#valorpermitido").val();

    //Verifico se o valor é permitido pelo contrato de acordo com o tipo de serviço marcado (Exceto Alimentação)
      // if(valor_total <= valorpermitido || tiposervico == 001) {

        $("#valor_total").val(parseFloat(valor_total).toFixed(2));
      // } else {
      //   $('#alertavaloracima').addClass('is-visible');
      //   $("#valor_total").val('0,00');
      //   $("#valor_unitario").val('0,00');
      // }



if(tiposervico == '011' || tiposervico == '025') {

  document.getElementById('observacao').value=(' Tarefa: ' + tiposervicodescricao +  ' Deslocamento origem: '  + comarcaorigem + ' para a comarca de destino: ' + comarcadestino + ' em km = Total ida e volta: ' + quantidade + ' - ' + quantidade + ' x ' + valor_unitario + ' = R$ ' + valor_total);
  document.getElementById("deslocamentodiv").style.display = "";
  document.getElementById("quantidadediv").style.display = "";
  $('#labelvalor').html('Valor únitario:');
} else {
  
  document.getElementById("deslocamentodiv").style.display = "none";
  document.getElementById("quantidadediv").style.display = "none";
  document.getElementById('observacao').value=(' Solicitante: ' + "{{Auth::user()->name}}" + ' - Data da solicitação: ' +  "{{$dataehora}}" + ' - Tipo de debite: ' + tiposervicodescricao);
  $("#quantidade").val("1");
  $('#labelvalor').html('Valor:');
}
});
</script>

<script>
$('#quantidade').blur(function(){
    var quantidade = parseFloat($("#quantidade").val().replace(',', '.'));
    var valor_unitario = parseFloat($("#valor_unitario").val().replace('.', '.').replace(',','.'));
    var valor_total = quantidade * valor_unitario;
    var valor_total = valor_total.toFixed(2);
    var tiposervico = $("#tiposervico").val();
    var tiposervicodescricao = $('#tiposervico option:selected').text();
    var comarcaorigem = $('#comarcaorigem option:selected').text();
    var comarcadestino = $('#comarcadestino option:selected').text();

    var valorpermitido = $("#valorpermitido").val();


    $("#valor_total").val(parseFloat(valor_total).toFixed(2));



if(tiposervico == '011' || tiposervico == '025') {

  document.getElementById('observacao').value=(' Tarefa: ' + tiposervicodescricao +  ' Deslocamento origem: '  + comarcaorigem + ' para a comarca de destino: ' + comarcadestino + ' em km = Total ida e volta: ' + quantidade + ' - ' + quantidade + ' x ' + valor_unitario + ' = R$ ' + valor_total);
  document.getElementById("deslocamentodiv").style.display = "";
  document.getElementById("quantidadediv").style.display = "";
  $('#labelvalor').html('Valor únitario:');
} else {
  
  document.getElementById("deslocamentodiv").style.display = "none";
  document.getElementById("quantidadediv").style.display = "none";
  document.getElementById('observacao').value=(' Solicitante: ' + "{{Auth::user()->name}}" + ' - Data da solicitação: ' +  "{{$dataehora}}" + ' - Tipo de debite: ' + tiposervicodescricao);
  $("#quantidade").val("1");
  $('#labelvalor').html('Valor:');
}
});
</script>


 <script>
$('#valor_unitario').blur(function(){

    var quantidade = parseFloat($("#quantidade").val().replace(',', '.'));
    var valor_unitario = parseFloat($("#valor_unitario").val().replace('.', '.').replace(',','.'));
    var valor_total = quantidade * valor_unitario;
    var valorpermitido = $("#valorpermitido").val();
    var comarcaorigem = $('#comarcaorigem option:selected').text();
    var comarcadestino = $('#comarcadestino option:selected').text();
    var valor_total = valor_total.toFixed(2);
    var tiposervicodescricao = $('#tiposervico option:selected').text();


      //Verifico se o valor é permitido pelo contrato de acordo com o tipo de serviço marcado (Exceto Alimentação)
      // if(valor_total <= valorpermitido || tiposervico == 001) {

        $("#valor_total").val(parseFloat(valor_total).toFixed(2));
      // } else {
      //   $('#alertavaloracima').addClass('is-visible');
      //   $("#valor_total").val('0,00');
      //   $("#valor_unitario").val('0,00');
      // }

  if(tiposervico == '011' || tiposervico == '025') {

    document.getElementById('observacao').value=(' Tarefa: ' + tiposervicodescricao +  ' Deslocamento origem: '  + comarcaorigem + ' para a comarca de destino: ' + comarcadestino + ' em km = Total ida e volta: ' + quantidade + ' - ' + quantidade + ' x ' + valor_unitario + ' = R$ ' + valor_total);
    document.getElementById("deslocamentodiv").style.display = "";
    document.getElementById("quantidadediv").style.display = "";
    $('#labelvalor').html('Valor únitario:');
  } else {
    
    document.getElementById("deslocamentodiv").style.display = "none";
    document.getElementById("quantidadediv").style.display = "none";
    document.getElementById('observacao').value=(' Solicitante: ' + "{{Auth::user()->name}}" + ' - Data da solicitação: ' +  "{{$dataehora}}" + ' - Tipo de debite: ' + tiposervicodescricao);
    $("#quantidade").val("1");
    $('#labelvalor').html('Valor:');
  }

  });
 </script>

<script>
  $(document).on("onChange", "#quantidade", function() {

    var quantidade = parseFloat($("#quantidade").val().replace(',', '.'));
    var valor_unitario = parseFloat($("#valor_unitario").val().replace('.', '').replace(',','.'));
    var valor_total = quantidade * valor_unitario;
    var valorpermitido = $("#valorpermitido").val();
    var comarcaorigem = $('#comarcaorigem option:selected').text();
    var comarcadestino = $('#comarcadestino option:selected').text();
    var valor_total = valor_total.toFixed(2)

      //Verifico se o valor é permitido pelo contrato de acordo com o tipo de serviço marcado (Exceto Alimentação)
      // if(valor_total <= valorpermitido || tiposervico == 001) {

        $("#valor_total").val(parseFloat(valor_total).toFixed(2));
      // } else {
      //   $('#alertavaloracima').addClass('is-visible');
      //   $("#valor_total").val('0,00');
      //   $("#valor_unitario").val('0,00');
      // }

  if(tiposervico == '011' || tiposervico == '025') {

    document.getElementById('observacao').value=(' Tarefa: ' + tiposervicodescricao +  ' Deslocamento origem: '  + comarcaorigem + ' para a comarca de destino: ' + comarcadestino + ' em km = Total ida e volta: ' + quantidade + ' - ' + quantidade + ' x ' + valor_unitario + ' = R$ ' + valor_total);
    document.getElementById("deslocamentodiv").style.display = "";
    document.getElementById("quantidadediv").style.display = "";
    $('#labelvalor').html('Valor únitario:');
  } else {
    
    document.getElementById("deslocamentodiv").style.display = "none";
    document.getElementById("quantidadediv").style.display = "none";
    document.getElementById('observacao').value=(' Solicitante: ' + "{{Auth::user()->name}}" + ' - Data da solicitação: ' +  "{{$dataehora}}" + ' - Tipo de debite: ' + tiposervicodescricao);

    $("#quantidade").val("1");
    $('#labelvalor').html('Valor:');
  }

  });
 </script>

<script>
$('#comarcadestino').on('change', function() {

  
    var quantidade = parseFloat($("#quantidade").val().replace(',', '.'));
    var valor_unitario = parseFloat($("#valor_unitario").val().replace('.', '.').replace(',','.'));
    var valor_total = quantidade * valor_unitario;
    var comarcaorigem = $('#comarcaorigem option:selected').text();
    var comarcadestino = $('#comarcadestino option:selected').text();
    var valor_total = valor_total.toFixed(2)
    var tiposervicodescricao = $('#tiposervico option:selected').text();

    document.getElementById('observacao').value=(' Tarefa: ' + tiposervicodescricao +  ' Deslocamento origem: '  + comarcaorigem + ' para a comarca de destino: ' + comarcadestino + ' em km = Total ida e volta: ' + quantidade + ' - ' + quantidade + ' x ' + valor_unitario + ' = R$ ' + valor_total);

});

</script>

<script>
$('#comarcaorigem').on('change', function() {

  
    var quantidade = parseFloat($("#quantidade").val().replace(',', '.'));
    var valor_unitario = parseFloat($("#valor_unitario").val().replace('.', '.').replace(',','.'));
    var valor_total = quantidade * valor_unitario;
    var comarcaorigem = $('#comarcaorigem option:selected').text();
    var comarcadestino = $('#comarcadestino option:selected').text();
    var valor_total = valor_total.toFixed(2)
    var tiposervicodescricao = $('#tiposervico option:selected').text();

    document.getElementById('observacao').value=(' Tarefa: ' + tiposervicodescricao +  ' Deslocamento origem: '  + comarcaorigem + ' para a comarca de destino: ' + comarcadestino + ' em km = Total ida e volta: ' + quantidade + ' - ' + quantidade + ' x ' + valor_unitario + ' = R$ ' + valor_total);

});

 </script>

<script>
document.addEventListener("DOMContentLoaded", function () {
   $('.modal').modal();
});
</script>




<script>
function abreconfirmacao() {

  var observacao = $('#observacao').val();
  var anexo = $('#select_file').val();
  var tiposervico = $("#tiposervico").val();
  var data = $("#data").val();
  var _token = $('input[name="_token"]').val();
  var comprovantetransferencia = $('#comprovantetransferencia').val();

  var valorprestado = 0;
    $('input[name="valor[]"]').each(function() {
      valorprestado += parseFloat($(this).val().replace('.', ',').replace(',','.'));
  });

  document.getElementById('valor_prestado').value=(valorprestado.toFixed(2));

  var pendente = parseFloat($("#valor_pendente").val().replace('.', ',').replace(',','.'));
  var sobra = pendente - valorprestado;

  $("#btnsubmit").attr("disabled", "disabled");

  //Se estiver faltando anexar o comprovante do debite
  if ($('#select_file').val().length == 0){
  $('#alertacamposfaltantes').addClass('is-visible');
  $("#btnsubmit").removeAttr("disabled");
  } 
  //Se o valor prestado for < do que o transferido pela PLC, ele deve devolver o valor restante
  else if(pendente > valorprestado && "{{$permissao}}" != "" && document.getElementById("comprovantetransferencia").files.length == 0) {
    $('#labeldevolutiva').html('Confirma a utilização do valor de R$ ' + sobra + ', para antecipação mensal?');
    $('#alertavalordevolutiva').addClass('is-visible');
    $("#btnsubmit").removeAttr("disabled");
  }
  else {
    $('#alertaconfirmacao').addClass('is-visible');
    $("#btnsubmit").removeAttr("disabled");
    document.getElementById("comprovantetransferenciadiv").style.display = "none";
  }
}
</script>


<script>
  function sim() {
    $('.modal').css('display', 'none');
    document.getElementById("loadingdiv").style.display = "";
    document.getElementById("div_all").style.display = "none";
    $('.cd-popup').removeClass('is-visible');
    document.getElementById("form").submit();
         
  }    
</script>

<script>
 function nao() {
  $('.cd-popup').removeClass('is-visible');
 }
</script>
@endsection
