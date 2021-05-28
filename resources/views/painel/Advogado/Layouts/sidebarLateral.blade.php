  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ asset('/public/AdminLTE/img/Avatar/img.png') }}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
            <p style="font-size: 12px">{{ auth()->user()->name }}</p>
          <a href="#"><i class="fa fa-circle text-success"></i>Perfíl Advogado</a>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
         <li>
          <a href="{{ route('Home.Principal.Show') }}">
            <i class="fa fa-home" style="padding-top: 6px;"></i>
            <span>
              Home
              <small class="label pull-right bg-green">Principal</small>
            </span>
          </a>
        </li>
        <li>
          <a href="{{ route('Painel.Notificacao.index') }}" >
            <i class="fa fa-bell" style="padding-top: 6px;"></i>
            <span>
              Notificações
              <small class="label pull-right bg-red">{{$totalNotificacaoAbertas}}</small>
            </span>
          </a>
        </li>
        @can('advogado')
        <li class="treeview">
          <a href="#">
            <i class="fa fa-edit"></i> 
            <span id="debite">Solicitação Debite
                <i class="fa fa-angle-left pull-right"></i>
            </span>
          
          </a>

          <style>
          #debite{
            width: 210px;
          }
          </style>

          <ul class="treeview-menu" id="debite">
            <li><a href="{{ route('Painel.Advogado.index') }}"><i class="fa fa-eye"></i>Solicitações em Andamento</a></li>
            <li><a href="{{ route('Painel.Advogado.pagas') }}"><i class="fa fa-calendar-plus-o"></i>Solicitações pagas</a></li>
          </ul>
        </li>
        @endcan
        
       


      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- =============================================== -->