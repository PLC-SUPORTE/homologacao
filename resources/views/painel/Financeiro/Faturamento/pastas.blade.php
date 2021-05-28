@extends('Painel.Financeiro.Layouts.index')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                [Faturamento pastas/processos] -
                <small>[Faturamento de pastas/processos no financeiro]</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Painel.Financeiro.principal') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Financeiro faturamento</li>
            </ol>
        </section>
        <section class="content">
            @include('flash::message')
            <div class="row">
                <div class="col-xs-12">
                  <div class="float-right">
                      <small>
                          <a href="{{route('Painel.Financeiro.gerarExcelFaturamentoContrato', $numero_contrato)}}" class="delete" target="_blank"><span class="btn btn-success fa fa-file-excel-o"> Exportar Dados</span></a>
                      </small>
                  </div>
                  {!! Form::open(['route' => ['Painel.Financeiro.solicitacoesfaturamento'], 'class' => 'form form-search form-ds']) !!}
                  {{ csrf_field() }}
                    <div class="box ">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped" style="font-size: 12px;">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Codigo pasta</th>
                                    <th>Descrição</th>
                                    <th>Cliente</th>
                                    <th>Setor</th>
                                    <th>Status atual</th>
                                    <th>Dias ativa</th>
                                    <th>Data cadastro</th>
                                    <th>Data saida</th>
                                    <th>Data atualização</th>
                                    {{-- <th>Ação</th> --}}
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($datas as $categoria)
                                    <tr>
                                        <td>{!! Form::checkbox('codigopasta[]', $categoria->CodigoPasta)  !!}</td>
                                        <td width=150px>{{ $categoria->CodigoPasta }}</td>
                                        <td>{{ $categoria->DescricaoPasta}}</td>
                                        <td>{{ $categoria->Cliente}}</td>
                                        <td width=80px>{{ $categoria->Setor}}</td>
                                        <td>{{ $categoria->StatusAtual}}</td>
                                        <td>{{ $categoria->QtdDiasAtiva}}</td>
                                        <td>{{ date('d/m/Y', strtotime($categoria->DataCadastro)) }}</td>
                                        @if($categoria->DataSaida == null)
                                        <td></td>
                                        @else
                                        <td>{{ date('d/m/Y', strtotime($categoria->DataSaida)) }}</td>
                                        @endif
                                        @if($categoria->DataUpdate== null)
                                        <td></td>
                                        @else
                                        <td>{{ date('d/m/Y', strtotime($categoria->DataUpdate)) }}</td>
                                        @endif
                                        {{-- <td>
                                       <a href=""><span class="btn btn-info fa fa-eye"data-toggle="tooltip" data-placement="left" title="Clique aqui para visualizar os dados desta pasta."  style="background-color:#4B4B4B;border-color:#4B4B4B;"></span></a> --}}
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                            <div style="margin-top: 20px;margin-left: 1050px;">    
                        <button class="btn btn-primary nextBtn  fa fa-check"  id="btn" name="btn" type="submit" style="background-color:#4B4B4B;border-color:#4B4B4B;">&nbsp;&nbsp;Atualizar para faturamento.</button>   
       </div>

    </div>
</div>
</form>


                </div>

            </div>
        </section>
    </div>
@endsection


