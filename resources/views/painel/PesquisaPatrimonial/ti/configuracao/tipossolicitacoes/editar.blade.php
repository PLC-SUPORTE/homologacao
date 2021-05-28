@extends('Painel.PesquisaPatrimonial.Layouts.index')

@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

 <div class="content-wrapper">
        <section class="content-header">
            <h1>
                [Editar] - 
                <small>Editar tipo de solicitação da pesquisa patrimonial.</small>
            </h1>
            <ol class="breadcrumb">
            <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
             <li class="active">Editar tipo solicitação</li>
            </ol>
        </section>
        <section class="content-header">
         @include('flash::message')
            <div class="row">
            <div class="col-xs-12">
    
    <form role="form" action="{{ route('Painel.PesquisaPatrimonial.ti.configurar.tipossolicitacoes.update') }}" method="POST" role="search"  enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="panel panel-primary setup-content" style="border-color:#965A2C;" id="step-1">
            <div class="panel-heading" style="background-color:#965A2C;border-color:#965A2C;">
                 <h3 class="panel-title">Atualizar descricao, informações debite e status deste tipo de solicitação.</h3>
            </div>
            <div class="panel-body">

                <input type="hidden" name="id" id="id" value="{{$datas->id}}"/>

                <div class="form-group">
                    <label class="control-label">Descrição:</label>
                    <input name="descricao" id="descricao" type="text" value="{{$datas->descricao}}" class="form-control" data-toggle="tooltip" data-placement="top" title="Descrição do tipo de solicitação." required="required">
                </div>

                <div class="form-group">
                    <label class="control-label">Deve gerar debite:</label>
                    <select class="form-control select2" required="required" style="width: 100%;" id="geradebite" name="geradebite"  data-toggle="tooltip" data-placement="top" title="Selecione abaixo se este tipo de solicitação deve gerar debite ou não">
                    <option selected="selected" value="SIM">SIM</option>
                    <option value="NAO">NÃO</option>
                  </select>
               </div>

                <div class="form-group">
                    <label class="control-label">Tipo debite:</label>
                    <select class="form-control select2" style="width: 100%;" id="tipodebite" name="tipodebite"  data-toggle="tooltip" data-placement="top" title="Selecione o tipo de debite que deve gerar para este tipo de solicitação.">
                    <option value="" selected="selected"></option>
                    @foreach($tiposdebite as $tipodebite)
                    <option value="{{$tipodebite->Codigo}}">{{$tipodebite->Descricao}}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-group">
                    <label class="control-label">Status:</label>
                    <select class="form-control select2" required="required" style="width: 100%;" id="status" name="status"  data-toggle="tooltip" data-placement="top" title="Selecione abaixo o status para este tipo de solicitação.">
                    <option value="A" selected="selected">Ativo</option>
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