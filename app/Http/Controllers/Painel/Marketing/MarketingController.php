<?php

namespace App\Http\Controllers\Painel\Marketing;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Posts;
use App\Models\Categoria;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\Sorteio\SorteioVencedor;



class MarketingController extends Controller
{

    protected $model;
    protected $totalPage = 10;   
    public $timestamps = false;
    
    
    public function __construct(Posts $model)
    {
        $this->model = $model;
        // $this->middleware('can:listagem_comunicados');
      }

      public function anexo($anexo) {
        return Storage::disk('marketing-sftp')->download($anexo);
      }

      public function index() {

       $title = 'Painel de Posts Marketing';
       $datas = DB::table('dbo.Marketing_Posts')
             ->select(
            'dbo.Marketing_Posts.id as id',   
            'dbo.Marketing_Posts.titulo as titulo',
            'dbo.Marketing_Posts.data as data',
            'dbo.Marketing_Posts.descricao as texto',
            'dbo.Marketing_Posts.image as anexo',
            'dbo.Marketing_Posts.link as link',
            'dbo.Marketing_Categoria_Posts.descricao as categoria',
            'dbo.Marketing_Posts_View.status as lido')
            ->leftjoin('dbo.Marketing_Posts_View', 'dbo.Marketing_Posts.id', '=', 'dbo.Marketing_Posts_View.id_post')
            ->leftjoin('dbo.Marketing_Categoria_Posts', 'dbo.Marketing_Posts.categoria_id', '=', 'dbo.Marketing_Categoria_Posts.id' )
            ->where('dbo.Marketing_Posts.status', '=', 'A')
            ->get();
              
       $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                ->where('status', 'A')
                ->where('destino_id','=', Auth::user()->id)
                ->count();
              
       $notificacoes = DB::table('dbo.Hist_Notificacao')
                ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                'data',
                'id_ref', 
                'user_id',
                'tipo', 
                'obs',
                'hist_notificacao.status', 
                'dbo.users.*')  
                ->limit(3)
                ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                ->where('dbo.Hist_Notificacao.status','=','A')
                ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                ->get();
       
