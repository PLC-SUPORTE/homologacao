<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('/public/AdminLTE/img/Avatar/img.png') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
            <p style="font-size: 12px">{{ auth()->user()->name }}</p>
            <a href="#"><i class="fa fa-circle text-success"></i>Perfíl DP/RH</a>
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
                        <a href="{{ route('Painel.Notificacao.index') }}"  id="notificacoes" data-toggle="tooltip" data-placement="left" title="[Sistema de Notificações de Envio e Recebimento de mensagens no portal PLC]&#013Através deste menu é possível visualizar as notificações de envio e recebimento do portal.">
                            <i class="fa fa-bell-o" aria-hidden="true"></i>
                            Notificações
                        <span class="pull-right-container">
                        <span class="label label-primary pull-right">{{$totalNotificacaoAbertas}}</span>
                        </span>
                        </a>
                    </li>
            </ul>   
        <br>
        <hr>
   @can('listagem_colaboradores_dprh')
        <ul class="sidebar-menu" data-widget="tree">
          <li class="header"><strong><u>Colaboradores</u></strong></li>
          
          <li>
           <a href="{{ route('Painel.DPRH.Colaboradores.index') }}">
            <i class="fa fa-eye" aria-hidden="true"></i>
            Status Ativo
            </a>
         </li>
         
          <li>
           <a href="{{ route('Painel.DPRH.Colaboradores.desativados') }}">
            <i class="fa fa-eye" aria-hidden="true"></i>
            Status inatívo
            </a>
         </li>
         
           </ul>
         <hr>
         @endcan
         <hr>
        <br>
           
    </section>
</aside>