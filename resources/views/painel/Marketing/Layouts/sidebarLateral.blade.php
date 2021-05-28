<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('/public/AdminLTE/img/Avatar/img.png') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ auth()->user()->name }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i>Perfíl Marketing</a>
            </div>
        </div>
        <br>
          <li>
           <a href="{{ route('Home.Principal.Show') }}">
            <i class="fa fa-home" aria-hidden="true"></i>
            
           </a>
         </li>
    
        <br>
        <hr>
   @can('marketing')
        <ul class="sidebar-menu" data-widget="tree">
          <li class="header"><strong><u>Comunicados</u></strong></li>
          
          <li>
           <a href="{{ route('Painel.Marketing.create') }}">
            <i class="fa fa-calendar-plus-o" aria-hidden="true"></i>
            Criar novo
            </a>
         </li>
         
          <li>
           <a href="{{ route('Painel.Marketing.index') }}">
            <i class="fa fa-calendar" aria-hidden="true"></i>
            Comunicados ativos
            </a>
         </li>
         
          <li>
           <a href="{{ route('Painel.Marketing.desativados') }}">
            <i class="fa fa-calendar-times-o" aria-hidden="true"></i>
            Comunicados desativados
            </a>
         </li>
         
           </ul>
         <hr>
       @endcan
         
   
        </ul>
         <hr>
        <br>
           
    </section>
</aside>