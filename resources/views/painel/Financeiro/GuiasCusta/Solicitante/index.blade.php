@extends('painel.Layout.header')

@section('title') Solicitações de pagamento de guia de custa @endsection <!-- Titulo da pagina -->

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
Solicitação de pagamento de guias de custas processuais
@endsection

@section('submenu')
<li class="breadcrumb-item active" style="color: black;">Minhas solicitações em andamento
</li>
@endsection

@section('body')
      <div>
         <div class="row">

         <div id="loadingdiv" style="display:none;margin-top: 300px; margin-left: 570px;">
         <img style="width: 30px; margin-top: -100px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
         <h6 style="font-size: 20px;margin-left:-140px;">Aguarde, carregando informações da pasta...</h6>
         </div>

            <div class="content-wrapper-before blue-grey lighten-5"></div>
            <div class="col s12" id="div_all">
               <div class="container">
                  <div class="section">
                     <section class="invoice-list-wrapper section">
                        <div class="invoice-filter-action mr-3">
 
                            <a href="#modalbuscapasta" class="waves-light btn modal-trigger" style="color: white; background-color: gray; font-size: 11px; border-radius: 50px;">
                              <i class="material-icons left">add</i>Nova solicitação</a>

                              <a href="{{ route('Painel.Financeiro.GuiasCustas.Solicitante.historico') }}" class="waves-light btn" style="color: white; background-color: gray; font-size: 11px; border-radius: 50px;">
                              <i class="material-icons left">list</i>Histórico</a>
                       
                        </div>
                        <div class="responsive-table">
                           <table class="table invoice-data-table white border-radius-4 pt-1" style="font-size: 11px;">
                              <thead>
                                 <tr>
                                    <th style="font-size: 11px"></th>
                                    <th style="font-size: 11px">Número do debite</th>
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


                                 <!--Modal editar --> 
                                    <div id="editar{{$data->NumeroDebite}}" class="modal" style="width: 1207px;height: 325px;">
                                       <form id="formeditar" role="form" action="{{ route('Painel.Financeiro.GuiasCustas.Solicitante.editado') }}" method="POST" role="create" enctype="multipart/form-data" >
                                          {{ csrf_field() }}
                                          <div class="modal-content">

                                             <button type="button" class="btn  mr-sm-1 mr-2 modal-close red" style="margin-top: -26px; margin-left: 1080px;"><i class="material-icons">close</i></button>
                                            
                                             <div id="corpodiv4">
                                                <h5 style="font-size: 11px;">Editar solicitação</h5>
                                                <p style="font-size: 11px;">A solicitação {{$data->NumeroDebite}} foi reprovada pelo motivo: <strong>{{$data->Motivo}}</strong>. Favor realizar a correção abaixo para uma nova revisão.</p>
                                                <br>

                                                <div class="input-field col s1">
                                                   <input style="font-size: 10px;" readonly id="numerodebite" type="text" name="numerodebite" value="{{ $data->NumeroDebite }}" class="validate">
                                                   <label style="font-size: 11px;" for="numerodebite">N. Debite:</label>
                                                </div>

                                                <div class="input-field col s2">
                                                   <input style="font-size: 10px;" readonly id="pasta" type="text" name="pasta" value="{{ $data->Pasta }}" class="validate">
                                                   <label style="font-size: 11px;" for="pasta">Código da pasta:</label>
                                                </div>

                                                <div class="input-field col s2">
                                                   <input style="font-size: 10px;" id="setor" readonly type="text" name="setor"  value="{{ $data->Setor }}" class="validate">
                                                   <label style="font-size: 11px;" for="setor">Setor do PL&C:</label>
                                                </div>

                                                <div class="input-field col s2">
                                                   <input style="font-size: 10px;" id="icon_telephone" readonly type="text" value="{{ date('d/m/Y', strtotime($data->DataServico)) }}" name="data" class="validate">
                                                   <label style="font-size: 11px;" for="icon_telephone">Data da solicitação:</label>
                                                </div>

                                                <div class="input-field col s2">
                                                   <input style="font-size: 10px;" id="icon_telephone" readonly type="text" value="{{ $data->TipoDebite }}" name="tiposervico" class="validate">
                                                   <label style="font-size: 11px;" for="icon_telephone">Tipo:</label>
                                                </div>

                                                <!--Valor incorreto do acordado com o contratante mostrar apenas o valor --> 
                                                @if($data->MotivoID == 3)
                                                <div class="input-field col s2">
                                                   <input style="font-size: 10px;" id="quantidade" type="number" value="<?php echo number_format($data->Quantidade,0,",",".") ?>" name="quantidade" class="validate">
                                                   <label style="font-size: 11px;" for="quantidade">Quantidade:</label>
                                                </div>

                                                <div class="input-field col s2">
                                                   <input style="font-size: 10px;" name="valor_unitario" id="valor_unitario" type="text" maxlength="8" value="<?php echo number_format($data->ValorUnitario,2,",",".") ?>" pattern="(?:\.|,|[0-9])*" class="form-control" placeholder="Valor(R$)" onKeyPress="return(moeda2(this,'.',',',event))" data-toggle="tooltip" data-placement="top" title="Preencha o valor unitário..." required="required">
                                                   <label style="font-size: 11px;" for="valor_unitario">Valor unitário:</label>
                                                </div>

                                                <div class="input-field col s2">
                                                   <input style="font-size: 10px;" type="text" value="<?php echo number_format($data->Valor,2,",",".") ?>"  name="valor_total" id="valor_total" class="validate">
                                                   <label style="font-size: 11px;" for="valor_total">Valor total:</label>
                                                </div>
                                                @elseif($data->MotivoID == 1 || $data->MotivoID == 2)
                                                <div class="col s6 m3 l6">
                                                   <input style="font-size: 10px;" type="file" id="input-file-now" name="select_file" accept=".jpg,.png,.xls,.xlsx,.pdf,.doc,.docx,.rtf,.jpeg" required class="dropify" data-default-file="" />
                                                </div>
                                                @else 
                                                <div class="input-field col s2">
                                                   <input style="font-size: 10px;" id="quantidade" type="number" value="<?php echo number_format($data->Quantidade,0,",",".") ?>" name="quantidade" class="validate">
                                                   <label style="font-size: 11px;" for="quantidade">Quantidade:</label>
                                                </div>

                                                <div class="input-field col s2">
                                                   <input style="font-size: 10px;" name="valor_unitario" id="valor_unitario" type="text" maxlength="8" value="<?php echo number_format($data->ValorUnitario,2,",",".") ?>" pattern="(?:\.|,|[0-9])*" class="form-control" placeholder="Valor(R$)" onKeyPress="return(moeda2(this,'.',',',event))" data-toggle="tooltip" data-placement="top" title="Preencha o valor unitário..." required="required">
                                                   <label style="font-size: 11px;" for="valor_unitario">Valor unitário:</label>
                                                </div>

                                                <div class="input-field col s2">
                                                   <input style="font-size: 10px;" type="text" value="<?php echo number_format($data->Valor,2,",",".") ?>"  name="valor_total" id="valor_total" class="validate">
                                                   <label style="font-size: 11px;" for="valor_total">Valor total:</label>
                                                </div>

                                                <div class="col s12 m12 l12">
                                                   <input style="font-size: 10px;" type="file" id="input-file-now" name="select_file" accept=".jpg,.png,.xls,.xlsx,.pdf,.doc,.docx,.rtf,.jpeg" required class="dropify" data-default-file="" />
                                                </div>
                                                @endif
                                                <div class="input-field col s12" style="display: none">
                                                   <textarea id="hist" rows="4" type="text" name="hist" readonly="" class="form-control" style="text-align: left;margin: 0;" placeholder="Hist debite">
{{$data->Hist}}
Solicitação de reembolso editada pelo(a): {{Auth::user()->name}} - {{$dataehora}}</textarea>
                                                </div>


                                             </div>
                                          </div>

                                          <div class="modal-footer" style="margin-top: 100px;">
                                             <button type="submit" id="btnsubmit" onClick="editar();" class="modal-action  btn-flat" style="background-color: gray;color:white;"><i class="material-icons left">refresh</i>Corrigir solicitação</button>
                                          </div>

                                    </div>
                                    </form>
                        </div>
                        <!--Fim Modal Editar --> 

                        <!--Inicio Modal Anexos --> 
                        <div id="anexos{{$data->NumeroDebite}}" class="modal modal-fixed-footer" style="width: 100%;height:100%;overflow:hidden;">
                        <button type="button" class="btn  mr-sm-1 mr-2 modal-close red" style="margin-left: 1255px; margin-top: 5px;">
                        <i class="material-icons">close</i> 
                        </button>
                        <iframe style=" position:absolute;
                           top:60;
                           left:0;
                           width:100%;
                           height:100%;" src="{{ route('Painel.Financeiro.GuiasCustas.anexos', $data->NumeroDebite) }}"></iframe>
                        </div>
                        <!--Fim Modal Anexos --> 

                        <td style="font-size: 10px"></td>
                        <td style="font-size: 10px">{{ $data->NumeroDebite }}</td>
                        <td style="font-size: 10px">{{ $data->Pasta }}</td>
                        <td style="font-size: 10px">{{ $data->Setor }}</td>
                        <td style="font-size: 10px">{{ $data->Unidade }}</td>
                        <td style="font-size: 10px">{{ $data->TipoDebite }}</td>
                        <td style="font-size: 10px">R$ <?php echo number_format($data->Valor,2,",",".") ?></td>
                        <td style="font-size: 10px">{{ date('d/m/Y', strtotime($data->DataSolicitacao)) }}</td>
                        @if($data->StatusID == 3)
                        <td style="font-size: 10px"><span class="bullet red"></span>{{$data->Status}}</td>
                        @else 
                        <td style="font-size: 10px"><span class="bullet yellow"></span>{{$data->Status}}</td>
                        @endif
                        <td style="font-size: 10px">
                        <div class="invoice-action">
                        @if($data->StatusID == 5)
                        <a href="#editar{{$data->NumeroDebite}}" class="invoice-action-view mr-4 tooltipped modal-trigger" data-position="bottom" data-tooltip="Clique aqui para editar está solicitação de guia de custa reprovada."><i class="material-icons">edit</i></a>
                        @endif
                        <a href="#anexos{{$data->NumeroDebite}}" class="invoice-action-view mr-4 tooltipped modal-trigger" data-position="bottom" data-tooltip="Clique aqui para visualizar os anexos desta solicitação de guia de custa."><i class="material-icons">attach_file</i></a>
                        </div>
                        </td>
                        </tr> 
                        @endforeach
                        </tbody>
                        </table>
                  </div>
                  </section>
               </div>
               <div class="content-overlay"></div>
            </div>
         </div>
      </div>


