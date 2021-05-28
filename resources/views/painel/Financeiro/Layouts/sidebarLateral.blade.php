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
            <a href="#"><i class="fa fa-circle text-success"></i>PerfílFinanceiro</a>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
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
            <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-home"></i>Home Page</a></li>
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
   
        @can('financeiro')

        <style>

          #debiteNovo{
            width: 100px;
          }

        </style>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-edit"></i> 
            <span>Solicitação Debite</span>
          </a>

          <ul class="treeview-menu" id="debite">
            <li><a href="{{ route('Painel.Financeiro.index') }}"><i class="fa fa-eye"></i>Solicitações a aprovar</a></li>
            <li><a href="{{ route('Painel.Financeiro.pagas') }}"><i class="fa fa-download"></i>Solicitações pagas</a></li>
          </ul>
        </li>
        @endcan
        
        @can('financeiro contas a pagar')
        
        <style>

          #debite{
            width: 200px;
          }

        </style>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-edit"></i> <span id="debite">Solicitação Debite</span>

          </a>
          <ul class="treeview-menu" id="debite">
            <li><a href="{{ route('Painel.Financeiro.programadas') }}"><i class="fa fa-calendar-plus-o"></i>Aguardando pagamento</a></li>
            <li><a href="{{ route('Painel.Financeiro.realizarconciliacao') }}"><i class="fa fa-download"></i>Realizar conciliação bancária</a></li>
            <li><a href="{{ route('Painel.Financeiro.pagas') }}"><i class="fa fa-download"></i>Solicitações pagas</a></li>
          </ul>
        </li>
        @endcan
        
        @can('financeiro_guias_custa')

        <style>
          #debite{
            width: 210px;
          }
        </style>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-folder"></i> <span id="debite">
              Pagamento guias
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </span>
          </a>
          <ul class="treeview-menu" id="debite">
            <li><a href="{{ route('Painel.Financeiro.guiascusta') }}"><i class="fa fa-folder"></i>Listagem pagamento guias</a></li>
          </ul>
        </li>
        @endcan

       @can('financeiro_faturamento')
        <style>
          #debite{
            width: 210px;
          }
        </style>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-folder"></i> <span id="debite">
              Faturamento 
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </span>
          </a>
          <ul class="treeview-menu" id="debite">
            <li><a href="{{ route('Painel.Financeiro.faturamento') }}"><i class="fa fa-folder"></i>Faturamento</a></li>
          </ul>
        </li>
        @endcan

        @can('advogado')
<style>
#countBg{
    background-color: rgba(0,0,0,0.2);
    color:black;
}
</style>

<ul class="sidebar-menu" data-widget="tree">
  <li class="header"><strong><u>Propostas</u></strong></li> 
  <li>
            <a href="{{ route('Painel.Proposta.solicitacao.create') }}" id="novasolicitacao" data-toggle="tooltip" data-placement="right" title="Clique aqui para solicitar uma nova proposta.">
                <i class="fa fa-plus" aria-hidden="true"></i>
            </a>
    </li>
   <li>
            <a href="{{ route('Painel.Proposta.solicitacao.index') }}" id="novasolicitacao" data-toggle="tooltip" data-placement="right" title="Clique aqui para visualizar as propostas.">
                <i class="fa fa-archive" aria-hidden="true"></i>
            </a>
    </li>
</ul>   

<hr>
@endcan 

@can('coordenador')
<style>
#countBg{
    background-color: rgba(0,0,0,0.2);
    color:black;
}
</style>

<ul class="sidebar-menu" data-widget="tree">
  <li class="header"><strong><u>Propostas</u></strong></li> 
  <li>
  <a href="{{ route('Painel.Proposta.solicitacao.create') }}" id="novasolicitacao" data-toggle="tooltip" data-placement="right" title="Clique aqui para solicitar uma nova proposta.">
                <i class="fa fa-plus" aria-hidden="true"></i>
            </a>
    </li>
   <li>
            <a href="{{ route('Painel.Proposta.solicitacao.index') }}" id="novasolicitacao" data-toggle="tooltip" data-placement="right" title="Clique aqui para visualizar as propostas.">
                <i class="fa fa-archive" aria-hidden="true"></i>
            </a>
    </li>
</ul> 

<hr>
@endcan 


        
        @can('financeiro_telefone_corporativo')


        <style>
          #debite{
            width: 260px;
          }
        </style>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-phone"></i> <span id="debite">
              Números corporativos
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </span>
          </a>
          <ul class="treeview-menu" id="debite">
            <li><a href="{{ route('Painel.Financeiro.telefones') }}"><i class="fa fa-phone"></i>Listagem número corporativos guias</a></li>
          </ul>
        </li>
        @endcan



      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- =============================================== -->