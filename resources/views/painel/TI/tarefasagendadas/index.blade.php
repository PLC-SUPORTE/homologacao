@extends('Painel.TI.Layouts.index')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                [Listagem] -
                <small>[Tarefas agendadas automáticamente no Portal PL&C]</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Tarefas ativas</li>
            </ol>
        </section>
        <section class="content">
            @include('flash::message')
            <div class="row">
                <div class="col-xs-12">
                  <div class="float-right">
                      <small>
                          <a href="" class="delete" target="_blank"><span class="btn btn-success fa fa-file-excel-o"> Exportar Dados</span></a>
                      </small>
                  </div>
                    <div class="box ">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped" style="font-size: 12px;">
                                <thead>
                                <tr>
                                    <th>Descrição</th>
                                    <th>Modúlo</th>
                                    <th>Frequência</th>
                                    <th>Horário</th>
                                    <th>Status</th>
                                    <th>Ação</th>
                                </tr>
                                </thead>
                                <tbody>
                                
                                @foreach($datas as $data)
                                    <tr>
                                        <td>{{ $data->Descricao}}</td>
                                        <td>{{ $data->Modulo}} </td>
                                        <td>{{ $data->Frequencia}} </td>
                                        <td>{{ $data->Horario}}</td>
                                        <td>{{ $data->Status}}</td>
                                        <td>
                                       <a href="{{route('Painel.TI.tarefasagendadas_hist', $data->Id)}}" class="delete"><span class="btn btn-primary fa fa-eye"data-toggle="tooltip" data-placement="left" title="Clique aqui para acompanhar esta tarefa."style="background-color:#4B4B4B;border-color:#4B4B4B;"></span></a>
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