<!-- Modal Structure -->
  <div id="modalbuscapasta" class="modal" style="width: 95%; height: 850px; background-color: #808080;">
  <form role="form" id="formbuscadados" action="{{ route('Painel.Financeiro.GuiasCustas.Solicitante.novasolicitacao') }}" method="POST" role="search" >
  {{ csrf_field() }}

    <center>
    <div id="loadingdiv" style="display:none;margin-top: 300px;">
        <img style="width: 100px; margin-top: -100px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
        <h6 style="font-size: 20px;margin-left:-140px;">Aguarde, carregando informações da pasta...</h6>
    </div>
    </center>

      <div class="modal-content" style="heigth: 700px;" id="corpodiv">
        <div class="input-field col s6">
          <div class="container" style="margin-top: 180px; margin-left: 20px;">
            <label style="color: white; font-size: 12px; margin-left: 250px;">Indique o número do processo ou código da pasta:</label>
            <a href="#" onClick="buscadados();" style="color: black;">
            <i name="load" id="load" class="material-icons prefix" style="margin-left: 432px; margin-top: 25px;">search</i>
            </a>
            <input value="" placeholder="Informe o número do processo ou código da pasta..." id="dado" name="dado" required type="text" class="validate autocomplete" 
                   style="width: 750px; margin-left: 230px; padding-left: 15px; border: 0 none;
                    outline: 0; background-color: white; border-radius: 80px;">
          </div>
        </div>
      </div>
      </form>
    </div>
    <!--Fim Modal Busca-->



