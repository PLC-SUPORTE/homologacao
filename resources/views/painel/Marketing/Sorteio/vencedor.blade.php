
<!DOCTYPE html>

<html class="loading" lang="en" data-textdirection="ltr">
  <!-- BEGIN: Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Realizar sorteio | Especialista</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/public/materialize/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/forgot.min.css') }}">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
    <meta name="_token" content="{{ csrf_token() }}"/>


<style>

::placeholder { 
  color: black;
  opacity: 1; 
}
* {
      box-sizing: border-box;
    }
    .wrapper {
      height: 50px;
      margin-top: calc(50vh - 150px);
      margin-left: calc(50vw - 600px);
      width: 180px;
    }
    .circle {
      border-radius: 50%;
      border: 3px #0a0a0a solid;
      float: left;
      height: 50px;
      margin: 0 5px;
      width: 50px;
    }
      .circle-1 {
        animation: move 2s ease-in-out infinite;
      }
      .circle-1a {
        animation: fade 2s ease-in-out infinite;
      }
      @keyframes fade {
        0% {opacity: 0;}
        100% {opacity: 1;}
      }
      .circle-2 {
        animation: move 1s ease-in-out infinite;
      }
      @keyframes move {
        0% {transform: translateX(0);}
        100% {transform: translateX(60px);}
      }
      .circle-1a {
        margin-left: -120px;
        opacity: 0;
      }
      .circle-3 {
        animation: circle-3 1s ease-in-out infinite;
        opacity: 1;
      }
      @keyframes circle-3 {
        0% {opacity: 1;}
        100% {opacity: 0;}
      }

    h1 {
      color: white;
      font-size: 12px;
      font-weight: 400;
      letter-spacing: 0.05em;
      margin: 40px auto;
      text-transform: uppercase;
    }

</style>

  </head>
  <!-- END: Head-->

  <body id="body" style="background-image: url(../../../../public/imgs/fundoespecialista.jpg);" class="vertical-layout page-header-light vertical-menu-collapsible vertical-menu-nav-dark preload-transitions 1-column forgot-bg   blank-page blank-page" data-open="click" data-menu="vertical-menu-nav-dark" data-col="1-column">
    <div class="row">



      <div class="col s12">
        <div class="container">

        <div id="forgot-password" class="row">

  {{-- <div class="col s12 m10 l8 z-depth-4 offset-m4 card-panel border-radius-6 forgot-card bg-opacity-8"> --}}

    <div id="loadingdiv" style="display:none">
      <div style="position: static; margin-top: 400px; margin-left: -35px;">
    <img style="width: 220px;" src="{{URL::asset('/public/imgs/gifpreto.gif')}}"/>
  </div>
     </div>

  <form id="form" class="login-form" role="form" method="POST" role="create">
    {{ csrf_field() }}      
    
    <input type="hidden" name="id" id="id" value="{{$id}}">

     <div id="corpodiv">

     <input type="text" style="font-size: 18px;color:green;display:none;font-weight: bolder;" name="resultado" id="resultado">
     
     <center>
     <div id="div_produto" style="margin-top: -30px;">
       <h1 style="font-size: 55px; font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif">Prêmio:</h1>
       <img style="width: 250px;" id="camimhoimg" name="camimhoimg" src=""/>
       <h1 style="font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; font-size: 30px; position: static; margin-left: 5%; margin-top: 40%;">
       <input id="premio" name="premio" style="font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; padding: 50px 10px; line-height: 28px; border-bottom:none; font-size: 17px;color:white;">
       </h1>
       </div> 
     </center>

     <center>
     <div id="numero_sorteado">
      <h1 style="color: white; margin-top: 80px; margin-left: -30px; font-size: 40px; font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif">
        O número sorteado foi:
        </h1>
     </div>
    </center>
  

       <div id="div_numero" class="col s4" style="margin-top: -70px; margin-left: 38%; position: static;">
          <b>
            <h1 style="color: #FF1212; margin-top: 80px; margin-left: 80px; font-size: 140px; font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif">
              <input readonly style="margin-left: -80px; color: #FF1212; font-size: 200px; padding: 50px 10px; line-height: 28px; border-bottom: none; font-family:'Franklin Gothic Medium',
               'Arial Narrow', Arial, sans-serif" type="text" id="vencedor_numero_2" name="vencedor_numero_2">
            </h1>

            <h1 style="color: #FF1212; margin-bottom: 80px; margin-left: 30px; margin-top: -200px; font-size: 140px; font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif">
              <input readonly style="margin-left: -100px; color: #FF1212; font-size: 200px; padding: 50px 10px; line-height: 28px; border-bottom: none; font-family:'Franklin Gothic Medium',
               'Arial Narrow', Arial, sans-serif" type="text" id="vencedor_numero_3" name="vencedor_numero_3">
            </h1>

          </b>
        </div> 

        <div id="div_nome" class="col s4" style="margin-top: 1px; margin-left: 38%; position: static;">
          <b>

            <h4 style="margin-left: -155px; margin-top: -50px; margin-bottom: 80px; font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif">
              <input readonly style="text-align: center; padding-left: 50%; width: 500px; margin-left: -45%; 
              font-size: 40px; border-bottom: none; font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif" 
              type="text" name="vencedor_nome_menor_12" id="vencedor_nome_menor_12">
            </h4>

            <h4 style="margin-left: -150px; margin-top: -140px; font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif">
              <input readonly style="text-align: center; padding-left: 50%; width: 500px; margin-left: -45%; 
              font-size: 40px; border-bottom: none; font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif" 
              type="text" name="vencedor_nome_menor_12_2" id="vencedor_nome_menor_12_2">
            </h4>

            <h4 style="margin-left: -145px; margin-top: -70px; font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif">
              <input readonly style="text-align: center; padding-left: 50%; width: 500px; margin-left: -45%; 
              font-size: 40px; border-bottom: none; font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif" 
              type="text" name="vencedor_nome_2" id="vencedor_nome_2">
            </h4>

            <h4 style=" margin-top: -60px; margin-left: -160px; margin-bottom: 70px; font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif">
              <input readonly style="text-align: center; padding-left: 50%; width: 500px; margin-left: -45%; 
              font-size: 40px; border-bottom: none; font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif" 
              type="text" name="vencedor_nome_3" id="vencedor_nome_3">
            </h4>
          </b>
        </div>

        <div id="div_parabens_3" class="col s4" style="margin-top: -50px; margin-left: 49.5%; position: static;">
          <img style="width: 550px; margin-top: -50px; margin-left: -75%;" src="{{URL::asset('/public/imgs/parabensnovo.gif')}}"/>  
        </div>
          
        <div id="div_parabens_2" class="col s4" style="margin-top: -50px; margin-left: 50%; position: static;">
          <img style="width: 550px; margin-top: -50px; margin-left: -70%;" src="{{URL::asset('/public/imgs/parabensnovo.gif')}}"/>  
        </div>

     <center>
            <button id="btnsubmit" type="button" onClick="realizarsorteio();" 
            class="btn border-round col s12" 
            style="background-color: white; color:black;position:static; margin-top: 370px; margin-left: -15px; width: 300px;">Realizar sorteio</button>
     </center>      

      </form>

  </div>