      return view('Painel.Marketing.Comunicados.index', compact('title', 'datas', 'totalNotificacaoAbertas', 'notificacoes'));
    }

    public function informativos(){
      $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
      ->where('status', 'A')
      ->where('destino_id','=', Auth::user()->id)
      ->count();
    
      $notificacoes = DB::table('dbo.Hist_Notificacao')
      ->select('dbo.Hist_Notificacao.id as idNotificacao', 
      'data',
      'id_ref', 
      'user_id',
      'tipo', 
      'obs',
      'hist_notificacao.status', 
      'dbo.users.*')  
      ->limit(3)
      ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
      ->where('dbo.Hist_Notificacao.status','=','A')
      ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
      ->orderBy('dbo.Hist_Notificacao.data', 'desc')
      ->get();


      $datas = DB::table('dbo.Marketing_Posts')
        ->select(
      'dbo.Marketing_Posts.id as id',   
      'dbo.Marketing_Posts.titulo as titulo',
      'dbo.Marketing_Posts.data as data',
      'dbo.Marketing_Posts.descricao as texto',
      'dbo.Marketing_Posts.image as anexo',
      'dbo.Marketing_Posts.link as link',
      'dbo.Marketing_Categoria_Posts.descricao as categoria',
      'dbo.Marketing_Posts_View.status as lido')
      ->leftjoin('dbo.Marketing_Posts_View', 'dbo.Marketing_Posts.id', '=', 'dbo.Marketing_Posts_View.id_post')
      ->leftjoin('dbo.Marketing_Categoria_Posts', 'dbo.Marketing_Posts.categoria_id', '=', 'dbo.Marketing_Categoria_Posts.id' )
      ->where('dbo.Marketing_Posts.status', '=', 'A')
      ->where('dbo.Marketing_Categoria_Posts.id', '=', '9')
      ->orderby('dbo.Marketing_Posts.data', 'desc')
      ->get();
      

      return view('Painel.Marketing.Comunicados.informativos', compact('datas', 'totalNotificacaoAbertas', 'notificacoes'));
    }
    
     public function create() {
       
       
       $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                ->where('status', 'A')
                ->where('destino_id','=', Auth::user()->id)
                ->count();
       
      $notificacoes = DB::table('dbo.Hist_Notificacao')
                ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                'data',
                'id_ref', 
                'user_id',
                'tipo', 
                'obs',
                'hist_notificacao.status', 
                'dbo.users.*')  
                ->limit(3)
                ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                ->where('dbo.Hist_Notificacao.status','=','A')
                ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                ->get();
       
       $categorias = Categoria::get()->pluck('descricao', 'id');
      
       
      return view('Painel.Marketing.Comunicados.create-edit', compact('categorias','totalNotificacaoAbertas', 'notificacoes'));
      
    }
    
     public function store(Request $request) {
        //Valida os dados
        //$this->validate($request, $this->model->rules());
        $model = new Posts();
        
        //Pega todos os dados do formulário
        $dataForm = $request->all();
        $usuarioid = Auth::user()->id;
        $carbon= Carbon::now();        
        $dataHoraMinuto = $carbon->format('dmY_His');

        $image = $request->file('select_file');

        //Verifico se teve anexo 

        if($image == null) {
          $model->user_id = $usuarioid;
          $model->categoria_id = $request->get('categoria_id');
          $model->titulo = $request->get('titulo');
          $model->image = '';
          $model->descricao = $request->get('descricao');  
          $model->link = $request->get('link');
          $model->data = $request->get('data');
          $model->status = $request->get('status'); 
          
          $model->save();
        } else {

        //Define o nome para o arquivo
        $new_name = $dataHoraMinuto . '.'  . $image->getClientOriginalExtension();
        //$image->move(storage_path('app/public/comunicados'), $new_name);

        $image->storeAs('marketing', $new_name);

        $model->user_id = $usuarioid;
        $model->categoria_id = $request->get('categoria_id');
        $model->titulo = $request->get('titulo');
        $model->image = $new_name;
        $model->descricao = $request->get('descricao');  
        $model->link = $request->get('link');
        $model->data = $request->get('data');
        $model->status = $request->get('status'); 
        
        $model->save();
        }
       // dd($model);       
       flash('Comunicado criado com sucesso !')->success();      
       return redirect()->route('Painel.Marketing.index');
    }
    
    public function show($id) {
   
        $carbon= Carbon::now();
        $usuarioid= Auth::user()->id;

        //Fazer antes verificação se já foi lida, se não tiver fazer insert na Post_View
         $verificacao = DB::table('dbo.Marketing_Posts_View')
             ->where('id_post', '=', $id)
             ->where('user_id', '=', $usuarioid)   
             ->count();
        
         //1 Leitura fazer Insert
         if($verificacao == '0') {
             
          $values = array('id_post' => $id, 'user_id' => $usuarioid, 'data' => $carbon);
          DB::table('dbo.Marketing_Posts_View')->insert($values);
         } 
         //Não é a 1 leitura, so vai da update na Data 
         else {
           DB::table('dbo.Marketing_Posts_View')
          ->where('id_post', $id) 
          ->limit(1) 
          ->update(array('data' => $carbon));
         }
            
        //Recupera o Comunicado
        $datas= DB::table('dbo.Marketing_Posts')
             ->select('dbo.Marketing_Posts.titulo as titulo',
                     'dbo.Marketing_Posts.data as data',
                     'dbo.Marketing_Posts.descricao as descricao',
                     'dbo.Marketing_Posts.image as arquivo',
                     'dbo.Marketing_Categoria_Posts.descricao as categoria',
                     'dbo.users.name as criador')
             ->leftjoin('dbo.Marketing_Categoria_Posts', 'dbo.Marketing_Posts.categoria_id', '=', 'dbo.Marketing_Categoria_Posts.id')  
             ->leftjoin('dbo.users', 'dbo.Marketing_Posts.user_id', '=', 'dbo.users.id')   
             ->where('dbo.Marketing_Posts.id', '=', $id)
             ->get(); 
                
        $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                ->where('status', 'A')
                ->where('destino_id','=', Auth::user()->id)
                ->count();
       
        $notificacoes = DB::table('dbo.Hist_Notificacao')
                ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                'data',
                'id_ref', 
                'user_id',
                'tipo', 
                'obs',
                'hist_notificacao.status', 
                'dbo.users.*')  
                ->limit(3)
                ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                ->where('dbo.Hist_Notificacao.status','=','A')
                ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                ->get();
        
        return view('Painel.Marketing.Comunicados.show', compact('datas','totalNotificacaoAbertas', 'notificacoes'));
    }

    public function editar($id) {

      //Recupera o Comunicado
      $datas= DB::table('dbo.Marketing_Posts')
             ->select(
                     'dbo.Marketing_Posts.id as id',
                     'dbo.Marketing_Posts.titulo as titulo',
                     'dbo.Marketing_Posts.data as data',
                     'dbo.Marketing_Posts.descricao as descricao',
                     'dbo.Marketing_Posts.image as arquivo',
                     'dbo.Marketing_Posts.status as status',
                     'dbo.Marketing_Posts.link as link',
                     'dbo.Marketing_Categoria_Posts.descricao as categoria',
                     'dbo.users.name as criador')
             ->leftjoin('dbo.Marketing_Categoria_Posts', 'dbo.Marketing_Posts.categoria_id', '=', 'dbo.Marketing_Categoria_Posts.id')  
             ->leftjoin('dbo.users', 'dbo.Marketing_Posts.user_id', '=', 'dbo.users.id')   
             ->where('dbo.Marketing_Posts.id', '=', $id)
             ->first(); 

     $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
             ->where('status', 'A')
             ->where('destino_id','=', Auth::user()->id)
             ->count();
    
     $notificacoes = DB::table('dbo.Hist_Notificacao')
             ->select('dbo.Hist_Notificacao.id as idNotificacao', 
             'data',
             'id_ref', 
             'user_id',
             'tipo', 
             'obs',
             'hist_notificacao.status', 
             'dbo.users.*')  
             ->limit(3)
             ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
             ->where('dbo.Hist_Notificacao.status','=','A')
             ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
             ->orderBy('dbo.Hist_Notificacao.data', 'desc')
             ->get();
     
     $categorias = Categoria::get()->pluck('descricao', 'id');
    
     return view('Painel.Marketing.Comunicados.edit', compact('datas','categorias','totalNotificacaoAbertas', 'notificacoes'));      

    }

    public function editado(Request $request) {


      $id_comunicado = $request->get('id_comunicado');
      $titulo = $request->get('titulo');
      $categoria_id = $request->get('categoria_id');
      $descricao = $request->get('descricao');
      $link = $request->get('link');
      $data = $request->get('data');
      $status = $request->get('status');
      $usuarioid = Auth::user()->id;
      $carbon= Carbon::now();        
      $dataHoraMinuto = $carbon->format('dmY_His');

      $image = $request->file('select_file');

        //Verifico se teve anexo 

        if($image == null) {

          DB::table('dbo.Marketing_Posts')
          ->where('id', '=' ,$id_comunicado) 
          ->limit(1) 
          ->update(array(
            'user_id' => $usuarioid,
            'categoria_id' => $categoria_id,
            'titulo' => $titulo,
            'image' => '',
            'descricao' => $descricao,
            'data' => $data,
            'status' => $status,
            'link' => $link));

         //Verifica se já existe anexo gravado, se não ira gravar como vazio já que ele não fez um novo upload
            
          
        } else {

        //Define o nome para o arquivo
        $new_name = $dataHoraMinuto . '.'  . $image->getClientOriginalExtension();
        //$image->move(storage_path('app/public/comunicados'), $new_name);

        $image->storeAs('marketing', $new_name);

        DB::table('dbo.Marketing_Posts')
        ->where('id', '=' ,$id_comunicado) 
        ->limit(1) 
        ->update(array(
          'user_id' => $usuarioid,
          'categoria_id' => $categoria_id,
          'titulo' => $titulo,
          'image' => $new_name,
          'descricao' => $descricao,
          'data' => $data,
          'status' => $status,
          'link' => $link));
        
        }
       // dd($model); 
       flash('Comunicado editado com sucesso !')->success();      
       return redirect()->route('Painel.Marketing.index');

    }


    public function sorteio_vencedor(Request $request) {

      $id = $request->get('id');
      $carbon= Carbon::now();      

      $response = DB::table('dbo.SorteioEspecialista_Matrix') 
      ->select('dbo.SorteioEspecialista_Matrix.id as id',
               'dbo.SorteioEspecialista_Matrix.name as vencedor_nome', 
               'dbo.SorteioEspecialista_Matrix.numero',
               'dbo.SorteioEspecialista_Matrix.cpf as vencedor_cpf',
               'dbo.SorteioEspecialista_Tipos.id as tipo_id' ,
               'dbo.SorteioEspecialista_Tipos.img as caminhoimg', 
               'dbo.SorteioEspecialista_Tipos.premio')
      ->leftjoin('dbo.SorteioEspecialista_Tipos', 'dbo.SorteioEspecialista_Matrix.tipo_id', 'dbo.SorteioEspecialista_Tipos.id')
      ->where('dbo.SorteioEspecialista_Tipos.id', $id)
      ->where('dbo.SorteioEspecialista_Matrix.status', '=','A')
      ->inRandomOrder()
      ->get(); 

      $numero = $response[0]->numero;
      $vencedor_nome = $response[0]->vencedor_nome;
      $tipo_id = $response[0]->tipo_id;
      $id_matrix = $response[0]->id;
      $cpf = $response[0]->vencedor_cpf;

      //Update Matrix
      DB::table('dbo.SorteioEspecialista_Tipos')
      ->where('id', $id)  
      ->limit(1) 
      ->update(array(
              'vencedor' => $id_matrix, 
              'resultado' => $response[0]->numero,
              'data_resultado'=> $carbon,
              'status' => 'Inativo'));   

      DB::table('dbo.SorteioEspecialista_Matrix')
      ->where('cpf', $cpf)  
      ->limit(1) 
      ->update(array(
              'status' => 'N'));          

      echo $response;

    }

    public function sorteio_realizarsorteio($id) {

      return view('Painel.Marketing.Sorteio.vencedor', compact('id'));      

    }

        
    
    
  
}