<div id="alerta" name="alertacamposfaltantes" class="cd-popup" role="alert">
<div class="cd-popup-container">
<p style="font-weight: bold;">Não foi possível encontrar nenhuma pasta com este número de processo ou código informados.</p>
<ul class="cd-buttons" style="width: 800px">
<li><a href="#" onClick="nao();" style="font-weight: bold;background-color:#b6bece">Fechar</a></li>
</ul>
<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
</div> 
</div> 

@endsection

      
      <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>

      <script>
         document.addEventListener("DOMContentLoaded", function () {
            $('.modal').modal();
         });
      </script>

      
@if(Session::has('message'))
            <script type="text/javascript">

                  $('#alerta').addClass('is-visible');
            </script>
@endif()

<script>
  $(document).on("onBlur", "#dado", function() {

  var codigo = $("#dado").val();
  var _token = $('input[name="_token"]').val();
  $("#load").attr("src","https://portal.plcadvogados.com.br/portal/public/imgs/loading.gif");


    $.ajax({
      type: 'POST',
      url:"{{ route('Painel.Financeiro.Reembolso.Solicitante.buscardados') }}",
      data:{codigo:codigo,_token:_token,},
      dataType: 'json',
      cache: false,
      success: function(response) {
        var countryArray = response;
        var dataCountry = {};

        if(countryArray.length == 1) {
          $('#dado').val(countryArray[0].Codigo_Comp)
          dataCountry[countryArray[0].Codigo_Comp] = countryArray[0].Codigo_Comp; 
        } else {
        for (var i = 0; i < countryArray.length; i++) {
          dataCountry[countryArray[i].Codigo_Comp] = countryArray[i].Codigo_Comp; 
        }
        }
        $('#dado').autocomplete({
          data: dataCountry,
          limit: 5,
        });

      }
  });
});
</script>


