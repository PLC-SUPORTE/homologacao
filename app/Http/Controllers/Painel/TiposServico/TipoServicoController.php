<?php

namespace App\Http\Controllers\Painel\TiposServico;

use App\Models\TipoServico;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TipoServicoController extends Controller
{

    protected $model;

    public function __construct(TipoServico $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        $title = 'Painel de Tipo Serviços';
        $categorias = $this->model->where('id', '!=', 0)->get();
        return view('Painel.TipoServico.index', compact('title', 'categorias'));
    }


    public function create()
    {
        $title = 'Painel Cadastro de Tipos de Serviço';
        return view('Painel.TipoServico.create-edit', compact('title'));
    }


    public function store(Request $request)
    {
    
      
        
        
        
        if( $update )
            return redirect()
                        ->route("Painel.TipoServico.index")
                        ->with(['success' => 'Criação realizada com sucesso!']);
        else
            return redirect()
                        ->route("Painel.TipoServico.create-edit", ['id' => $id])
                        ->withErrors(['errors' => 'Falha ao editar'])
                        ->withInput();
    
    }

    public function show($id)
    {
        //Recupera o usuário
        $categoria= $this->model->find($id);
        
        $title = "Tipo Serviço: {$categoria->model}";
        
        return view('Painel.TipoServico.show', compact('categoria', 'title'));
    }


   public function edit($id)
    {
        //Recupera a categoria  pelo id
        $categoria = $this->model->find($id);
        
        $title = "Editar Tipo de Serviço: {$categoria->descricao}";
        
        return view('Painel.TipoServico.create-edit', compact('categoria', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        //Valida os dados
        $this->validate($request, $this->model->rules($id));
        
        //Pega todos os dados do formulário
        $dataForm = $request->all();
        
        //Cria o objeto da model
        $data = $this->model->find($id);
      
        //Altera os dados
        $update = $data->update($dataForm);
        
        if( $update )
            return redirect()
                        ->route("Painel.TipoServico.index")
                        ->with(['success' => 'Alteração realizada com sucesso!']);
        else
            return redirect()
                        ->route("Painel.TipoServico.create-edit", ['id' => $id])
                        ->withErrors(['errors' => 'Falha ao editar'])
                        ->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        //
    }
}
