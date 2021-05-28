@extends('painel.Layout.header')
@section('title') Formulário para reprovação de solicitação de debite @endsection
<!-- Titulo da pagina -->

@section('header')

@endsection

@section('header_title')
Formulário para reprovação de solicitação de debite
@endsection
@section('submenu')
<li class="active">Reprovação de debite</li>
@endsection
@section('body')
  <div class="row">
    <div class="col s12">
      <div class="card">
        <div class="card-content">
        @include('flash::message')

          <form role="form" onsubmit="btnsubmit.disabled = true; return true;" action="{{ route('Painel.Financeiro.pendenciar', $notas->NumeroDebite)}}" method="POST" role="create">
            {{ csrf_field() }}
            <div class="row">
                <div class="col s2">
                    <div class="form-group">
                        <div class="form-group">
                            <label class="control-label">Número Solicitação:</label>
                            <input type="text" id="numerodebite" name="numerodebite" class="form-control" readonly=""
                                value="{{$notas->NumeroDebite}}">
                        </div>
                    </div>
                </div>


                <div class="col s3">
                    <div class="form-group">
                        <div class="form-group">
                            <label class="control-label">Status Atual:</label>
                            <input type="text" id="inputClientCompany" readonly="" class="form-control"
                                value="{{$notas->descricao}}">
                        </div>
                    </div>
                </div>

                <div class="col s2">
                    <div class="form-group">
                        <div class="form-group">
                            <label class="control-label">Valor Total:</label>
                            <input type="text" id="ValorT" name="ValorT" readonly="" value="R$ {{$notas->ValorTotal}}"
                                class="form-control" placeholder="Valor(R$)">
                        </div>
                    </div>
                </div>

                <div class="col s2">
                    <div class="form-group">
                        <div class="form-group">
                            <label class="control-label">Data Solicitação:</label>
                            <input type="text" id="data" name="data" readonly=""
                                value="{{ date('d/m/Y', strtotime($notas->DataFicha)) }}" class="form-control"
                                placeholder="Data Solicitação">
                        </div>
                    </div>
                </div>


                <div class="col s6" style="margin-top: 15px;">
                    <div class="form-group">
                        <label>Selecione o motivo da reprovação:</label>
                        <select class="form-control select2" style="width: 100%;" id="motivo" name="motivo"
                            data-toggle="tooltip" data-placement="left" title="Selecione o motivo da reprovação.">
                            @foreach($motivos as $motivo)
                            <option selected="selected">{{ $motivo->descricao }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top: 15px;">
              <div class="col s12">
                  <div class="form-group">
                      <div class="form-group">
                          <label class="control-label">Observação:</label>
                          <textarea id="observacao" rows="4" type="text" name="observacao" class="form-control"
                              placeholder="Digite a observação" style="text-align:left; overflow:auto;" required="">
                          </textarea>
                      </div>
                  </div>
              </div>
            </div>

            <div class="row" style="margin-top: 15px;">
              <div class="col s12">
                <button class="btn red waves-effect waves-light" id="btnsubmit" name="btn" type="submit"><i class="material-icons left">block</i> Reprovar solicitação</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection