@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Informativo RV histó @endsection <!-- Titulo da pagina -->

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
      <link rel="stylesheet" href="{{ asset('/public/materialize/css/dropify.min.css') }}">
      <link rel="stylesheet" href="{{ asset('/public/materialize/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('/public/materialize/css/select2-materialize.css') }}">
      <link rel="stylesheet" href="{{ asset('/public/materialize/css/app-invoice.min.css') }}">
      <style>
         * {
         box-sizing: border-box;
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
   Histórico dos informativos de RV
   @endsection

   @section('submenu')
   <li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.index') }}">Dashboard</a>
   </li>
   <li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.CartaRV.index') }}">Listagem mês</a>
   </li>
   <li class="breadcrumb-item active" style="color: black;">Histórico dos informativos de RV
   </li>
   @endsection

   @section('body')
      <div>
         <div class="row">
            <center>
               <div id="loading">
                  <div class="wrapper">
                     <div class="circle circle-1"></div>
                     <div class="circle circle-1a"></div>
                     <div class="circle circle-2"></div>
                     <div class="circle circle-3"></div>
                  </div>
                  <h1 style="text-align: center;">Aguarde, estamos carregando os lançamentos do ano de apuração...&hellip;</h1>
               </div>
            </center>
            <div class="row" id="corpodiv">
               <div class="content-wrapper-before blue-grey lighten-5"></div>
               <div class="col s12">
                  <div class="container">
                     <section class="invoice-list-wrapper section">

                     <div class="invoice-filter-action mr-4">
                       <a href="#modal" class="btn-floating btn-mini waves-effect waves-light tooltipped modal-trigger"data-position="left" data-tooltip="Clique aqui para adicionar uma novo informativo de RV."  style="margin-left: 5px;background-color: gray;"><i class="material-icons">add</i></a>
                       <a href="{{ route('Painel.Gestao.Controlador.CartaRV.historico') }}" class="btn-floating btn-mini waves-effect waves-light tooltipped"data-position="left" data-tooltip="Clique aqui para visualizar os informativos de RV referente ao ano de apuração."  style="margin-left: 5px;background-color: gray;"><i class="material-icons">assignment</i></a>
                       <a  href="{{ route('Painel.Gestao.Controlador.CartaRV.exportarano') }}" class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" data-position="left" data-tooltip="Clique aqui para exportar em Excel os informativos de RV do ano de apuração." style="background-color: gray;"><img style="margin-top: 8px; width: 20px;margin-left:8px;" src="{{URL::asset('/public/imgs/icon.png')}}"/></a>
                       </div>


                        <div class="responsive-table">
                           <table class="table invoice-data-table white border-radius-4 pt-1" style="width: 100%;">
                              <thead>
                                 <tr>
                                    <th style="font-size: 11px"></th>
                                    <th style="font-size: 11px">Usuário</th>
                                    <th style="font-size: 11px">Setor</th>
                                    <th style="font-size: 11px">Data</th>
                                    <th style="font-size: 11px">PLC</th>
                                    <th style="font-size: 11px">Superintendência</th>
                                    <th style="font-size: 11px">Gerência</th>
                                    <th style="font-size: 11px">Área</th>
                                    <th style="font-size: 11px">Score</th>
                                    <th style="font-size: 11px">RV Máximo</th>
                                    <th style="font-size: 11px">RV Apurado</th>
                                    <th style="font-size: 11px">RV Recebido</th>
                                    <th style="font-size: 11px">RV Projetado</th>
                                    <th style="font-size: 11px"></th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @foreach($datas as $data)  
                                 <tr>
                                    <td style="font-size: 10px"></td>
                                    <td style="font-size: 10px">{{mb_convert_case($data->usuario_nome, MB_CASE_TITLE, "UTF-8")}}</td>
                                    <td style="font-size: 10px">{{$data->setor}}</td>
                                    <td style="font-size: 10px">{{$data->mes}}</td>
                                    <td style="font-size: 10px">{{$data->plc_porcent}}</td>
                                    <td style="font-size: 10px">{{$data->unidade_porcent}}</td>
                                    <td style="font-size: 10px">{{$data->gerencia_porcent}}</td>
                                    <td style="font-size: 10px">{{$data->area_porcent}}</td>
                                    <td style="font-size: 10px">{{$data->score_porcent}}</td>
                                    <td style="font-size: 11px">R$ <?php echo number_format($data->rv_maximo,2,",",".") ?></td>
                                    <td style="font-size: 11px">R$ <?php echo number_format($data->rv_apurado,2,",",".") ?></td>
                                    <td style="font-size: 11px">R$ <?php echo number_format($data->rv_recebido,2,",",".") ?></td>
                                    <td style="font-size: 11px">R$ <?php echo number_format($data->rv_projetado,2,",",".") ?></td>
                                    <td style="font-size: 11px">
                                       <div class="invoice-action">
                                          <a href="{{route('Painel.Gestao.Controlador.CartaRV.editar', $data->id)}}" class="invoice-action-view mr-4"><i class="material-icons">edit</i></a>
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
         <div id="modal" class="modal" style="width: 980px;">
            <form id="form" role="form" action="{{ route('Painel.Gestao.Controlador.CartaRV.gravar') }}" method="POST" role="create" enctype="multipart/form-data" >
               {{ csrf_field() }}
               <input required type="hidden" name="opcao" id="opcao" value="">
               <div class="modal-content">
                  <center>
                     <div id="loadingdiv" style="display:none">
                        <div class="wrapper">
                           <div class="circle circle-1"></div>
                           <div class="circle circle-1a"></div>
                           <div class="circle circle-2"></div>
                           <div class="circle circle-3"></div>
                        </div>
                        <h1 style="text-align: center;">Gravando registro(s)...&hellip;</h1>
                     </div>
                  </center>
                  {{-- <a class="waves-effect modal-close waves-light btn red right align" style="margin-top: -20px; margin-right: -20px;"><i class="material-icons left">close</i></a> --}}
                  {{-- <a class="waves-effect modal-close waves-light btn red right align" style="margin-top: -20px; margin-right: -25px;"><i class="material-icons left">close</i>Fechar</a> --}}
                  <div id="corpodiv">
                     <a class="waves-effect modal-close waves-light btn red right align" style="margin-top: -32px; margin-right: -20px;"><i style="margin-left: 15px; font-size: 20px;" class="material-icons left">close</i></a>
                     <h5>Nova nota</h5>
                     <p>Deseja adicionar o informativo RV manualmente ou via importação Excel ? 
                        <a href="#modalInfo" style="color: gray;" class="modal-trigger"><i class="material-icons">info</i></a>
                     </p>
                     <!-- Modal Structure -->
                     <div id="modalInfo" class="modal" style="overflow: hidden;">
                        <div class="modal-content" style="overflow: hidden;">
                           <a class="waves-effect modal-close waves-light btn red right align" style="margin-top: -20px; margin-right: -25px;"><i style="margin-left: 15px;" class="material-icons left">close</i></a>
                           <h6>Informações</h6>
                           <p>Para importação de notas em massa, utilize a planilha com o layout fixo, no qual o formato das colunas deverá ser texto.</p>
                        </div>
                     </div>
                     <a id="btnmanualmente" onClick="manualmente();" class="modal-action waves-effect waves-green btn-flat" style="background-color: gray;color:white"><i class="material-icons left">source</i>Manualmente</a>
                     <a id="btnimportacao" onClick="importacao();" class="modal-action  waves-effect btn-flat" style="background-color: gray;color:white;"><i class="material-icons left">import_export</i>Importação Excel</a>
                     <a href="{{ route('Painel.Gestao.anexo', 'CartaRV.xlsx') }}" class="waves-effect waves-green btn-flat" style="background-color: green;color:white;"><i class="material-icons left">text_snippet</i>Baixar modelo importação</a>
                     <br>
                     <!--Div Manualmente -->
                     <div id="manualmentediv">
                        <div class="row">
                           <br>
                           <div class="input-field col s4">
                              <select class="select2-customize-result browser-default" name="usuario">
                                 @foreach($usuarios as $usuario)
                                 <option value="{{$usuario->id}}">{{$usuario->name}}</option>
                                 @endforeach
                              </select>
                              <label>Selecione o usuário</label>
                           </div>
                           <div class="input-field col s3">
                              <select class="select2-customize-result browser-default" name="unidade">
                                 @foreach($unidades as $unidade)
                                 <option value="{{$unidade->codigo}}">{{$unidade->codigo}} - {{$unidade->descricao}}</option>
                                 @endforeach
                              </select>
                              <label>Selecione a unidade</label>
                           </div>
                           <div class="input-field col s3">
                              <select class="select2-customize-result browser-default" name="setor">
                                 @foreach($setores as $setor)
                                 <option value="{{$setor->Codigo}}">{{$setor->Codigo}} - {{$setor->Descricao}}</option>
                                 @endforeach
                              </select>
                              <label>Selecione o setor</label>
                           </div>
                           <div class="input-field col s2">
                              <select class="select2-customize-result browser-default" name="mes">
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
                              <label>Selecione o mês</label>
                           </div>
                           <div class="input-field col s2">
                              <input id="ano" type="text" name="ano" value="2020" class="validate" maxlength="4" placeholder="Informe o ano de apuração...">
                              <label for="ano">Informe o ano</label>
                           </div>
                           <div class="input-field col s2">
                              <input id="plc_porcent" type="text" name="plc_porcent" value="00,00" class="validate" maxlength="8" pattern="(?:\.|,|[0-9])*"  placeholder="Informe a porcentagem...">
                              <label for="plc_porcent">PLC %</label>
                           </div>
                           <div class="input-field col s2">
                              <input id="icon_telephone" type="text" name="unidade_porcent"  value="00,00" class="validate" maxlength="8" pattern="(?:\.|,|[0-9])*" placeholder="Informe a porcentagem...">
                              <label for="icon_telephone">Superintendência %</label>
                           </div>
                           <div class="input-field col s2">
                              <input id="icon_telephone" type="text" name="gerencia_porcent"  value="00,00" class="validate" maxlength="8" pattern="(?:\.|,|[0-9])*" placeholder="Informe a porcentagem...">
                              <label for="icon_telephone">Gerência %</label>
                           </div>
                           <div class="input-field col s2">
                              <input id="icon_telephone" type="text" name="area_porcent" value="00,00" class="validate" maxlength="8"  pattern="(?:\.|,|[0-9])*" placeholder="Informe a porcentagem...">
                              <label for="icon_telephone">Área %</label>
                           </div>
                           <div class="input-field col s2">
                              <input id="icon_telephone" type="text" name="score_porcent" value="00,00" class="validate" maxlength="8" pattern="(?:\.|,|[0-9])*" placeholder="Informe o score individual...">
                              <label for="icon_telephone">Score individual</label>
                           </div>
                           <div class="input-field col s2">
                              <input id="icon_telephone" type="text" name="rv_maximo" class="validate" maxlength="8" pattern="(?:\.|,|[0-9])*" placeholder="Valor(R$)" onKeyPress="return(moeda2(this,'.',',',event))">
                              <label for="icon_telephone">RV Máximo</label>
                           </div>
                           <div class="input-field col s2">
                              <input id="icon_telephone" type="text" name="rv_apurado" class="validate" maxlength="8" pattern="(?:\.|,|[0-9])*" placeholder="Valor(R$)" onKeyPress="return(moeda2(this,'.',',',event))">
                              <label for="icon_telephone">RV Apurado</label>
                           </div>
                           <div class="input-field col s2">
                              <input id="icon_telephone" type="text" name="rv_recebido" class="validate" maxlength="8" pattern="(?:\.|,|[0-9])*" placeholder="Valor(R$)" onKeyPress="return(moeda2(this,'.',',',event))">
                              <label for="icon_telephone">RV Recebido</label>
                           </div>
                           <div class="input-field col s2">
                              <input id="icon_telephone" type="text" name="rv_projetado" class="validate" maxlength="8" pattern="(?:\.|,|[0-9])*" placeholder="Valor(R$)" onKeyPress="return(moeda2(this,'.',',',event))">
                              <label for="icon_telephone">RV Projetado</label>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!--Fim div manualmente -->
                  <!--Div Importação -->
                  <div id="importacaodiv">
                     <div class="col s12 m8 l9">
                        <br>
                        <h6>Clique no botão abaixo e escolha um arquivo.</h6>
                        <input type="file" id="input-file-now" name="select_file" accept=".xls,.xlsx,.csv" />
                     </div>
                  </div>
                  <!--Fim div importação -->
               </div>
               <div class="modal-footer" style="margin-top: -30px;">
                  <a type="button" id="btnsubmit" onClick="envia();" class="modal-action waves-effect waves-green btn-flat" style="background-color: green;color:white; margin-right: -3px; margin-top: 10px;"><i class="material-icons left">save_alt</i>Salvar</a>
               </div>
         </div>
         </form>
      </div>
      <!--Fim Modal -->
      @endsection


@section('scripts')
      <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/dropify.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/select2.full.min.js') }}"></script>
      <script src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/js/scripts/form-select2.min.js"></script>
      <script src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/js/scripts/form-file-uploads.min.js"></script>
      <script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
      <script>
         document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('.modal');
            var instances = M.Modal.init(elems, options);
         });
         
         document.addEventListener("DOMContentLoaded", function () {
            $('.modal').modal();
            $("#manualmentediv").hide();
            $("#importacaodiv").hide();
            $("#corpodiv").hide();
         });
      </script>
      <script>
         setTimeout(function() {
            $('#loading').fadeOut('fast');
            $("#corpodiv").show();
         }, 3000);
      </script>
      <script>
         function manualmente() {
         
             $("#manualmentediv").show();
             $("#importacaodiv").hide();
             $("#opcao").val("manualmente");
         
         
         }
      </script>
      <script>
         function importacao() {
         
             $("#manualmentediv").hide();
             $("#importacaodiv").show();
             $("#opcao").val("importacao"); 
         }
      </script>
      <script>
         function envia() {
         
             document.getElementById("loadingdiv").style.display = "";
             document.getElementById("corpodiv").style.display = "none";
             document.getElementById("form").submit();
         }    
      </script>
      <script language="javascript">
         $(document).ready(function(){
           $(".porcent").inputmask('Regex', { regex: "^[1-9][0-9]?$|^120$" });
         });
         
      </script> 
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
      @endsection
