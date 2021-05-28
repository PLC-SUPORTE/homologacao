<html>
	<head>
		<title>Cadastro candidato | Portal PL&C</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

		<!-- Include jQuery -->
		<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

		<!-- Include Date Range Picker -->
		<style>
			body{
				background-position-x: center;
				background-position-y: center;
				background-size: cover;
				background-repeat-x: no-repeat;
				background-repeat-y: no-repeat;
				background-attachment: fixed;
				background-origin: initial;
				background-clip: initial;
				background-color: initial;
			}
			.top-buffer-1 { margin-top:20px; }
			.top-buffer { margin-top:2px; }
			fieldset.scheduler-border {
				border: 1px groove #ddd !important;
				padding: 0 1.4em 1.4em 1.4em !important;
				margin: 0 0 1.5em 0 !important;
				-webkit-box-shadow:  0px 0px 0px 0px #000;
						box-shadow:  0px 0px 0px 0px #000;
			}

				legend.scheduler-border {
					font-size: 1.2em !important;
					font-weight: bold !important;
					text-align: left !important;
					width:auto;
					padding:0 1px;
					border-bottom:none;
				}
			.login-wrapper {
				width: 100%;
				height: 100%;
				position: absolute;
				display: table;
				z-index: 2;
			}
			.note
			{
				text-align: center;
				height: 80px;
				background: gray;
				color: #fff;
				font-weight: bold;
				line-height: 80px;
			}
			.note p{ font-size:30px; }
			.form-content
			{
				padding: 5%;
				border: 1px solid #ced4da;
				margin-bottom: 2%;
			}
			.form-control{
				border-radius:1.5rem;
			}
			.bk{
				background-color: white;
			}
			@media print {
				body * {
					visibility: visible;
				  }
				  #section-to-print, #section-to-print * {
					visibility: hidden;
				  }
				  #section-to-print {
					position: absolute;
					left: 0;
					top: 0;
				  }
			   .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
					float: left;
			   }
			   .col-sm-12 {
					width: 100%;
			   }
			   .col-sm-11 {
					width: 91.66666667%;
			   }
			   .col-sm-10 {
					width: 83.33333333%;
			   }
			   .col-sm-9 {
					width: 75%;
			   }
			   .col-sm-8 {
					width: 66.66666667%;
			   }
			   .col-sm-7 {
					width: 58.33333333%;
			   }
			   .col-sm-6 {
					width: 50%;
			   }
			   .col-sm-5 {
					width: 41.66666667%;
			   }
			   .col-sm-4 {
					width: 33.33333333%;
			   }
			   .col-sm-3 {
					width: 25%;
			   }
			   .col-sm-2 {
					width: 16.66666667%;
			   }
			   .col-sm-1 {
					width: 8.33333333%;
			   }
			}
		</style>


	</head>
	<body style="background-image: url(../../../././../public/imgs/home.jpg);">
		<div class="container register-form top-buffer-1">
		  <div class="form">
			  <div class="note">
				<p>Preenchimento de dados cadastrais</p>
			  </div>

			  <form id="form" role="form" onsubmit="btnsubmit.disabled = true; return true;" action="{{ route('Painel.Contratacao.Candidato.store') }}" method="POST" role="create" enctype="multipart/form-data"> 
                            {{ csrf_field() }}

            <input type="hidden" name="id" id="id" value="{{$datas->id}}">
			<input name="salario" type="hidden" value="<?php echo number_format($datas->salario,2,",",".") ?>">
			<input type="hidden" name="cargo" value="{{$datas->CargoCodigo}}">

                 <div class="form-content bk">
        
					<fieldset class="scheduler-border">
					<div class="section-to-print" id="section-to-print">

					<legend class="scheduler-border">DADOS PESSOAIS</legend>
						<div class="row">

							<div class="col-SM-3">
							  <label for="nomecompleto" style="font-size: 11px;">Nome completo:</label>
							  <input style="font-size: 10px;" readonly required type="name" class="form-control" id="nomecompleto" value="{{$datas->candidatonome}}" name="nomecompleto">
							</div>

							<div class="col-SM-3">
							  <label for="nomecompleto" style="font-size: 11px;">E-mail plc:</label>
							  <input style="font-size: 10px;" type="email" required class="form-control" id="email" value="{{$emailplc}}" name="email">
							</div>

							<div class="col-sm-2">
							  <label for="datanascimento" style="font-size: 11px;">Data de Nascimento:</label>
							  <input style="font-size: 10px;" type="date" max="{{$datahoje}}" required class="form-control" id="datanascimento" name="datanascimento">
							</div>

                            <div class="col-sm-3">
							  <label for="filiacaomae" style="font-size: 11px;">Filiação:</label>
							  <input style="font-size: 10px;" type="text" required class="form-control" id="filiacaomae" placeholder="Mãe" name="filiacaomae">
							</div>

						    <div class="col-sm-3">
							  <label for="filiacaopai" style="font-size: 11px;">Filiação:</label>
							  <input style="font-size: 10px;" type="text" required class="form-control" id="filiacaopai" placeholder="Pai" name="filiacaopai">
					        </div>


              	        <div class="form-group col-sm-3">
								<label for="cep" style="font-size: 11px;">CEP:</label>
								<input style="font-size: 10px;" type="text" required class="form-control" id="cep" name="cep" placeholder="CEP">
						</div>

                        <div class="form-group col-sm-3">
								<label for="endereco" style="font-size: 11px;">Endereço:</label>
								<input style="font-size: 10px;" type="text" required class="form-control" id="endereco" name="endereco" placeholder="">
						</div>


                         <div class="form-group col-sm-2">
								<label for="bairro" style="font-size: 11px;">Bairro:</label>
								<input style="font-size: 10px;" type="text" required class="form-control" id="bairro" name="bairro" placeholder="">
						</div>

                        <div class="form-group col-sm-3">
								<label for="cidade" style="font-size: 11px;">Cidade:</label>
								<input style="font-size: 10px;" type="text" required class="form-control" id="cidade" name="cidade" placeholder="">
						 </div>

                        <div class="form-group col-sm-2">
								  <label for="complemento" style="font-size: 11px;">Complemento</label>
								  <input style="font-size: 10px;" type="text"  class="form-control" name="complemento" id="complemento" placeholder="Apto...">
						</div>

                        <div class="form-group col-sm-1">
								  <label for="uf" style="font-size: 11px;">UF:</label>
								  <input style="font-size: 10px;" type="text" required class="form-control" name="uf" id="uf">
						</div>


                         <div class="form-group col-sm-3">
								<label for="estadocivil" style="font-size: 11px;">Estado Civil:</label>
								<input style="font-size: 10px;" type="text" required class="form-control" id="estadocivil" name="estadocivil">
						</div>

						<div class="form-group col-sm-2">
								<label for="filhos" style="font-size: 11px;">Filhos:</label>
								<input style="font-size: 10px;" type="number" value="0" required class="form-control" id="filhos" name="filhos">
						</div>

						<div class="col-sm-3">
							  <label for="nomeconjuge" style="font-size: 11px;">Nome do Cônjuge:</label>
							  <input style="font-size: 10px;" type="text" class="form-control" id="nomeconjuge"  name="nomeconjuge">
					    </div>

						<div class="col-sm-3">
							  <label for="sexo" style="font-size: 11px;">Sexo:</label>
							  <select style="font-size: 10px;" class="form-control" required id="sexo" name="sexo" style="height: 60%">
									<option value="Feminino">Feminino</option>
									<option value="Masculino">Masculino</option>
								  </select>
					    </div>

						</div>

					</div>
					</fieldset>
					<!-- Fim Dados Pessoais -->


					<!--Contatos Referencia --> 
					<fieldset class="scheduler-border">
						<legend class="scheduler-border">CONTATOS / GRUPO FAMILIAR (Mínimo 2 contatos)</legend>
						<div class="row">

						   <div class="col-SM-3">
							  <label for="contato_nome" style="font-size: 11px;">Nome:</label>
							  <input style="font-size: 10px;" type="name" required class="form-control" id="contato_nome"  name="contato_nome[]">
							</div>

							<div class="col-sm-2">
							  <label for="contato_datanasc" style="font-size: 11px;">Data Nasc.:</label>
							  <input style="font-size: 10px;" type="date" required class="form-control" id="contato_datanasc"  name="contato_datanasc[]" required>
							</div>

							<div class="col-sm-3">
							  <label for="contato_numero1" style="font-size: 11px;">Contato 1:</label>
							  <input style="font-size: 10px;" type="text"  class="form-control" id="contato_numero1" placeholder="(xx) xxxxx-xxxx" name="contato_numero1[]">
							</div>

							<div class="col-sm-3">
							  <label for="contato_numero2" style="font-size: 11px;">Contato 2:</label>
							  <input style="font-size: 10px;" type="text"  class="form-control" id="contato_numero2" placeholder="(xx) xxxxx-xxxx" name="contato_numero2[]">
							</div>
						</div>

						<div class="row">

						   <div class="col-SM-3">
							  <label for="contato_nome" style="font-size: 11px;">Nome:</label>
							  <input style="font-size: 10px;" type="name" required class="form-control" id="contato_nome"  name="contato_nome[]">
							</div>

							<div class="col-sm-2">
							  <label for="contato_datanasc" style="font-size: 11px;">Data Nasc.:</label>
							  <input style="font-size: 10px;" type="date" max="{{$datahoje}}" required class="form-control" id="contato_datanasc"  name="contato_datanasc[]" required>
							</div>

							<div class="col-sm-3">
							  <label for="contato_numero1" style="font-size: 11px;">Contato 1:</label>
							  <input style="font-size: 10px;" type="text"  class="form-control" id="contato_numero1" placeholder="(xx) xxxxx-xxxx" name="contato_numero1[]">
							</div>

							<div class="col-sm-3">
							  <label for="contato_numero2" style="font-size: 11px;">Contato 2:</label>
							  <input style="font-size: 10px;" type="text"  class="form-control" id="contato_numero2" placeholder="(xx) xxxxx-xxxx" name="contato_numero2[]">
							</div>
						</div>

						<div class="row">

						   <div class="col-SM-3">
							  <label for="contato_nome" style="font-size: 11px;">Nome:</label>
							  <input style="font-size: 10px;" type="name" class="form-control" id="contato_nome"  name="contato_nome[]">
							</div>

							<div class="col-sm-2">
							  <label for="contato_datanasc" style="font-size: 11px;">Data Nasc.:</label>
							  <input style="font-size: 10px;" type="date" max="{{$datahoje}}" class="form-control" id="contato_datanasc"  name="contato_datanasc[]">
							</div>

							<div class="col-sm-3">
							  <label for="contato_numero1" style="font-size: 11px;">Contato 1:</label>
							  <input style="font-size: 10px;" type="text" class="form-control" id="contato_numero1" placeholder="(xx) xxxxx-xxxx" name="contato_numero1[]">
							</div>

							<div class="col-sm-3">
							  <label for="contato_numero2" style="font-size: 11px;">Contato 2:</label>
							  <input style="font-size: 10px;" type="text" class="form-control" id="contato_numero2" placeholder="(xx) xxxxx-xxxx" name="contato_numero2[]">
							</div>
						</div>

					</fieldset>
					<!--Fim Contatos Referencia --> 
					
					<!--Dados Bancarios --> 
					<fieldset class="scheduler-border">
						<legend class="scheduler-border">DADOS BANCÁRIOS</legend>
						<div class="row">

						   <div class="col-SM-3">
							  <label for="banco" style="font-size: 11px;">Banco:</label>
							  <input style="font-size: 10px;" type="text" required class="form-control" id="banco"  name="banco">
							</div>

							<div class="col-sm-3">
							  <label for="agencia" style="font-size: 11px;">Agência:</label>
							  <input style="font-size: 10px;" type="text" required class="form-control" id="agencia"  name="agencia">
							</div>

							<div class="col-sm-3">
							  <label for="conta" style="font-size: 11px;">Conta:</label>
							  <input style="font-size: 10px;" type="text" required class="form-control" id="conta"  name="conta">
							</div>

							<div class="col-sm-3">
							  <label for="tipoconta" style="font-size: 11px;">Tipo:</label>
							  <input style="font-size: 10px;" type="text" required class="form-control" id="tipoconta" placeholder="Conta corrente" name="tipoconta">
							</div>
						</div>

					</fieldset>
					<!--Fim Dados Bancarios -->

                    <!--DOCUMENTOS PESSOAIS -->
					<fieldset class="scheduler-border">
						<legend class="scheduler-border">DOCUMENTOS PESSOAIS</legend>
					
						<div class="row">

							<div class="col-sm-2">
							  <label for="identidade" style="font-size: 11px;">Identidade:</label>
							  <input style="font-size: 10px;" type="text" required class="form-control" id="identidade"  name="identidade">
							</div>

							<div class="col-sm-2">
							  <label for="dataexpedicao" style="font-size: 11px;">Data Expedição:</label>
							  <input style="font-size: 10px;" type="date" max="{{$datahoje}}" required class="form-control" id="dataexpedicao" name="dataexpedicao">
							</div>

							<div class="col-sm-2">
							  <label for="cpf" style="font-size: 11px;">CPF:</label>
							  <input style="font-size: 10px;" type="text" required class="form-control" id="cpf" placeholder="xxx.xxx.xxx-xx" name="cpf_cnpj">
							</div>

							<div class="col-sm-2" >
							  <label for="numerooab" style="font-size: 11px;">N° OAB:</label>
							  <input style="font-size: 10px;" type="text" required class="form-control" id="numerooab" placeholder="xxx.xxxx" name="numerooab">
							</div>

							<div class="col-sm-2">
							  <label for="tituloeleitor" style="font-size: 11px;">Título de Eleitor:</label>
							  <input style="font-size: 10px;" type="text" required class="form-control" id="tituloeleitor" placeholder="" name="tituloeleitor">
							</div>

						</div>

						<div class="row top-buffer">

							<div class="col-sm-1">
							  <label for="zona" style="font-size: 11px;">Zona:</label>
							  <input style="font-size: 10px;" type="text" required class="form-control" id="zona" name="zona">
							</div>

							<div class="col-sm-1">
							  <label for="secao" style="font-size: 11px;">Seção:</label>
							  <input style="font-size: 10px;" type="text" required class="form-control" id="secao" name="secao">
							</div>

							<div class="col-sm-2">
							  <label for="ctps" style="font-size: 11px;">CTPS:</label>
							  <input style="font-size: 10px;" name="ctps" required class="form-control" id="ctps" type="text" />
							</div>

							<div class="col-sm-2">
							  <label for="serienf" style="font-size: 11px;">Série/UF:</label>
							  <input style="font-size: 10px;" name="serienf" required class="form-control" id="serienf" type="text" />
							</div>

							<div class="col-sm-2">
							  <label for="numeropis" style="font-size: 11px;">N° PIS:</label>
							  <input style="font-size: 10px;" name="numeropis" required class="form-control" id="numeropis" type="text" />
							</div>

							<div class="col-sm-4">
							  <label for="numeropis" style="font-size: 11px;">Certificado de Reservista (Homens):</label>
							  <input style="font-size: 10px;" name="certificadoreservista"  class="form-control" id="certificadoreservista" type="text" />
							</div>


						</div>
					</fieldset>
                    <!--FIM DOCUMENTOS PESSOAIS -->

                  <!-- Dados Faculdade Direito -->
					<fieldset class="scheduler-border">
						<legend class="scheduler-border">GRADUAÇÃO</legend>
						<div class="row">

                           <div class="col-sm-3">
							  <label for="faculdade_nome" style="font-size: 11px;">Faculdade:</label>
							  <input style="font-size: 10px;" type="name" required class="form-control" id="faculdade_nome" name="faculdade_nome">
							</div>

                            <div class="col-sm-3">
							  <label for="faculdade_curso" style="font-size: 11px;">Nome do Curso:</label>
							  <input style="font-size: 10px;" type="name" required class="form-control" id="faculdade_curso"  name="faculdade_curso">
							</div>

							<div class="col-sm-3">
							  <label for="faculdade_status" style="font-size: 11px;">Status:</label>
							  <select style="font-size: 10px;" class="form-control" required id="faculdade_status" name="faculdade_status" style="height: 60%">
									<option value="Em Andamento">Em Andamento</option>
									<option value="Suspenso">Suspenso</option>
									<option value="Concluído">Concluído</option>
								  </select>
							</div>

							</div>

							<div class="row">

							<div class="col-sm-6">
							  <label for="faculdade_trabalho" style="font-size: 11px;">TCU, Monografia, Dissertação ou Tese:</label>
							  <input style="font-size: 10px;" type="name" class="form-control" id="faculdade_trabalho"  name="faculdade_trabalho">
							</div>

							<div class="col-sm-2">
							  <label for="faculdade_datainicio" style="font-size: 11px;">Data de Início:</label>
							  <input style="font-size: 10px;" type="date" max="{{$datahoje}}" required class="form-control" id="faculdade_datainicio"  name="faculdade_datainicio">
							</div>

							<div class="col-sm-2">
							  <label for="faculdade_datafim" style="font-size: 11px;">Data de Término:</label>
							  <input style="font-size: 10px;" type="date"  class="form-control" id="faculdade_datafim"  name="faculdade_datafim">
							</div>


						</div>
					</fieldset>
					<!-- Fim Faculdade Direito -->


                  @if($datas->CargoCodigo == 15 || $datas->CargoCodigo == 13)
				  <!--Beneficios --> 
				  <fieldset class="scheduler-border">
						<legend class="scheduler-border">BENEFICIOS (Apenas para Celetistas e Estagiários)</legend>
						<div class="row">

                           <div class="col-sm-3">
							  <label for="valetransporte_linha" style="font-size: 11px;">Vale transporte linha utilizada</label>
							  <input style="font-size: 10px;" type="name" required class="form-control" id="valetransporte_linha" name="valetransporte_linha">
							</div>

                            <div class="col-sm-3">
							  <label for="valetransporte_valorida" style="font-size: 11px;">Vale transporte valor ida:</label>
							  <input style="font-size: 10px;" type="name" required class="form-control" value="00,00" id="valetransporte_valorida"  name="valetransporte_valorida">
							</div>

							<div class="col-sm-3">
							  <label for="valetransporte_valorvolta" style="font-size: 11px;">Vale transporte valor volta:</label>
							  <input style="font-size: 10px;" type="name" required class="form-control" value="00,00" id="valetransporte_valorvolta"  name="valetransporte_valorvolta">
							</div>

							<div class="col-sm-3">
							  <label for="ticketrefeicao_uso" style="font-size: 11px;">Deseja solicitar o ticket refeição:</label>
							  <select style="font-size: 10px;" class="form-control" required id="ticketrefeicao_uso" name="ticketrefeicao_uso" style="height: 80%">
							        <option value="Sim">Sim</option>
									<option value="Não">Não</option>
								  </select>
							</div>

							</div>

							<div class="row">

							<div class="col-sm-3">
							  <label for="planosaude_uso" style="font-size: 11px;">Deseja solicitar o plano de saúde:</label>
							  <select style="font-size: 10px;" class="form-control" required id="planosaude_uso" name="planosaude_uso" style="height: 80%">
									<option value="Sim">Sim</option>
									<option value="Não">Não</option>
								  </select>
							</div>

						</div>
					</fieldset>
					<!--Fim Beneficios --> 
					@endif


					 <!-- LÍNGUA ESTRANGEIRA -->
					<fieldset class="scheduler-border">
						<legend class="scheduler-border">LÍNGUA ESTRANGEIRA</legend>
						<div class="row">

                           <div class="col-sm-3">
							  <label for="estrangeira_nome" style="font-size: 11px;">Instituição de Ensino:</label>
							  <input style="font-size: 10px;" type="name" class="form-control" id="estrangeira_nome" name="estrangeira_nome">
							</div>

                            <div class="col-sm-3">
							  <label for="estrangeira_curso" style="font-size: 11px;">Nome do Curso:</label>
							  <input style="font-size: 10px;" type="name" class="form-control" id="estrangeira_curso"  name="estrangeira_curso">
							</div>

							<div class="col-sm-3">
							  <label for="estrangeira_idioma" style="font-size: 11px;">Idioma:</label>
							  <input style="font-size: 10px;" type="name" class="form-control" id="estrangeira_idioma"  name="estrangeira_idioma">
							</div>

							<div class="col-sm-3">
							<label for="estrangeira_nivel" style="font-size: 11px;">Nível:</label>
							  <select style="font-size: 10px;" class="form-control" id="estrangeira_nivel" name="estrangeira_nivel" style="height: 60%">
									<option>Básico</option>
									<option>Intermediário</option>
									<option>Avançado</option>
									<option>Fluente</option>
								  </select>
							</div>

							</div>

							<div class="row">

							<div class="col-sm-2">
							  <label for="faculdade_datainicio" style="font-size: 11px;">Data de Início:</label>
							  <input style="font-size: 10px;" type="date" max="{{$datahoje}}" class="form-control" id="faculdade_datainicio"  name="faculdade_datainicio">
							</div>

							<div class="col-sm-2">
							  <label for="faculdade_datafim" style="font-size: 11px;">Data de Término:</label>
							  <input style="font-size: 10px;" type="date" class="form-control" id="faculdade_datafim"  name="faculdade_datafim">
							</div>


						</div>
					</fieldset>
					<!-- Fim Lingua Estrangeira -->

					<!-- CURSOS COMPLEMENTARES 1 -->
					<fieldset class="scheduler-border">
						<legend class="scheduler-border">CURSOS COMPLEMENTARES 1</legend>
						<div class="row">

						<div class="col-sm-3">
							  <label for="cursos_nome" style="font-size: 11px;">Instituição de Ensino:</label>
							  <input style="font-size: 10px;" type="name" class="form-control" id="cursos_nome" name="cursos_nome[]">
							</div>

                            <div class="col-sm-3">
							  <label for="cursos_curso" style="font-size: 11px;">Nome do Curso:</label>
							  <input style="font-size: 10px;" type="name" class="form-control" id="cursos_curso"  name="cursos_curso[]">
							</div>

							<div class="col-sm-3">
							  <label for="cursos_status" style="font-size: 11px;">Status:</label>
							  <select style="font-size: 10px;" class="form-control" id="cursos_status" name="cursos_status[]" style="height: 60%">
									<option>Em Andamento</option>
									<option>Suspenso</option>
									<option>Concluído</option>
								  </select>
							</div>

							<div class="col-sm-2">
							  <label for="curso_datainicio" style="font-size: 11px;">Data de Início:</label>
							  <input style="font-size: 10px;" type="date" max="{{$datahoje}}" class="form-control" id="curso_datainicio"  name="curso_datainicio[]">
							</div>

							<div class="col-sm-2">
							  <label for="curso_datafim" style="font-size: 11px;">Data de Término:</label>
							  <input style="font-size: 10px;" type="date" class="form-control" id="curso_datafim"  name="curso_datafim[]">
							</div>


						</div>
					</fieldset>
					<!-- CURSOS COMPLEMENTARES -->

					<!-- CURSOS COMPLEMENTARES 2 -->
					<fieldset class="scheduler-border">
						<legend class="scheduler-border">CURSOS COMPLEMENTARES 2</legend>
						<div class="row">

						<div class="col-sm-3">
							  <label for="cursos_nome" style="font-size: 11px;">Instituição de Ensino:</label>
							  <input style="font-size: 10px;" type="name" class="form-control" id="cursos_nome" name="cursos_nome">
							</div>

                            <div class="col-sm-3">
							  <label for="cursos_curso" style="font-size: 11px;">Nome do Curso:</label>
							  <input style="font-size: 10px;" type="name" class="form-control" id="cursos_curso"  name="cursos_curso">
							</div>

							<div class="col-sm-3">
							  <label for="cursos_status" style="font-size: 11px;">Status:</label>
							  <select style="font-size: 10px;" class="form-control" id="cursos_status" name="cursos_status" style="height: 60%">
									<option>Em Andamento</option>
									<option>Suspenso</option>
									<option>Concluído</option>
								  </select>
							</div>

							<div class="col-sm-2">
							  <label for="curso_datainicio" style="font-size: 11px;">Data de Início:</label>
							  <input style="font-size: 10px;" type="date" max="{{$datahoje}}" class="form-control" id="curso_datainicio"  name="curso_datainicio">
							</div>

							<div class="col-sm-2">
							  <label for="curso_datafim" style="font-size: 11px;">Data de Término:</label>
							  <input style="font-size: 10px;" type="date" class="form-control" id="curso_datafim"  name="curso_datafim">
							</div>


						</div>
					</fieldset>
					<!-- CURSOS COMPLEMENTARES 2 -->

					<!-- CURSOS COMPLEMENTARES 3 -->
					<fieldset class="scheduler-border">
						<legend class="scheduler-border">CURSOS COMPLEMENTARES 3</legend>
						<div class="row">

						<div class="col-sm-3">
							  <label for="cursos_nome" style="font-size: 11px;">Instituição de Ensino:</label>
							  <input style="font-size: 10px;" type="name" class="form-control" id="cursos_nome" name="cursos_nome">
							</div>

                            <div class="col-sm-3">
							  <label for="cursos_curso" style="font-size: 11px;">Nome do Curso:</label>
							  <input style="font-size: 10px;" type="name" class="form-control" id="cursos_curso"  name="cursos_curso">
							</div>

							<div class="col-sm-3">
							  <label for="cursos_status" style="font-size: 11px;">Status:</label>
							  <select style="font-size: 10px;" class="form-control" id="cursos_status" name="cursos_status" style="height: 60%">
									<option>Em Andamento</option>
									<option>Suspenso</option>
									<option>Concluído</option>
								  </select>
							</div>

							<div class="col-sm-2">
							  <label for="curso_datainicio" style="font-size: 11px;">Data de Início:</label>
							  <input style="font-size: 10px;" type="date" max="{{$datahoje}}" class="form-control" id="curso_datainicio"  name="curso_datainicio">
							</div>

							<div class="col-sm-2">
							  <label for="curso_datafim" style="font-size: 11px;">Data de Término:</label>
							  <input style="font-size: 10px;" type="date" class="form-control" id="curso_datafim"  name="curso_datafim">
							</div>


						</div>
					</fieldset>
					<!-- CURSOS COMPLEMENTARES 3-->

					<!-- CURSOS COMPLEMENTARES 4 -->
					<fieldset class="scheduler-border">
						<legend class="scheduler-border">CURSOS COMPLEMENTARES 4</legend>
						<div class="row">

						<div class="col-sm-3">
							  <label for="cursos_nome" style="font-size: 11px;">Instituição de Ensino:</label>
							  <input style="font-size: 10px;" type="name" class="form-control" id="cursos_nome" name="cursos_nome">
							</div>

                            <div class="col-sm-3">
							  <label for="cursos_curso" style="font-size: 11px;">Nome do Curso:</label>
							  <input style="font-size: 10px;" type="name" class="form-control" id="cursos_curso"  name="cursos_curso">
							</div>

							<div class="col-sm-3">
							  <label for="cursos_status" style="font-size: 11px;">Status:</label>
							  <select style="font-size: 10px;" class="form-control" id="cursos_status" name="cursos_status" style="height: 60%">
									<option>Em Andamento</option>
									<option>Suspenso</option>
									<option>Concluído</option>
								  </select>
							</div>

							<div class="col-sm-2">
							  <label for="curso_datainicio" style="font-size: 11px;">Data de Início:</label>
							  <input style="font-size: 10px;" type="date" max="{{$datahoje}}" class="form-control" id="curso_datainicio"  name="curso_datainicio">
							</div>

							<div class="col-sm-2">
							  <label for="curso_datafim" style="font-size: 11px;">Data de Término:</label>
							  <input style="font-size: 10px;" type="date" class="form-control" id="curso_datafim"  name="curso_datafim">
							</div>


						</div>
					</fieldset>
					<!-- CURSOS COMPLEMENTARES 4-->

					<!-- CURSOS COMPLEMENTARES 5 -->
					<fieldset class="scheduler-border">
						<legend class="scheduler-border">CURSOS COMPLEMENTARES 5</legend>
						<div class="row">

						<div class="col-sm-3">
							  <label for="cursos_nome" style="font-size: 11px;">Instituição de Ensino:</label>
							  <input style="font-size: 10px;" type="name" class="form-control" id="cursos_nome" name="cursos_nome">
							</div>

                            <div class="col-sm-3">
							  <label for="cursos_curso" style="font-size: 11px;">Nome do Curso:</label>
							  <input style="font-size: 10px;" type="name" class="form-control" id="cursos_curso"  name="cursos_curso">
							</div>

							<div class="col-sm-3">
							  <label for="cursos_status" style="font-size: 11px;">Status:</label>
							  <select style="font-size: 10px;" class="form-control" id="cursos_status" name="cursos_status" style="height: 60%">
									<option>Em Andamento</option>
									<option>Suspenso</option>
									<option>Concluído</option>
								  </select>
							</div>

							<div class="col-sm-2">
							  <label for="curso_datainicio" style="font-size: 11px;">Data de Início:</label>
							  <input style="font-size: 10px;" type="date" max="{{$datahoje}}" class="form-control" id="curso_datainicio"  name="curso_datainicio">
							</div>

							<div class="col-sm-2">
							  <label for="curso_datafim" style="font-size: 11px;">Data de Término:</label>
							  <input style="font-size: 10px;" type="date" class="form-control" id="curso_datafim"  name="curso_datafim">
							</div>


						</div>
					</fieldset>
					<!-- CURSOS COMPLEMENTARES 5-->

					<!-- CURSOS COMPLEMENTARES 1 -->
					<fieldset class="scheduler-border">
						<legend class="scheduler-border">ANEXOS</legend>
						<div class="row">

						   <div class="col-sm-4">
							  <label for="anexo_fichaassinada" style="font-size: 11px;">Ficha Anexa preenchida e assinada:</label>
							  <input style="font-size: 10px;" required type="file" class="form-control" id="anexo_fichaassinada" name="anexo_fichaassinada">
							</div>

                            <div class="col-sm-4">
							  <label for="anexo_cpf" style="font-size: 11px;">CPF (frente e verso):</label>
							  <input style="font-size: 10px;" required type="file" class="form-control" id="anexo_cpf"  name="anexo_cpf">
							</div>

							@if($datas->CargoCodigo != 15 && $datas->CargoCodigo != 13)
							<div class="col-sm-4">
							  <label for="anexo_oab" style="font-size: 11px;">OAB (frente e verso):</label>
							  <input style="font-size: 10px;" required type="file" class="form-control" id="anexo_oab"  name="anexo_oab">
							</div>

							<div class="col-sm-4">
							  <label for="anexo_certidaooab" style="font-size: 11px;">Certidão vigente de quitação junto a OAB:</label>
							  <input style="font-size: 10px;" required type="file" class="form-control" id="anexo_certidaooab"  name="anexo_certidaooab">
							</div>
							@endif

							<div class="col-sm-4">
							  <label for="anexo_certidaonascimento" style="font-size: 11px;">Cópia Certidão de nascimento/casamento(menos de 60 dias):</label>
							  <input style="font-size: 10px;" required type="file" class="form-control" id="anexo_certidao"  name="anexo_certidao">
							</div>

							@if($datas->CargoCodigo != 15 && $datas->CargoCodigo != 13)
							<div class="col-sm-4">
							  <label for="anexo_conclusaofaculdade" style="font-size: 11px;">Cópia autenticada do certificado de conclusão do curso de direito:</label>
							  <input style="font-size: 10px;" type="file" class="form-control" id="anexo_conclusaofaculdade"  name="anexo_conclusaofaculdade">
							</div>

							<div class="col-sm-4">
							  <label for="anexo_cursolato" style="font-size: 11px;">Cópia autenticada do Certificado de cursos de Especialização Lato e Stricto Sensu, se aplicável:</label>
							  <input style="font-size: 10px;" type="file" class="form-control" id="anexo_cursolato"  name="anexo_cursolato">
							</div>
							@else 
							<div class="col-sm-4">
							  <label for="anexo_conclusaoensinomedio" style="font-size: 11px;">Cópia autenticada do certificado de conclusão do ensino médio:</label>
							  <input style="font-size: 10px;" type="file" class="form-control" required id="anexo_conclusaoensinomedio"  name="anexo_conclusaoensinomedio">
							</div>
							@endif

							<div class="col-sm-4">
							  <label for="anexo_cursoidioma" style="font-size: 11px;">Cópia autenticada do certificado de curso que comprovem a pro eficiência em outros idiomas:</label>
							  <input style="font-size: 10px;" type="file" class="form-control" id="anexo_cursoidioma"  name="anexo_cursoidioma">
							</div>

							<div class="col-sm-4">
							  <label for="anexo_quitacaoeleitoral" style="font-size: 11px;">Certidão vigente de quitação eleitoral:</label>
							  <input style="font-size: 10px;" type="file" required class="form-control" id="anexo_quitacaoeleitoral"  name="anexo_quitacaoeleitoral">
							</div>

							<div class="col-sm-4">
							  <label for="anexo_criminal" style="font-size: 11px;">Certidão vigente de “Nada Consta” Criminal:</label>
							  <input style="font-size: 10px;" type="file" required class="form-control" id="anexo_criminal"  name="anexo_criminal">
							</div>

							<div class="col-sm-4">
							  <label for="anexo_cursoespecializacao" style="font-size: 11px;">Cópia do Certificado de cursos de Especialização, se aplicável:</label>
							  <input style="font-size: 10px;" type="file" class="form-control" id="anexo_cursoespecializacao"  name="anexo_cursoespecializacao">
							</div>

							<div class="col-sm-4">
							  <label for="anexo_comprovanteendereco" style="font-size: 11px;">Comprovante de endereço atualizado:</label>
							  <input style="font-size: 10px;" type="file" required class="form-control" id="anexo_comprovanteendereco"  name="anexo_comprovanteendereco">
							</div>

							<div class="col-sm-4">
							  <label for="anexo_dadosbancarios" style="font-size: 11px;">Dados Bancários:</label>
							  <input style="font-size: 10px;" type="file" required class="form-control" id="anexo_dadosbancarios"  name="anexo_dadosbancarios">
							</div>


						</div>
					</fieldset>
					<!-- CURSOS COMPLEMENTARES -->

                    <div style="margin-left: 700px;">
					<button style="font-size: 11px;" id="btnsubmit" type="submit" class="btn btn-success" value="submit">Enviar dados para conferência </button>
					<button style="font-size: 11px;" onclick="window.print();return false;" class="btn btn-primary">Imprimir </button>
			        </div>

				</form>
			</div>
		  </div>
		  </div>



	</body>

    <script src="{{ asset('/public/AdminLTE/dist/js/valida_cpf_cnpj.js') }}"></script>
    <script src="{{ asset('/public/AdminLTE/dist/js/exemplo_2.js') }}"></script>
    <script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
	
	<script language="javascript">   
         $(document).ready(function($){
         $("input[id*='cpf_cnpj']").inputmask({
         mask: ['999.999.999-99', '99.999.999/9999-99']
         });
         
         });
     </script>

