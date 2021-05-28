<!DOCTYPE html>

<html class="loading" lang="pt-br" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Language" content="pt-br">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Portal PL&C">
    <title>Anexos da pesquisa patrimonial | Portal PL&C</title>
    <link rel="apple-touch-icon" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/app-file-manager.min.css') }}">

    </head>

<body data-open="click" data-menu="vertical-modern-menu" data-col="2-columns" style="overflow: hidden;">


 <!-- BEGIN: Page Main-->
   <div class="section app-file-manager-wrapper" style="height: 550px; margin-top: -2px; overflow: scroll;">

  <div class="app-file-overlay"></div>

  <div class="sidebar-left">
    <div class="app-file-sidebar display-flex">
      <div class="app-file-sidebar-left">
        <span class="app-file-sidebar-close hide-on-med-and-up"><i class="material-icons">close</i></span>


        <div class="app-file-sidebar-content" style="height: 510px;overflow:auto;">

          <span class="app-file-label">Pastas</span>

          <!--Todas os arquivos --> 
          <div class="collection file-manager-drive mt-3">
            <a onclick="todosarquivos();" class="collection-item file-item-action active">
              <div class="fonticon-wrap display-inline mr-3">
                <i class="material-icons">folder_open</i>
              </div>
              <span style="color: black;">Todos arquivos</span>
              <span class="chip red lighten-5 float-right red-text">{{$QuantidadeAnexos}}</span>
            </a>

          </div>

          <!-- Fim todos os arquivos --> 

          <!--Boletos --> 
          <div class="collection file-manager-drive mt-3">
            <a href="#" class="collection-item file-item-action">
              <div class="fonticon-wrap display-inline mr-3">
                <i class="material-icons">folder_open</i>
              </div>
              <span style="color: black;">Boletos</span>
              <span class="chip red lighten-5 float-right red-text"></span>
            </a>
          </div>

          <!-- Fim boletos --> 

          <!--Comprovantes de pagamento --> 
          <div class="collection file-manager-drive mt-3">
            <a href="#" class="collection-item file-item-action">
              <div class="fonticon-wrap display-inline mr-3">
                <i class="material-icons">folder_open</i>
              </div>
              <span style="color: black;">Comprovantes</span>
              <span class="chip red lighten-5 float-right red-text"></span>
            </a>
          </div>
          <!--Fim Comprovantes de pagamento --> 

          <!--Aba Status --> 
          <div class="collection file-manager-drive mt-3">
            <a onClick="status();" class="collection-item file-item-action">
              <div class="fonticon-wrap display-inline mr-3">
                <i class="material-icons">folder_open</i>
              </div>
              <span style="color: black;">Status</span>
              <span class="chip red lighten-5 float-right red-text">{{$QuantidadeAnexosStatus}}</span>
            </a>
          </div>
          <!--Fim Aba Status --> 

          <!--Aba imóvel --> 
          <div class="collection file-manager-drive mt-3">
            <a onClick="imovel();" class="collection-item file-item-action">
              <div class="fonticon-wrap display-inline mr-3">
                <i class="material-icons">folder_open</i>
              </div>
              <span style="color: black;">Imóvel</span>
              <span class="chip red lighten-5 float-right red-text">{{$QuantidadeAnexosImovel}}</span>
            </a>
          </div>
          <!--Fim Aba imóvel --> 

          <!--Aba veículo --> 
          <div class="collection file-manager-drive mt-3">
            <a onClick="veiculo();" class="collection-item file-item-action">
              <div class="fonticon-wrap display-inline mr-3">
                <i class="material-icons">folder_open</i>
              </div>
              <span style="color: black;">Veículo</span>
              <span class="chip red lighten-5 float-right red-text">{{$QuantidadeAnexosVeiculo}}</span>
            </a>
          </div>
          <!--Fim Aba veículo --> 

          <!--Aba empresa --> 
          <div class="collection file-manager-drive mt-3">
            <a onClick="empresa();" class="collection-item file-item-action">
              <div class="fonticon-wrap display-inline mr-3">
                <i class="material-icons">folder_open</i>
              </div>
              <span style="color: black;">Empresa</span>
              <span class="chip red lighten-5 float-right red-text">{{$QuantidadeAnexosEmpresa}}</span>
            </a>
          </div>
          <!--Fim Aba empresa --> 

          <!--Aba infojud --> 
          <div class="collection file-manager-drive mt-3">
            <a onClick="infojud();" class="collection-item file-item-action">
              <div class="fonticon-wrap display-inline mr-3">
                <i class="material-icons">folder_open</i>
              </div>
              <span style="color: black;">Infojud</span>
              <span class="chip red lighten-5 float-right red-text">{{$QuantidadeAnexosInfojud}}</span>
            </a>
          </div>
          <!--Fim Aba infojud --> 

          <!--Aba bacenjud --> 
          <div class="collection file-manager-drive mt-3">
            <a onClick="bacenjud();" class="collection-item file-item-action">
              <div class="fonticon-wrap display-inline mr-3">
                <i class="material-icons">folder_open</i>
              </div>
              <span style="color: black;">Bcenjud</span>
              <span class="chip red lighten-5 float-right red-text">{{$QuantidadeAnexosBacenjud}}</span>
            </a>
          </div>
          <!--Fim Aba bacenjud --> 

          <!--Aba protestos --> 
          <div class="collection file-manager-drive mt-3">
            <a onClick="protestos();" class="collection-item file-item-action">
              <div class="fonticon-wrap display-inline mr-3">
                <i class="material-icons">folder_open</i>
              </div>
              <span style="color: black;">Protestos</span>
              <span class="chip red lighten-5 float-right red-text">{{$QuantidadeAnexosProtesto}}</span>
            </a>
          </div>
          <!--Fim Aba protestos --> 

          <!--Aba processos judiciais --> 
            <div class="collection file-manager-drive mt-3">
            <a onClick="processosjudiciais();" class="collection-item file-item-action">
              <div class="fonticon-wrap display-inline mr-3">
                <i class="material-icons">folder_open</i>
              </div>
              <span style="color: black;">Processos judiciais</span>
              <span class="chip red lighten-5 float-right red-text">{{$QuantidadeAnexosProcessosJudiciais}}</span>
            </a>
          </div>
          <!--Fim Aba processos judiciais --> 

          <!--Aba Pesquisa cadastral --> 
            <div class="collection file-manager-drive mt-3">
            <a onClick="pesquisacadastral();" class="collection-item file-item-action">
              <div class="fonticon-wrap display-inline mr-3">
                <i class="material-icons">folder_open</i>
              </div>
              <span style="color: black;">Pesquisa cadastral</span>
              <span class="chip red lighten-5 float-right red-text">{{$QuantidadeAnexosPesquisaCadastral}}</span>
            </a>
          </div>
          <!--Fim Aba Pesquisa cadastral--> 

          <!--Aba Dossiê comercial --> 
          <div class="collection file-manager-drive mt-3">
            <a onClick="dossiecomercial();" class="collection-item file-item-action">
              <div class="fonticon-wrap display-inline mr-3">
                <i class="material-icons">folder_open</i>
              </div>
              <span style="color: black;">Dossiê comercial</span>
              <span class="chip red lighten-5 float-right red-text">{{$QuantidadeAnexosDossieComercial}}</span>
            </a>
          </div>
          <!--Fim Aba Dossiê comercial --> 

          <!--Aba Diversos --> 
          <div class="collection file-manager-drive mt-3">
            <a onClick="diversos();" class="collection-item file-item-action">
              <div class="fonticon-wrap display-inline mr-3">
                <i class="material-icons">folder_open</i>
              </div>
              <span style="color: black;">Diversos</span>
              <span class="chip red lighten-5 float-right red-text">{{$QuantidadeAnexosDiversos}}</span>
            </a>
          </div>
          <!--Fim Aba Diversos --> 


        </div>
      </div>
    </div>

  </div>

  <div class="content-right">
    <div class="app-file-area">

      <!-- App File Content Starts -->
      <div class="app-file-content">


        <div id="todosarquivos">
        <h6 class="font-weight-700 mb-3">Todos arquivos</h6>
        <div class="row app-file-recent-access mb-3">

