  <aside class="main-sidebar">
    <section class="sidebar">
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ asset('/public/AdminLTE/img/Avatar/img.png') }}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
            <p style="font-size: 12px">{{ auth()->user()->name }}</p>
            <a href="#"><i class="fa fa-circle text-success"></i>Perfíl Revisão Técnica</a>
        </div>
      </div>
      <ul class="sidebar-menu" data-widget="treeview">
            <li class="treeview">
              <a href="#">
                <i class="fa fa-home" style="padding-top: 6px;"></i> 
                <span id="debite">
                  Home
                </span>
              </a>
              <style>
                #debite{
                  width: 210px;
                }
              </style>
              <ul class="treeview-menu" id="debite">
              <li><a href="{{ route('Home.Principal.Show') }}" ><i class="fa fa-home"></i>Home Page</a></li>
              </ul>
            </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-bell" style="padding-top: 6px;"></i> 
            <span id="debite">
            Notificações
              <small class="label pull-right bg-red">{{$totalNotificacaoAbertas}}</small>
            </span>
          </a>
          <style>
            #debite{
              width: 210px;
            }
          </style>
          <ul class="treeview-menu" id="debite">
          <li><a href="{{ route('Painel.Notificacao.index') }}" ><i class="fa fa-bell"></i>Notificações</a></li>
          </ul>
        </li>


        @can('coordenador')
        <li class="treeview">
          <a href="#">
            <i class="fa fa-edit" style="padding-top: 6px;"></i> 
            <span id="debite">
              Solicitação Debite
            </span>
          </a>
          <style>

            #debite{
              width: 210px;
            }

          </style>
          <ul class="treeview-menu" id="debite">
            <li><a href="{{ route('Painel.Coordenador.index') }}"><i class="fa fa-eye"></i>Solicitações para aprovar</a></li>
            <li><a href="{{ route('Painel.Coordenador.acompanharSolicitacoes') }}"><i class="fa fa-calendar-plus-o"></i>Listagem Solicitações</a></li>
          </ul>
        </li>
        @endcan
      </ul>
    </section>
  </aside>

  <!-- =============================================== -->