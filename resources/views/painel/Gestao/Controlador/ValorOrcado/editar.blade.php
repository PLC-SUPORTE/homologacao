@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Editar valor orçado anual @endsection
<!-- Titulo da pagina -->

@section('header')
<link rel="apple-touch-icon"
    href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
<link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">


<style>
h1 {
    color: #222;
    font-size: 15px;
    font-weight: 400;
    letter-spacing: 0.05em;
    margin: 40px auto;
    text-transform: uppercase;
}
</style>

@endsection
@section('header_title')
>Editar valor orçado anual
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.index') }}">Dashboard</a>
</li>
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.ValorOrcado.index') }}">Valor orçado anual</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Editar valor orçado anual
</li>
@endsection
@section('body')
    <div>
        <div class="row">
            <div class="container">

                <div class="col s12 m12 l12">

                    <center>
                        <div id="loadingdiv" style="display:none">
                            <div class="wrapper">
                                <div class="circle circle-1"></div>
                                <div class="circle circle-1a"></div>
                                <div class="circle circle-2"></div>
                                <div class="circle circle-3"></div>
                            </div>
                            <h1 style="text-align: center;">Aguarde estamos atualizando o registro...&hellip;</h1>
                        </div>
                    </center>

                    <div id="Form-advance" class="card card card-default scrollspy">
                        <div class="card-content">
                            <form id="form" role="form"
                                action="{{ route('Painel.Gestao.Controlador.ValorOrcado.editado') }}" method="POST"
                                role="create">
                                {{ csrf_field() }}


                                <div class="row">

                                    <div class="input-field col m2 s12">
                                        <span>Setor</span>
                                        <input value="{{$datas->setor}}" id="setor" name="setor" class="form-control"
                                            type="text" required readonly>
                                    </div>

                                    <div class="input-field col m2 s12">
                                        <span>Setor descrição</span>
                                        <input value="{{$datas->descricao}}" id="setordescricao" name="setordescricao"
                                            class="form-control" readonly type="text">
                                    </div>

                                    <div class="input-field col m2 s12">
                                        <span>Unidade</span>
                                        <input value="{{$datas->unidade}}" id="unidade" name="unidade"
                                            class="form-control" type="text" readonly>
                                    </div>

                                    <div class="input-field col m2 s12">
                                        <span>Unidade descrição</span>
                                        <input value="{{$datas->unidade_descricao}}" id="unidadedescricao"
                                            name="unidadedescricao" readonly class="form-control" type="text">
                                    </div>


                                    <div class="input-field col m2 s12">
                                        <span>Valor</span>
                                        <input value="<?php echo number_format($datas->valor,2,",",".") ?>" id="valor"
                                            name="valor" class="form-control" type="text" maxlength="8"
                                            pattern="(?:\.|,|[0-9])*" placeholder="Valor(R$)"
                                            onKeyPress="return(moeda2(this,'.',',',event))">
                                    </div>


                                </div>

                                <div class="right-align">
                                    <a class="btn red"
                                        href="{{route('Painel.Gestao.Controlador.ValorOrcado.index')}}"><i
                                            class="material-icons left">close</i>Cancelar</a>
                                    <button type="button" id="btnsubmit" onClick="envia();" class="btn green"><i
                                            class="material-icons left">save_alt</i>Salvar</button>
                                </div>

                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

@endsection
@section('scripts')
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>


    <script>
    function envia() {

        document.getElementById("loadingdiv").style.display = "";
        document.getElementById("Form-advance").style.display = "none";
        document.getElementById("form").submit();
    }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>

    <script language="javascript">
    function moeda2(a, e, r, t) {
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

@endsection