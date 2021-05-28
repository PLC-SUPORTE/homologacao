<header class="main-header">
  <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button" style="background-color:#965A2C; color:white">
    <span class="sr-only">Toggle navigation</span>
  </a>
    <a href="{{ route('Home.Principal.Show') }}" class="logo" style="background-color:#965A2C;">
        <span class="logo-lg">{{ env('APP_NAME') }}</span>
    </a>
    <nav class="navbar navbar-static-top" style="background-color:#965A2C;">
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

            <li id="menu-item-2015" class="wiloke-menu-item dropdown messages-menu">
            <ul class="dropdown-menu" id="dropDownMenuNotificacao" style="width: 300px; background-color: transparent; border:none;">
              <li>
                <ul class="menu" style="margin-top: -35px;">
                   <!-- Inicio Notificação -->

                  </li>
                  <!-- Fim Notificação -->
                </ul>
                </li>
            </ul>
          </li>
</li>


          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{ asset('public/AdminLTE/img/Avatar/img.png') }}" class="user-image" alt="User Image">
              <span class="hidden-xs">{{ auth()->user()->name }}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-header">
              <img src="{{ asset('public/AdminLTE/img/Avatar/img.png') }}" class="img-circle" alt="User Image">
                <p>
                  {{ auth()->user()->name }}
                </p>
              </li>
              <li class="user-footer">
                <div class="pull-right">
                  <a href="{{ route('logout') }}"  class="btn btn-default btn-flat">Sair</a>
                </div>
              </li>
            </ul>
          </li>
            </ul>
        </div>
    </nav>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false" style=".modal-backdrop {z-index: -1;}"">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header" style="background-color: #965A2C;color:white">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                    <h4 class="modal-title" id="myModalLabel">Meu Perfíl</h4>
                    </div>
                <div class="modal-body">
                   <!-- <img src="https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcRbezqZpEuwGSvitKy3wrwnth5kysKdRqBW54cAszm_wiutku3R" name="aboutme" width="140" height="140" border="0" class="img-circle"></a> -->
                   <!-- <h3 class="media-heading">{{ auth()->user()->name }}</h3>
                    <span><strong>Profíle: </strong></span>
                       Loop Permissões 
                        <span class="label label-info">Correspondente</span>
                        Loop Permissões 
                    </center> -->
            <form role="form" action="" method="post"  enctype="multipart/form-data">
           {{ csrf_field() }}
           <div class="panel-body">
           <div class="card card-default">
           <div class="card-body">    

            <div class="row">         
            <div class="col-md-12">
                <div class="form-group">
                   <div class="form-group">
               <label class="control-label">Nome:</label>
               <input name="name" id="name" type="text" value="{{ auth()->user()->name }}"  maxlength="255" class="form-control" placeholder="Nome completo" data-toggle="tooltip" data-placement="top" title="Preencha seu nome completo." required="required">
                   </div>
                </div>
              </div>
              </div>  
            
              <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                   <div class="form-group">
              <label class="control-label">Email:</label>
               <input name="email" id="email" type="email" value="{{ auth()->user()->email }}" class="form-control" placeholder="Digite seu email" data-toggle="tooltip" data-placement="top" title="Preencha seu email." required="required">
                   </div>
                </div>
              </div> 
           </div>

           <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                   <div class="form-group">
              <label class="control-label">CPF:</label>
               <input name="cpf" id="cpf" type="cpf" readonly value="{{ auth()->user()->cpf }}"  class="form-control" placeholder="Ex.: 000.000.000-00" data-toggle="tooltip" data-placement="top" title="Preencha seu CPF." required="required">
                   </div>
                </div>
              </div> 
           </div>

           <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                   <div class="form-group">
              <label class="control-label">Senha:</label>
               <input name="password" id="password" type="password"  class="form-control" placeholder="Digite sua senha" data-toggle="tooltip" data-placement="top" title="Preencha sua senha." required="required">
                   </div>
                </div>
              </div> 
           </div>

           <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                   <div class="form-group">
              <label class="control-label">Confirmar senha:</label>
               <input name="password_confirmation" id="password_confirmation" type="password" class="form-control" placeholder="Confirme sua senha" data-toggle="tooltip" data-placement="top" title="Confirmação de senha." required="required">
                   </div>
                </div>
              </div> 
           </div>

           <div class="row">
                <div class="col-md-12">
                <div class="form-group">
                   <div class="form-group">
                    <label class="control-label" name="labelfile"  id="labelfile">Anexar foto perfíl:</label>
                    <input  id="image" name="image" type="file" class="form-control" accept=".jpg,.png,.jpeg" required="required">
                </div>
                </div>
              </div>      
              </div>

          <button class="btn btn-primary nextBtn pull-right fa fa-check"  id="btn" name="btn" type="submit" style="background-color:#4B4B4B;border-color:#4B4B4B;">&nbsp;&nbsp;Salvar alterações</button>
          

          </div>
            </div>
        </div>
    </form>
                </div>
            </div>
        </div>


</header>
