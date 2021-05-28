<!DOCTYPE html>

<html class="loading" lang="pt-br" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Language" content="pt-br">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Portal PL&C">
    <title>Solicitação reembolso anexos | Portal PL&C</title>
    <link rel="apple-touch-icon" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


    <link rel="stylesheet" href="{{ asset('/public/materialize/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/app-file-manager.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/widget-timeline.min.css') }}">

    </head>

<body data-open="click" data-menu="vertical-modern-menu" data-col="2-columns" style="overflow: hidden;">


 <!-- BEGIN: Page Main-->

            <div class="section app-file-manager-wrapper" style="height: 550px; margin-top: -2px; overflow: hidden;">

  <div class="app-file-overlay"></div>

  <div class="sidebar-left">
    <div class="app-file-sidebar display-flex">
      <div class="app-file-sidebar-left">
        <span class="app-file-sidebar-close hide-on-med-and-up"><i class="material-icons">close</i></span>


        <div class="app-file-sidebar-content">

          <span class="app-file-label">Fluxo aprovação</span>
          <div class="collection file-manager-drive mt-3">

          <div class="activity-tab" id="file-activity">
            <ul class="widget-timeline mb-0">

              @foreach($historicos as $historico)
              <li class="timeline-items timeline-icon-green active">
                <div class="timeline-time" style="font-size: 8px;">{{ date('d/m/Y', strtotime($historico->data)) }}</div>
                <h6 class="timeline-title" style="font-size: 10px;">{{$historico->name}}</h6>
                <p class="timeline-text" style="font-size: 10px;">{{$historico->status}}</p>
              </li>
              @endforeach

             
            </ul>
          </div>

        </div>
      </div>

          </div>
        </div>

  </div>


  <!-- content-right start -->
  <div class="content-right">
    <!-- file manager main content start -->
    <div class="app-file-area">
      <!-- File App Content Area -->

      <!-- App File Content Starts -->
      <div class="app-file-content">
        <h6 class="font-weight-700 mb-3">Revisar solicitação</h6>

        <div class="row app-file-recent-access mb-3">

    <form id="form" role="form" onsubmit="btnsubmit2.disabled = true; return true;" onsubmit="btnsubmit3.disabled = true; return true;"  action="{{ route('Painel.Contratacao.CEO.solicitacaorevisada') }}" method="POST" role="create" enctype="multipart/form-data" >
    {{ csrf_field() }}

    <input type="hidden" name="id" id="id" value="{{$data->id}}">

    <div id="loadingdiv" style="display:none; margin-left: 600px; margin-top: -300px;">
      <img style="width: 100px; margin-top: -100px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
    </div>

    <div class="modal-content"> 

  <div id="corpodiv2">

        <div class="row">

        <div class="input-field col s3">
          <span style="font-size: 11px;color:black;">Nome do candidato:</span>
          <input style="font-size: 10px;" readonly placeholder="Informe o nome do candidato." id="plc_porcent" type="text" value="{{$data->candidatonome}}" name="candidatonome" class="validate">
        </div>

        <div class="input-field col s3">
          <span style="font-size: 11px;color:black;">E-mail do candidato:</span>
          <input style="font-size: 10px;" readonly placeholder="Informe o e-mail do candidato." id="plc_porcent" type="text" value="{{$data->candidatoemail}}" name="candidatoemail" class="validate">
        </div>

        <div class="input-field col m2 s12">
            <span style="font-size: 11px;color:black;">Setor de custo:</span>
            <input style="font-size: 10px;color:black;" readonly id="plc_porcent" type="text" value="{{$data->Setor}}" name="setordescricao" class="validate">
            <input type="hidden" name="setor" value="{{$data->SetorCodigo}}">
        </div>

        <div class="input-field col m2 s12">
            <span style="font-size: 11px;color:black;">Tipo da vaga:</span>
            <input style="font-size: 10px;color:black;" readonly id="tipovaga" type="text" value="{{$data->TipoVaga}}" name="tipovaga" class="validate">
        </div>

        <div class="input-field col m2 s12">
            <span style="font-size: 11px;color:black;">Função:</span>
            <input style="font-size: 10px;color:black;" readonly id="plc_porcent" type="text" value="{{$data->TipoAdvogado}}" name="tipocargo" class="validate">
        </div>

        <div class="input-field col s2">
          <span style="font-size: 11px;color:black;">Distribuição mensal:</span>
          <input style="font-size: 10px;"  readonly name="salario" id="salario" type="text" value="<?php echo number_format($data->Salario,2,",",".") ?>" maxlength="8"  pattern="(?:\.|,|[0-9])*" class="form-control" placeholder="Valor(R$)" onKeyPress="return(moeda2(this,'.',',',event))" data-toggle="tooltip" data-placement="top" title="Preencha o salário." required="required">
        </div>

        <div class="input-field col m2 s12">
            <span style="font-size: 11px;color:black;">Classificação:</span>
            <input style="font-size: 10px;color:black;" readonly id="classificacao" type="text" value="{{$data->Classificacao}}" name="classificacao" class="validate">
        </div>

        <div class="input-field col m3 s12">
            <span style="font-size: 11px;color:black;">Data prevista para entrada:</span>
            <input style="font-size: 10px;color:black;" readonly id="dataentrada" type="text" value="{{ date('d/m/Y', strtotime($data->DataEntrada)) }}" name="dataentrada" class="validate">
        </div>

        </div>



        <div class="row">

        <div class="input-field col s12" style="margin-top: -15px;">
         <div class="form-group">
            <div class="form-group">
              <label class="control-label" style="font-size: 11px;color:black;">Observação:</label>
                <textarea id="observacao" readonly rows="3" type="text" name="observacao" class="form-control" placeholder="Insira a observação abaixo." style="height: 3rem;text-align:left; overflow:auto;font-size: 10px;">{{$data->observacao}}</textarea>
             </div>
          </div>
        </div>   
       </div>  

       <div class="row" >
       <div class="input-field col s3" style="margin-top: -30px;">
          <p>
                           <label style="font-size:11px;">Deseja anexar aval do conselho:</label><br>
                              <label>
                                 <input class="with-gap" name="avalconselho" value="Não" type="radio" checked onClick="conselhonao();"/>
                                 <span style="font-size: 10px;">Não</span>
                              </label>
                              <label>
                                 <input class="with-gap" name="avalconselho" value="Sim" type="radio" onClick="conselhosim();"/>
                                 <span style="font-size: 10px;">Sim</span>
                              </label>
                           </p>
       </div>
       </div>

       <div class="row" style="display: none;" id="anexoconselhodiv">
       <div class="col s9 input-field" style="margin-top: -20px;">
        <span style="font-size: 11px;">Anexar documento:</span>
        <input style="font-size: 10px;" name="anexoconselho" id="anexoconselho" type="file" >
       </div>
       </div>


       <div class="modal-footer" style="margin-top: 0px;margin-left: 490px;">
       <button type="submit"  name="acao" value="2"  class="modal-action waves-effect waves-green btn-flat" style="background-color: gray;color:white;font-size: 11px;"><i class="material-icons left">close</i>Glosar solicitação</button>
       <button type="button" onClick="abremodalconfirmando();"  name="acao" value="1"  class="modal-action waves-effect waves-green btn-flat" style="background-color: gray;color:white;font-size: 11px;"><i class="material-icons left">save</i>Aprovar solicitação</button>       </div>
       </div>

    </div>
    </form>



      </div>
    </div>

  </div>
 
