@extends('Painel.TI.Layouts.index')

@section('content')


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
<script src="{{ asset('/public/AdminLTE/dist/js/valida_cpf_cnpj.js') }}"></script>
<script src="{{ asset('/public/AdminLTE/dist/js/exemplo_2.js') }}"></script>


    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Criação novo úsuario
                <small>Informe os dados abaixo para cadastro de novo úsuario</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Cadastro úsuario</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
            <div class="col-xs-12">
            
<div class="content-din">
    @if( isset($errors) && count($errors) > 0 )
    <div class="alert alert-warning">
        @foreach($errors->all() as $error)
            <p>{{$error}}</p>
        @endforeach
    </div>
    @endif
    @if( isset($user) )
        {!! Form::model($user, ['route' => ['usuarios.update', $user->id], 'class' => 'form form-search form-ds', 'files' => true, 'method' => 'PUT']) !!}
    @else
        {!! Form::open(['route' => 'usuarios.store', 'class' => 'form form-search form-ds', 'files' => true]) !!}
    @endif
    <div class="panel-body">
        <div class="form-group">
        <label>Nome Completo:</label>
            {!! Form::text('name', null, ['placeholder' => 'Nome:', 'class' => 'form-control']) !!}
        </div>
        <div class="form-group">
        <label>Email:</label>
            {!! Form::email('email', null, ['placeholder' => 'Email:', 'class' => 'form-control']) !!}
        </div>
        <div class="form-group">
        <label>Digite a senha:</label>
            {!! Form::password('password', ['placeholder' => 'Senha:', 'class' => 'form-control']) !!}
        </div>
        <div class="form-group">
        <label>Confirmação de senha:</label>
            {!! Form::password('password_confirmation', ['placeholder' => 'Confirmar Senha:', 'class' => 'form-control']) !!}
        </div>
       <div class="form-group">
       <label>Informe o CPF/CNPJ do novo úsuario:</label>
       <input name="cpf" id="cpf" type="text" required="required" class="form-control cpf" placeholder="Informe o CPF/CNPJ." data-toggle="tooltip" data-placement="top" title="Informe o CPF/CNPJ."/>
        </div>
        <div class="form-group">
            {!! Form::file('image', ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
        <label>Selecione o nível de úsuario:</label>
             <select class="form-control selectpicker" required="" style="width: 100%;" id="profile_id" name="profile_id"  data-toggle="tooltip" data-placement="top" title="Selecione o nível de úsuario.">
                <option selected="selected" value=""></option>
                @foreach($profiles as $data)   
                <option  value="{{$data->id}}">{{$data->name}}</option>
                @endforeach
             </select>
       </div>

       <div class="form-group">
        <label>Deseja mandar manual e vídeo(Caso tênha)?</label>
             <select class="form-control selectpicker" required="" style="width: 100%;" id="escolha" name="escolha"  data-toggle="tooltip" data-placement="top" title="Selecione se deseja enviar manual e vídeo para o novo úsuario.">
                <option selected="selected" value=""></option>
                <option value="SIM">SIM</option>
                <option value="NÃO">NÃO</option>
             </select>
       </div>

        <div class="form-group">
            {!! Form::submit('Salvar', ['class' => 'btn btn-success btn-success']) !!}
        </div>


    {!! Form::close() !!}
</div>
</div><!--Content DinÃ¢mico-->
</div>
</div>
</div>

<script language="javascript">   
    $(document).ready(function($){
  $("input[name*='cpf_cnpj']").inputmask({
  mask: ['999.999.999-99', '99.999.999/9999-99']
  });

    });
</script>

@endsection

