@extends('Painel.Advogado.Layouts.index')

<script src="https://code.jquery.com/jquery-1.9.1.js"></script>

@section('content')

    
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                [Solicitações Abertas] -  
                <small>[Listagem de solicitações de pagamento - Status principal/Substatus]</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Painel.Correspondente.principal') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Formulário</li>
            </ol>
        </section>
        <section class="content">
            @include('flash::message')
            <div class="row">

                <div class="col-xs-12">
                    <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th style="font-size: 12px">#</th>
                                    <th style="font-size: 12px">Pasta</th>
                                    <th style="font-size: 12px">Data Solicitação</th>
                                    <th style="font-size: 12px">Serviço</th>
                                    <th style="font-size: 12px">Valor</th>
                                    <th style="font-size: 12px">Status</th>
                                    <th style="font-size: 12px">SubStatus</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($notas as $categoria)                
                                
                                    <tr>
                                        <td style="font-size: 12px">{{ $categoria->NumeroDebite }}</td>                  
                                        <td style="font-size: 12px">{{ $categoria->Pasta }}</td>
                                        <td style="font-size: 12px">{{ date('d/m/Y', strtotime($categoria->DataFicha)) }}</td>
                                        <td style="font-size: 12px">{{ $categoria->TipoServicoDescricao}} </td>
                                        <td style="font-size: 12px">R$ {{ $categoria->ValorTotal}}</td>
                                        <td style="font-size: 12px">Em Andamento</td>
                                        <td style="font-size: 12px">{{$categoria->descricao}}</td>
                                        <td>
                                         <a href="{{route('Painel.Advogado.show', $categoria->NumeroDebite)}}" data-toggle="modal" data-target="fsModal{{$categoria->NumeroDebite}}"><span class="btn btn-primary fa fa-book" value="{{$categoria->NumeroDebite}}" data-toggle="tooltip" data-placement="left" title="Clique aqui para acompanhar o workflow desta solicitação de pagamento." style="background-color:#4B4B4B;border-color:#4B4B4B;"></span></a>
                                        <a href="{{route('Painel.Advogado.imprimir', $categoria->NumeroDebite)}}" target="_blank" class="delete"><span class="btn btn-primary fa fa-file"data-toggle="tooltip" data-placement="left" title="Clique aqui para gerar o relatório desta solicitação de pagamento." style="background-color:#4B4B4B;border-color:#4B4B4B;"></span></a>
                                        </td>
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
