<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('./public/AdminLTE/img/Avatar/img.png') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
            <p style="font-size: 12px">{{ auth()->user()->name }}</p>
            <a href="#"><i class="fa fa-circle text-success"></i>Pesquisa especialista</a> 
            </div>
        </div>

          <ul class="sidebar-menu" data-widget="tree">
             <li class="header"><strong><u>Avaliação operador</u></strong></li>  
               <li>
                    <a href="{{ route('Painel.Correspondente.create') }}" id="novasolicitacao" data-toggle="tooltip" data-placement="right" title="Clique aqui para realizar uma avaliação do operador.">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>
                    </li>
            </ul>  
            <hr> 
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header"><strong><u>Adicionar Carteira</u></strong></li>  
                <li>
                    <a href="{{ route('Painel.Correspondente.novoCliente') }}" id="novocliente" data-toggle="tooltip" data-placement="right" title="Clique aqui para inserir uma nova carteira.">
                        <i class="fa fa-drivers-license-o" aria-hidden="true"></i>
                    </a>
                </li>
            </ul>  
        <hr>
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header"><strong><u>Adicionar Operador(a)</u></strong></li>  
            <li>
                <a href="{{ route('Painel.Correspondente.novoOperador') }}" id="novooperador" data-toggle="tooltip" data-placement="right" title="Clique aqui para inserir um(a) novo(a) operador(a).">
                    <i class="fa fa-user-plus" aria-hidden="true"></i>
                </a>
            </li>
        </ul>  
    </section>
</aside>