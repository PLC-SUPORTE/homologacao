@extends('Painel.Advogado.Layouts.index')

<script src="https://code.jquery.com/jquery-1.9.1.js"></script>
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                [Solicitação de Pagamento] - 
                <small>[Listagem de solicitações de pagamento baixadas.]</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Listagem de solicitações de pagamento baixadas.</li>
            </ol>
        </section>
        <section class="content">
            @include('flash::message')
            <div class="row">

                <div class="col-xs-12">

                    <div class="box ">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped" style="font-size: 11px;">
                                <thead>
                                <tr>
                                    <th>Número</th>
                                    <th>Pasta</th>
                                    <th>Tipo Serviço</th>
                                    <th>Valor Total (R$)</th>
                                    <th>Status </th>
                                    <th>Ação</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($notas as $categoria)
                                    <tr>
                                        <td>{{ $categoria->NumeroDebite }}</td>                  
                                        <td>{{ $categoria->Pasta }}</td>
                                        <td>{{ $categoria->TipoServicoDescricao}} </td>
                                        <td>R$ {{ $categoria->ValorTotal}}</td>
                                        <td>{{ $categoria->descricao}}</td>
                                        <td>
                                       <a href="{{route('Painel.Advogado.show', $categoria->NumeroDebite)}}" target="_blank" class="delete"><span class="btn btn-primary fa fa-book" data-toggle="tooltip" data-placement="left" title="Clique aqui para acompanhar o processo desta solicitação de pagamento." style="background-color:#4B4B4B;border-color:#4B4B4B;"></span></a>
                                       <a href="{{route('Painel.Advogado.imprimir', $categoria->NumeroDebite)}}" class="delete"><span class="btn btn-warning fa fa-remove"data-toggle="tooltip" data-placement="left" title="Clique aqui para gerar o relatório desta solicitação de pagamento." style="background-color:#4B4B4B;border-color:#4B4B4B;"></span></a>
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