<!--Loop Arquivos --> 
@foreach($datas as $data)

<div id="anexos{{$data->nome}}" class="modal modal-fixed-footer" style="width: 100%;height:100%;overflow:hidden;">

<iframe src="https://docs.google.com/gview?url=https://portal.plcadvogados.com.br/portal/public/imgs/reembolso/{{$data->nome}}&embedded=true" style="width:100%;height:100%;" scrolling="no" frameborder="0" allowfullscreen></iframe> 

</div>
<!--Fim Modal  Visualizar Anexos --> 


        <a href="#anexos{{$data->nome}}" class="modal-trigger">
          <div class="col xl2 l3 m2 s6">
            <div class="card box-shadow-none mb-1 app-file-info">
              <div class="card-content">
                <div class="app-file-content-logo grey lighten-4">

                  <img class="recent-file" src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/icon/pdf.png" height="38" width="30"
                    alt="Card image cap">
                  </div>

                <div class="app-file-recent-details">
                  <div class="app-file-name" style="font-size: 12px;color:#6b6f82;">{{$data->nome}}</div>
                  <div class="app-file-size" style="font-size: 10px;color:#6b6f82;">Responsável: {{$data->Responsavel}} </div>
                  <div class="app-file-last-access" style="font-size: 10px;color:#6b6f82;">Data upload : {{ date('d/m/Y H:i:s', strtotime($data->data)) }}</div>
                </div>

              </div>
            </div>
          </div>
         </a> 
        @endforeach  
        <!--Fim Loop--> 
       </div>
       </div>
       <!--Fim Div Todos Arquivos --> 


       <!--Status --> 
       <div id="status" style="display: none">
        <h6 class="font-weight-700 mb-3">Status</h6>
        <div class="row app-file-recent-access mb-3">

        <!--Loop Arquivos --> 
        @foreach($statuss as $status)

