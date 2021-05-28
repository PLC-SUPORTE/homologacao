@extends('painel.Layout.header')
@section('title') Conciliação bancária - Formulário para conciliação bancária @endsection <!-- Titulo da pagina -->

@section('header') 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-1.9.1.js"></script>

    <style>
        body::-webkit-scrollbar-track {
            background-color: #F4F4F4;
        }       
        body::-webkit-scrollbar {
            width: 6px;
            background: #F4F4F4;
        }
        body::-webkit-scrollbar-thumb {
            background: #dad7d7;
        }
    </style>
@endsection

@section('header_title')
Formulário para conciliação bancária
@endsection

@section('submenu')
<li class="breadcrumb-item active" style="color: black">Conciliação bancária
</li>
@endsection

@section('body')
  <div class="row">
    <div class="col s12">
      <div class="card">
        <div class="card-content">
          <form role="form" id="form-baixar" onsubmit="btnsubmit.disabled = true; return true;" action="{{ route('Painel.Financeiro.baixado', $notas->NumeroDebite)}}" method="POST" role="create" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="card-body">
              <div class="row">
                <div class="col s2">
                  <div class="form-group">
                    <div class="form-group">
                      <label class="control-label">Número solicitação:</label>
                      <input type="text" id="numerodebite" name="numerodebite" class="form-control" readonly="" value="{{$notas->NumeroDebite}}">
                    </div>
                  </div>
                </div>
                  
                <div class="col s2">
                  <div class="form-group">
                    <div class="form-group">
                      <label class="control-label">Código pasta:</label>
                      <input type="text" id="pasta" name="pasta" class="form-control" readonly="" value="{{$notas->Pasta}}">
                    </div>
                  </div>
                </div>             
                
                <div class="col s2">
                  <div class="form-group">
                    <div class="form-group">
                      <label class="control-label">Data Programação:</label>
                      <input type="date" value="{{$notas->DataProgramacao}}" id="dataprogramacao" name="dataprogramacao" readonly="" class="form-control" required="required">
                    </div>
                  </div>
                </div>

                
                <div class="col s2">
                  <div class="form-group">  
                    <div class="form-group"> 
                      <label class="control-label">Data Serviço:</label>
                      <input type="date" value="{{$notas->DataServico}}" id="dataservico" name="dataservico" readonly="" class="form-control" required="required">
                    </div>
                  </div>
                </div>
                  
                <div class="col s2">
                  <div class="form-group">
                    <div class="form-group">
                      <label class="control-label">Valor a Pagar:</label>
                      <input type="text" id="valortotal" value="{{$notas->ValorTotal}}" name="valortotal"  readonly="" class="form-control" required="required">
                    </div>
                  </div>
                </div> 

                <div class="col s2">
                  <div class="form-group">
                    <div class="form-group">
                      <label class="control-label">Tipo lançamento:</label>
                      <input type="text" id="tipolan" name="tipolan" value="{{$notas->tipolan}}" class="form-control" required="required" readonly="" >
                    </div>
                  </div>
                </div>  

                  
                <div class="col s12" hidden="">
                  <div class="form-group">
                    <div class="form-group">
                      <label class="control-label">Histórico preview:</label>
                      <textarea id="hist" rows="4" type="text" name="hist" readonly="" class="form-control" placeholder="Hist debite">
                        {{$notas->Hist}} Baixa realizada pelo(a): {{Auth::user()->name}} - {{$dataHoraMinuto}}
                      </textarea>
                    </div>
                  </div>
                </div>       
                  
                <div class="col s3">
                  <div class="form-group">
                    <label>Selecione o tipo do documento:</label>
                    <select class="form-control select2" required="required" style="width: 100%;" id="tipodoc" name="tipodoc"  data-toggle="tooltip" data-placement="top" title="Selecione o tipo do documento abaixo.">
                      <option selected="selected" value=""></option>
                      @foreach($tiposdoc as $tipodoc)   
                      <option value="{{$tipodoc->Codigo}}">{{$tipodoc->Codigo}} - {{ $tipodoc->Descricao}}</option>
                      @endforeach
                    </select>
                  </div>
                </div> 
            
                <div class="col s2">
                  <div class="form-group">
                    <div class="form-group">
                      <label class="control-label">Data Conciliação:</label>
                      <input type="date" id="dataconciliacao" name="dataconciliacao" class="form-control" required="required">
                    </div>
                  </div>
                </div>
                  
                <div class="col s2">
                  <div class="form-group">
                    <div class="form-group">
                      <label class="control-label">Data Baixa:</label>
                      <input type="date" id="databaixa" name="databaixa" class="form-control" required="required">
                    </div>
                  </div>
                </div>
              
                <div class="col s2">
                  <div class="form-group" id="teste3">
                    <label>O banco é da empresa ?</label>
                    <div class="form-check">
                      <!-- <label class="form-check-label">
                        <input type="radio" class="form-check-input" id="pergunta1" name="pergunta" value="SIM" onclick="buscaempresa();">  Sim
                        <input type="radio" class="form-check-input" id="pergunta2" name="pergunta" onclick="buscanempresa();" value="NAO">  Não
                      </label> -->
                      <p>
                        <label>
                          <input onclick="buscaempresa();" value="SIM" id="pergunta1" class="with-gap" name="pergunta" type="radio"  />
                          <span>Sim</span>
                        </label>
                        <label>
                          <input onclick="buscanempresa();" value="NAO" id="pergunta1" class="with-gap" name="pergunta" type="radio"  />
                          <span>Não</span>
                        </label>
                      </p>
                    </div>    
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col s4">
                  <div class="form-group" >
                    <label>Selecione o banco:</label>
                    <select class="browser-default" style="width: 100%;" id="portador" name="portador"  data-toggle="tooltip" data-placement="top" title="Selecione o banco abaixo.">
                      <option selected="selected" value=""></option>
                    </select>
                  </div>
                </div> 
              </div>
            </div>
          </form>
        </div>
        <div class="card-action">
          <button class="btn blue waves-light waves-effect" id="btnsubmit" name="btn" onclick="$('#form-baixar').submit();" type="button" style="background-color:#4B4B4B;border-color:#4B4B4B;"><i class="material-icons left">navigate_next</i>Avançar</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
<script type="text/javascript" >
 function buscaempresa() {


  var resposta = $('#pergunta').val();

  var _token = $('input[name="_token"]').val();

    $.ajax({
      url:"{{ route('Painel.PesquisaPatrimonial.financeiro.buscabancoempresa') }}",
      type: 'POST',
      data:{_token:_token,},
      success:function(response){
        var len = response.length;
        $('#portador').html(response);
      }
  }); 

};
</script>


<script type="text/javascript" >
 function buscanempresa() {


  var resposta = $('#pergunta').val();

  var _token = $('input[name="_token"]').val();

    $.ajax({
      url:"{{ route('Painel.PesquisaPatrimonial.financeiro.buscabanconempresa') }}",
      type: 'POST',
      data:{_token:_token,},
      success:function(response){
          var len = response.length;
          $('#portador').html(response);
      }
  }); 
};
</script>
@endsection