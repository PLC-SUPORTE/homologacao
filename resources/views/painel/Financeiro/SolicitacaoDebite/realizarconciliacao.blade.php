@extends('Painel.Financeiro.Layouts.index')

@section('content')


    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                [Solicitações] -
                <small>[Listagem de fornecedor com solicitações de pagamento em aberto.]</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Listagem fornecedor.</li>
            </ol>
        </section>
        <section class="content">
            @include('flash::message')
            <div class="row">

                <div class="col-xs-12">
                  <div class="float-right">
                      <small>
                          <a href="{{ route('Painel.Financeiro.gerarExcelAguardandoPagamento')}}" class="delete" target="_blank"><span class="btn btn-success fa fa-file-excel-o"> Exportar Dados</span></a>
                      </small>
                  </div>
                    <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                         <table id="example1" class="table table-bordered table-striped" style="font-size: 11px;">
                                <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Correspondente</th>
                                    <th>Quantidade solicitações abertas</th>
                                    <th>Valor total</th>
                                    <th>Ação</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($notas as $categoria)
                                    <tr>
                                    <td>{{ $categoria->CorrespondenteCodigo}}</td>
                                    <td>{{ $categoria->CorrespondenteNome}}</td>
                                    <td>{{ $categoria->QuantidadeSolicitacoes}}</td>
                                    <td><?php echo number_format($categoria->ValorTotal, 2); ?></td>
                                    <td>
                                    <a href="{{route('Painel.Financeiro.listasolicitacoespagar', $categoria->CorrespondenteID)}}" class="delete"><span class="btn btn-success fa fa-eye" data-toggle="tooltip" data-placement="left" title="Clique aqui para realizar a conciliação bancária deste fornecedor."style="background-color:#4B4B4B;border-color:#4B4B4B;"></span></a>    
                                    <a href="{{route('Painel.Financeiro.listasolicitacoespagarexcel', $categoria->CorrespondenteID)}}" class="delete"><span class="btn btn-primary fa fa-file-excel-o"data-toggle="tooltip" data-placement="left" title="Clique aqui para gerar o relatório das solicitações em aberto deste correspondente."style="background-color:#4B4B4B;border-color:#4B4B4B;"></span></a>    
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </section>
    </div>
@endsection