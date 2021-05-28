<?php

namespace App\Http\Controllers\Painel\Controladoria;

use App\Models\Nota;
use App\Models\TipoServico;
use App\Models\Moeda;
use App\Models\Advogado;
use App\Models\Pasta;
use App\Models\FichaArquivo;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ControladoriaController extends Controller
{

    protected $model;
    protected $totalPage = 10;   
    public $timestamps = false;
    
    
    public function __construct(Nota $model)
    {
        $this->model = $model;
    }

    public function index()
    {
       $title = 'Painel de Notas';
       $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
             ->join('PLCFULL.dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','PLCFULL.dbo.Jurid_Situacao_Ficha.numerodebite')
             ->join('PLCFULL.dbo.Jurid_Status_Ficha', 'PLCFULL.dbo.Jurid_Situacao_Ficha.status_id', '=', 'PLCFULL.dbo.Jurid_Status_Ficha.id')
             ->join('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
             ->join('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
             ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                     'PLCFULL.dbo.Jurid_Debite.Pasta',
                     DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS Date) as DataFicha'), 
                     DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
                     DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                     'PLCFULL.dbo.Jurid_Status_Ficha.*',
                     'dbo.users.name')
             ->where('PLCFULL.dbo.Jurid_Situacao_Ficha.status_id','=','6')
             ->get();
       return view('Painel.Controladoria.index', compact('title', 'notas'));
      // dd($notas);
    }
    
    public function aprovadas()
    {
       $title = 'Painel de Notas Aprovadas';
       $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
             ->join('PLCFULL.dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','PLCFULL.dbo.Jurid_Situacao_Ficha.numerodebite')
             ->join('PLCFULL.dbo.Jurid_Status_Ficha', 'PLCFULL.dbo.Jurid_Situacao_Ficha.status_id', '=', 'PLCFULL.dbo.Jurid_Status_Ficha.id')
             ->join('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
             ->where('PLCFULL.dbo.Jurid_Situacao_Ficha.status_id','=','7')
             ->get();
       return view('Painel.Controladoria.aprovadas', compact('title', 'notas'));
       //dd($notas);
    }
      
  public function show($Numero)
    {
        
          $numeroprocesso= 
                 DB::table('PLCFULL.dbo.Jurid_Debite')
                   ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                           DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS DateTime) as DataFicha'), 
                           'PLCFULL.dbo.Jurid_Debite.Status as StatusFicha', 
                           'ValorT as ValorTotal', 
                           'DebPago as Pago',
                           'PLCFULL.dbo.Jurid_Debite.Moeda as Moeda',
                           'Pasta', 
                           'PLCFULL.dbo.Jurid_Pastas.PRConta',
                           'PLCFULL.dbo.Jurid_Pastas.UF as UF',
                           'PLCFULL.dbo.Jurid_Pastas.Tribunal as Tribunal',
                           'PLCFULL.dbo.Jurid_Pastas.OutraParte as OutraParte',
                           'PLCFULL.dbo.Jurid_Tribunais.Descricao as DescricaoTribunal',
                           'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumPrc1_Sonumeros',
                           DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                           'dbo.Jurid_Status_Ficha.descricao', 
                           'PLCFULL.dbo.Jurid_Advogado.Razao as Advogado',
                           'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                           'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                           'PLCFULL.dbo.Jurid_CliFor.banco as Banco',
                           'PLCFULL.dbo.Jurid_CliFor.agencia as Agencia',
                           'PLCFULL.dbo.Jurid_CliFor.conta as Conta',
                           'dbo.users.name as Nome')  
                   ->join('dbo.Jurid_Hist_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Hist_Ficha.id_hist')
                   ->join('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Hist_Ficha.id_status', '=', 'dbo.Jurid_Status_Ficha.id')
                   ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                  ->join('PLCFULL.dbo.Jurid_Advogado', 'PLCFULL.dbo.Jurid_Debite.Advogado', '=', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
                   ->join('PLCFULL.dbo.Jurid_CliFor','PLCFULL.dbo.Jurid_Pastas.Advogado','=','PLCFULL.dbo.Jurid_CliFor.Codigo')     
                   ->join('PLCFULL.dbo.Jurid_Tribunais','PLCFULL.dbo.Jurid_Pastas.Tribunal','=','PLCFULL.dbo.Jurid_Tribunais.Codigo')
                   ->join('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                   ->join('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                   ->join('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
                   ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$Numero)
                   ->where('dbo.Jurid_Hist_Ficha.id_status', '=', '6')
                   ->get()
                   ->first();
          
          $dataCancelada= 
                 DB::table('PLCFULL.dbo.Jurid_Debite')
                   ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                           DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS DateTime) as DataFicha'), 
                           'PLCFULL.dbo.Jurid_Debite.Status as StatusFicha', 
                           'ValorT as ValorTotal', 
                           'DebPago as Pago',
                           'PLCFULL.dbo.Jurid_Debite.Moeda as Moeda',
                           'Pasta', 
                           'PLCFULL.dbo.Jurid_Pastas.PRConta',
                           'PLCFULL.dbo.Jurid_Pastas.UF as UF',
                           'PLCFULL.dbo.Jurid_Pastas.Tribunal as Tribunal',
                           'PLCFULL.dbo.Jurid_Pastas.OutraParte as OutraParte',
                           'PLCFULL.dbo.Jurid_Tribunais.Descricao as DescricaoTribunal',
                           'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumPrc1_Sonumeros',
                           DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                           'dbo.Jurid_Status_Ficha.descricao', 
                           'PLCFULL.dbo.Jurid_Advogado.Razao as Advogado',
                           'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                           'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                           'PLCFULL.dbo.Jurid_CliFor.banco as Banco',
                           'PLCFULL.dbo.Jurid_CliFor.agencia as Agencia',
                           'PLCFULL.dbo.Jurid_CliFor.conta as Conta',
                           'dbo.users.name as Nome',
                           'dbo.Jurid_Situacao_Ficha.observacao as ObservacaoMotivo',
                           'dbo.Jurid_Nota_Motivos.descricao as Motivo')  
                   ->join('dbo.Jurid_Hist_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Hist_Ficha.id_hist')
                   ->join('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Hist_Ficha.id_status', '=', 'dbo.Jurid_Status_Ficha.id')
                   ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                   ->join('PLCFULL.dbo.Jurid_Advogado', 'PLCFULL.dbo.Jurid_Debite.Advogado', '=', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
                   ->join('PLCFULL.dbo.Jurid_CliFor','PLCFULL.dbo.Jurid_Pastas.Advogado','=','PLCFULL.dbo.Jurid_CliFor.Codigo')     
                   ->join('PLCFULL.dbo.Jurid_Tribunais','PLCFULL.dbo.Jurid_Pastas.Tribunal','=','PLCFULL.dbo.Jurid_Tribunais.Codigo')
                   ->join('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                   ->join('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                   ->join('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
                   ->join('dbo.Jurid_Situacao_Ficha', 'PLCFULL.dbo.Jurid_Debite.Numero', '=', 'dbo.Jurid_Situacao_Ficha.numerodebite')
                   ->join('dbo.Jurid_Nota_Motivos', 'dbo.Jurid_Situacao_Ficha.motivo_id', '=', 'dbo.Jurid_Nota_Motivos.id')
                   ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$Numero)
                   ->where('dbo.Jurid_Hist_Ficha.id_status', '=', '12')
                   ->get()
                   ->first();
          
            $dataReprovada= 
                 DB::table('PLCFULL.dbo.Jurid_Debite')
                   ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                           DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS DateTime) as DataFicha'), 
                           'PLCFULL.dbo.Jurid_Debite.Status as StatusFicha', 
                           'ValorT as ValorTotal', 
                           'DebPago as Pago',
                           'PLCFULL.dbo.Jurid_Debite.Moeda as Moeda',
                           'Pasta', 
                           'PLCFULL.dbo.Jurid_Pastas.PRConta',
                           'PLCFULL.dbo.Jurid_Pastas.UF as UF',
                           'PLCFULL.dbo.Jurid_Pastas.Tribunal as Tribunal',
                           'PLCFULL.dbo.Jurid_Pastas.OutraParte as OutraParte',
                           'PLCFULL.dbo.Jurid_Tribunais.Descricao as DescricaoTribunal',
                           'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumPrc1_Sonumeros',
                           DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                           'dbo.Jurid_Status_Ficha.descricao', 
                           'PLCFULL.dbo.Jurid_Advogado.Razao as Advogado',
                           'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                           'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                           'PLCFULL.dbo.Jurid_CliFor.banco as Banco',
                           'PLCFULL.dbo.Jurid_CliFor.agencia as Agencia',
                           'PLCFULL.dbo.Jurid_CliFor.conta as Conta',
                           'dbo.users.name as Nome',
                           'dbo.Jurid_Situacao_Ficha.observacao as ObservacaoMotivo',
                           'dbo.Jurid_Nota_Motivos.descricao as Motivo')  
                   ->join('dbo.Jurid_Hist_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Hist_Ficha.id_hist')
                   ->join('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Hist_Ficha.id_status', '=', 'dbo.Jurid_Status_Ficha.id')
                   ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                   ->join('PLCFULL.dbo.Jurid_Advogado', 'PLCFULL.dbo.Jurid_Debite.Advogado', '=', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
                   ->join('PLCFULL.dbo.Jurid_CliFor','PLCFULL.dbo.Jurid_Pastas.Advogado','=','PLCFULL.dbo.Jurid_CliFor.Codigo')     
                   ->join('PLCFULL.dbo.Jurid_Tribunais','PLCFULL.dbo.Jurid_Pastas.Tribunal','=','PLCFULL.dbo.Jurid_Tribunais.Codigo')
                   ->join('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                   ->join('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                   ->join('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
                   ->join('dbo.Jurid_Situacao_Ficha', 'PLCFULL.dbo.Jurid_Debite.Numero', '=', 'dbo.Jurid_Situacao_Ficha.numerodebite')
                   ->join('dbo.Jurid_Nota_Motivos', 'dbo.Jurid_Situacao_Ficha.motivo_id', '=', 'dbo.Jurid_Nota_Motivos.id')
                   ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$Numero)
                   ->where('dbo.Jurid_Hist_Ficha.id_status', '=', '8')
                   ->get()
                   ->first();
          
          $dataAprovada= 
                 DB::table('PLCFULL.dbo.Jurid_Debite')
                   ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                           DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS DateTime) as DataFichaAprovada'), 
                           'PLCFULL.dbo.Jurid_Debite.Status as StatusFicha', 
                           'ValorT as ValorTotal', 
                           'DebPago as Pago',
                           'PLCFULL.dbo.Jurid_Debite.Moeda as Moeda',
                           'Pasta', 
                           'PLCFULL.dbo.Jurid_Pastas.PRConta',
                           'PLCFULL.dbo.Jurid_Pastas.UF as UF',
                           'PLCFULL.dbo.Jurid_Pastas.Tribunal as Tribunal',
                           'PLCFULL.dbo.Jurid_Pastas.OutraParte as OutraParte',
                           'PLCFULL.dbo.Jurid_Tribunais.Descricao as DescricaoTribunal',
                           'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumPrc1_Sonumeros',
                           DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                           'dbo.Jurid_Status_Ficha.descricao', 
                           'PLCFULL.dbo.Jurid_Advogado.Razao as Advogado',
                           'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                           'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                           'PLCFULL.dbo.Jurid_CliFor.banco as Banco',
                           'PLCFULL.dbo.Jurid_CliFor.agencia as Agencia',
                           'PLCFULL.dbo.Jurid_CliFor.conta as Conta',
                           'dbo.users.name as Nome')  
                   ->join('dbo.Jurid_Hist_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Hist_Ficha.id_hist')
                   ->join('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Hist_Ficha.id_status', '=', 'dbo.Jurid_Status_Ficha.id')
                   ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                   ->join('PLCFULL.dbo.Jurid_Advogado', 'PLCFULL.dbo.Jurid_Debite.Advogado', '=', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
                   ->join('PLCFULL.dbo.Jurid_CliFor','PLCFULL.dbo.Jurid_Pastas.Advogado','=','PLCFULL.dbo.Jurid_CliFor.Codigo')     
                   ->join('PLCFULL.dbo.Jurid_Tribunais','PLCFULL.dbo.Jurid_Pastas.Tribunal','=','PLCFULL.dbo.Jurid_Tribunais.Codigo')
                   ->join('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                   ->join('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                   ->join('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
                   ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$Numero)
                   ->where('dbo.Jurid_Hist_Ficha.id_status', '=', '7')
                   ->get()
                   ->first();
          
          $dataAprovadaFinanceiro= 
                 DB::table('PLCFULL.dbo.Jurid_Debite')
                   ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                           DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS DateTime) as DataFicha'), 
                           'PLCFULL.dbo.Jurid_Debite.Status as StatusFicha', 
                           'ValorT as ValorTotal', 
                           'DebPago as Pago',
                           'PLCFULL.dbo.Jurid_Debite.Moeda as Moeda',
                           'Pasta', 
                           'PLCFULL.dbo.Jurid_Pastas.PRConta',
                           'PLCFULL.dbo.Jurid_Pastas.UF as UF',
                           'PLCFULL.dbo.Jurid_Pastas.Tribunal as Tribunal',
                           'PLCFULL.dbo.Jurid_Pastas.OutraParte as OutraParte',
                           'PLCFULL.dbo.Jurid_Tribunais.Descricao as DescricaoTribunal',
                           'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumPrc1_Sonumeros',
                           DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                           'dbo.Jurid_Status_Ficha.descricao', 
                           'PLCFULL.dbo.Jurid_Advogado.Razao as Advogado',
                           'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                           'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                           'PLCFULL.dbo.Jurid_CliFor.banco as Banco',
                           'PLCFULL.dbo.Jurid_CliFor.agencia as Agencia',
                           'PLCFULL.dbo.Jurid_CliFor.conta as Conta',
                           'dbo.users.name as Nome')  
                   ->join('dbo.Jurid_Hist_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Hist_Ficha.id_hist')
                   ->join('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Hist_Ficha.id_status', '=', 'dbo.Jurid_Status_Ficha.id')
                   ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                   ->join('PLCFULL.dbo.Jurid_Advogado', 'PLCFULL.dbo.Jurid_Debite.Advogado', '=', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
                   ->join('PLCFULL.dbo.Jurid_CliFor','PLCFULL.dbo.Jurid_Pastas.Advogado','=','PLCFULL.dbo.Jurid_CliFor.Codigo')     
                   ->join('PLCFULL.dbo.Jurid_Tribunais','PLCFULL.dbo.Jurid_Pastas.Tribunal','=','PLCFULL.dbo.Jurid_Tribunais.Codigo')
                   ->join('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                   ->join('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                   ->join('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
                   ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$Numero)
                   ->where('dbo.Jurid_Hist_Ficha.id_status', '=', '12')
                   ->get()
                   ->first();
          
          $dataProgramadaPagamento = 
                 DB::table('PLCFULL.dbo.Jurid_Debite')
                   ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                           DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS DateTime) as DataFicha'), 
                           'PLCFULL.dbo.Jurid_Debite.Status as StatusFicha', 
                           'ValorT as ValorTotal', 
                           'DebPago as Pago',
                           'PLCFULL.dbo.Jurid_Debite.Moeda as Moeda',
                           'Pasta', 
                           'PLCFULL.dbo.Jurid_Pastas.PRConta',
                           'PLCFULL.dbo.Jurid_Pastas.UF as UF',
                           'PLCFULL.dbo.Jurid_Pastas.Tribunal as Tribunal',
                           'PLCFULL.dbo.Jurid_Pastas.OutraParte as OutraParte',
                           'PLCFULL.dbo.Jurid_Tribunais.Descricao as DescricaoTribunal',
                           'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumPrc1_Sonumeros',
                           DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                           'dbo.Jurid_Status_Ficha.descricao', 
                           'PLCFULL.dbo.Jurid_Advogado.Razao as Advogado',
                           'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                           'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                           'PLCFULL.dbo.Jurid_CliFor.banco as Banco',
                           'PLCFULL.dbo.Jurid_CliFor.agencia as Agencia',
                           'PLCFULL.dbo.Jurid_CliFor.conta as Conta',
                           'dbo.users.name as Nome')  
                   ->join('dbo.Jurid_Hist_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Hist_Ficha.id_hist')
                   ->join('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Hist_Ficha.id_status', '=', 'dbo.Jurid_Status_Ficha.id')
                   ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                   ->join('PLCFULL.dbo.Jurid_Advogado', 'PLCFULL.dbo.Jurid_Debite.Advogado', '=', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
                   ->join('PLCFULL.dbo.Jurid_CliFor','PLCFULL.dbo.Jurid_Pastas.Advogado','=','PLCFULL.dbo.Jurid_CliFor.Codigo')     
                   ->join('PLCFULL.dbo.Jurid_Tribunais','PLCFULL.dbo.Jurid_Pastas.Tribunal','=','PLCFULL.dbo.Jurid_Tribunais.Codigo')
                   ->join('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                   ->join('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                   ->join('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
                   ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$Numero)
                   ->where('dbo.Jurid_Hist_Ficha.id_status', '=', '14')
                   ->get()
                   ->first();
          
            $dataPagamento= 
                 DB::table('PLCFULL.dbo.Jurid_Debite')
                   ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                           DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS DateTime) as DataFicha'), 
                           'PLCFULL.dbo.Jurid_Debite.Status as StatusFicha', 
                           'ValorT as ValorTotal', 
                           'DebPago as Pago',
                           'PLCFULL.dbo.Jurid_Debite.Moeda as Moeda',
                           'Pasta', 
                           'PLCFULL.dbo.Jurid_Pastas.PRConta',
                           'PLCFULL.dbo.Jurid_Pastas.UF as UF',
                           'PLCFULL.dbo.Jurid_Pastas.Tribunal as Tribunal',
                           'PLCFULL.dbo.Jurid_Pastas.OutraParte as OutraParte',
                           'PLCFULL.dbo.Jurid_Tribunais.Descricao as DescricaoTribunal',
                           'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumPrc1_Sonumeros',
                           DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                           'dbo.Jurid_Status_Ficha.descricao', 
                           'PLCFULL.dbo.Jurid_Advogado.Razao as Advogado',
                           'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                           'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                           'PLCFULL.dbo.Jurid_CliFor.banco as Banco',
                           'PLCFULL.dbo.Jurid_CliFor.agencia as Agencia',
                           'PLCFULL.dbo.Jurid_CliFor.conta as Conta',
                           'dbo.users.name as Nome')  
                   ->join('dbo.Jurid_Hist_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Hist_Ficha.id_hist')
                   ->join('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Hist_Ficha.id_status', '=', 'dbo.Jurid_Status_Ficha.id')
                   ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                   ->join('PLCFULL.dbo.Jurid_Advogado', 'PLCFULL.dbo.Jurid_Debite.Advogado', '=', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
                   ->join('PLCFULL.dbo.Jurid_CliFor','PLCFULL.dbo.Jurid_Pastas.Advogado','=','PLCFULL.dbo.Jurid_CliFor.Codigo')     
                   ->join('PLCFULL.dbo.Jurid_Tribunais','PLCFULL.dbo.Jurid_Pastas.Tribunal','=','PLCFULL.dbo.Jurid_Tribunais.Codigo')
                   ->join('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                   ->join('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                   ->join('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
                   ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$Numero)
                   ->where('dbo.Jurid_Hist_Ficha.id_status', '=', '9')
                   ->get()
                   ->first();
            
            $dataComprovante= 
                 DB::table('PLCFULL.dbo.Jurid_Debite')
                   ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                           DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS DateTime) as DataFicha'), 
                           'PLCFULL.dbo.Jurid_Debite.Status as StatusFicha', 
                           'ValorT as ValorTotal', 
                           'DebPago as Pago',
                           'PLCFULL.dbo.Jurid_Debite.Moeda as Moeda',
                           'Pasta', 
                           'PLCFULL.dbo.Jurid_Pastas.PRConta',
                           'PLCFULL.dbo.Jurid_Pastas.UF as UF',
                           'PLCFULL.dbo.Jurid_Pastas.Tribunal as Tribunal',
                           'PLCFULL.dbo.Jurid_Pastas.OutraParte as OutraParte',
                           'PLCFULL.dbo.Jurid_Tribunais.Descricao as DescricaoTribunal',
                           'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumPrc1_Sonumeros',
                           DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                           'dbo.Jurid_Status_Ficha.descricao', 
                           'PLCFULL.dbo.Jurid_Advogado.Razao as Advogado',
                           'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                           'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                           'PLCFULL.dbo.Jurid_CliFor.banco as Banco',
                           'PLCFULL.dbo.Jurid_CliFor.agencia as Agencia',
                           'PLCFULL.dbo.Jurid_CliFor.conta as Conta',
                           'dbo.users.name as Nome')  
                   ->join('dbo.Jurid_Hist_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Hist_Ficha.id_hist')
                   ->join('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Hist_Ficha.id_status', '=', 'dbo.Jurid_Status_Ficha.id')
                   ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                   ->join('PLCFULL.dbo.Jurid_Advogado', 'PLCFULL.dbo.Jurid_Debite.Advogado', '=', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
                   ->join('PLCFULL.dbo.Jurid_CliFor','PLCFULL.dbo.Jurid_Pastas.Advogado','=','PLCFULL.dbo.Jurid_CliFor.Codigo')     
                   ->join('PLCFULL.dbo.Jurid_Tribunais','PLCFULL.dbo.Jurid_Pastas.Tribunal','=','PLCFULL.dbo.Jurid_Tribunais.Codigo')
                   ->join('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                   ->join('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                   ->join('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
                   ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$Numero)
                   ->where('dbo.Jurid_Hist_Ficha.id_status', '=', '10')
                   ->get()
                   ->first();
                              
      return view('Painel.Controladoria.SolicitacaoDebite.show', compact('numeroprocesso', 'dataCancelada', 'dataReprovada' ,'dataAprovada', 'dataAprovadaFinanceiro', 'dataProgramadaPagamento', 'dataPagamento', 'dataComprovante'));
    }
    
      public function aprovar($numero)
    {
        //Recupera a ficha
        $nota = $this->model->find($numero);
        
        $title = "Ficha: {$nota->model}";
                
        //Aprova a ficha colocando o RevisadoDB = 1 e automaticamente dando Insert no StatusFicha para o ID 4
       DB::table('PLCFULL.dbo.Jurid_Debite')
        ->where('Numero', $numero)  
        ->limit(1) 
        ->update(array('Revisado_DB' => '1'));
       
                
     $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
             ->join('PLCFULL.dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','PLCFULL.dbo.Jurid_Situacao_Ficha.numerodebite')
             ->join('PLCFULL.dbo.Jurid_Status_Ficha', 'PLCFULL.dbo.Jurid_Situacao_Ficha.status_id', '=', 'PLCFULL.dbo.Jurid_Status_Ficha.id')
             ->join('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
             ->where('PLCFULL.dbo.Jurid_Situacao_Ficha.status_id','=','7')
             ->get();
       return view('Painel.Controladoria.index', compact('title', 'notas'));
       
    }

   public function reprovar($numero)
    {
        //Recupera a ficha
        $nota = $this->model->find($numero);
        
        $title = "Ficha: {$nota->model}";
        
        //dd($notas);
        
        //Reprova a ficha dando Insert no HistFicha para o status ID 2 (Pendente)

       $values = array('id_hist' => $numero, 'id_status' => '8', 'id_user' => $usuarioid, 'data' => $carbon);
       DB::table('PLCFULL.dbo.Jurid_Hist_Ficha')->insert($values);
       
       //Update Status 2(Pendente) na Tabela Situacao
       DB::table('PLCFULL.dbo.Jurid_Situacao_Ficha')
        ->where('numerodebite', $numero)  
        ->limit(1) 
        ->update(array('id_status' => '8'));
       
       $title = "Ficha: {$nota->model}";
        
        $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
             ->join('PLCFULL.dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','PLCFULL.dbo.Jurid_Situacao_Ficha.numerodebite')
             ->join('PLCFULL.dbo.Jurid_Status_Ficha', 'PLCFULL.dbo.Jurid_Situacao_Ficha.status_id', '=', 'PLCFULL.dbo.Jurid_Status_Ficha.id')
             ->join('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
             ->where('PLCFULL.dbo.Jurid_Situacao_Ficha.status_id','=','6')
             ->get();
       return view('Painel.Controladoria.index', compact('title', 'notas'));
    }
 
  
    public function destroy($id)
    {
        //
    }
    
   
}
