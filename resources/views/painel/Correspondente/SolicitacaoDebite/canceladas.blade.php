@extends('Painel.Correspondente.Layouts.index')

<script src="https://code.jquery.com/jquery-1.9.1.js"></script>
@section('content')


    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                [Listagem solicitações]  - 
                <small>[Listagem de solicitações canceladas correspondente]</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Painel.Correspondente.principal') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Listagem de solicitações canceladas correspondente</li>
            </ol>
        </section>
        <section class="content">
            @include('flash::message')
            <div class="row">

                <div class="col-xs-12">

                    <div class="box ">
                        <div class="box-header">
                            
                         <small>Adicionar nova solicitação de pagamento:
                                <a type="button"    href="{{ route('Painel.Correspondente.create') }}" class="btn btn-sm  btn-success fa fa-plus" data-toggle="tooltip" data-placement="top" title="Adicionar nova solicitação de pagamento para Correspondente.">&nbsp;&nbsp;Novo</a>
                            </small>
                        </div>
                        
                        <div class="box-body">
                        <div class="table-responsive"><div class="box-body">
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

                        <!--Inicio Modal Anexos --> 
                        <div class="modal fade" id="anexo{{$categoria->NumeroDebite}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <button type="button" class="btn  mr-sm-1 mr-2 modal-close red" data-dismiss="modal" style="margin-left: 1255px; margin-top: 5px;">
                        <i class="material-icons">close</i> 
                        </button>
                        <iframe style=" position:absolute;
                           top:60;
                           left:0;
                           width:100%;
                           height:100%;" src="{{ route('Painel.Correspondente.anexo', $categoria->NumeroDebite) }}"></iframe>
                        </div>
                        <!--Fim Modal Anexos --> 

                                        <td>{{ $categoria->NumeroDebite }}</td>                  
                                        <td>{{ $categoria->Pasta }}</td>
                                        <td>{{ $categoria->TipoServicoDescricao}} </td>
                                        <td>R$ {{ $categoria->ValorTotal}}</td>
                                        <td>{{ $categoria->descricao}}</td>
                                        <td>
                                        <a href="{{route('Painel.Correspondente.show', $categoria->NumeroDebite)}}" target="_blank" ><span class="btn btn-primary fa fa-book" value="{{$categoria->NumeroDebite}}" data-toggle="tooltip" data-placement="left" title="Clique aqui para acompanhar o workflow desta solicitação de pagamento." style="background-color:#4B4B4B;border-color:#4B4B4B;"></span></a>
                                        <a href="{{route('Painel.Correspondente.imprimir', $categoria->NumeroDebite)}}" target="_blank" ><span class="btn btn-primary fa fa-file" value="{{$categoria->NumeroDebite}}" data-toggle="tooltip" data-placement="left" title="Clique aqui para gerar o relatório desta solicitação de pagamento." style="background-color:#4B4B4B;border-color:#4B4B4B;"></span></a>
                                        <a href="#" data-toggle="modal" data-target="#anexo{{$categoria->NumeroDebite}}" ><span class="btn btn-primary fa fa-file-pdf-o" value="{{$categoria->NumeroDebite}}" data-toggle="tooltip" data-placement="left" title="Clique aqui para acompanhar o workflow desta solicitação de pagamento." style="background-color:#4B4B4B;border-color:#4B4B4B;"></span></a>
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


