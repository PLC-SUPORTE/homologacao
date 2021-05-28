<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script>
    $(document).ready(function(){
        // Show hide popover
        $(".dropdown").click(function(){
            $(this).find(".dropdown-menu").slideToggle("fast");
        });
    });
    $(document).on("click", function(event){
        var $trigger = $(".dropdown");
        if($trigger !== event.target && !$trigger.has(event.target).length){
            $(".dropdown-menu").slideUp("fast");
        }            
    });
</script>


<header class="main-header">
  <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button" style="background-color:#965A2C; color:white">
    <span class="sr-only">Toggle navigation</span>
  </a>
    <a href="{{ route('Painel.Coordenador.principal') }}" class="logo">
        <span class="logo-lg">{{ env('APP_NAME') }}</span>
    </a>
    <nav class="navbar navbar-static-top">
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">


  <li  class="wiloke-menu-item dropdown messages-menu">
  <a data-toggle="dropdown">
    <i class="wiloke-menu-item-icon fa fa-bell-o"></i> Notificações
    @if ($totalNotificacaoAbertas == 0)
    @else
    <span style="color: yellow;font-size: 16px"> ({{$totalNotificacaoAbertas}}) </span>
    @endif
  </a>
            <ul class="dropdown-menu" id="dropDownMenuNotificacao" style="width: 300px; background-color: transparent; border:none;">
              <li>
                <ul class="menu" style="margin-top: -21px;">
                  @if ($totalNotificacaoAbertas != 0)
                    <span class="box"></span>
                  @endif
                   <!-- Inicio Notificação -->
                   @foreach($notificacoes as $notificacao)
                   <ul class="list-group" style="font-size: 14px;"><br>
                   <li class="list-group-item" style="background-color: #965A2C;color: white;font-size: 16px; border-top-right-radius:10px; border-top-left-radius:10px;"><i class="fa fa-exclamation"></i><strong>&nbsp;&nbsp;Modúlo:</strong>  Correspondente </li>
                   <li class="list-group-item" style="border-bottom-right-radius:10px;border-bottom-left-radius:10px; background-color:#E9E8E8;">
                    <small><i class="fa fa-clock-o"></i><strong>&nbsp;&nbsp;Data:</strong> {{ date('d/m/Y', strtotime($notificacao->data)) }}</small> 
                    <br>
                    <small><i class="fa fa-user"></i><strong>&nbsp;&nbsp;Enviado de:</strong> {{$notificacao->name}} </small> 
                    <br>
                    <small><i class="fa fa-comment-o"></i><strong>&nbsp;&nbsp;Tipo:</strong>  {{$notificacao->obs}}</small>
                    <br>
                    <small><i class="fa fa-book"></i><strong>&nbsp;&nbsp;Nª Solicitação:</strong>  {{$notificacao->id_ref}}</small>
                     </li>
                     </ul>
                    @endforeach
                  </li>
                  <!-- Fim Notificação -->
                </ul>
                </li>
              @if ($totalNotificacaoAbertas == 0)
              <center> <li class="footer"><a style="text-decoration:none; color: black;font-size: 15px; margin-top:20px; background-color:#E9E8E8;" href="#">Você não possui notificações.</a></li></center>
              @else
             <center> <li class="footer"><a style="text-decoration:none; color: black;font-size: 15px; margin-left:32px;" href="{{ route('Painel.Notificacao.index') }}">Visualizar todas as notificações</a></li>
             @endif
            </ul>
          </li>
</li>


