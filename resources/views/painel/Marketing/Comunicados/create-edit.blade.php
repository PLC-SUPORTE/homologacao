@extends('Painel.Marketing.Layouts.index')

@section('content')


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>


<script>
    function test() {
        $("#link").show();
    }
</script>

<script>
    function test2() {
        $("#link").hide();
    }
</script>


<div class="content-wrapper">
        <section class="content-header">
<div class="bred">
    <a href="{{ route('Home.Principal.Show') }}" class="bred">Home  > </a>
    <a href="" class="bred">Comunicados > Gestão de Comunicados</a>
</div>

<div class="title-pg">
    <h1 class="title-pg">Gestão de Comunicados: <b>{{$data->title or 'Novo'}}</b></h1>
</div>

<div class="content-din">
    @if( isset($errors) && count($errors) > 0 )
    <div class="alert alert-warning">
        @foreach($errors->all() as $error)
            <p>{{$error}}</p>
        @endforeach
    </div>
    @endif
    @if( isset($data) )
        {!! Form::model($data, ['route' => ['Painel.Marketing.update', $data->id], 'class' => 'form form-search form-ds', 'files' => true, 'method' => 'PUT']) !!}
    @else
        {!! Form::open(['route' => 'Painel.Marketing.store', 'class' => 'form form-search form-ds', 'files' => true]) !!}
    @endif
        <div class="form-group">
            {!! Form::text('titulo', null, ['placeholder' => 'Título do Comunicado:', 'class' => 'form-control', 'required' => '']) !!}
        </div>
        <div class="form-group">
            Selecione a Categoria:
            {!! Form::select('categoria_id', $categorias, null, ['class' => 'form-control', 'required' => '']) !!}
        </div>
        <div class="form-group">
            {!! Form::date('data', null, ['class' => 'form-control', 'required' => '']) !!}
        </div>
        <div class="form-group">
            {!! Form::textarea('descricao', null, ['placeholder' => 'Descrição:', 'class' => 'form-control', 'required' => '']) !!}
        </div>
        <div class="form-group">
        <label>Deseja inserir link?</label>
        <br>
        <input class="form-check" type="radio" name="pergunta" id="pergunta" value="S" onclick="test();" checked> Sim
        <input class="form-check" type="radio" name="pergunta" id="pergunta" value="N" onclick="test2();"> Não<br>
        </div>
        <div class="form-group">
            {!! Form::text('link', null, ['placeholder' => 'Link:', 'class' => 'form-control', 'id' => 'link']) !!}
        </div>
        <div class="form-group">
            Selecione o Status do Comunicado:
            {!! Form::select('status', ['A' => 'Ativo', 'R' => 'Rascunho'], null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::file('select_file', ['class' => 'form-control', 'accept' => 'jpg,.png,.xls,.xlsx,.pdf,.doc,.docx,.rtf,.jpeg']) !!}
        </div>

        <div class="form-group">
            {!! Form::submit('Enviar', ['class' => 'btn btn-success fa fa-check']) !!}
        </div>
    {!! Form::close() !!}

</div><!--Content Dinâmico-->
</div>


@endsection

@push('scripts')
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>tinymce.init({ selector:'textarea' });</script>
@endpush