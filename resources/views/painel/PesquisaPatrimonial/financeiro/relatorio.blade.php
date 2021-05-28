<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>


<body onload="window.print();">

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-primary">
                <div class="panel-body">
                   <center> <strong><h3>Movimentação Bancária</h3></strong></center>
                </div>
                    <ul class="list-group">
                    <div class="float-right" style="margin-left: 20px;">
                      <small>
                          <a href="" class="delete" target="_blank"><span class="btn btn-success fa fa-file-excel-o"> Exportar Dados</span></a>
                      </small>
                  </div>
                    <li class="list-group-item">
                    <p>Data de Pagamento Ínicio:  {{ date('d/m/Y', strtotime($datainicio)) }} Fim: {{ date('d/m/Y', strtotime($datafim)) }} </p>
                    <p>Banco:  {{$descricaobanco}} </p>
                    <center><p>Saldo anterior a: </center>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Número</th>
                                <th>Origem</th>
                                <th>Cliente/Fornecedor</th>
                                <th>Data</th>
                                <th>Classificação</th>
                                <th>Moeda</th>
                                <th>Valor</th>
                                <th>Tipo</th>
                                <th>Observação</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($datas as $data)
                            <tr>
                                <td>{{$data->Tipodoc}}</td>
                                <td>{{$data->Numdoc}}</td>
                                <td>{{$data->TipodocOr}}</td>
                                <td>{{$data->Razao}}</td>
                                <td>{{ date('d/m/Y', strtotime($data->Dt_baixa)) }}</td>
                                @if($data->Tipo == "P")
                                <td>Debíto (P)</td>
                                @else
                                <td>Crédito (R)</td>
                                @endif
                                <td>{{$data->Moeda}}</td>
                                <td><?php echo number_format($data->Valor, 2); ?></td>
                                <td>{{$data->TipoLancamento}}</td>
                                <td>{{$data->Observacao}}</td>
                            </tr>
                        @endforeach    

                        </tbody>
                    </table>
                    </li>
               
                    </ul>
            </div>
        </div>
    </div>
</div>

