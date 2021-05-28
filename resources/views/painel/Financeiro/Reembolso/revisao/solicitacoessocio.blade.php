@extends('painel.Layout.header')
@section('title') Sócio solicitações de reembolso @endsection <!-- Titulo da pagina -->

@section('header') 
    <link rel="apple-touch-icon" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
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
Solicitação de Reembolso
@endsection

@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.Financeiro.Reembolso.Revisao.index') }}">Sócios</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Revisar solciitações
</li>
@endsection

@section('body')
   <div>

   
   <div id="loadingdiv" style="display:none;margin-top: 300px; margin-left: 570px;">
    <img style="width: 30px; margin-top: -100px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
  </div>


      <div class="row" id="div_all">
        <div class="content-wrapper-before blue-grey lighten-5"></div>

        <div class="col s12" id="corpodiv">

          <div class="container">
            <div class="section section-data-tables">

  <div class="row">
    <div class="col s12">
      <div class="card">
        <div class="card-content">

          <table id="multi-select" class="display white border-radius-4 pt-1">
          <thead>
          <tr>
          <th>
          <label><input type="checkbox" onClick="marcatodos();"/><span></span></label></th>
          <th style="font-size: 11px">Número do debite</th>
          <th style="font-size: 11px">Solicitante</th>
          <th style="font-size: 11px">Código pasta</th>
          <th style="font-size: 11px">Setor do PL&C</th>
          <th style="font-size: 11px">Unidade</th>
          <th style="font-size: 11px">Tipo debite</th>
          <th style="font-size: 11px">Valor</th>
          <th style="font-size: 11px">Data da solicitação</th>
          <th style="font-size: 11px">Status</th>
          <th style="font-size: 11px"></th>
        </tr>
      </thead>

      <tbody>
      @foreach($datas as $data)  
        <tr>



<!--Inicio Modal Anexos --> 
<div id="anexos{{$data->NumeroDebite}}" class="modal modal-fixed-footer" style="width: 100%;height:100%;overflow:hidden;">

<button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 1255px; margin-top: 5px;">
  <i class="material-icons">close</i> 
</button>

<iframe style=" position:absolute;
top:60;
left:0;
width:100%;
height:100%;" src="{{ route('Painel.Financeiro.Reembolso.anexos', $data->NumeroDebite) }}"></iframe>

</div>
<!--Fim Modal Anexos --> 


          {!! Form::open(['route' => ['Painel.Financeiro.Reembolso.Revisao.revisaoemmassa'], 'id' => 'formmassa', 'files' => true ,'class' => 'form form-search form-ds']) !!}
          {{ csrf_field() }}

          <input type="hidden" name="solicitante_codigo" id="solicitante_codigo" value="{{$solicitante_codigo}}">
          <!--Deixar baixar em massa apenas se estiver sido visualizada --> 
          @if($data->visualizada == "S")
          <td><label><input type="checkbox" class="check" name="numerodebite[]" id="numerodebite[]" value="{{$data->NumeroDebite}}" /><span></span></label></td>
          <!--Fim --> 
          @else 
          <td><label><input disabled type="checkbox" class="check" name="numerodebite[]" id="numerodebite[]" value="{{$data->NumeroDebite}}" /><span></span></label></td>
          @endif 

          <td style="font-size: 10px">{{ $data->NumeroDebite }}</td>
          <td style="font-size: 10px">{{ $data->SolicitanteNome }}</td>
          <td style="font-size: 10px">{{ $data->Pasta }}</td>
          <td style="font-size: 10px">{{ $data->SetorDescricao }}</td>
          <td style="font-size: 10px">{{ $data->UnidadeDescricao }}</td>
          <td style="font-size: 10px">{{ $data->TipoDebite }}</td>
          <td style="font-size: 10px">R$ <?php echo number_format($data->Valor,2,",",".") ?></td>
          <td style="font-size: 10px">{{ date('d/m/Y', strtotime($data->DataSolicitacao)) }}</td>
          @if($data->StatusID == 7)
          <td style="font-size: 10px"><span class="bullet green"></span>{{$data->Status}}</td>
          @elseif($data->StatusID == 6)
          <td style="font-size: 10px"><span class="bullet red"></span>{{$data->Status}}</td>
          @else 
          <td style="font-size: 10px"><span class="bullet yellow"></span>{{$data->Status}}</td>
          @endif
          <td style="font-size: 10px">

          <a href="{{route('Painel.Financeiro.Reembolso.Revisao.revisar', $data->NumeroDebite)}}" class="invoice-action-view mr-4 tooltipped modal-trigger" data-position="bottom" data-tooltip="Clique aqui para visualizar está solicitação de reembolso."><i class="material-icons">remove_red_eye</i></a>


          <a href="#anexos{{$data->NumeroDebite}}" class="invoice-action-view mr-4 tooltipped modal-trigger" data-position="bottom" data-tooltip="Clique aqui para visualizar os anexos desta solicitação de reembolso."><i class="material-icons">attach_file</i></a>

          </td>

          <div class="input-field col s12" style="display: none">
                <div class="form-group">
                   <div class="form-group">
                    <label class="control-label">Histórico preview:</label>
                    <textarea id="hist" rows="4" type="text" name="hist[]" readonly="" class="form-control" style="text-align: left;margin: 0;" placeholder="Hist debite">
{{$data->Hist}}
Número da solicitação: {{$data->NumeroDebite}}. Solicitação de reembolso revisada em massa pelo(a): {{Auth::user()->name}} na Revisão Técnica - {{$dataehora}}</textarea>
                </div>
                </div>
              </div> 

        </tr>
        @endforeach
        
        
      </tbody>
    </table>

    <div class="card" style="background-color: #e2e3e5; border-color: #d6d8db;">
        <div class="card-content" style="padding: 5px;"> 
         <div class="row">
        <div class="col s12 m12 l12">


         <div class="input-field col s3" style="margin-left: 870px;">
                  <button id="btnemmassa" class="btn waves-effect right" style="background-color: gray;" onClick="abreconfirmacao();" type="button" name="action">Aprovar em massa
                    <i class="material-icons left">check</i>
                  </button>
        </div> 

           
        </div>
    </div>
    </div>

   </div> 
    </form>

      </div>
    </div>
  </div>
</div>

<div class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Deseja aprovar as solicitações de reembolso?</p>
		<ul class="cd-buttons">
			<li><a href="#" onClick="nao();" style="font-weight: bold;">Não</a></li>
			<li><a href="#" onClick="sim();" style="font-weight: bold;">Sim</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div> 

@endsection

@section('scripts')

    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>

    <script>
document.addEventListener("DOMContentLoaded", function () {
   $('.modal').modal();
});
</script>

<script>
function marcatodos() {

     $.each($('.check'),function(){
      if($(this).is(':checked')){
        $(this).prop('checked',false);
        $("#btnemmassa").prop("disabled",false);
      }else{
        $(this).prop('checked',true);
        $("#btnemmassa").prop("disabled",true);
      }
    });
}    
</script>

<script>
function abreconfirmacao() {
  $('.cd-popup').addClass('is-visible');
}
</script>

<script>
  function sim() {
    $('.modal').css('display', 'none');
    document.getElementById("loadingdiv").style.display = "";
    document.getElementById("div_all").style.display = "none";
    $('.cd-popup').removeClass('is-visible');
    document.getElementById("formenvia").submit();
         
  }    
</script>

<script>
 function nao() {
  $('.cd-popup').removeClass('is-visible');
 }
</script>

@endsection