@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Atualizar NF @endsection <!-- Titulo da pagina -->

@section('header') 
    <link rel="apple-touch-icon" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">

    <link rel="stylesheet" type="text/css" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/css/pages/app-file-manager.min.css">
    <link rel="stylesheet" type="text/css" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/css/pages/widget-timeline.min.css">
    <link rel="stylesheet" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/css/pages/app-invoice.min.css">
    <!-- <link rel="stylesheet" type="text/css" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/vendors/flag-icon/css/flag-icon.min.css"> -->
    <link rel="stylesheet" type="text/css" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/vendors/dropify/css/dropify.min.css">

    <style>
    * {
      box-sizing: border-box;
    }
    .wrapper {
      height: 50px;
      margin-top: calc(50vh - 150px);
      margin-left: calc(50vw - 600px);
      width: 180px;
    }

    h1 {
      color: #222;
      font-size: 15px;
      font-weight: 400;
      letter-spacing: 0.05em;
      margin: 40px auto;
      text-transform: uppercase;
    }
</style>
@endsection

@section('header_title')
Associar NF
@endsection

@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.Financeiro.AssociacaoNF.index') }}">Listagem NF's</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Associar NF
</li>
@endsection

@section('body')
    <div> 
        <div class="row">
            <div class="container">

                <div class="col s12 m12 l12">

                <center>
  <div id="loadingdiv" style="display:none">
      <div class="wrapper">
      <div class="circle circle-1"></div>
      <div class="circle circle-1a"></div>
      <div class="circle circle-2"></div>
      <div class="circle circle-3"></div>
     </div>
     <h1 style="text-align: center;">Aguarde, estamos atualizando o registro...&hellip;</h1>
     </div>
  </center>   
                   
                    <div id="Form-advance" class="card card card-default scrollspy">
                      <div class="card-content">
                        <h4 class="card-title">Atualizar registro</h4>
                        <form id="form" role="form" action="{{ route('Painel.Financeiro.AssociacaoNF.atualizado') }}" method="POST" role="create"  enctype="multipart/form-data" >
                            {{ csrf_field() }}

                            
                          <input type="hidden" name="unidadecodigo" id="unidadecodigo" value="{{$datas->UnidadeCodigo}}">
                          <input type="hidden" name="clientecodigo" id="clientecodigo" value="{{$datas->ClienteCodigo}}">

                          <div class="row">

                          <div class="input-field col m1 s12">
                                <span>CPR</span>
                                <div class="form-group bmd-form-group is-filled">
                                    <input value="{{$datas->Numdoc}}" id="cpr" name="cpr" readonly class="form-control" required>
                                </div>
                            </div>


                            <div class="input-field col m1 s12">
                                <span>Tipodoc</span>
                                <div class="form-group bmd-form-group is-filled">
                                    <input value="{{$datas->Tipodoc}}" id="tipodoc" name="tipodoc" readonly class="form-control">
                                </div>
                            </div>

                            <div class="input-field col m4 s12">
                                <span>Cliente</span>
                                <div class="form-group bmd-form-group is-filled">
                                    <input value="{{$datas->Cliente}}" id="cliente" name="cliente" readonly class="form-control">
                                </div>
                            </div>

                            <div class="input-field col m2 s12">
                                <span>Código pasta</span>
                                <div class="form-group bmd-form-group is-filled">
                                    <input value="{{$datas->Pasta}}" id="pasta" name="pasta" readonly class="form-control">
                                </div>
                            </div>

                            <div class="input-field col m3 s12">
                                <span>Setor descrição</span>
                                <div class="form-group bmd-form-group is-filled">
                                    <input value="{{$datas->Setor}}" id="setor" name="setor" readonly class="form-control">
                                </div>
                            </div>

                            <div class="input-field col m2 s12">
                                <span>Unidade descrição</span>
                                <div class="form-group bmd-form-group is-filled">
                                    <input value="{{$datas->Unidade}}" id="unidade" name="unidade" readonly class="form-control">
                                </div>
                            </div>

                            <div class="input-field col m2 s12">
                                <span>Valor</span>
                                <div class="form-group bmd-form-group is-filled">
                                    <input value="R$ <?php echo number_format($datas->Valor,2,",",".") ?>" id="valor" name="valor" readonly class="form-control">
                                </div>
                            </div>


                            <div class="input-field col m2 s12">
                                <span>Data vencimento</span>
                                <input value="{{ date('d/m/Y H:i:s', strtotime($datas->DataVencimento)) }}"  id="datavencimento" name="datavencimento" class="form-control" type="text" required>
                            </div>

                            <div class="input-field col m2 s12">
                                <span>Data programação</span>
                                <input value="{{ date('d/m/Y H:i:s', strtotime($datas->DataProgramacao)) }}"  id="dataprogramacao" name="dataprogramacao" class="form-control" type="text" required>
                            </div>

                            <div class="input-field col m1 s12">
                                <span>CSLL</span>
                                <div class="form-group bmd-form-group is-filled">
                                    <input value="<?php echo number_format($datas->CSLL,2,",",".") ?>" id="csll" name="csll" readonly class="form-control">
                                </div>
                            </div>

                            <div class="input-field col m1 s12">
                                <span>COFINS</span>
                                <div class="form-group bmd-form-group is-filled">
                                    <input value="<?php echo number_format($datas->COFINS,2,",",".") ?>" id="cofins" name="cofins" readonly class="form-control">
                                </div>
                            </div>

                            <div class="input-field col m1 s12">
                                <span>PIS</span>
                                <div class="form-group bmd-form-group is-filled">
                                    <input value="<?php echo number_format($datas->PIS,2,",",".") ?>" id="pis" name="pis" readonly class="form-control">
                                </div>
                            </div>

                            <div class="input-field col m1 s12">
                                <span>ISS</span>
                                <div class="form-group bmd-form-group is-filled">
                                    <input value="<?php echo number_format($datas->ISS,2,",",".") ?>" id="iss" name="iss" readonly class="form-control">
                                </div>
                            </div>

                            <div class="input-field col m1 s12">
                                <span>INSS</span>
                                <div class="form-group bmd-form-group is-filled">
                                    <input value="<?php echo number_format($datas->INSS,2,",",".") ?>" id="inss" name="inss" readonly class="form-control">
                                </div>
                            </div>

                            <div class="input-field col m2 s12">
                                <span>Número NF Advwin</span>
                                <div class="form-group bmd-form-group is-filled">
                                    <input value="{{$datas->NumeroNF}}" id="numeronf" name="numeronf" readonly class="form-control">
                                </div>
                            </div>

                            <div class="input-field col m2 s12">
                                <span>Fatura Advwin</span>
                                <div class="form-group bmd-form-group is-filled">
                                    <input value="{{$datas->Fatura}}" id="fatura" name="fatura" readonly class="form-control">
                                </div>
                            </div>

                            <div class="input-field col m2 s12">
                                <span>Contrato</span>
                                <div class="form-group bmd-form-group is-filled">
                                    <input value="{{$datas->Contrato}}" id="contrato" name="contrato" readonly class="form-control">
                                </div>
                            </div>

                            <div class="input-field col m5 s12">
                                <span>Contrato descrição</span>
                                <div class="form-group bmd-form-group is-filled">
                                    <input value="{{$datas->ContratoDescricao}}" id="contratodescricao" name="contratodescricao" readonly class="form-control">
                                </div>
                            </div>

                            <div class="input-field col s12">
                            <textarea id="textarea2" name="observacao" class="materialize-textarea">{{$datas->Observacao}}</textarea>
                            <label for="textarea2">Observação CPR</label>
                            </div>
                            

                            <div class="input-field col m4 s12">
                                <span>Preencha o Nª da NF indicado pela prefeitura:</span>
                                <div class="form-group bmd-form-group is-filled">
                                    <input value="" id="numeronfprefeitura" onBlur="verifica();" name="numeronfprefeitura" required class="form-control">
                                </div>
                            </div>

                            </div>

                            <div class="row">

                            <div class="input-field col m4 s12">
                            <span>Deseja anexar arquivo XML da nota fiscal da prefeitura ?</span>
                            <p>
                            <label>
                            <input class="with-gap" name="pergunta" value="SIM" onClick="anexosim();" type="radio" checked />
                            <span>SIM</span>
                            </label>
                            </p>

                            <p>
                            <label>
                            <input class="with-gap" name="pergunta" value="NAO" onClick="anexonao();" type="radio" />
                            <span>NÃO</span>
                            </label>
                            </p>
                            </div>

                             <div class="col s12 m8 l9" id="anexo">
                            <input type="file" id="input-file-now" accept=".xml" name="select_file" class="dropify" data-default-file="" />
                            </div>



                            </div>

                          <div class="right-align">
                            <button type="button" id="btnsubmit" onClick="envia();" class="btn green"><i class="material-icons left">refresh</i>Atualizar</button>
                          </div>

                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
            </div>
    </div>
</div>

@endsection

@section('scripts')

    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>

    <script src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/js/scripts/app-file-manager.min.js"></script>
    <script src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/js/scripts/form-file-uploads.min.js"></script>
    <script src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/vendors/dropify/js/dropify.min.js"></script>


<script>
function envia() {

    document.getElementById("loadingdiv").style.display = "";
    document.getElementById("Form-advance").style.display = "none";
    document.getElementById("form").submit();
}    
</script> 

<script>
function anexosim() {

    document.getElementById("anexo").style.display = "";

}
</script>



<script>
function anexonao() {

  document.getElementById("anexo").style.display = "none";

}
</script>

<script type="text/javascript" >
 function verifica() {

  var count_elements = $('#numeronfprefeitura').length;


  if(count_elements > 12) {

    document.getElementById('numeronfprefeitura').value=('');
    alert('Quantidade de caracteres não permitida!');
  }

};
</script>

@endsection