<div id="status{{$status->nome}}" class="modal modal-fixed-footer" style="width: 100%;height:100%;overflow:hidden;">

<iframe src="https://docs.google.com/gview?url=https://portal.plcadvogados.com.br/portal/public/imgs/reembolso/{{$status->nome}}&embedded=true" style="width:100%;height:100%;" scrolling="no" frameborder="0" allowfullscreen></iframe> 

</div>
<!--Fim Modal  Visualizar Anexos --> 


        <a href="#status{{$status->nome}}" class="modal-trigger">
          <div class="col xl2 l3 m2 s6">
            <div class="card box-shadow-none mb-1 app-file-info">
              <div class="card-content">
                <div class="app-file-content-logo grey lighten-4">

                  <img class="recent-file" src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/icon/pdf.png" height="38" width="30"
                    alt="Card image cap">
                </div>

                <div class="app-file-recent-details">
                  <div class="app-file-name" style="font-size: 12px;color:#6b6f82;">{{$status->nome}}</div>
                  <div class="app-file-size" style="font-size: 10px;color:#6b6f82;">Responsável: {{$status->Responsavel}} </div>
                  <div class="app-file-last-access" style="font-size: 10px;color:#6b6f82;">Data upload : {{ date('d/m/Y H:i:s', strtotime($status->data)) }}</div>
                </div>

              </div>
            </div>
          </div>
         </a> 
        @endforeach  
        <!--Fim Loop--> 
       </div>
       </div>
       <!--Fim Div Status --> 

        <!--Imovel --> 
        <div id="imovel" style="display: none">
        <h6 class="font-weight-700 mb-3">Imóvel</h6>
        <div class="row app-file-recent-access mb-3">

        <!--Loop Arquivos --> 
        @foreach($imovels as $imovel)

<div id="imovel{{$imovel->nome}}" class="modal modal-fixed-footer" style="width: 100%;height:100%;overflow:hidden;">

<iframe src="https://docs.google.com/gview?url=https://portal.plcadvogados.com.br/portal/public/imgs/reembolso/{{$imovel->nome}}&embedded=true" style="width:100%;height:100%;" scrolling="no" frameborder="0" allowfullscreen></iframe> 

</div>
<!--Fim Modal  Visualizar Anexos --> 


        <a href="#imovel{{$imovel->nome}}" class="modal-trigger">
          <div class="col xl2 l3 m2 s6">
            <div class="card box-shadow-none mb-1 app-file-info">
              <div class="card-content">
                <div class="app-file-content-logo grey lighten-4">

                  <img class="recent-file" src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/icon/pdf.png" height="38" width="30"
                    alt="Card image cap">
                </div>

                <div class="app-file-recent-details">
                  <div class="app-file-name" style="font-size: 12px;color:#6b6f82;">{{$imovel->nome}}</div>
                  <div class="app-file-size" style="font-size: 10px;color:#6b6f82;">Responsável: {{$imovel->Responsavel}} </div>
                  <div class="app-file-last-access" style="font-size: 10px;color:#6b6f82;">Data upload : {{ date('d/m/Y H:i:s', strtotime($imovel->data)) }}</div>
                </div>

              </div>
            </div>
          </div>
         </a> 
        @endforeach  
        <!--Fim Loop--> 
       </div>
       </div>
       <!--Fim Div Imovel --> 


        <!--Veículo --> 
        <div id="veiculo" style="display: none">
        <h6 class="font-weight-700 mb-3">Veículo</h6>
        <div class="row app-file-recent-access mb-3">

        <!--Loop Arquivos --> 
        @foreach($veiculos as $veiculo)

  <div id="veiculo{{$veiculo->nome}}" class="modal modal-fixed-footer" style="width: 100%;height:100%;overflow:hidden;">

<iframe src="https://docs.google.com/gview?url=https://portal.plcadvogados.com.br/portal/public/imgs/reembolso/{{$veiculo->nome}}&embedded=true" style="width:100%;height:100%;" scrolling="no" frameborder="0" allowfullscreen></iframe> 