<script>
  function abremodalconfirmando() {

    $('.modal').modal();
	$('#modalconfirmacao').modal('show');
         
  }    
</script>

<script>
  function nao() {

    window.location.reload(true);
         
  }    
</script>

<script>
  function sim() {

    document.getElementById("form").submit();
         
  }    
</script>


  <!-- Adicionando Javascript -->
  <script type="text/javascript" >
  
    function meu_callback(conteudo) {
        if (!("erro" in conteudo)) {
            //Atualiza os campos com os valores.
            document.getElementById('rua').value=(conteudo.logradouro);
            document.getElementById('bairro').value=(conteudo.bairro);
            document.getElementById('cidade').value=(conteudo.localidade);
            document.getElementById('uf').value=(conteudo.uf);
        } 
        else {
            //CEP não Encontrado.
            alert("CEP não encontrado.");
        }
    }
        
    function pesquisacep() {

      var valor = document.getElementById("cep").value

        //Nova variável "cep" somente com dígitos.
        var cep = valor;

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

                //Preenche os campos com "..." enquanto consulta webservice.
                document.getElementById('rua').value="...";
                document.getElementById('bairro').value="...";
                document.getElementById('cidade').value="...";
                document.getElementById('uf').value="...";

                //Cria um elemento javascript.
                var script = document.createElement('script');

                //Sincroniza com o callback.
                script.src = '//viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';

                //Insere script no documento e carrega o conteúdo.
                document.body.appendChild(script);

            } //end if.
            else {
                //cep é inválido.
                alert("Formato de CEP inválido.");
            }
    };

    </script>



</html>