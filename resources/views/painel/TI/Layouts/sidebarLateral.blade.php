<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('/public/AdminLTE/img/Avatar/img.png') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
            <p style="font-size: 12px">{{ auth()->user()->name }}</p>
            <a href="#"><i class="fa fa-circle text-success"></i>Perfíl T.I</a>
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
        <ul class="sidebar-menu" data-widget="tree">
       <li class="header"><strong><u>Controlar Permissões</u></strong></li></a>
      @can('users')
           <li>
               <a href="{{ route('Painel.TI.users.index') }}">
                <i class="fa fa-users" aria-hidden="true"></i>
                            Usuários
             </a>
            </li>
      @endcan
      @can('profiles')
                    <li>
                        <a href="{{url('/painel/perfis')}}">
                            <i class="fa fa-user-plus" aria-hidden="true"></i>
                            Perfis
                        </a>
                    </li>  
       @endcan
       @can('setores')
                    <li>
                        <a href="{{url('/painel/setorcusto')}}">
                            <i class="fa fa-user-plus" aria-hidden="true"></i>
                            Setor de Custo
                        </a>
                    </li>   
       @endcan
       @can('permissions')
                    <li>
                        <a href="{{url('/painel/permissoes')}}">
                            <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                            Permissões
                        </a>
                    </li>
      @endcan
      @can('ti_tarefasagendadas')
                    <li>
                        <a href="{{ route('Painel.TI.tarefasagendadas_index') }}">
                            <i class="fa fa-calendar" aria-hidden="true"></i>
                            Tarefas agendadas
                        </a>
                    </li>
       @endcan
        </ul>

           
    </section>
</aside>