</div>
<!--Fim Modal  Visualizar Anexos --> 


        <a href="#veiculo{{$veiculo->nome}}" class="modal-trigger">
          <div class="col xl2 l3 m2 s6">
            <div class="card box-shadow-none mb-1 app-file-info">
              <div class="card-content">
                <div class="app-file-content-logo grey lighten-4">

                  <img class="recent-file" src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/icon/pdf.png" height="38" width="30"
                    alt="Card image cap">
                </div>

                <div class="app-file-recent-details">
                  <div class="app-file-name" style="font-size: 12px;color:#6b6f82;">{{$veiculo->nome}}</div>
                  <div class="app-file-size" style="font-size: 10px;color:#6b6f82;">Responsável: {{$veiculo->Responsavel}} </div>
                  <div class="app-file-last-access" style="font-size: 10px;color:#6b6f82;">Data upload : {{ date('d/m/Y H:i:s', strtotime($veiculo->data)) }}</div>
                </div>

              </div>
            </div>
          </div>
         </a> 
        @endforeach  
        <!--Fim Loop--> 
       </div>
       </div>
       <!--Fim Div Veiculo --> 

    <!--Empresa --> 
      <div id="empresa" style="display: none">
        <h6 class="font-weight-700 mb-3">Empresa</h6>
        <div class="row app-file-recent-access mb-3">

        <!--Loop Arquivos --> 
        @foreach($empresas as $empresa)

<!--Inicio Modal Visualizar Anexo --> 
<div id="empresa{{$empresa->nome}}" class="modal modal-fixed-footer" style="width: 100%;height:100%;overflow:hidden;">

<iframe src="https://docs.google.com/gview?url=https://portal.plcadvogados.com.br/portal/public/imgs/reembolso/{{$empresa->nome}}&embedded=true" style="width:100%;height:100%;" scrolling="no" frameborder="0" allowfullscreen></iframe> 

</div>
<!--Fim Modal  Visualizar Anexos --> 


        <a href="#empresa{{$empresa->nome}}" class="modal-trigger">
          <div class="col xl2 l3 m2 s6">
            <div class="card box-shadow-none mb-1 app-file-info">
              <div class="card-content">
                <div class="app-file-content-logo grey lighten-4">

                  <img class="recent-file" src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/icon/pdf.png" height="38" width="30"
                    alt="Card image cap">
                </div>

                <div class="app-file-recent-details">
                  <div class="app-file-name" style="font-size: 12px;color:#6b6f82;">{{$empresa->nome}}</div>
                  <div class="app-file-size" style="font-size: 10px;color:#6b6f82;">Responsável: {{$empresa->Responsavel}} </div>
                  <div class="app-file-last-access" style="font-size: 10px;color:#6b6f82;">Data upload : {{ date('d/m/Y H:i:s', strtotime($empresa->data)) }}</div>
                </div>

              </div>
            </div>
          </div>
         </a> 
        @endforeach  
        <!--Fim Loop--> 
       </div>
       </div>
       <!--Fim Div Empresa --> 

    <!--Infojud --> 
      <div id="infojud" style="display: none">
        <h6 class="font-weight-700 mb-3">Infojud</h6>
        <div class="row app-file-recent-access mb-3">

        <!--Loop Arquivos --> 
        @foreach($infojuds as $infojud)

<!--Inicio Modal Visualizar Anexo --> 
<div id="infojud{{$infojud->nome}}" class="modal modal-fixed-footer" style="width: 100%;height:100%;overflow:hidden;">

<iframe src="https://docs.google.com/gview?url=https://portal.plcadvogados.com.br/portal/public/imgs/reembolso/{{$infojud->nome}}&embedded=true" style="width:100%;height:100%;" scrolling="no" frameborder="0" allowfullscreen></iframe> 

</div>
<!--Fim Modal  Visualizar Anexos --> 


        <a href="#infojud{{$infojud->nome}}" class="modal-trigger">
          <div class="col xl2 l3 m2 s6">
            <div class="card box-shadow-none mb-1 app-file-info">
              <div class="card-content">
                <div class="app-file-content-logo grey lighten-4">

                  <img class="recent-file" src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/icon/pdf.png" height="38" width="30"
                    alt="Card image cap">
                </div>

                <div class="app-file-recent-details">
                  <div class="app-file-name" style="font-size: 12px;color:#6b6f82;">{{$infojud->nome}}</div>
                  <div class="app-file-size" style="font-size: 10px;color:#6b6f82;">Responsável: {{$infojud->Responsavel}} </div>
                  <div class="app-file-last-access" style="font-size: 10px;color:#6b6f82;">Data upload : {{ date('d/m/Y H:i:s', strtotime($infojud->data)) }}</div>
                </div>

              </div>
            </div>
          </div>
         </a> 
        @endforeach  
        <!--Fim Loop--> 
       </div>
       </div>
       <!--Fim Div Infojud --> 


      <!--Bacenjud --> 
      <div id="bacenjud" style="display: none">
        <h6 class="font-weight-700 mb-3">Bacenjud</h6>
        <div class="row app-file-recent-access mb-3">

        <!--Loop Arquivos --> 
  @foreach($bacenjuds as $bacenjud)

<!--Inicio Modal Visualizar Anexo --> 
<div id="bacenjud{{$bacenjud->nome}}" class="modal modal-fixed-footer" style="width: 100%;height:100%;overflow:hidden;">