</div>
        </div>
        <div class="content-overlay"></div>
      </div>
    </div>

    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>


    <script>

    document.addEventListener('DOMContentLoaded', function() {
      $('#div_produto').hide();
      $('#div_numero').hide();
      $('#div_nome').hide();
      $('#div_parabens_2').hide();
      $('#div_parabens_3').hide();
      $('#numero_sorteado').hide();
    });

    function realizarsorteio() {

      $("#loadingdiv").show();
      $("#corpodiv").hide();


      setTimeout(function() {
            $('#loadingdiv').fadeOut('fast');

            //Busco o vencedor via Ajax
            var _token = $('input[name="_token"]').val();
            var id = $('#id').val();

           $.ajax({
           url:"{{ route('Painel.Marketing.Sorteio.vencedor') }}",
           type: 'POST',
           dataType: "json",
           data:{_token: _token, id:id},
           success:function(response){
            

            var qtd_num = response[0].numero.length;
            var qtd_nome = response[0].vencedor_nome.length;
            var caminhoimg = response[0].caminhoimg;
            var premio = response[0].premio;

            $("#camimhoimg").attr("src",caminhoimg);
            $('#premio').val(premio);


            if(qtd_num == 2){
              if(qtd_nome <= 15){
                $('#vencedor_nome_menor_12_2').val(response[0].vencedor_nome);
              }else{
                $('#vencedor_nome_2').val(response[0].vencedor_nome);
              }
                $('#vencedor_numero_2').val(response[0].numero);
            } else {
              if(qtd_nome <= 15){
                $('#vencedor_nome_menor_12').val(response[0].vencedor_nome);
              }else{
                $('#vencedor_nome_3').val(response[0].vencedor_nome);
              }
              $('#vencedor_numero_3').val(response[0].numero);
            }

            $("#corpodiv").show();

            $("#btnsubmit").hide();

            $('#div_produto').show();

            $('#div_nome').hide();

            $('#div_parabens_2').hide();
            $('#div_parabens_3').hide();

            $('#numero_sorteado').hide();
          
            
             document.body.style.backgroundImage = "url(../../../../public/imgs/fundoespecialista.jpg)";

          setInterval(
            ()=> {
              $('#div_numero').show();
              $('#div_produto').hide();
              $('#div_nome').show();
              if(qtd_num == 2){
               $('#div_parabens_2').show();
              } else {
                $('#div_parabens_3').show();
              }
              $('#numero_sorteado').show();
              document.body.style.backgroundImage = "url(../../../../public/imgs/confete.gif)";
            }, 8000
          )

           }

           });


      }, 8000);


    }
    </script>


  </body>



</html>