@extends('Painel.PesquisaPatrimonial.Layouts.index')

@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

 <div class="content-wrapper">
        <section class="content-header">
            <h1>
                [Novo] - 
                <small>Adicionar novo cliente para pesquisa patrimonial.</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Painel.PesquisaPatrimonial.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Novo cliente</li>
            </ol>
        </section>
        <section class="content-header">
         @include('flash::message')
            <div class="row">
            <div class="col-xs-12">
    
    <form role="form" action="{{ route('Painel.PesquisaPatrimonial.supervisao.clientes.store') }}" method="POST" role="search"  enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="panel panel-primary setup-content" style="border-color:#965A2C;" id="step-1">
            <div class="panel-heading" style="background-color:#965A2C;border-color:#965A2C;">
                 <h3 class="panel-title">Selecione o grupo, cliente e a quantidade de dias para vencimento do boleto.</h3>
            </div>
            <div class="panel-body">

               <div class="form-group">
                  <label>Selecione o grupo:</label>
                  <select class="form-control select2" onClick="buscaclientes();" required="required" style="width: 100%;" id="grupoclienteselected" name="grupoclienteselected"  data-toggle="tooltip" data-placement="top" title="Selecione o grupo.">
                    <option selected="selected" value=""></option>
                   @foreach($gruposcliente as $data)   
                    <option value="{{ $data->descricao }}">{{ $data->descricao }}</option>
                    @endforeach
                  </select>
                </div>   

                <div class="form-group">
                  <label>Selecione o cliente:</label>
                  <select class="form-control select2" required="required" style="width: 100%;" id="clienteselected" name="clienteselected"  data-toggle="tooltip" data-placement="top" title="Selecione o cliente">
                    <option selected="selected" value=""></option>
                  </select>
                </div> 

                <div class="form-group">
                    <label class="control-label">Quantidade dias para vencimento:</label>
                    <input name="datavencimento" id="datavencimento" type="number" maxlength="8" pattern="(?:\.|,|[0-9])*" class="form-control" placeholder="Dias vencimento do boleto" data-toggle="tooltip" data-placement="top" title="Informe a quantidade de dias para o vencimento do boleto." required="required">
                </div>
              

                <button class="btn btn-primary nextBtn pull-right fa fa-arrow-right" type="submit" style="background-color:#4B4B4B;border-color:#4B4B4B;">&nbsp;&nbsp;Cadastrar</button>
            </div>
        </div>
        </div>
    </form>
        </section>
        </div>



@endsection


<script type="text/javascript" >
 function buscaclientes() {

  $("#grupoclienteselected").click(function(){

  var grupo = $('#grupoclienteselected :selected').val();

  var _token = $('input[name="_token"]').val();

            $.ajax({
            url:"{{ route('dynamicdependent.fetch50') }}",
            type: 'POST',
            data:{grupo:grupo,_token:_token,},
            success:function(response){
                
                $('#clienteselected').html(response);
            
            }
        }); 

  });    
};
</script>