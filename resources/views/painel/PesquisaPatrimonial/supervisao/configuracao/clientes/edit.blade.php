@extends('Painel.PesquisaPatrimonial.Layouts.index')

@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

 <div class="content-wrapper">
        <section class="content-header">
            <h1>
                [Editar] - 
                <small>Adicionar editar cliente para pesquisa patrimonial.</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Painel.PesquisaPatrimonial.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Editar cliente</li>
            </ol>
        </section>
        <section class="content-header">
         @include('flash::message')
            <div class="row">
            <div class="col-xs-12">
    
    <form role="form" action="{{ route('Painel.PesquisaPatrimonial.supervisao.clientes.update') }}" method="POST" role="search"  enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="panel panel-primary setup-content" style="border-color:#965A2C;" id="step-1">
            <div class="panel-heading" style="background-color:#965A2C;border-color:#965A2C;">
                 <h3 class="panel-title">Atualizar data de vencimento do boleto para este cliente e Status.</h3>
            </div>
            <div class="panel-body">

                <input type="hidden" name="cliente_id" id="cliente_id" value="{{$datas->id}}"/>

                <div class="form-group">
                    <label class="control-label">Grupo:</label>
                    <input name="grupo" id="grupo" readonly type="text" value="{{$datas->Grupo}}" class="form-control" placeholder="Grupo Cliente" data-toggle="tooltip" data-placement="top" title="Grupo do cliente." required="required">
                </div>

                <div class="form-group">
                    <label class="control-label">Cliente:</label>
                    <input name="cliente" readonly id="cliente" type="text" value="{{$datas->Descricao}}" class="form-control" placeholder="Grupo Cliente" data-toggle="tooltip" data-placement="top" title="Grupo do cliente." required="required">
                </div>

                <div class="form-group">
                    <label class="control-label">Quantidade dias para vencimento:</label>
                    <input name="datavencimento" id="datavencimento" value="{{$datas->DiasVencimento}}" type="number" pattern="(?:\.|,|[0-9])*" class="form-control" placeholder="Dias vencimento do boleto" data-toggle="tooltip" data-placement="top" title="Informe a quantidade de dias para o vencimento do boleto." required="required">
                </div>

                <div class="form-group">
                    <label class="control-label">Status:</label>
                    <select class="form-control select2" required="required" style="width: 100%;" id="status" name="status"  data-toggle="tooltip" data-placement="top" title="Selecione o status deste cliente">
                    <option selected="selected" value="A">Ativo</option>
                    <option value="I">Ínativo</option>
                  </select>
                </div>
              

                <button class="btn btn-primary nextBtn pull-right fa fa-arrow-right" type="submit" style="background-color:#4B4B4B;border-color:#4B4B4B;">&nbsp;&nbsp;Atualizar dados</button>
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