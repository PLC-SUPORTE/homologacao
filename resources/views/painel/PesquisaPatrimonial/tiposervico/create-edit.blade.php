@extends('Painel.PesquisaPatrimonial.Layouts.index')

@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>

<script language="javascript">   
function moeda2(a, e, r, t) {
    let n = ""
      , h = j = 0
      , u = tamanho2 = 0
      , l = ajd2 = ""
      , o = window.Event ? t.which : t.keyCode;
    if (13 == o || 8 == o)
        return !0;
    if (n = String.fromCharCode(o),
    -1 == "0123456789".indexOf(n))
        return !1;
    for (u = a.value.length,
    h = 0; h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r); h++)
        ;
    for (l = ""; h < u; h++)
        -1 != "0123456789".indexOf(a.value.charAt(h)) && (l += a.value.charAt(h));
    if (l += n,
    0 == (u = l.length) && (a.value = ""),
    1 == u && (a.value = "0" + r + "0" + l),
    2 == u && (a.value = "0" + r + l),
    u > 2) {
        for (ajd2 = "",
        j = 0,
        h = u - 3; h >= 0; h--)
            3 == j && (ajd2 += e,
            j = 0),
            ajd2 += l.charAt(h),
            j++;
        for (a.value = "",
        tamanho2 = ajd2.length,
        h = tamanho2 - 1; h >= 0; h--)
            a.value += ajd2.charAt(h);
        a.value += r + l.substr(u - 2, u)
    }
    return !1
}

 </script> 

 <div class="content-wrapper">
        <section class="content-header">
            <h1>
                [Adicionar novo tipo de serviço] - 
                <small>Selecione o estado e indique a taxa serviço.</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Painel.PesquisaPatrimonial.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Novo tipo de serviço</li>
            </ol>
        </section>
        <section class="content-header">
         @include('flash::message')
            <div class="row">
            <div class="col-xs-12">
    
    <form role="form" action="{{ route('Painel.PesquisaPatrimonial.tiposdeservico.store') }}" method="POST" role="search"  enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="panel panel-primary setup-content" style="border-color:#965A2C;" id="step-1">
            <div class="panel-heading" style="background-color:#965A2C;border-color:#965A2C;">
                 <h3 class="panel-title">Selecione o estado e indique a taxa do serviço.</h3>
            </div>
            <div class="panel-body">

               <div class="form-group">
                  <label>Selecione o estado:</label>
                  <select class="form-control select2" onClick="buscaufs();" required="required" style="width: 100%;" id="estado" name="estado"  data-toggle="tooltip" data-placement="top" title="Selecione o estado.">
                    <option selected="selected" value=""></option>
                  </select>
                </div>   

            <div class="form-group">
                    <label class="control-label">Descrição:</label>
                    <input name="tipodeservico" id="tipodeservico" type="text" required="required" maxlength="255" class="form-control" placeholder="Informe a descrição do tipo de serviço." data-toggle="tooltip" data-placement="top" title="Informe a descrição do tipo de serviço.">
            </div>
              
                <button class="btn btn-primary nextBtn pull-right fa fa-arrow-right" type="submit" style="background-color:#4B4B4B;border-color:#4B4B4B;">&nbsp;&nbsp;Cadastrar</button>
            </div>
        </div>
        </div>
    </form>
        </section>
        </div>


<script type="text/javascript" >
 function buscaufs() {

  $("#estado").click(function(){

  var _token = $('input[name="_token"]').val();

            $.ajax({
            url:"https://servicodados.ibge.gov.br/api/v1/localidades/estados",
            type: 'GET',
            datatype: 'JSON',
            contentType: "application/json; charset=utf-8",
            cache: false,
            success:function(response){

                var len = response.length;

                for( var i = 0; i<len; i++){
                var id = response[i]['id'];
                var name = response[i]['nome'];
                $("#estado").append("<option value='"+id+"'>"+name+"</option>");
                }

            }
        }); 

  });    
};
</script>


@endsection