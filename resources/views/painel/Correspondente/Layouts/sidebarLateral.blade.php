<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('/public/AdminLTE/img/Avatar/img.png') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
            <p style="font-size: 12px">{{ auth()->user()->name }}</p>
            <a href="#"><i class="fa fa-circle text-success"></i>Perfíl Correspondente</a>
            </div>
        </div>
        <br>
          <li>
           <a href="{{ route('Home.Principal.Show') }}">
            <i class="fa fa-home" aria-hidden="true"></i>
            Home
           </a>
         </li>
        
        <br> 

        <hr>
          <ul class="sidebar-menu" data-widget="tree">
              <li class="header"><strong><u>Notificações</u></strong></li> 
                    <li>
                        <a href="#"  id="notificacoes" data-toggle="tooltip" data-placement="left" title="[Sistema de Notificações de Envio e Recebimento de mensagens no portal PLC]&#013Clique aqui para visualizar as notificações de envio e recebimento do portal.">
                            <i class="fa fa-bell-o" aria-hidden="true"></i>
                            Notificações
                        <span class="pull-right-container">
                        <span class="label label-warning pull-right">{{$totalNotificacaoAbertas}}</span>
                        </span>
                        </a>
                    </li>
            </ul>   
        <br>
        <hr>
        @can('correspondentes')

        <style>

            #countBg{
                background-color: rgba(0,0,0,0.2);
                color:black;
            }

        </style>

          <ul class="sidebar-menu" data-widget="tree">
              <li class="header"><strong><u>Solicitação de Debite</u></strong></li> 
               <li>
                        <a href="{{ route('Painel.Correspondente.create') }}" id="novasolicitacao" data-toggle="tooltip" data-placement="left" title="Clique aqui para realizar o cadastro de uma nova solicitação de pagamento.">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                          Nova Solicitação
                        </a>
                    </li>
                <li>
                        <a href="{{ route('Painel.Correspondente.index') }}" id="correspondentes" data-toggle="tooltip" data-placement="left" title="Clique aqui para listar suas solicitações cujo o status esteja em 'Andamento'">
                            <i class="fa fa-calendar-plus-o" aria-hidden="true"></i>
                        Solicitações em andamento
                        <span class="pull-right-container">
                        <span class="label pull-right" id="countBg">{{$totalSolicitacoesAbertas}}</span>
                        </span>
                        </a>
                </li>
                <li>
                       <a href="{{ route('Painel.Correspondente.pagas') }}" id="correspondentes" data-toggle="tooltip" data-placement="left" title="Clique aqui para listar suas solicitações cujo status esteja 'Pago'.">
                         <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                        Solicitações pagas
                        <span class="pull-right-container">
                        <span class="label pull-right" id="countBg">{{$totalSolicitacoesPagasCorrespondente}}</span>
                        </span>
                        </a>
                </li>
                <li>
                       <a href="{{ route('Painel.Correspondente.canceladas') }}" id="correspondentes" data-toggle="tooltip" data-placement="left" title="Clique aqui para listar suas solicitações cujo status esteja 'Cancelado'.">
                            <i class="fa fa-calendar-times-o" aria-hidden="true"></i>
                        Solicitações canceladas
                        <span class="pull-right-container">
                        <span class="label label-danger pull-right">{{$totalSolicitacoesCancelada}}</span>
                        </span>
                        </a>
                </li>
            </ul>   
        <hr>
         @endcan        

         
    </section>
</aside>