@extends('Painel.Financeiro.Layouts.index')

@section('content')
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  <div class="content-wrapper">
    <section class="content-header">
        <h1>
          [Faturamento] - <small>[Selecione o grupo, contrato e status.]</small>
        </h1>
    </section>
    <section class="content">
      @include('flash::message')
      <form role="form" action="{{ route('Painel.Financeiro.faturamento.pastas') }}" method="POST" role="create">
        {{ csrf_field() }}
           <div class="panel panel-primary setup-content" style="border-color:#965A2C;" id="step2">
                <div class="panel-heading" style="background-color:#965A2C;border-color:#965A2C;">
                     <h3 class="panel-title">Selecione o grupo, contrato e status.</h3>
                </div>
                <div class="panel-body">
            <div class="card card-default">
         
              <div class="card-body">
               <div class="row">

              <div class="col-md-3">
                  <div class="form-group">
                     <label class="control-label">Grupo:</label>
                          <select name="country" id="country" class="form-control  dynamic" data-dependent="state" required="required">
                            @foreach($grupos as $country)
                              <option value="{{ $country->Codigo}}">{{ $country->Descricao }}</option>
                            @endforeach
                          </select>
                   </div>
              </div>   

                <div class="col-md-2">
                  <div class="form-group">
                    <label class="control-label">Contrato:</label>
                    <select name="state" id="state" class="form-control dynamic" data-dependent="city">
                    <option value=""></option>
                    </select>
                   </div>
              </div>   
                   
   
            
            <div class="col-md-3">
             <div class="form-group">
                <label class="control-label">Status:</label>
                <select name="status" id="status" class="form-control dynamic">
                <option value="Ativa" selected="">Ativa</option>
                <option value="Inativa">Inativa</option>
                <option value="Geral">Geral</option>
                </select>
              </div>
          </div> 

          <button class="btn btn-primary nextBtn pull-right fa fa-search"  id="btn" name="btn" type="submit" style="background-color:#4B4B4B;border-color:#4B4B4B; margin-top: 50px; margin-right: 10px;">&nbsp;&nbsp;Buscar faturamento</button>
           <a href="{{ route('Painel.Financeiro.gerarExcelFaturamento') }}" class="delete" target="_blank"><span class="btn btn-success pull-right fa fa-file-excel-o" style="margin-top: 50px;"> Exportar Dados</span></a>  
              </div>
               </div>
              </div>
        </form>
    </section>
</div>

<script>
    $(document).ready(function(){

      $('.dynamic').change(function(){
          if($(this).val() != '')
          {
            var select = $(this).attr("id");
            var value = $(this).val();
            var dependent = $(this).data('dependent');
            var _token = $('input[name="_token"]').val();
            
            $.ajax({
                url:"{{ route('dynamicdependent.fetch') }}",
                method:"POST",
                data:{select:select, value:value, _token:_token, dependent:dependent},
                success:function(result)
                {
                  $('#'+dependent).html(result);
                }
            })
          }
      });

      $('#country').change(function(){
        $('#state').val('');
        $('#city').val('');
      });

      $('#state').change(function(){
        $('#city').val('');
      });
    });
</script>

@endsection