</div>


          </div>
          <div class="content-overlay"></div>

    <!-- END: Page Main-->


    <div id="modalconfirmacao" class="modal"  style="width: 30% !important;height: 30% !important;">
    <div class="modal-content">
      <center><p style="font-size: 18px;">Deseja aprovar a solicitação de nova contratação?</p></center>
    </div>
    <div class="modal-footer">
      <a  class="modal-action  waves-effect waves-red btn-flat " style="background-color: red;color:white;font-size:11px;" onClick="nao();"><i class="material-icons left">close</i>Não</a>
      <a  class="modal-action  waves-effect waves-green btn-flat " style="background-color: green;color:white;font-size:11px;" onClick="sim();"><i class="material-icons left">check</i>Sim</a>
    </div>
  </div>


 


    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>


    
<script>
document.addEventListener("DOMContentLoaded", function () {
   $('.modal').modal();
});
</script>

<script>
  function conselhosim() {
         
    document.getElementById("anexoconselhodiv").style.display = "";
  }    
</script>

<script>
  function conselhonao() {
         
    document.getElementById("anexoconselhodiv").style.display = "none";
  }    
</script>

<script>
  function abremodalconfirmando() {

    $('.modal').modal();
    $('#modalconfirmacao').modal('open');
         
  }    
</script>


<script>
  function nao() {

    $('#modalconfirmacao').modal('close');
  }    
</script>

<script>
  function sim() {

    document.getElementById("form").submit();
  }    
</script>


  </body>
</html>