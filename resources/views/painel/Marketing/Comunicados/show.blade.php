@extends('Painel.Marketing.Layouts.index')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                [Visualizar comunicado]
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Visualizar comunicado</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                   <div class="content-din">
    
    @foreach($datas as $data)                   
    <br>
    <div class="form-group">
    <label class="control-label">Título comunicado:</label>
    <input name="numeroprocesso" value="{{$data->titulo}}" id="numeroprocesso" type="text" required="required" maxlength="50" class="form-control" placeholder="Informe o Nª Processo ou Codigo Pasta" data-toggle="tooltip" data-placement="left" title="Informe o número fornecido na fase de contratação, conforme informado pela contratante."/>
    </div>
    <div class="form-group">
    <label class="control-label">Data:</label>
    <input name="numeroprocesso" value="{{ date('d/m/Y H:m:s', strtotime($data->data)) }}" id="numeroprocesso" type="text" required="required" maxlength="50" class="form-control" placeholder="Informe o Nª Processo ou Codigo Pasta" data-toggle="tooltip" data-placement="left" title="Informe o número fornecido na fase de contratação, conforme informado pela contratante."/>
    </div>
    <div class="form-group">
    <label class="control-label">Criador:</label>
    <input name="numeroprocesso" value="{{$data->criador}}" id="numeroprocesso" type="text" required="required" maxlength="50" class="form-control" placeholder="Informe o Nª Processo ou Codigo Pasta" data-toggle="tooltip" data-placement="left" title="Informe o número fornecido na fase de contratação, conforme informado pela contratante."/>
    </div>
    <div class="form-group">
    <label class="control-label">Categoria:</label>
    <input name="numeroprocesso" value="{{$data->categoria}}" id="numeroprocesso" type="text" required="required" maxlength="50" class="form-control" placeholder="Informe o Nª Processo ou Codigo Pasta" data-toggle="tooltip" data-placement="left" title="Informe o número fornecido na fase de contratação, conforme informado pela contratante."/>
    </div>
    <div class="form-group">
    <label class="control-label">Descrição:</label>
    <textarea id="observacao" rows="7" type="text" name="observacao" class="form-control" placeholder="Digite a observação" style="text-align:left; overflow:auto;">
    {{$data->descricao}}
    </textarea>
    </div>
    
    @if(!empty($data->arquivo))
    <a href="" class="btn btn-primary fa fa-download">&nbsp;&nbsp;Baixar anexo</a>
    @else
    <p>Este comunicado não possui anexo.</p>
    @endif
    <br>
    @endforeach
   
</div><!--Content DinÃ¢mico-->

            </div>
        </section>
    </div>
@endsection