<iframe src="https://docs.google.com/gview?url=https://portal.plcadvogados.com.br/portal/public/imgs/reembolso/{{$bacenjud->nome}}&embedded=true" style="width:100%;height:100%;" scrolling="no" frameborder="0" allowfullscreen></iframe> 

</div>
<!--Fim Modal  Visualizar Anexos --> 


        <a href="#bacenjud{{$bacenjud->nome}}" class="modal-trigger">
          <div class="col xl2 l3 m2 s6">
            <div class="card box-shadow-none mb-1 app-file-info">
              <div class="card-content">
                <div class="app-file-content-logo grey lighten-4">

                  <img class="recent-file" src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/icon/pdf.png" height="38" width="30"
                    alt="Card image cap">
                </div>

                <div class="app-file-recent-details">
                  <div class="app-file-name" style="font-size: 12px;color:#6b6f82;">{{$bacenjud->nome}}</div>
                  <div class="app-file-size" style="font-size: 10px;color:#6b6f82;">Responsável: {{$bacenjud->Responsavel}} </div>
                  <div class="app-file-last-access" style="font-size: 10px;color:#6b6f82;">Data upload : {{ date('d/m/Y H:i:s', strtotime($bacenjud->data)) }}</div>
                </div>

              </div>
            </div>
          </div>
         </a> 
        @endforeach  
        <!--Fim Loop--> 
       </div>
       </div>
       <!--Fim Div Bacenjud --> 

     <!--Protestos --> 
      <div id="protestos" style="display: none">
        <h6 class="font-weight-700 mb-3">Protestos</h6>
        <div class="row app-file-recent-access mb-3">

        <!--Loop Arquivos --> 
        @foreach($protestoss as $protestoss)
 <!--Inicio Modal Visualizar Anexo --> 
 <div id="protesto{{$protestoss->nome}}" class="modal modal-fixed-footer" style="width: 100%;height:100%;overflow:hidden;">

<iframe src="https://docs.google.com/gview?url=https://portal.plcadvogados.com.br/portal/public/imgs/reembolso/{{$protestoss->nome}}&embedded=true" style="width:100%;height:100%;" scrolling="no" frameborder="0" allowfullscreen></iframe> 

</div>
<!--Fim Modal  Visualizar Anexos --> 


        <a href="#protesto{{$protestoss->nome}}" class="modal-trigger">
          <div class="col xl2 l3 m2 s6">
            <div class="card box-shadow-none mb-1 app-file-info">
              <div class="card-content">
                <div class="app-file-content-logo grey lighten-4">

                  <img class="recent-file" src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/icon/pdf.png" height="38" width="30"
                    alt="Card image cap">
                </div>

                <div class="app-file-recent-details">
                  <div class="app-file-name" style="font-size: 12px;color:#6b6f82;">{{$protestoss->nome}}</div>
                  <div class="app-file-size" style="font-size: 10px;color:#6b6f82;">Responsável: {{$protestoss->Responsavel}} </div>
                  <div class="app-file-last-access" style="font-size: 10px;color:#6b6f82;">Data upload : {{ date('d/m/Y H:i:s', strtotime($protestoss->data)) }}</div>
                </div>

              </div>
            </div>
          </div>
         </a> 
        @endforeach  
        <!--Fim Loop--> 
       </div>
       </div>
       <!--Fim Div Protestos --> 

      <!--Processos Judiciais --> 
      <div id="processosjudiciais" style="display: none">
        <h6 class="font-weight-700 mb-3">Processos Judiciais</h6>
        <div class="row app-file-recent-access mb-3">

        <!--Loop Arquivos --> 
        @foreach($processosjudiciaiss as $processojudicial)


 <!--Inicio Modal Visualizar Anexo --> 
 <div id="processojudicial{{$processojudicial->nome}}" class="modal modal-fixed-footer" style="width: 100%;height:100%;overflow:hidden;">

<iframe src="https://docs.google.com/gview?url=https://portal.plcadvogados.com.br/portal/public/imgs/reembolso/{{$processojudicial->nome}}&embedded=true" style="width:100%;height:100%;" scrolling="no" frameborder="0" allowfullscreen></iframe> 
</div>
<!--Fim Modal  Visualizar Anexos --> 


        <a href="#processojudicial{{$processojudicial->nome}}" class="modal-trigger">
          <div class="col xl2 l3 m2 s6">
            <div class="card box-shadow-none mb-1 app-file-info">
              <div class="card-content">
                <div class="app-file-content-logo grey lighten-4">

                  <img class="recent-file" src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/icon/pdf.png" height="38" width="30"
                    alt="Card image cap">
                </div>

                <div class="app-file-recent-details">
                  <div class="app-file-name" style="font-size: 12px;color:#6b6f82;">{{$processojudicial->nome}}</div>
                  <div class="app-file-size" style="font-size: 10px;color:#6b6f82;">Responsável: {{$processojudicial->Responsavel}} </div>
                  <div class="app-file-last-access" style="font-size: 10px;color:#6b6f82;">Data upload : {{ date('d/m/Y H:i:s', strtotime($processojudicial->data)) }}</div>
                </div>

              </div>
            </div>
          </div>
         </a> 
        @endforeach  
        <!--Fim Loop--> 
       </div>
       </div>
       <!--Fim Div Processos Judiciais --> 

      <!--Pesquisa Cadastral --> 
      <div id="pesquisacadastral" style="display: none">
        <h6 class="font-weight-700 mb-3">Pesquisa Cadastral</h6>
        <div class="row app-file-recent-access mb-3">

        <!--Loop Arquivos --> 
        @foreach($pesquisacadastrals as $pesquisacadastral)