<script>
  $(document).on("change", "#dado", function() {

  var codigo = $("#dado").val();
  var _token = $('input[name="_token"]').val();
  $("#load").attr("src","https://portal.plcadvogados.com.br/portal/public/imgs/loading.gif");


    $.ajax({
      type: 'POST',
      url:"{{ route('Painel.Financeiro.Reembolso.Solicitante.buscardados') }}",
      data:{codigo:codigo,_token:_token,},
      dataType: 'json',
      cache: false,
      success: function(response) {
        var countryArray = response;
        var dataCountry = {};

        if(countryArray.length == 1) {
          $('#dado').val(countryArray[0].Codigo_Comp)
          dataCountry[countryArray[0].Codigo_Comp] = countryArray[0].Codigo_Comp; 
        } else {
        for (var i = 0; i < countryArray.length; i++) {
          dataCountry[countryArray[i].Codigo_Comp] = countryArray[i].Codigo_Comp; 
        }
        }
        $('#dado').autocomplete({
          data: dataCountry,
          limit: 5,
        });

      }
  });
});
</script>

<script>

function buscadados() {
   $('.modal').css('display', 'none');
  document.getElementById("loadingdiv").style.display = "";
  document.getElementById("div_all").style.display = "none";
  document.getElementById("formbuscadados").submit();
}

</script>

<script>

$(document).on('keypress',function(e) {
    if(e.which == 13) {
      $('.modal').css('display', 'none');
      document.getElementById("loadingdiv").style.display = "";
      document.getElementById("div_all").style.display = "none";
      document.getElementById("formbuscadados").submit();

    }
});
</script>



      <script>
         function editar() {
            $('.modal').css('display', 'none');
             document.getElementById("loadingdiv").style.display = "";
             document.getElementById("corpodiv4").style.display = "none";
             document.getElementById("div_all").style.display = "none";
             document.getElementById("formeditar").submit();
         }    
      </script>

<script>
function cancelar() {
  var motivo = $('#motivocancelamento').val();
  if ($('#motivocancelamento').val().length != ''){
      $('.modal').modal();
      $('#confirmacao').addClass('is-visible');
  } else {
      alert('Favor informar o motivo do cancelamento desta solicitação de guia de custa.')
    }
}
</script>

<script>
  function sim() {
    $('.modal').css('display', 'none');
    document.getElementById("loadingdiv").style.display = "";
    document.getElementById("div_all").style.display = "none";
    $('.cd-popup').removeClass('is-visible');
    document.getElementById("formcancelar").submit();
         
  }    
</script>

<script>

 function nao() {
  $('.cd-popup').removeClass('is-visible');
 }
</script>





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
         $('#valor_unitario').on('change', function() {
         
           var quantidade = $("#quantidade").val();
           var valor_unitario = parseFloat($("#valor_unitario").val().replace(',', '.'));
           var valor_total = quantidade * valor_unitario;
         
           $("#valor_total").val(parseFloat(valor_total).toFixed(2));
         
         });
      </script>

