@extends('Painel.Coordenador.Layouts.index')


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

<script>
    function admissao() {
        $("#teste2").hide();
        funcionario.setAttribute('required', ''); 
    }
</script>

<script>
    function substituicao() {
        $("#teste2").show();
    }
</script>

<script>
    function test() {
        $("#teste3").show();
    }
</script>

<script>
    function test2() {
        $("#teste3").hide();
    }
</script>



    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                [Solicitação de contratação/substituição] -
                <small>[Realizar uma nova solicitação de contratação.]</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Realizar uma nova solicitação de contratação/substituição.</li>
            </ol>
        </section>
        <section class="content">
            @include('flash::message')
            <div class="row">
                <div class="col-xs-12">
        
        <form role="form" action="{{ route('Painel.Contratacao.solicitado') }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="panel panel-primary setup-content" style="border-color:#965A2C;" id="step-1">
            <div class="panel-heading" style="background-color:#965A2C;border-color:#965A2C;">
                 <h3 class="panel-title">Preencha os campos para realizar uma nova solicitação de contratação/substituição.</h3>
            </div>
            <div class="panel-body">

            <div class="row">
            <div class="col-md-2">
            <div class="form-group" requiered>
                  <label>Selecione o tipo:</label>
                  <br>
                  <input class="form-check" type="radio" name="tipocontratacao" id="tipocontratacao" value="A" checked onclick="admissao();"> Admissão
                  <input class="form-check" type="radio" name="tipocontratacao" id="tipocontratacao" value="S" onclick="substituicao();"> Substituição<br>
            </div>
            </div>  

            <div class="col-md-2">
            <div class="form-group" requiered>
                  <label>Tem Headhunder?</label>
                  <br>
                  <input class="form-check" type="radio" name="headhunder" id="headhunder" value="S" checked onclick="test();" > Sim
                  <input class="form-check" type="radio" name="headhunder" id="headhunder" value="N" onclick="test2();"> Não<br>
            </div>
            </div>  

                
            <div class="col-md-2">
            <div class="form-group" requiered>
                  <label>Cargo:</label>
                  <select class="form-control select2" required="required" style="width: 100%;" id="cargo" name="cargo"  data-toggle="tooltip" data-placement="top" title="Selecione o tipo do cargo á ser contratado.">
                      <option selected="selected" value=""></option>
                      @foreach($cargos as $cargo)   
                      <option value="{{$cargo->id}}">{{$cargo->descricao}}</option> 
                      @endforeach
                  </select>
            </div>
            </div>

            <div class="col-md-2">
            <div class="form-group" requiered>
                  <label>Nível de experiência:</label>
                  <select class="form-control select2" required="required" style="width: 100%;" id="tipocargo" name="tipo"  data-toggle="tooltip" data-placement="top" title="Selecione o nível do cargo á ser contratado.">
                      <option selected="selected" value=""></option>
                      @foreach($tiposcargo as $tipo)   
                      <option value="{{$tipo->id}}">{{$tipo->descricao}}</option> 
                      @endforeach
                  </select>
            </div>
            </div>

            <div class="col-md-2">
            <div class="form-group" requiered>
                    <label class="control-label">Valor pessoal (R$):</label>
                    <input name="custo" id="custo" type="text" maxlength="8" pattern="(?:\.|,|[0-9])*" class="form-control" placeholder="Exemplo: 1.000,00" onKeyPress="return(moeda2(this,'.',',',event))" data-toggle="tooltip" data-placement="top" title="Preencha o valor do custo" required="required">
             </div>
             </div>

        <div class="col-md-2">
            <div class="form-group" requiered>
                    <label>Unidade:</label>
                    <select class="form-control select2" required="required" style="width: 100%;" id="unidade" name="unidade"  data-toggle="tooltip" data-placement="top" title="Selecione a unidade.">
                        <option selected="selected" value=""></option>
                        @foreach($unidades as $unidade)   
                        <option value="{{$unidade->Codigo}}">{{$unidade->Descricao}}</option> 
                        @endforeach
                    </select>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group" requiered>
                    <label>Setor:</label>
                    <select class="form-control select2" required="required" style="width: 100%;" id="setor" name="setor"  data-toggle="tooltip" data-placement="top" title="Selecione a unidade.">
                        <option selected="selected" value=""></option>
                        @foreach($setores as $setor)   
                        <option value="{{$setor->Id}}">{{$setor->Codigo}} - {{$setor->Descricao}}</option> 
                        @endforeach
                    </select>
            </div>
        </div>

        <div class="col-md-3" id="teste2" hidden="">
            <div class="form-group" requiered>
                    <label>Advogado:</label>
                    <select class="form-control select2" style="width: 100%;" id="funcionario" name="funcionario"  data-toggle="tooltip" data-placement="top" title="Selecione o advogado que deseja substituir.">
                        <option selected="selected" value=""></option>
                        @foreach($funcionarios as $funcionario)   
                        <option value="{{$funcionario->id}}">{{$funcionario->Nome}}</option> 
                        @endforeach
                    </select>
            </div>
        </div>

        <div class="col-md-8" id="teste3">
                <div class="form-group">
                <label>Currículo:</label>
                <input type="file" class="form-control-file" id="select_file" name="select_file" accept=".pdf,.doc,.docx"data-toggle="tooltip" data-placement="bellow" title="Favor anexar o currículo no formato PDF, DOC ou DOCX.">
            </div>
        </div>
</div>
        
       <div class="row">
        <div class="col-md-12">
                <div class="form-group">
                    <label>Observação</label>
                    <textarea class="form-control" id="observacao" name="observacao" rows="7"></textarea>
                </div>
            </div>
       </div>

         <button class="btn btn-primary nextBtn pull-right fa fa-arrow-right" type="submit" style="background-color:#4B4B4B;border-color:#4B4B4B;">&nbsp;&nbsp;Cadastrar solicitação</button>
        </div>
            
        </div>
        </div>
    </form>

                </div>

            </div>
        </section>
    </div>
@endsection