<!--Inicio Modal Visualizar Anexo --> 
<div id="pesquisacadastral{{$pesquisacadastral->nome}}" class="modal modal-fixed-footer" style="width: 100%;height:100%;overflow:hidden;">

<iframe src="https://docs.google.com/gview?url=https://portal.plcadvogados.com.br/portal/public/imgs/reembolso/{{$pesquisacadastral->nome}}&embedded=true" style="width:100%;height:100%;" scrolling="no" frameborder="0" allowfullscreen></iframe> 

</div>
<!--Fim Modal  Visualizar Anexos --> 


        <a href="#pesquisacadastral{{$pesquisacadastral->nome}}" class="modal-trigger">
          <div class="col xl2 l3 m2 s6">
            <div class="card box-shadow-none mb-1 app-file-info">
              <div class="card-content">
                <div class="app-file-content-logo grey lighten-4">

                  <img class="recent-file" src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/icon/pdf.png" height="38" width="30"
                    alt="Card image cap">
                </div>

                <div class="app-file-recent-details">
                  <div class="app-file-name" style="font-size: 12px;color:#6b6f82;">{{$pesquisacadastral->nome}}</div>
                  <div class="app-file-size" style="font-size: 10px;color:#6b6f82;">Responsável: {{$pesquisacadastral->Responsavel}} </div>
                  <div class="app-file-last-access" style="font-size: 10px;color:#6b6f82;">Data upload : {{ date('d/m/Y H:i:s', strtotime($pesquisacadastral->data)) }}</div>
                </div>

              </div>
            </div>
          </div>
         </a> 
        @endforeach  
        <!--Fim Loop--> 
       </div>
       </div>
       <!--Fim Div Pesquisa Cadastral --> 

    <!--Dossiê Comercial --> 
      <div id="dossiecomercial" style="display: none">
        <h6 class="font-weight-700 mb-3">Dossiê Comercial</h6>
        <div class="row app-file-recent-access mb-3">

        <!--Loop Arquivos --> 
        @foreach($dossiecomercials as $dossiecomercial)

<!--Inicio Modal Visualizar Anexo --> 
<div id="dossiecomercial{{$dossiecomercial->nome}}" class="modal modal-fixed-footer" style="width: 100%;height:100%;overflow:hidden;">

<iframe src="https://docs.google.com/gview?url=https://portal.plcadvogados.com.br/portal/public/imgs/reembolso/{{$dossiecomercial->nome}}&embedded=true" style="width:100%;height:100%;" scrolling="no" frameborder="0" allowfullscreen></iframe> 

</div>
<!--Fim Modal  Visualizar Anexos --> 


        <a href="#dossiecomercial{{$dossiecomercial->nome}}" class="modal-trigger">
          <div class="col xl2 l3 m2 s6">
            <div class="card box-shadow-none mb-1 app-file-info">
              <div class="card-content">
                <div class="app-file-content-logo grey lighten-4">

                  <img class="recent-file" src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/icon/pdf.png" height="38" width="30"
                    alt="Card image cap">
                </div>

                <div class="app-file-recent-details">
                  <div class="app-file-name" style="font-size: 12px;color:#6b6f82;">{{$dossiecomercial->nome}}</div>
                  <div class="app-file-size" style="font-size: 10px;color:#6b6f82;">Responsável: {{$dossiecomercial->Responsavel}} </div>
                  <div class="app-file-last-access" style="font-size: 10px;color:#6b6f82;">Data upload : {{ date('d/m/Y H:i:s', strtotime($dossiecomercial->data)) }}</div>
                </div>

              </div>
            </div>
          </div>
         </a> 
        @endforeach  
        <!--Fim Loop--> 
       </div>
       </div>
       <!--Fim Div Dossiê Comercial --> 

      <!--Diversos --> 
      <div id="diversos" style="display: none">
        <h6 class="font-weight-700 mb-3">Diversos</h6>
        <div class="row app-file-recent-access mb-3">

        <!--Loop Arquivos --> 
        @foreach($diversoss as $diversos)

<!--Inicio Modal Visualizar Anexo --> 
<div id="diversos{{$diversos->nome}}" class="modal modal-fixed-footer" style="width: 100%;height:100%;overflow:hidden;">

