<!DOCTYPE html>
<html lang="en">
<head>
    <title>PL&C Advogados - Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
        <link rel="stylesheet" href="{{ asset('/public/assets/auth/fonts/Linearicons-Free-v1.0.0/icon-font.min.css') }}">
        <link rel="stylesheet" href="{{ asset('/public/assets/auth/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('/public/assets/auth/fonts/Linearicons-Free-v1.0.0/icon-font.min.css') }}">
        <link rel="stylesheet" href="{{ asset('/public/assets/auth/assets/vendor/animate/animate.css') }}">
        <link rel="stylesheet" href="{{ asset('/public/assets/auth/vendor/css-hamburgers/hamburgers.min.css') }}">
        <link rel="stylesheet" href="{{ asset('/public/assets/auth/vendor/animsition/css/animsition.min.css') }}">
        <link rel="stylesheet" href="{{ asset('/public/assets/auth/css/util.css') }}">
		<link rel="stylesheet" href="{{ asset('/public/assets/auth/css/main.css') }}">
		<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

</head>
<body style="background-color: #666666;overflow:hidden;">
	
@include('flash::message')

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">

				<form class="login100-form validate-form" role="form" action="{{ route('login') }}" method="POST" role="login" >
					{{ csrf_field() }}
					
					@if($errors->any())
						@foreach($errors->all() as $error)
							@if ($error == "auth.failed")
							<div class="alert alert-danger" role="alert">
								Email ou senha inválidos.
							  </div>
							@endif
						@endforeach
					@endif
						
					<span class="login100-form-title p-b-43">
						LOGIN
					</span>
					
					<div class="wrap-input100 validate-input" data-validate = "Favor digitar um email válido">
                        <input class="input100" type="email" name="email" placeholder="E-mail" style="background-color: transparent" required>
					</div>

					<button type="button" id="show_password" title="Ver senha" name="show_password" class="fa fa-eye-slash" aria-hidden="true" style="color:white; padding-left:95%"></button>  
					<div class="wrap-input100 validate-input" data-validate="Campo senha é obrigatório">
						<input class="input100" type="password" name="password" id="password" placeholder="******" style="background-color: transparent;" required>
					</div>

					<script>
						jQuery(document).ready(function($) {
						
						$('#show_password').click(function(e) {
							e.preventDefault();
							if ( $('#password').attr('type') == 'password' ) {
							$('#password').attr('type', 'text');
							$('#show_password').attr('class', 'fa fa-eye');
							} else {
								$('#password').attr('type', 'password');
								$('#show_password').attr('class', 'fa fa-eye-slash');
							}
						});
						
						});
					</script>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							ACESSAR
						</button>
					</div>

					<div class="container-login100-form-btn" style="margin-top: 10px;">
					<p class="margin medium-small">
					<a style="color: white;" href="{{route('recuperarsenha')}}">Esqueci minha senha</a>
					</p>
					</div>
					
					<br>
					<br>

					<div class="login100-form-social flex-c-m" style="margin-top: -10px;">
						<a href="#" class="">
							<img src="{{url('/public/imgs/selos_portal.png')}}" alt="Smiley face" style="width: 100%;
							height:auto;">
						</a>
					</div>
					
						<div class="text-center p-t-46 p-b-20" style="margin-top: -20px;">
						<span class="txt2">
							Copyright 2020 | PL&C Advogados. Todos os Direitos Reservados.
						</span>
					</div>
				</form>	

				<div class="login100-more" style="background-image: url('./public/bg-01.jpg');">
				</div>
			</div>
		</div>
	</div>
	
	

	
	
 <script src="{{ asset('/public/assets/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
 <script src="{{ asset('/public/assets/vendor/animsition/js/animsition.min.js') }}"></script>
<script src="{{ asset('/public/assets/vendor/bootstrap/js/popper.js') }}"></script>
<script src="{{ asset('/public/assets/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('/public/assets/vendor/daterangepicker/moment.min.js') }}"></script>
<script src="{{ asset('/public/assets/js/main.js') }}"></script>
        

</body>
</html>