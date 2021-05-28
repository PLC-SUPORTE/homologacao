@extends('Painel.Marketing.Layouts.index')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                [Listagem comunicados] -
                <small>[Listagem de comunicados da PL&C]</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Listagem de comunicados PL&C</li>
            </ol>
        </section>
        <section class="content">
            @include('flash::message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="box ">
                    @can('marketing') 
                         <div class="box-header"> 
                            <small>Criar um novo comunicado:
                            <a type="button" href="{{ route('Painel.Marketing.create') }}" class="btn btn-sm  btn-success fa fa-plus" data-toggle="tooltip" data-placement="top" title="Criar um novo comunicado.">&nbsp;&nbsp;Novo</a>
                            </small>
                        </div>
                        @endcan
                        <div class="box-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Categoria</th>
                                    <th>Título</th>
                                    <th>Texto</th>
                                    <th>Status</th>
                                    <th>Data</th>
                                    <th width="120">Ação</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($datas as $data)
                                    <tr>
                                       <td>{{ $data->categoria}}</td>
                                       <td>{{ $data->titulo}}</td>
                                       <td>{{ $data->texto}}</td>
                                       @if(!empty($data->lido))
                                       <td>Lido</td>
                                       @else
                                       <td>Não lido</td>
                                       @endif
                                       <td>{{ date('d/m/Y', strtotime($data->data)) }}</td>
                                       <td>
                                       <a href="{{route('Home.Principal.updateTable', $data->id)}}"  class="delete"><span class="btn btn-success fa fa-check" data-toggle="tooltip" data-placement="top" title="Clique aqui para marcar como visualizado."></span></a>
                                       @if(!empty($data->anexo))
                                       <a href="{{route('Home.Principal.anexo', $data->anexo)}}"class="delete"><span data-toggle="tooltip" data-placement="top" title="Clique aqui para baixar o anexo deste comunicado." class="btn btn-success fa fa-download"></span></a>
                                       @endif
                                       @if(!empty($data->link))
                                       <a href="{{$data->link}}" target="_blank" class="delete"><span data-toggle="tooltip" data-placement="top" title="Clique aqui para abrir o link deste comunicado." class="btn btn-success fa fa-globe"></span></a>
                                       @endif
                                       @can('marketing') 
                                       <a href="{{route('Painel.Marketing.editar', $data->id)}}" class="btn btn-warning fa fa-edit" data-toggle="tooltip" data-placement="left" title="Clique aqui para editar este comunicado"></a>
                                       @endcan
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


