@extends('Painel.PesquisaPatrimonial.Layouts.index')

@section('content')
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  <div class="content-wrapper">
    <section class="content-header">
        <h1>
          [Relátorios] - <small>[Extrato de contas.]</small>
        </h1>
    </section>
    <section class="content">
      @include('flash::message')
      <form role="form" action="{{ route('Painel.PesquisaPatrimonial.financeiro.relatorio') }}" method="POST" role="create">
        {{ csrf_field() }}
           <div class="panel panel-primary setup-content" style="border-color:#965A2C;" id="step2">
                <div class="panel-heading" style="background-color:#965A2C;border-color:#965A2C;">
                     <h3 class="panel-title">Selecione o banco, data ínicio e fim.</h3>
                </div>
                <div class="panel-body">
            <div class="card card-default">
         
              <div class="card-body">
               <div class="row">

               <div class="col-md-2">
               <div class="form-group" id="teste3">
               <label>Mostrar adiantamentos ?</label>
                 <div class="form-check">
                 <label class="form-check-label">
                 <input type="radio" class="form-check-input" id="adiantamento" name="adiantamento" value="SIM" checked="">  Sim
                 <input type="radio" class="form-check-input" id="adiantamento" name="adiantamento" value="NAO">  Não
                </label>
                </div>    
              </div>    
              </div>

               <div class="col-md-3">
               <div class="form-group" id="teste3">
               <label>O banco é da empresa ?</label>
                 <div class="form-check">
                 <label class="form-check-label">
                 <input type="radio" class="form-check-input" id="pergunta1" name="pergunta" value="SIM" checked="" onclick="daempresa();">  Sim
                 <input type="radio" class="form-check-input" id="pergunta2" name="pergunta" onclick="outraempresa();" value="NAO">  Não
                </label>
                </div>    
              </div>    
              </div>

              <div class="col-md-3">
               <div class="form-group" >
                  <label>Selecione o banco:</label>
                  <select class="form-control selectpicker" style="width: 100%;" id="banco" name="banco"  data-toggle="tooltip" data-placement="top" title="Selecione o banco abaixo.">
                    <option selected="selected" value=""></option>
                  </select>
                </div>
           </div> 

           <div class="col-md-2">
                <div class="form-group">
                   <div class="form-group">
                  <label class="control-label">Data ínicio:</label>
                  <input name="datainicio" id="datainicio" type="date" max="{{$datahoje}}" class="form-control" data-toggle="tooltip" data-placement="top" title="Selecione a data ínicio." required="required">
                   </div>
                </div>
              </div> 

              <div class="col-md-2">
                <div class="form-group">
                   <div class="form-group">
                  <label class="control-label">Data fim:</label>
                  <input name="datafim" id="datafim" type="date" max="{{$datahoje}}" class="form-control" data-toggle="tooltip" data-placement="top" title="Selecione a data fim." required="required">
                   </div>
                </div>
              </div> 



          <button class="btn btn-primary nextBtn pull-right fa fa-search" type="submit" style="background-color:#4B4B4B;border-color:#4B4B4B; margin-top: 50px; margin-right: 10px;">&nbsp;&nbsp;Preview</button>
          <!-- <a href="" class="delete" target="_blank"><span class="btn btn-success pull-right fa fa-file-excel-o" style="margin-top: 50px;"> Exportar Dados</span></a>   -->
             
         </div>
               </div>
              </div>
        </form>
    </section>
</div>


<script type="text/javascript" >
 function daempresa() {


  var _token = $('input[name="_token"]').val();


    $.ajax({
            url:"{{ route('Painel.PesquisaPatrimonial.financeiro.buscabancoempresa') }}",
            type: 'POST',
            data:{_token:_token,},
            success:function(response){

                var len = response.length;
                
                $('#banco').html(response);
            
            }
        }); 

};
</script>

<script type="text/javascript" >
 function outraempresa() {

  var _token = $('input[name="_token"]').val();

    $.ajax({
            url:"{{ route('Painel.PesquisaPatrimonial.financeiro.buscabanconempresa') }}",
            type: 'POST',
            data:{_token:_token,},
            success:function(response){

                var len = response.length;
                
                $('#banco').html(response);
            
            }
        }); 
};
</script>

<script language="javascript">   
  $(document).ready(function() {

    var _token = $('input[name="_token"]').val();


    $.ajax({
            url:"{{ route('Painel.PesquisaPatrimonial.financeiro.buscabancoempresa') }}",
            type: 'POST',
            data:{_token:_token,},
            success:function(response){

                var len = response.length;
                
                $('#banco').html(response);
            
            }
        }); 

   });
</script>



@endsection