<iframe src="https://docs.google.com/gview?url=https://portal.plcadvogados.com.br/portal/public/imgs/reembolso/{{$diversos->nome}}&embedded=true" style="width:100%;height:100%;" scrolling="no" frameborder="0" allowfullscreen></iframe> 

</div>
<!--Fim Modal  Visualizar Anexos --> 


        <a href="#diversos{{$diversos->nome}}" class="modal-trigger">
          <div class="col xl2 l3 m2 s6">
            <div class="card box-shadow-none mb-1 app-file-info">
              <div class="card-content">
                <div class="app-file-content-logo grey lighten-4">

                  <img class="recent-file" src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/icon/pdf.png" height="38" width="30"
                    alt="Card image cap">
                </div>

                <div class="app-file-recent-details">
                  <div class="app-file-name" style="font-size: 12px;color:#6b6f82;">{{$diversos->nome}}</div>
                  <div class="app-file-size" style="font-size: 10px;color:#6b6f82;">Responsável: {{$diversos->Responsavel}} </div>
                  <div class="app-file-last-access" style="font-size: 10px;color:#6b6f82;">Data upload : {{ date('d/m/Y H:i:s', strtotime($diversos->data)) }}</div>
                </div>

              </div>
            </div>
          </div>
         </a> 
        @endforeach  
        <!--Fim Loop--> 
       </div>
       </div>
       <!--Fim Div Diversos --> 








  </div>
 
</div>


          </div>
          <div class="content-overlay"></div>

    <!-- END: Page Main-->