<li  class="wiloke-menu-item dropdown messages-menu">
    <a data-toggle="modal" data-target="#modalMeuPerfil">
      <i class="wiloke-menu-item-icon fa fa-user"></i> Meu Perfil
    </a>
    </li>

    <div class="modal fade" id="modalMeuPerfil" tabindex="-1" role="dialog" data-backdrop="false" aria-labelledby="modalMeuPerfil" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: #965A2C; color:white; height: 50px;">
            <h5 class="modal-title" id="exampleModalLongTitle">Meu Perfil</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px; color:white;">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" style="background-color: #FFFFF0;">
            <form role="form" action="{{ route('Painel.Correspondente.update', auth()->user()->id )}}" method="post"  enctype="multipart/form-data">
           {{ csrf_field() }}
           <div class="panel-body">
           <div class="card card-default">
           <div class="card-body">    

            <div class="row">         
            <div class="col-md-12">
                <div class="form-group">
                   <div class="form-group">
               <label class="control-label" style="font-size: 12px;color: black;">Nome:</label>
               <input name="name" id="name" type="text" value="{{ auth()->user()->name }}"  maxlength="255" class="form-control" placeholder="Nome completo" data-toggle="tooltip" data-placement="top" title="Preencha seu nome completo." required="required">
                   </div>
                </div>
              </div>
              </div>  
            
              <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                   <div class="form-group">
                   <label class="control-label" style="font-size: 12px;color: black;">Email:</label>
               <input name="email" id="email" type="email" value="{{ auth()->user()->email }}" class="form-control" placeholder="Digite seu email" data-toggle="tooltip" data-placement="top" title="Preencha seu email." required="required">
                   </div>
                </div>
              </div> 
           </div>

           <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                   <div class="form-group">
                   <label class="control-label" style="font-size: 12px;color: black;">CPF:</label>
               <input name="cpf" id="cpf" type="cpf" readonly value="{{ auth()->user()->cpf }}"  class="form-control" placeholder="Ex.: 000.000.000-00" data-toggle="tooltip" data-placement="top" title="Preencha seu CPF." required="required">
                   </div>
                </div>
              </div> 
           </div>

           <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                   <div class="form-group">
                   <label class="control-label" style="font-size: 12px;color: black;">Senha:</label>
                   <input name="password" id="password" type="password"  maxlength="255" class="form-control" placeholder="Digite sua senha" data-toggle="tooltip" data-placement="top" title="Preencha sua senha." required="required">
                   </div>
                </div>
              </div> 
           </div>

           <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                   <div class="form-group">
                   <label class="control-label" style="font-size: 12px;color: black;">Confirmar senha:</label>
               <input name="password_confirmation" id="password_confirmation" type="password" class="form-control" placeholder="Confirme sua senha" data-toggle="tooltip" data-placement="top" title="Confirmação de senha." required="required">
                   </div>
                </div>
              </div> 
           </div>

           <div class="row">
                <div class="col-md-12">
                <div class="form-group">
                   <div class="form-group">
                   <label class="control-label" style="font-size: 12px;color: black;">Anexar foto perfíl:</label>
                    <input  id="image" name="image" type="file" class="form-control" accept=".jpg,.png,.jpeg">
                </div>
                </div>
              </div>      
              </div>

       
          </div>
        </div>
        </div>
      </div>
      <div class="modal-footer" style="height: 50px;">
        <div style="margin-top: -10px;">
          <button class="btn btn-primary nextBtn pull-right fa fa-check"  id="btn" name="btn" type="submit" style="background-color:#4B4B4B;border-color:#4B4B4B;">&nbsp;&nbsp;Salvar alterações</button>
          <button class="btn btn-primary nextBtn pull-right fa fa-close"  data-dismiss="modal" style="background-color:#4B4B4B;border-color:#4B4B4B;">&nbsp;&nbsp;Fechar</button>
        </div>
      </div>
    </div>
  </div>
</div>
</li>
</form>



<!-- Fim Menu Notificações -->        



          <!--
          <li class="dropdown tasks-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger">9</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">Você possui 3 tarefas</li>
              <li>
                <ul class="menu">
                  <li>
                    <a href="#">
                      <h3>
                        Design some buttons
                        <small class="pull-right">20%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar"
                             aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">20% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <h3>
                        Some task I need to do
                        <small class="pull-right">60%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-red" style="width: 60%" role="progressbar"
                             aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">60% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <h3>
                        Make beautiful transitions
                        <small class="pull-right">80%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-yellow" style="width: 80%" role="progressbar"
                             aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">80% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer">
                <a href="#">Visualizar Todas Tarefas</a>
              </li>
            </ul>
          </li>          
          <!-- Usuario  -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{ asset('/public/AdminLTE/img/Avatar/img.png') }}" class="user-image" alt="User Image">
              <span class="hidden-xs">{{ auth()->user()->name }}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-header">
                <img src="{{ asset('/public/AdminLTE/img/Avatar/img.png') }}" class="img-circle" alt="User Image">
                <p>
                  {{ auth()->user()->name }}
                  <small>Tipo: Correspondente</small>
                </p>
              </li>
              <li class="user-footer">
               <div class="pull-left">
                  <a href="{{ route('Home.Principal.Show') }}" class="btn btn-default btn-flat">Voltar para a home</a>
                  </div> 
                <div class="pull-right">
                  <a href="{{ route('logout') }}"  class="btn btn-default btn-flat">Sair</a>
                </div>
              </li>
            </ul>
          </li>
            </ul>
        </div>
    </nav>
</header>