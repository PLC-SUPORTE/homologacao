<?php

namespace App\Http\Controllers\Painel\StatusFicha;

use App\Models\StatusFicha;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatusFichaController extends Controller
{

    protected $model;

    public function __construct(StatusFicha $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        $title = 'Painel de Status Ficha';
        $datas = $this->model->get();
        return view('Painel.StatusFicha.index', compact('title', 'datas'));
    }


    public function create()
    {
        $title = 'Painel Cadastro de Advogados';
        return view('Painel.Advogado.create-edit', compact('title'));
    }


    public function store(Request $request)
    {
    
      
        
        
        
        if( $update )
            return redirect()
                        ->route("Painel.Advogado.index")
                        ->with(['success' => 'Criação realizada com sucesso!']);
        else
            return redirect()
                        ->route("Painel.Advogado.create-edit", ['id' => $id])
                        ->withErrors(['errors' => 'Falha ao editar'])
                        ->withInput();
    
    }

    public function show($id)
    {
        //Recupera a moeda
        $advogados= $this->model->find($id);
        
        $title = "Advogado: {$advogados->model}";
        
        return view('Painel.Advogado.show', compact('advogados', 'title'));
    }


   public function edit($id)
    {
        //Recupera a categoria  pelo id
        $advogados = $this->model->find($id);
        
        $title = "Editar Advogado: {$advogados->descricao}";
        
        return view('Painel.Advogado.create-edit', compact('advogados', 'title'));
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
                        ->route("Painel.Advogado.index")
                        ->with(['success' => 'Alteração realizada com sucesso!']);
        else
            return redirect()
                        ->route("Painel.Advogado.create-edit", ['id' => $id])
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
