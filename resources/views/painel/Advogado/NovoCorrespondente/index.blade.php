@extends('painel.Layout.header')
@section('title') Contratação de correspondente @endsection
<!-- Titulo da pagina -->

@section('header')
<link rel="apple-touch-icon"
    href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
<link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/data-tables.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/app-invoice.min.css') }}">


<style>
* {
    box-sizing: border-box;
}

.wrapper {
    height: 50px;
    margin-top: calc(50vh - 150px);
    margin-left: calc(50vw - 600px);
    width: 180px;
}

h1 {
    color: #222;
    font-size: 15px;
    font-weight: 400;
    letter-spacing: 0.05em;
    margin: 40px auto;
    text-transform: uppercase;
}
</style>
</head>
@endsection

@section('header_title') Contratação de correspondente @endsection

@section('submenu')
<li class="breadcrumb-item active" style="color: black;">Correspondentes cadastrados
</li>
@endsection

<!-- BEGIN: Page Main-->
@section('body')
    <div class="row">
        <div class="content-wrapper-before blue-grey lighten-5"></div>

        <!-- <div id="loadingdiv" style="margin-top:400px;margin-left:550px;">
       <img style="width: 100px; margin-top: -100px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
       </div> -->


        <div id="loadingdiv3" style="display:none;margin-top:400px;margin-left:550px;">
            <img style="width: 100px; margin-top: -100px;" src="{{URL::asset('/public/imgs/loading.gif')}}" />
        </div>

        <div class="col s12" id="corpodivcarrega">

            <div class="container">
                <div class="section">

                    <section class="invoice-list-wrapper section">

                        <div class="invoice-filter-action mr-3">
                            <a href="#modalnovoprestador"
                                class="waves-effect waves-light btn modal-trigger tooltipped border-round"
                                data-position="left"
                                data-tooltip="Clique aqui para solicitar o cadastro de um novo correspondente."
                                style="background-color: gray;color:white;font-size:11px;"><i
                                    class="material-icons left">add</i> Novo correspondente</a>
                        </div>



                        <div class="responsive-table">
                            <table class="table invoice-data-table white border-radius-4 pt-1">
                                <thead>

                                    <tr>
                                        <th style="font-size: 11px;"></th>
                                        <th style="font-size: 11px;">Nome</th>
                                        <th style="font-size: 11px;">E-mail</th>
                                        <th style="font-size: 11px;">Comarca</th>
                                        <th style="font-size: 11px;">UF</th>
                                        <th style="font-size: 11px;">Classificação</th>
                                        <th style="font-size: 11px;">Qtd. serviços</th>
                                        <th style="font-size: 11px;">Valor audiência</th>
                                        <th style="font-size: 11px;">Valor diligência</th>
                                        <th style="font-size: 11px;"></th>

                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($datas as $data)

                                    <!--Inicio Modal -->
                                    <div id="modalnovoservico{{$data->id}}" class="modal"
                                        style="width: 1207px;height:250px;">
                                        <form id="form" role="form"
                                            action="{{ route('Painel.Advogado.NovoPrestador.solicitacaoenviada') }}"
                                            method="POST" role="create" enctype="multipart/form-data">
                                            {{ csrf_field() }}

                                            <input type="hidden" name="id" id="id" value="{{$data->id}}">
                                            <input type="hidden" name="correspondente_cpf"
                                                value="{{$data->correspondente_codigo}}">


                                            <div class="modal-content">

                                                <button type="button"
                                                    class="btn waves-effect mr-sm-1 mr-2 modal-close red"
                                                    style="margin-top: -20px; margin-left: 1080px;position: fixed;"><i
                                                        class="material-icons">close</i></button>

                                                <h6>[Solicitar novo serviço ao correspondente:
                                                    {{$data->correspondente_nome}}]</h6>

                                                <div class="row">

                                                    <div class="input-field col s2">
                                                        <span style="font-size: 11px;color:black;">Correspondente
                                                            nome:</span>
                                                        <input style="font-size: 10px;" readonly type="text"
                                                            value="{{$data->correspondente_nome}}"
                                                            name="correspondente_nome" class="validate">
                                                    </div>

                                                    <div class="input-field col s2">
                                                        <span style="font-size: 11px;color:black;">Correspondente
                                                            e-mail:</span>
                                                        <input style="font-size: 10px;" readonly type="text"
                                                            value="{{$data->correspondente_email}}"
                                                            name="correspondente_email" class="validate">
                                                    </div>

                                                    <div class="input-field col m2 s12">
                                                        <span style="font-size: 11px;color:black;">Comarca:</span>
                                                        <input style="font-size: 10px;color:black;" readonly type="text"
                                                            value="{{$data->comarca_descricao}}"
                                                            name="comarca_descricao" class="validate">
                                                    </div>

                                                    <div class="input-field col m3 s12">
                                                        <span style="font-size: 11px;color:black;">Número do processo ou
                                                            código da pasta:</span>
                                                        <input style="font-size: 10px;color:black;" required
                                                            placeholder="Informe o número do processo ou código da pasta..."
                                                            type="text" name="dado" class="validate">
                                                    </div>

                                                    <div class="input-field col m3 s12">
                                                        <span style="font-size: 11px;color:black;">Selecione o tipo de
                                                            serviço:</span>
                                                        <select name="tiposervico" required style="font-size: 10px;"
                                                            class="browser-default">
                                                            @foreach($tipos as $tipo)
                                                            <option value="{{$tipo->descricao}}"
                                                                style="font-size: 10px;">{{$tipo->descricao}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="modal-footer" style="margin-top: 0px;">
                                                <button type="submit"
                                                    class="modal-action waves-effect waves-green btn-flat"
                                                    style="background-color: gray;color:white;font-size: 11px;"><i
                                                        class="material-icons left">send</i>Solicitar serviço</button>
                                            </div>


                                        </form>
                                    </div>
                                    <!--Fim Modal -->




                                    <!-- Inicio Modal Revisar -->
                                    <div id="modaleditar{{$data->id}}" class="modal"
                                        style="width: 1207px;height:350px;">
                                        <form id="form" role="form"
                                            action="{{ route('Painel.Advogado.NovoPrestador.editarclassificacao') }}"
                                            method="POST" role="create" enctype="multipart/form-data">
                                            {{ csrf_field() }}

                                            <input type="hidden" name="id" value="{{$data->id}}">
                                            <input type="hidden" name="correspondente_cpf"
                                                value="{{$data->correspondente_codigo}}">
                                            <input type="hidden" name="comarca_id" value="{{$data->comarca_id}}">


                                            <div class="modal-content">

                                                <button type="button"
                                                    class="btn waves-effect mr-sm-1 mr-2 modal-close red"
                                                    style="margin-top: -20px; margin-left: 1080px;position: fixed;"><i
                                                        class="material-icons">close</i></button>

                                                <h6>[Editar informações do correspondente:
                                                    {{$data->correspondente_nome}}]</h6>

                                                <div class="row">

                                                    <div class="input-field col s2">
                                                        <span style="font-size: 11px;color:black;">Correspondente
                                                            nome:</span>
                                                        <input style="font-size: 10px;" readonly type="text"
                                                            value="{{$data->correspondente_nome}}"
                                                            name="correspondente_nome" class="validate">
                                                    </div>

                                                    <div class="input-field col s2">
                                                        <span style="font-size: 11px;color:black;">Correspondente
                                                            e-mail:</span>
                                                        <input style="font-size: 10px;" readonly type="text"
                                                            value="{{$data->correspondente_email}}"
                                                            name="correspondente_email" class="validate">
                                                    </div>

                                                    <div class="input-field col m2 s12">
                                                        <span style="font-size: 11px;color:black;">Comarca:</span>
                                                        <input style="font-size: 10px;color:black;" readonly type="text"
                                                            value="{{$data->comarca_descricao}}"
                                                            name="comarca_descricao" class="validate">
                                                    </div>

                                                    <div class="input-field col m2 s12">
                                                        <span style="font-size: 11px;color:black;">Valor da
                                                            audiência:</span>
                                                        <input style="font-size: 10px;color:black;"
                                                            onKeyPress="javascript:return(moeda2(this,event))"
                                                            type="text"
                                                            value="<?php echo number_format($data->comarca_valoraudiencia,2,",",".") ?>"
                                                            name="comarca_valoraudiencia" class="validate">
                                                    </div>

                                                    <div class="input-field col m2 s12">
                                                        <span style="font-size: 11px;color:black;">Valor da
                                                            diligência:</span>
                                                        <input style="font-size: 10px;color:black;"
                                                            onKeyPress="javascript:return(moeda2(this,event))"
                                                            type="text"
                                                            value="<?php echo number_format($data->comarca_valordiligencia,2,",",".") ?>"
                                                            name="comarca_valordiligencia" class="validate">
                                                    </div>

                                                    <div class="input-field col m2 s12">
                                                        <span style="font-size: 11px;color:black;">Classificação:</span>
                                                        <select name="classificacao" required style="font-size: 10px;"
                                                            class="browser-default">
                                                            @if($data->correspondente_classificacao == "Não utilizar")
                                                            <option value="{{$data->correspondente_classificacao}}"
                                                                selected>{{$data->correspondente_classificacao}}
                                                            </option>
                                                            <option value="Favoritos" style="font-size: 10px;">Favoritos
                                                            </option>
                                                            <option value="Utilizar" style="font-size: 10px;">Utilizar
                                                            </option>
                                                            @elseif($data->correspondente_classificacao == "Utilizar")
                                                            <option value="{{$data->correspondente_classificacao}}"
                                                                selected>{{$data->correspondente_classificacao}}
                                                            </option>
                                                            <option value="Favoritos" style="font-size: 10px;">Favoritos
                                                            </option>
                                                            <option value="Não utilizar" style="font-size: 10px;">Não
                                                                utilizar</option>
                                                            @else
                                                            <option value="{{$data->correspondente_classificacao}}"
                                                                selected>{{$data->correspondente_classificacao}}
                                                            </option>
                                                            <option value="Não utilizar" style="font-size: 10px;">Não
                                                                utilizar</option>
                                                            <option value="Utilizar" style="font-size: 10px;">Utilizar
                                                            </option>
                                                            @endif
                                                        </select>
                                                    </div>

                                                    <div class="row">

                                                        <div class="input-field col s12" style="margin-top: -15px;">
                                                            <div class="form-group">
                                                                <div class="form-group">
                                                                    <label class="control-label"
                                                                        style="font-size: 11px;">Observação:</label>
                                                                    <textarea rows="3" type="text" name="observacao"
                                                                        class="form-control"
                                                                        placeholder="Insira a observação abaixo."
                                                                        style="height: 4rem;text-align:left; overflow:auto;font-size: 10px;">{{$data->correspondente_observacao}}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>



                                                </div>
                                            </div>

                                            <div class="modal-footer" style="margin-top: 0px;">
                                                <button type="submit"
                                                    class="modal-action waves-effect waves-green btn-flat"
                                                    style="background-color: gray;color:white;font-size: 11px;"><i
                                                        class="material-icons left">save</i>Atualizar</button>
                                            </div>


                                        </form>
                                    </div>
                                    <!--Fim Modal Revisar solicitação  -->


                                    @if($data->correspondente_classificacao == "Não utilizar")
                                    <tr style="color: red;">

                                        <td style="font-size: 10px"></td>
                                        <td style="font-size: 10px">{{ $data->correspondente_nome }}</td>
                                        <td style="font-size: 10px">{{ $data->correspondente_email }}</td>
                                        <td style="font-size: 10px">{{ $data->comarca_descricao }}</td>
                                        <td style="font-size: 10px">{{ $data->comarca_uf }}</td>
                                        <td style="font-size: 10px">{{ $data->correspondente_classificacao }}</td>
                                        <td style="font-size: 10px">0</td>
                                        <td style="font-size: 10px">R$
                                            <?php echo number_format($data->comarca_valoraudiencia,2,",",".") ?></td>
                                        <td style="font-size: 10px">R$
                                            <?php echo number_format($data->comarca_valordiligencia,2,",",".") ?></td>


                                        <td style="font-size: 10px">
                                            <div class="invoice-action">

                                                <a href="#modalnovoservico{{$data->id}}"
                                                    class="invoice-action-view mr-4 tooltipped modal-trigger"
                                                    data-position="bottom"
                                                    data-tooltip="Clique aqui para solicitar um novo serviço a este correspondente."><i
                                                        class="material-icons">add</i></a>
                                                <a href="#modaleditar{{$data->id}}"
                                                    class="invoice-action-view mr-4 tooltipped modal-trigger"
                                                    data-position="bottom"
                                                    data-tooltip="Clique aqui para editar os dados deste correspondente."><i
                                                        class="material-icons">edit</i></a>

                                            </div>
                                        </td>


                                    </tr>
                                    @elseif($data->correspondente_classificacao == "Utilizar")
                                    <tr style="color: green;">

                                        <td style="font-size: 10px"></td>
                                        <td style="font-size: 10px">{{ $data->correspondente_nome }}</td>
                                        <td style="font-size: 10px">{{ $data->correspondente_email }}</td>
                                        <td style="font-size: 10px">{{ $data->comarca_descricao }}</td>
                                        <td style="font-size: 10px">{{ $data->comarca_uf }}</td>
                                        <td style="font-size: 10px">{{ $data->correspondente_classificacao }}</td>
                                        <td style="font-size: 10px">0</td>
                                        <td style="font-size: 10px">R$
                                            <?php echo number_format($data->comarca_valoraudiencia,2,",",".") ?></td>
                                        <td style="font-size: 10px">R$
                                            <?php echo number_format($data->comarca_valordiligencia,2,",",".") ?></td>

                                        <td style="font-size: 10px">
                                            <div class="invoice-action">

                                                <a href="#modalnovoservico{{$data->id}}"
                                                    class="invoice-action-view mr-4 tooltipped modal-trigger"
                                                    data-position="bottom"
                                                    data-tooltip="Clique aqui para solicitar um novo serviço a este correspondente."><i
                                                        class="material-icons">add</i></a>
                                                <a href="#modaleditar{{$data->id}}"
                                                    class="invoice-action-view mr-4 tooltipped modal-trigger"
                                                    data-position="bottom"
                                                    data-tooltip="Clique aqui para editar os dados deste correspondente."><i
                                                        class="material-icons">edit</i></a>

                                            </div>
                                        </td>


                                    </tr>
                                    @else
                                    <tr>

                                        <td style="font-size: 10px"></td>
                                        <td style="font-size: 10px">{{ $data->correspondente_nome }}</td>
                                        <td style="font-size: 10px">{{ $data->correspondente_email }}</td>
                                        <td style="font-size: 10px">{{ $data->comarca_descricao }}</td>
                                        <td style="font-size: 10px">{{ $data->comarca_uf }}</td>
                                        <td style="font-size: 10px">{{ $data->correspondente_classificacao }}</td>
                                        <td style="font-size: 10px">0</td>
                                        <td style="font-size: 10px">R$
                                            <?php echo number_format($data->comarca_valoraudiencia,2,",",".") ?></td>
                                        <td style="font-size: 10px">R$
                                            <?php echo number_format($data->comarca_valordiligencia,2,",",".") ?></td>


                                        <td style="font-size: 10px">
                                            <div class="invoice-action">

                                                <a href="#modalnovoservico{{$data->id}}"
                                                    class="invoice-action-view mr-4 tooltipped modal-trigger"
                                                    data-position="bottom"
                                                    data-tooltip="Clique aqui para solicitar um novo serviço a este correspondente."><i
                                                        class="material-icons">add</i></a>
                                                <a href="#modaleditar{{$data->id}}"
                                                    class="invoice-action-view mr-4 tooltipped modal-trigger"
                                                    data-position="bottom"
                                                    data-tooltip="Clique aqui para editar os dados deste correspondente."><i
                                                        class="material-icons">edit</i></a>

                                            </div>
                                        </td>


                                    </tr>
                                    @endif


                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </section>

                </div>
                <div class="content-overlay"></div>
            </div>
        </div>
    </div>




<div id="modalnovoprestador" class="modal">
    <div class="modal-content">
        <h6>Cadastrar novo correspondente</h6>
        <p style="font-size: 10px">O correspondente recebe um e-mail para cadastro de suas informações para a revisão da
            equipe do financeiro.</p>

        <form id="formnovocorrespondente" role="form" class="edit-contact-item mb-5 mt-5"
            onsubmit="btnsubmit.disabled = true; return true;"
            action="{{ route('Painel.Advogado.NovoPrestador.solicitacaoenviada') }}" method="post"
            enctype="multipart/form-data">
            {{ csrf_field() }}

            <input type="hidden" name="correspondente_id" id="correspondente_id">

            <div class="row">

                <div class="input-field col s12">
                    <span style="font-size: 11px;">Informe o e-mail do novo correspondente:</span>
                    <input type="email" name="correspondente_email" placeholder="Informe o e-mail do correspondente..."
                        class="validate" style="font-size: 10px;">
                </div>

                <div class="input-field col s12">
                    <span style="font-size: 11px;">Informe o número do processo ou código da pasta:</span>
                    <input type="text" placeholder="Informe o número do processo ou código da pasta..." class="validate"
                        name="dado" style="font-size: 10px;" required>
                </div>

                <div class="input-field col s12">
                    <span style="font-size: 11px;">Selecione o tipo de serviço:</span>
                    <select name="tiposervico" required style="font-size: 10px;" class="browser-default">
                        <option value="" selected></option>
                        @foreach($tipos as $tipo)
                        <option value="{{$tipo->descricao}}" style="font-size: 10px;">{{$tipo->descricao}}</option>
                        @endforeach
                    </select>
                </div>

            </div>


            <div class="card-action pl-0 pr-0 right-align">

                <button type="button" onClick="abreconfirmacao();" id="btnsubmit"
                    style="background-color: gray;color:white;font-size:11px;" class="waves-effect waves-light  btn"><i
                        class="material-icons left">send</i> Enviar e-mail</button>

            </div>


        </form>

    </div>

</div>


<div id="modalconfirmacao" class="modal" style="width: 50% !important;height: 30% !important;">
    <div id="corpodiv3">
        <div class="modal-content">
            <center>
                <p style="font-size: 18px;">Deseja confirmar a solicitação de novo correspondente?</p>
            </center>
        </div>
        <div class="modal-footer">
            <a class="modal-action  waves-effect waves-red btn-flat "
                style="background-color: red;color:white;font-size:11px;" onClick="nao();"><i
                    class="material-icons left">close</i>Não</a>
            <a class="modal-action  waves-effect waves-green btn-flat "
                style="background-color: green;color:white;font-size:11px;" onClick="sim();"><i
                    class="material-icons left">check</i>Sim</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
<script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
<script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
<script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>


<script>
document.addEventListener("DOMContentLoaded", function() {
    $('.modal').modal();
    //  $("#corpodivcarrega").hide();

});
</script>

<script language="javascript">
function moeda2(a, t) {

    var e = '.';
    var r = ',';

    let n = "",
        h = j = 0,
        u = tamanho2 = 0,
        l = ajd2 = "",
        o = window.Event ? t.which : t.keyCode;
    if (13 == o || 8 == o)
        return !0;
    if (n = String.fromCharCode(o),
        -1 == "0123456789".indexOf(n))
        return !1;
    for (u = a.value.length,
        h = 0; h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r); h++)
    ;
    for (l = ""; h < u; h++)
        -
        1 != "0123456789".indexOf(a.value.charAt(h)) && (l += a.value.charAt(h));
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
function abreconfirmacao() {

    $('.modal').modal();
    $('#modalconfirmacao').modal('open');
}
</script>

<script>
function nao() {

    window.location.reload(true);

}
</script>

<script>
setTimeout(function() {
    $('#loadingdiv').fadeOut('fast');
    //  $("corpodivcarrega").show();
}, 4000);
</script>


<script>
function sim() {
    $('.modal').css('display', 'none');
    document.getElementById("loadingdiv3").style.display = "";
    document.getElementById("corpodivcarrega").style.display = "none";
    document.getElementById("corpodiv3").style.display = "none";
    document.getElementById("formnovocorrespondente").submit();

}
</script>
@endsection