<script>
document.addEventListener("DOMContentLoaded", function () {
   $('.modal').modal();
});
</script>


    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>


  <script>

  function todosarquivos() {
      document.getElementById("todosarquivos").style.display = "";
      document.getElementById("status").style.display = "none";
      document.getElementById("imovel").style.display = "none";
      document.getElementById("veiculo").style.display = "none";
      document.getElementById("empresa").style.display = "none";
      document.getElementById("infojud").style.display = "none";
      document.getElementById("bacenjud").style.display = "none";
      document.getElementById("protestos").style.display = "none";
      document.getElementById("processosjudiciais").style.display = "none";
      document.getElementById("pesquisacadastral").style.display = "none";
      document.getElementById("dossiecomercial").style.display = "none";
      document.getElementById("diversos").style.display = "none";

  }

  function status() {
      document.getElementById("todosarquivos").style.display = "none";
      document.getElementById("status").style.display = "";
      document.getElementById("imovel").style.display = "none";
      document.getElementById("veiculo").style.display = "none";
      document.getElementById("empresa").style.display = "none";
      document.getElementById("infojud").style.display = "none";
      document.getElementById("bacenjud").style.display = "none";
      document.getElementById("protestos").style.display = "none";
      document.getElementById("processosjudiciais").style.display = "none";
      document.getElementById("pesquisacadastral").style.display = "none";
      document.getElementById("dossiecomercial").style.display = "none";
      document.getElementById("diversos").style.display = "none";
  }

  function imovel() {
      document.getElementById("todosarquivos").style.display = "none";
      document.getElementById("status").style.display = "none";
      document.getElementById("imovel").style.display = "";
      document.getElementById("veiculo").style.display = "none";
      document.getElementById("empresa").style.display = "none";
      document.getElementById("infojud").style.display = "none";
      document.getElementById("bacenjud").style.display = "none";
      document.getElementById("protestos").style.display = "none";
      document.getElementById("processosjudiciais").style.display = "none";
      document.getElementById("pesquisacadastral").style.display = "none";
      document.getElementById("dossiecomercial").style.display = "none";
      document.getElementById("diversos").style.display = "none";
  }
  function veiculo() {
      document.getElementById("todosarquivos").style.display = "none";
      document.getElementById("status").style.display = "none";
      document.getElementById("imovel").style.display = "none";
      document.getElementById("veiculo").style.display = "";
      document.getElementById("empresa").style.display = "none";
      document.getElementById("infojud").style.display = "none";
      document.getElementById("bacenjud").style.display = "none";
      document.getElementById("protestos").style.display = "none";
      document.getElementById("processosjudiciais").style.display = "none";
      document.getElementById("pesquisacadastral").style.display = "none";
      document.getElementById("dossiecomercial").style.display = "none";
      document.getElementById("diversos").style.display = "none";
  }
  function empresa() {
      document.getElementById("todosarquivos").style.display = "none";
      document.getElementById("status").style.display = "none";
      document.getElementById("imovel").style.display = "none";
      document.getElementById("veiculo").style.display = "none";
      document.getElementById("empresa").style.display = "";
      document.getElementById("infojud").style.display = "none";
      document.getElementById("bacenjud").style.display = "none";
      document.getElementById("protestos").style.display = "none";
      document.getElementById("processosjudiciais").style.display = "none";
      document.getElementById("pesquisacadastral").style.display = "none";
      document.getElementById("dossiecomercial").style.display = "none";
      document.getElementById("diversos").style.display = "none";
  }
  function infojud() {
      document.getElementById("todosarquivos").style.display = "none";
      document.getElementById("status").style.display = "none";
      document.getElementById("imovel").style.display = "none";
      document.getElementById("veiculo").style.display = "none";
      document.getElementById("empresa").style.display = "none";
      document.getElementById("infojud").style.display = "";
      document.getElementById("bacenjud").style.display = "none";
      document.getElementById("protestos").style.display = "none";
      document.getElementById("processosjudiciais").style.display = "none";
      document.getElementById("pesquisacadastral").style.display = "none";
      document.getElementById("dossiecomercial").style.display = "none";
      document.getElementById("diversos").style.display = "none";
  }
  function bacenjud() {
      document.getElementById("todosarquivos").style.display = "none";
      document.getElementById("status").style.display = "none";
      document.getElementById("imovel").style.display = "none";
      document.getElementById("veiculo").style.display = "none";
      document.getElementById("empresa").style.display = "none";
      document.getElementById("infojud").style.display = "none";
      document.getElementById("bacenjud").style.display = "";
      document.getElementById("protestos").style.display = "none";
      document.getElementById("processosjudiciais").style.display = "none";
      document.getElementById("pesquisacadastral").style.display = "none";
      document.getElementById("dossiecomercial").style.display = "none";
      document.getElementById("diversos").style.display = "none";
  }
  function protestos() {
      document.getElementById("todosarquivos").style.display = "none";
      document.getElementById("status").style.display = "none";
      document.getElementById("imovel").style.display = "none";
      document.getElementById("veiculo").style.display = "none";
      document.getElementById("empresa").style.display = "none";
      document.getElementById("infojud").style.display = "none";
      document.getElementById("bacenjud").style.display = "none";
      document.getElementById("protestos").style.display = "";
      document.getElementById("processosjudiciais").style.display = "none";
      document.getElementById("pesquisacadastral").style.display = "none";
      document.getElementById("dossiecomercial").style.display = "none";
      document.getElementById("diversos").style.display = "none";
  }
  function processosjudiciais() {
      document.getElementById("todosarquivos").style.display = "none";
      document.getElementById("status").style.display = "none";
      document.getElementById("imovel").style.display = "none";
      document.getElementById("veiculo").style.display = "none";
      document.getElementById("empresa").style.display = "none";
      document.getElementById("infojud").style.display = "none";
      document.getElementById("bacenjud").style.display = "none";
      document.getElementById("protestos").style.display = "none";
      document.getElementById("processosjudiciais").style.display = "";
      document.getElementById("pesquisacadastral").style.display = "none";
      document.getElementById("dossiecomercial").style.display = "none";
      document.getElementById("diversos").style.display = "none";
  }
  function pesquisacadastral() {
      document.getElementById("todosarquivos").style.display = "none";
      document.getElementById("status").style.display = "none";
      document.getElementById("imovel").style.display = "none";
      document.getElementById("veiculo").style.display = "none";
      document.getElementById("empresa").style.display = "none";
      document.getElementById("infojud").style.display = "none";
      document.getElementById("bacenjud").style.display = "none";
      document.getElementById("protestos").style.display = "none";
      document.getElementById("processosjudiciais").style.display = "none";
      document.getElementById("pesquisacadastral").style.display = "";
      document.getElementById("dossiecomercial").style.display = "none";
      document.getElementById("diversos").style.display = "none";
  }
  function dossiecomercial() {

      document.getElementById("todosarquivos").style.display = "none";
      document.getElementById("status").style.display = "none";
      document.getElementById("imovel").style.display = "none";
      document.getElementById("veiculo").style.display = "none";
      document.getElementById("empresa").style.display = "none";
      document.getElementById("infojud").style.display = "none";
      document.getElementById("bacenjud").style.display = "none";
      document.getElementById("protestos").style.display = "none";
      document.getElementById("processosjudiciais").style.display = "none";
      document.getElementById("pesquisacadastral").style.display = "none";
      document.getElementById("dossiecomercial").style.display = "";
      document.getElementById("diversos").style.display = "none";

  }
  function diversos() {
      document.getElementById("todosarquivos").style.display = "none";
      document.getElementById("status").style.display = "none";
      document.getElementById("imovel").style.display = "none";
      document.getElementById("veiculo").style.display = "none";
      document.getElementById("empresa").style.display = "none";
      document.getElementById("infojud").style.display = "none";
      document.getElementById("bacenjud").style.display = "none";
      document.getElementById("protestos").style.display = "none";
      document.getElementById("processosjudiciais").style.display = "none";
      document.getElementById("pesquisacadastral").style.display = "none";
      document.getElementById("dossiecomercial").style.display = "none";
      document.getElementById("diversos").style.display = "";
  }


  </script>

  </body>
</html>