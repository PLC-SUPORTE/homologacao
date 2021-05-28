@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Editar carta RV @endsection <!-- Titulo da pagina -->

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


    <style>
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
Editar lançamento de carta RV
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.index') }}">Dashboard</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Editar lançamento de carta RV
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
     <h1 style="text-align: center;">Editando registro...&hellip;</h1>
     </div>
  </center>   
                   
                    <div id="Form-advance" class="card card card-default scrollspy">
                      <div class="card-content">
                        <form id="form" role="form" action="{{ route('Painel.Gestao.Controlador.CartaRV.editado') }}" method="POST" role="create" >
                            {{ csrf_field() }}

                           <input type="hidden" name="id" id="id" value="{{$datas->id}}">
                           <input type="hidden" name="usuario_id" id="usuario_id" value="{{$datas->usuario_id}}">
                            
                          <div class="row">

                          <div class="input-field col m4 s12">
                                <span>Usuários</span>
                                <div class="form-group bmd-form-group is-filled">
                                    <input value="{{$datas->usuario_nome}}" id="usuario" name="usuario" readonly class="form-control" required>
                                </div>
                            </div>


                            <div class="input-field col m2 s12">
                                <span>Unidade</span>
                                <select class="form-control" style="width: 100%;  max-height: 200px;"  id="unidade" name="unidade"  data-toggle="tooltip" data-placement="top" title="Selecione a unidade." required="required">
                                     <option value="{{$datas->unidade_codigo}}">{{$datas->unidade_codigo}} - {{$datas->unidade_descricao}}</option>
                                    @foreach($unidades as $u)   
                                      <option value="{{$u->codigo}}">{{$u->codigo}} - {{$u->descricao}}</option>
                                     @endforeach
                                   </select>
                            </div>

                            <div class="input-field col m3 s12">
                                <span>Área</span>
                                <select class="form-control" style="width: 100%;  max-height: 200px;" id="setor" name="setor"  data-toggle="tooltip" data-placement="top" title="Selecione o setor do PL&C." required="required">
                                <option value="{{$datas->setor_codigo}}">{{$datas->setor_codigo}} - {{$datas->setor_descricao}}</option>
                                    @foreach($setores as $a)   
                                      <option value="{{$a->Codigo}}">{{$a->Codigo}} - {{$a->Descricao}}</option>
                                     @endforeach
                                   </select>
                            </div>

                            <div class="input-field col m2 s12">
                                <span>Mês de Apuração</span>
                                <select class="form-control" style="width: 100%;  max-height: 200px;" id="mes" name="mes"  data-toggle="tooltip" data-placement="top" title="Selecione o mês de apuração." required="required">
                                <option value="{{$datas->mes_id}}" selected>{{$datas->mes}}</option>
                                <option value="1">Janeiro</option>
                                <option value="2">Fevereiro</option>
                                <option value="3">Março</option>
                                <option value="4">Abril</option>
                                <option value="5">Maio</option>
                                <option value="6">Junho</option>
                                <option value="7">Julho</option>
                                <option value="8">Agosto</option>
                                <option value="9">Setembro</option>
                                <option value="10">Outubro</option>
                                <option value="11">Novembro</option>
                                <option value="12">Dezembro</option>
                                </select>
                            </div>

                            <div class="input-field col m2 s12">
                                <span>Ano:</span>
                                <input value="{{$datas->ano}}" id="ano" name="ano" class="form-control" type="text" required>
                            </div>


                            <div class="input-field col m2 s12">
                                <span>PLC %</span>
                                <input value="{{$datas->plc_porcent}}" id="plc_porcent" name="plc_porcent" class="form-control" type="text" required>
                            </div>

                            <div class="input-field col m2 s12">
                                <span>Superintendência %</span>
                                <input value="{{$datas->unidade_porcent}}"  id="unidade_porcent" name="unidade_porcent" class="form-control" type="text" required>
                            </div>

                            <div class="input-field col m2 s12">
                                <span>Gerência %</span>
                                <input value="{{$datas->gerencia_porcent}}"  id="gerencia_porcent" name="gerencia_porcent" class="form-control" type="text" required>
                            </div>


                            <div class="input-field col m2 s12">
                                <span>Área %</span>
                                <input value="{{$datas->area_porcent}}"  id="area_porcent" name="area_porcent" class="form-control" type="text" required>
                            </div>

                            <div class="input-field col m2 s12">
                                <span>Score Individual %</span>
                                <input value="{{$datas->score_porcent}}"  id="score_porcent" name="score_porcent" class="form-control" type="text" required>
                            </div>

                            <div class="input-field col m2 s12">
                                <span>RV Máximo</span>
                                <input value="<?php echo number_format($datas->rv_maximo, 2); ?>"  id="rv_maximo" name="rv_maximo" class="form-control" type="text" required maxlength="8" pattern="(?:\.|,|[0-9])*" placeholder="Valor(R$)" onKeyPress="return(moeda2(this,'.',',',event))">
                            </div>


                            <div class="input-field col m2 s12">
                                <span>RV Apurado</span>
                                <input value="<?php echo number_format($datas->rv_apurado, 2); ?>"  id="rv_apurado" name="rv_apurado" class="form-control" type="text" required maxlength="8" pattern="(?:\.|,|[0-9])*" placeholder="Valor(R$)" onKeyPress="return(moeda2(this,'.',',',event))">
                            </div>

                            <div class="input-field col m2 s12">
                                <span>RV Recebido</span>
                                <input value="<?php echo number_format($datas->rv_recebido, 2); ?>"  id="rv_recebido" name="rv_recebido" class="form-control" type="text" required maxlength="8" pattern="(?:\.|,|[0-9])*" placeholder="Valor(R$)" onKeyPress="return(moeda2(this,'.',',',event))">
                            </div>

                            <div class="input-field col m2 s12">
                                <span>RV Projetado</span>
                                <input value="<?php echo number_format($datas->rv_projetado, 2); ?>"  id="rv_projetado" name="rv_projetado" class="form-control" type="text" required maxlength="8" pattern="(?:\.|,|[0-9])*" placeholder="Valor(R$)" onKeyPress="return(moeda2(this,'.',',',event))">
                            </div>
                          </div>

                          <div class="right-align">
                            <a class="btn red" href="{{route('Painel.Gestao.Controlador.CartaRV.index')}}"><i class="material-icons left">close</i>Cancelar</a>
                            <button type="button" id="btnsubmit" onClick="envia();" class="btn green"><i class="material-icons left">save_alt</i>Salvar</button>
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
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>

    <script src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/js/scripts/app-file-manager.min.js"></script>
    
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
function envia() {

    document.getElementById("loadingdiv").style.display = "";
    document.getElementById("Form-advance").style.display = "none";
    document.getElementById("form").submit();
}    
</script> 
