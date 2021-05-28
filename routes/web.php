<?php

use Illuminate\Support\Facades\Input;


/******************************************************************
 * Rotas do Painel
 ******************************************************************/
Route::group(['middleware' => 'auth'], function(){


    
    
    //Routes Profiles
    Route::get('/painel/perfil/{id}/usuarios/{userId}/delete', 'Painel\ProfileController@deleteUser')->name('profile.user.delete');
    Route::post('/painel/perfil/{id}/usuarios/cadastrar', 'Painel\ProfileController@usersAddProfile')->name('profile.users.add');
    Route::get('/painel/perfil/{id}/usuarios/cadastrar', 'Painel\ProfileController@usersAdd')->name('profile.users.add');
    Route::get('/painel/perfil/{id}/usuarios', 'Painel\ProfileController@users')->name('profile.users');
    Route::any('/painel/perfis/pesquisar', 'Painel\ProfileController@search')->name('profiles.search');
    Route::resource('/painel/perfis', 'Painel\ProfileController');

    
    
    //Routes Permissions
    Route::any('/painel/permissao/{id}/perfis/search', 'Painel\PermissionController@searchProfile')->name('permissao.profiles.search');
    Route::get('/painel/permissao/{id}/perfis/{profileId}/delete', 'Painel\PermissionController@deleteProfile')->name('permissao.profile.delete');
    Route::post('/painel/permissao/{id}/perfis/cadastrar', 'Painel\PermissionController@profilesAddPermission')->name('permissao.profiles.add');
    Route::get('/painel/permissao/{id}/perfis/cadastrar', 'Painel\PermissionController@profilesAdd')->name('permissao.profiles.add');
    Route::get('/painel/permissao/{id}/perfis', 'Painel\PermissionController@profiles')->name('permissao.perfis');
    Route::any('/painel/permissions/pesquisar', 'Painel\PermissionController@search')->name('permissions.search');
    Route::resource('/painel/permissoes', 'Painel\PermissionController');

    
    //Route::get('/', 'Painel\PainelController@index'); 
    //Route::get('/', 'Painel\PainelController@index')->name('Home.Principal.Show');
    
    
    //Tipos Serviço  Rota Index
    Route::get('/Painel/TiposServico', 'Painel\TiposServico\TipoServicoController@index')->name('Painel.TipoServico.index');
    Route::get('/Painel/TiposServico/create', 'Painel\TiposServico\TipoServicoController@create')->name('Painel.TipoServico.create');
    Route::get('/Painel/TiposServico/store', 'Painel\TiposServico\TipoServicoController@store')->name('Painel.TipoServico.store');
    Route::get('/Painel/TiposServico/{id}/show', 'Painel\TiposServico\TipoServicoController@show')->name('Painel.TipoServico.show');
    Route::get('/Painel/TiposServico/{id}/edit', 'Painel\TiposServico\TipoServicoController@edit')->name('Painel.TipoServico.edit');
    Route::get('/Painel/TiposServico/{id}/delete', 'Painel\TiposServico\TipoServicoController@delete')->name('Painel.TipoServico.delete');
    Route::get('/Painel/TiposServico/{id}/update', 'Painel\TiposServico\TipoServicoController@update')->name('Painel.TipoServico.update');
    
    //Moeda Rota Index
    Route::get('/Painel/Moeda', 'Painel\Moeda\MoedaController@index')->name('Painel.Moeda.index');
    Route::get('/Painel/Moeda/create', 'Painel\Moeda\MoedaController@create')->name('Painel.Moeda.create');
    Route::get('/Painel/Moeda/store', 'Painel\Moeda\MoedaController@store')->name('Painel.Moeda.store');
    Route::get('/Painel/Moeda/{Codigo}/show', 'Painel\Moeda\MoedaController@show')->name('Painel.Moeda.show');
    Route::get('/Painel/Moeda/{Codigo}/edit', 'Painel\Moeda\MoedaController@edit')->name('Painel.Moeda.edit');
    Route::get('/Painel/Moeda/{Codigo}/delete', 'Painel\Moeda\MoedaController@delete')->name('Painel.Moeda.delete');
    Route::get('/Painel/Moeda/{Codigo}/update', 'Painel\Moeda\MoedaController@update')->name('Painel.Moeda.update');
        
    
    //Setor de Custo Rota Index
    Route::any('/painel/setorcusto/{id}/usuarios/search', 'Painel\SetorCustoController@searchUser')->name('setorcusto.users.search');
    Route::get('/painel/setorcusto/{setor_id}/setor/{userId}/usuarios/delete', 'Painel\SetorCustoController@deleteUser')->name('setorcusto.user.delete');
    Route::post('/painel/setorcusto/{id}/usuarios/cadastrar', 'Painel\SetorCustoController@usersAddProfile')->name('setorcusto.users.add');
    Route::get('/painel/setorcusto/{id}/usuarios/cadastrar', 'Painel\SetorCustoController@usersAdd')->name('setorcusto.users.add');
    Route::get('/painel/setorcusto/{id}/usuarios', 'Painel\SetorCustoController@users')->name('setorcusto.users');
    Route::any('/painel/setorcusto/pesquisar', 'Painel\SetorCustoController@search')->name('setorcusto.search');
    Route::resource('/painel/setorcusto', 'Painel\SetorCustoController');
    
    //Status Ficha Rota Index
    Route::get('/Painel/StatusFicha', 'Painel\StatusFicha\StatusFichaController@index')->name('Painel.StatusFicha.index');
    Route::get('/Painel/StatusFicha/create', 'Painel\StatusFicha\StatusFichaController@create')->name('Painel.StatusFicha.create');
    Route::get('/Painel/StatusFicha/{Id}/show', 'Painel\StatusFicha\StatusFichaController@show')->name('Painel.StatusFicha.show');
    Route::get('/Painel/StatusFicha/{Id}/edit', 'Painel\StatusFicha\StatusFichaController@edit')->name('Painel.StatusFicha.edit');
    Route::get('/Painel/StatusFicha/{Id}/delete', 'Painel\StatusFicha\StatusFichaController@delete')->name('Painel.StatusFicha.delete');

    //Pasta Rota Index
    Route::get('/Painel/Pasta', 'Painel\Pasta\PastaController@index')->name('Painel.Pasta.index');
    Route::get('/Painel/Pasta/{Codigo_Comp}/show', 'Painel\PastaController\Pasta@show')->name('Painel.Pasta.show');
    
    //Notificações Rota Home
    Route::get('/Painel/Notificacao/{id}/{numerodebite}/update', 'Painel\Notificacao\NotificacaoController@update')->name('Painel.Notificacao.update');
    Route::get('/Painel/Notificacao', 'Painel\Notificacao\NotificacaoController@index')->name('Painel.Notificacao.index');
    Route::get('/Painel/Notificacao/{id}/update', 'Painel\Notificacao\NotificacaoController@update')->name('Painel.Notificacao.update2');
    Route::get('/Painel/Notificacao/gerarexcelrecebidas', 'Painel\Notificacao\NotificacaoController@gerarNotificacoesRecebidas')->name('Painel.Notificacao.gerarNotificacoesRecebidas');
    Route::get('/Painel/Notificacao/gerarexcelenviadas', 'Painel\Notificacao\NotificacaoController@gerarNotificacoesEnviadas')->name('Painel.Notificacao.gerarNotificacoesEnviadas');


    //Controladoria Rota Index
    Route::get('/Painel/Controladoria', 'Painel\Controladoria\ControladoriaController@index')->name('Painel.Controladoria.index');
    Route::get('/Painel/Controladoria/aprovadas', 'Painel\Controladoria\ControladoriaController@aprovadas')->name('Painel.Controladoria.aprovadas');
    Route::get('/Painel/Controladoria/{Numero}/aprovar', 'Painel\Controladoria\ControladoriaController@aprovar')->name('Painel.Controladoria.aprovar');
    Route::get('/Painel/Controladoria/{Numero}/reprovar', 'Painel\Controladoria\ControladoriaController@reprovar')->name('Painel.Controladoria.reprovar');
    Route::get('/Painel/Controladoria/{Numero}/anexos', 'Painel\Controladoria\ControladoriaController@anexos')->name('Painel.Controladoria.anexos');
    Route::get('/Painel/Controladoria/{Numero}/show', 'Painel\Controladoria\ControladoriaController@show')->name('Painel.Controladoria.show');
    
    //Force 1
    Route::get('/painel/ti/force1/index', 'Painel\TI\TIController@forceone_index')->name('Painel.TI.ForceOne.index');
    Route::get('/painel/ti/force1/gravardados/{data}', 'Painel\TI\TIController@forceOneSalvarDados')->name('Painel.TI.ForceOne.salvardados');

    
Route::get('/painel/marketing/sorteio/index', 'Painel\Marketing\MarketingController@sorteio_index')->name('Painel.Marketing.Sorteio.index');
Route::get('/painel/marketing/sorteio/{id}/realizarsorteio', 'Painel\Marketing\MarketingController@sorteio_realizarsorteio')->name('Painel.Marketing.Sorteio.realizarsorteio');
Route::post('/painel/marketing/sorteio/vencedor', 'Painel\Marketing\MarketingController@sorteio_vencedor')->name('Painel.Marketing.Sorteio.vencedor');

/******************************************************************
 * End Rota Marketing
 ******************************************************************/
 

/******************************************************************
 * Rotas do T.I
 ******************************************************************/
/******************************************************************
 * Rotas do T.I
 ******************************************************************/
Route::get('/painel/ti/dashboard', 'Painel\TI\PainelController@index')->name('Painel.TI.principal');
Route::get('/painel/ti/usuarios', 'Painel\UserController@index')->name('Painel.TI.users.index');
// Route::get('/painel/tI/usuarios/create', 'Painel\UserController@create')->name('Painel.TI.users.create');
Route::post('/painel/ti/usuarios/salvarnewusuario', 'Painel\UserController@salvarnewusuario')->name('Painel.TI.users.salvarnewusuario');
Route::post('/painel/tI/usuarios/importacaomassa', 'Painel\UserController@usuarios_importacaomassa')->name('Painel.TI.users.importacao');
Route::get('/painel/ti/tarefasagendadas', 'Painel\TI\TIController@tarefasagendadas_index')->name('Painel.TI.tarefasagendadas_index');
Route::get('/painel/ti/{id_matrix}/tarefasagendadas', 'Painel\TI\TIController@tarefasagendadas_hist')->name('Painel.TI.tarefasagendadas_hist');

Route::get('/painel/ti/tarefasagendadas', 'Painel\TI\TIController@tarefasagendadas_index')->name('Painel.TI.tarefasagendadas_index');
Route::get('/painel/ti/{id_matrix}/tarefasagendadas', 'Painel\TI\TIController@tarefasagendadas_hist')->name('Painel.TI.tarefasagendadas_hist');
Route::get('/painel/ti/chat', 'Painel\TI\TIController@chat_index')->name('Painel.TI.chat.index');
Route::post('/painel/ti/chat/enviamensagem', 'Painel\TI\TIController@chat_enviamensagem')->name('Painel.TI.chat.enviamensagem');
Route::post('/painel/ti/chat/trazmensagens', 'Painel\TI\TIController@chat_trazmensagens')->name('Painel.TI.chat.trazmensagens');
Route::post('/painel/ti/chat/buscacontato', 'Painel\TI\TIController@chat_buscacontato')->name('Painel.TI.chat.buscacontato');

Route::get('/painel/ti/correspodente', 'Painel\TI\TIController@correspondente_index')->name('Painel.TI.correspondente.index');
Route::get('/painel/ti/correspodente/{id}/notificacoes', 'Painel\TI\TIController@correspondente_notificacoes')->name('Painel.TI.correspondente.notificacoes');
Route::get('/painel/ti/correspondente/gerarExcelAbertas', 'Painel\TI\TIController@correspondente_gerarExcelAbertas')->name('Painel.TI.correspondente.gerarExcelAbertas');
       
Route::get('/painel/ti/agendamento/index', 'Painel\TI\TIController@agendamentomesa_index')->name('Painel.TI.agendamentomesa.index');
Route::get('/painel/ti/agendamento/agendar', 'Painel\TI\TIController@agendamentomesa_agendar')->name('Painel.TI.agendamentomesa.agendar');
Route::post('/painel/ti/agendamento/locaisdisponiveis', 'Painel\TI\TIController@agendamentomesa_mesasdisponiveis')->name('Painel.TI.agendamentomesa.mesasdisponiveis');
Route::post('/painel/ti/agendamento/agendamesa', 'Painel\TI\TIController@agendamentomesa_agendamesa')->name('Painel.TI.agendamentomesa.agendamesa');
Route::post('/painel/ti/agendamento/agendamentoreuniao', 'Painel\TI\TIController@agendamentomesa_agendamentoreuniao')->name('Painel.TI.agendamentomesa.agendamentoreuniao');
Route::post('/painel/ti/agendamento/agendamentocancelado', 'Painel\TI\TIController@agendamentomesa_agendamentocancelado')->name('Painel.TI.agendamentomesa.agendamentocancelado');
Route::get('/painel/ti/agendamento/agenda', 'Painel\TI\TIController@agendamentomesa_agenda')->name('Painel.TI.agendamentomesa.agenda');
Route::post('/painel/ti/agendamento/buscardados', 'Painel\TI\TIController@agendamentomesa_buscardados')->name('Painel.TI.agendamentomesa.buscardados');


Route::get('/painel/ti/agendamento/restrito/index', 'Painel\TI\TIController@agendamentomesa_restrito_index')->name('Painel.TI.agendamentomesa.restrito.index');

Route::get('/painel/ti/agendamento/restrito/salas/listagem', 'Painel\TI\TIController@agendamentomesa_restrito_salas_index')->name('Painel.TI.agendamentomesa.restrito.salas.index');
Route::get('/painel/ti/agendamento/restrito/salas/novasala', 'Painel\TI\TIController@agendamentomesa_restrito_salas_novasala')->name('Painel.TI.agendamentomesa.restrito.salas.novasala');
Route::post('/painel/ti/agendamento/restrito/salas/salacriada', 'Painel\TI\TIController@agendamentomesa_restrito_salas_salacriada')->name('Painel.TI.agendamentomesa.restrito.salas.salacriada');

Route::get('/painel/ti/agendamento/restrito/{id}/mesas', 'Painel\TI\TIController@agendamentomesa_restrito_mesas')->name('Painel.TI.agendamentomesa.restrito.mesas.index');
Route::get('/painel/ti/agendamento/restrito/{id}/mesas/agenda', 'Painel\TI\TIController@agendamentomesa_restrito_mesas_agenda')->name('Painel.TI.agendamentomesa.restrito.mesas.agenda');
Route::get('/painel/ti/agendamento/restrito/{id}/mesas/agendareuniao', 'Painel\TI\TIController@agendamentomesa_restrito_mesas_agendareuniao')->name('Painel.TI.agendamentomesa.restrito.mesas.agendareuniao');
Route::get('/painel/ti/agendamento/restrito/{id}/novamesa', 'Painel\TI\TIController@agendamentomesa_restrito_novamesa')->name('Painel.TI.agendamentomesa.restrito.create');
Route::post('/painel/ti/agendamento/restrito/store', 'Painel\TI\TIController@agendamentomesa_restrito_gravanovamesa')->name('Painel.TI.agendamentomesa.restrito.gravanovamesa');

Route::post('/painel/ti/usuario/novasenha', 'Painel\TI\TIController@usuario_novasenha')->name('Painel.TI.HomePage.NovaSenha');


//Routes Users
Route::any('usuarios/pesquisar', 'Painel\UserController@search')->name('usuarios.search');
Route::resource('usuarios', 'Painel\UserController');
Route::post('usuarios/cadastrarcorrespondente', 'Painel\UserController@storecorrespondente')->name('usuarios.storecorrespondentemodal');
Route::post('/painel/ti/usuario/alterarsenha', 'Painel\TI\TIController@usuario_alterarsenha')->name('Painel.TI.usuario.alterarsenha');
Route::post('/painel/ti/usuario/alterarstatus', 'Painel\TI\TIController@usuario_alterarstatus')->name('Painel.TI.usuario.alterarstatus');

Route::post('/painel/ti/usuario/salvar-new-usuario', 'Painel\TI\TIController@salvarnewusuario')->name('Painel.TI.usuario.salvarnewusuario');
 
 /******************************************************************
 * Rotas do DP/RH
 ******************************************************************/
 Route::get('/painel/dprh/dashboard', 'Painel\DPRH\PainelController@index')->name('Painel.DPRH.principal');
 Route::get('/painel/dprh/colaboradores/ativos', 'Painel\DPRH\DPRHController@index')->name('Painel.DPRH.Colaboradores.index');
 Route::get('/painel/dprh/colaboradores/desativados', 'Painel\DPRH\DPRHController@desativados')->name('Painel.DPRH.Colaboradores.desativados');
 Route::get('/painel/dprh/colaboradores/gerarExcelAtivos', 'Painel\DPRH\DPRHController@gerarExcelAtivos')->name('Painel.DPRH.Colaboradores.gerarExcelAtivos');
 Route::get('/painel/dprh/colaboradores/gerarExcelInativos', 'Painel\DPRH\DPRHController@gerarExcelInativos')->name('Painel.DPRH.Colaboradores.gerarExcelInativos');

 /******************************************************************
 * End Rota DP/RH
 ******************************************************************/


/******************************************************************
 * Rotas da Pesquisa Patrimonial
 ******************************************************************/
Route::get('/painel/pesquisapatrimonial/solicitacao/index', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_index')->name('Painel.PesquisaPatrimonial.solicitacao.index');
Route::get('/painel/pesquisapatrimonial/solicitacao/create', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_create')->name('Painel.PesquisaPatrimonial.solicitacao.create');
Route::get('/painel/pesquisapatrimonial/solicitacao/minhassolicitacoes', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_solicitacoes')->name('Painel.PesquisaPatrimonial.solicitacao.solicitacoes');
Route::get('/painel/pesquisapatrimonial/solicitacao/solicitacoesemandamento', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_solicitacoesemandamento')->name('Painel.PesquisaPatrimonial.solicitacao.solicitacoesemandamento');
Route::get('/painel/pesquisapatrimonial/solicitacao/solicitacoesfinalizadas', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_solicitacoesfinalizadas')->name('Painel.PesquisaPatrimonial.solicitacao.solicitacoesfinalizadas');
Route::post('/painel/pesquisapatrimonial/solicitacao/store', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_store')->name('Painel.PesquisaPatrimonial.solicitacao.store');
Route::get('/painel/pesquisapatrimonial/solicitacao/pesquisaprevia/{id}/visualizarpesquisa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitante_pesquisaprevia_visualizarpesquisa')->name('Painel.PesquisaPatrimonial.solicitacao.pesquisaprevia.visualizarpesquisa');
Route::get('/painel/pesquisapatrimonial/solicitacao/pesquisaprevia/{id}/{tiposervico}/abas/aba', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitante_pesquisaprevia_aba')->name('Painel.PesquisaPatrimonial.solicitacao.pesquisaprevia.abas.aba');
Route::post('/painel/pesquisapatrimonial/solicitacao/buscaclientes', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_buscaclientes')->name('Painel.PesquisaPatrimonial.solicitacao.buscaclientes');

Route::post('/painel/pesquisapatrimonial/buscapesquisado', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@buscapesquisado')->name('Painel.PesquisaPatrimonial.buscapesquisado');


Route::post('/painel/pesquisapatrimonial/solicitacao/buscapesquisado', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_buscapesquisado')->name('Painel.PesquisaPatrimonial.solicitacao.buscapesquisado');
Route::post('/painel/pesquisapatrimonial/solicitacao/buscapesquisado2', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_buscapesquisado2')->name('Painel.PesquisaPatrimonial.solicitacao.buscapesquisado2');


Route::get('/painel/pesquisapatrimonial/solicitacao/{codigo}/buscacapa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_buscacapa')->name('Painel.PesquisaPatrimonial.solicitacao.buscacapa');


Route::get('/painel/pesquisapatrimonial/solicitacao/{codigo}/capa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_capa')->name('Painel.PesquisaPatrimonial.solicitacao.capa');
Route::get('/painel/pesquisapatrimonial/solicitacao/{codigo}/trazpesquisado', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_trazpesquisado')->name('Painel.PesquisaPatrimonial.solicitacao.trazpesquisado');
Route::get('/painel/pesquisapatrimonial/nucleo/{codigo}/capa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_capa')->name('Painel.PesquisaPatrimonial.solicitacao.capa');
Route::get('/painel/pesquisapatrimonial/supervisao/{codigo}/capa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_capa')->name('Painel.PesquisaPatrimonial.solicitacao.capa');
Route::get('/painel/pesquisapatrimonial/financeiro/{codigo}/capa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_capa')->name('Painel.PesquisaPatrimonial.solicitacao.capa');

Route::get('/painel/pesquisapatrimonial/financeiro/indexprestacao', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@indexprestacao')->name('Painel.PesquisaPatrimonial.financeiro.indexprestacao');
Route::post('/painel/pesquisapatrimonial/financeiro/prestacaodecontas', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@prestacaodecontas')->name('Painel.PesquisaPatrimonial.financeiro.prestacaodecontas');
Route::post('/painel/pesquisapatrimonial/financeiro/buscacliente', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@prestacaodecontas_buscacliente')->name('Painel.PesquisaPatrimonial.financeiro.prestacaodecontas.buscacliente');


Route::get('/painel/pesquisapatrimonial/tiposdeservico', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@tiposdeservico')->name('Painel.PesquisaPatrimonial.tiposdeservico');
Route::get('/painel/pesquisapatrimonial/tiposdeservico/create', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@tiposdeservico_create')->name('Painel.PesquisaPatrimonial.tiposdeservico.create');
Route::post('/painel/pesquisapatrimonial/tiposdeservico/store', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@tiposdeservico_store')->name('Painel.PesquisaPatrimonial.tiposdeservico.store');

Route::get('/painel/pesquisapatrimonial/nucleo/index', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_index')->name('Painel.PesquisaPatrimonial.nucleo.index');
Route::get('/painel/pesquisapatrimonial/nucleo/create', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_create')->name('Painel.PesquisaPatrimonial.Nucleo.create');
Route::post('/painel/pesquisapatrimonial/nucleo/store', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_store')->name('Painel.PesquisaPatrimonial.Nucleo.store');


Route::get('/painel/pesquisapatrimonial/nucleo/{id}/informacoes', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_informacoes')->name('Painel.PesquisaPatrimonial.Nucleo.informacoes');
Route::post('/painel/pesquisapatrimonial/nucleo/atualizarinformacoes', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_storeinformacoes')->name('Painel.PesquisaPatrimonial.Nucleo.storeinformacoes');

Route::get('/painel/pesquisapatrimonial/nucleo/solicitacoes', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_solicitacoes')->name('Painel.PesquisaPatrimonial.nucleo.solicitacoes');
Route::get('/painel/pesquisapatrimonial/nucleo/solicitacoesaguardandomontagemdafichafinanceira', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_solicitacoesaguardandoficha')->name('Painel.PesquisaPatrimonial.nucleo.solicitacoesaguardandoficha');
Route::get('/painel/pesquisapatrimonial/nucleo/solicitacoesaguardandocliente', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_solicitacoesaguardandocliente')->name('Painel.PesquisaPatrimonial.nucleo.solicitacoesaguardandocliente');
Route::get('/painel/pesquisapatrimonial/nucleo/solicitacoesaguardandorevisaosupervisor', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_solicitacoesaguardandorevisaosupervisor')->name('Painel.PesquisaPatrimonial.nucleo.solicitacoesaguardandorevisaosupervisor');
Route::get('/painel/pesquisapatrimonial/nucleo/solicitacoesaguardandorevisaofinanceiro', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_solicitacoesaguardandorevisaofinanceiro')->name('Painel.PesquisaPatrimonial.nucleo.solicitacoesaguardandorevisaofinanceiro');
Route::get('/painel/pesquisapatrimonial/nucleo/solicitacoesnaocobravel', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_solicitacoesnaocobravel')->name('Painel.PesquisaPatrimonial.nucleo.solicitacoesnaocobravel');
Route::get('/painel/pesquisapatrimonial/nucleo/solicitacoesaguardandofinanceiro', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_solicitacoesaguardandofinanceiro')->name('Painel.PesquisaPatrimonial.nucleo.solicitacoesaguardandofinanceiro');
Route::get('/painel/pesquisapatrimonial/nucleo/solicitacoesemandamento', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_solicitacoesemandamento')->name('Painel.PesquisaPatrimonial.nucleo.solicitacoesemandamento');
Route::get('/painel/pesquisapatrimonial/nucleo/solicitacoesfinalizadas', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_solicitacoesfinalizadas')->name('Painel.PesquisaPatrimonial.nucleo.solicitacoesfinalizadas');
Route::get('/painel/pesquisapatrimonial/nucleo/solicitacoesreprovadas', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_solicitacoesreprovadas')->name('Painel.PesquisaPatrimonial.nucleo.solicitacoesreprovadas');
Route::get('/painel/pesquisapatrimonial/nucleo/solicitacoesaguardandoanexonucleo', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_solicitacoesaguardandoanexonucleo')->name('Painel.PesquisaPatrimonial.nucleo.solicitacoesaguardandoanexonucleo');

Route::post('/painel/pesquisapatrimonial/nucleo/buscaboletogerado', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_buscaboletogerado')->name('Painel.PesquisaPatrimonial.Nucleo.buscaboletogerado');

Route::post('/painel/pesquisapatrimonial/nucleo/removersolicitacao', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_removersolicitacao')->name('Painel.PesquisaPatrimonial.Nucleo.removesolicitacao');

Route::get('/painel/pesquisapatrimonial/{Numero}/anexo', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@anexo')->name('Painel.PesquisaPatrimonial.anexo');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/realizarpesquisa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@step1')->name('Painel.PesquisaPatrimonial.step1');

Route::get('/painel/pesquisapatrimonial/nucleo/pesquisaprevia/{id}/abas', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_pesquisaprevia_abas')->name('Painel.PesquisaPatrimonial.nucleo.pesquisaprevia.abas');
Route::post('/painel/pesquisapatrimonial/nucleo/pesquisaprevia/finalizarpesquisa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_pesquisaprevia_finalizarpesquisa')->name('Painel.PesquisaPatrimonial.nucleo.pesquisaprevia.finalizarpesquisa');

Route::get('/painel/pesquisapatrimonial/nucleo/pesquisaprevia/{id}/{tiposervico}/aba', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_pesquisaprevia_aba')->name('Painel.PesquisaPatrimonial.nucleo.pesquisaprevia.abas.aba');
Route::get('/painel/pesquisapatrimonial/nucleo/pesquisaprevia/{id}/cadastrarimovel', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_pesquisaprevia_cadastrarimovel')->name('Painel.PesquisaPatrimonial.nucleo.pesquisaprevia.cadastrar.imovel');
Route::post('/painel/pesquisapatrimonial/nucleo/pesquisaprevia/cadastrarimovelstore', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_pesquisaprevia_cadastrarimovel_store')->name('Painel.PesquisaPatrimonial.nucleo.pesquisaprevia.cadastrar.imovelstore');

Route::get('/painel/pesquisapatrimonial/nucleo/pesquisaprevia/{id}/cadastrarveiculo', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_pesquisaprevia_cadastrarveiculo')->name('Painel.PesquisaPatrimonial.nucleo.pesquisaprevia.cadastrar.veiculo');
Route::post('/painel/pesquisapatrimonial/nucleo/pesquisaprevia/cadastrarveiculostore', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_pesquisaprevia_cadastrarveiculo_store')->name('Painel.PesquisaPatrimonial.nucleo.pesquisaprevia.cadastrar.veiculostore');

Route::get('/painel/pesquisapatrimonial/nucleo/pesquisaprevia/{id}/cadastrarempresa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_pesquisaprevia_cadastrarempresa')->name('Painel.PesquisaPatrimonial.nucleo.pesquisaprevia.cadastrar.empresa');
Route::post('/painel/pesquisapatrimonial/nucleo/pesquisaprevia/cadastrarempresastore', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_pesquisaprevia_cadastrarempresa_store')->name('Painel.PesquisaPatrimonial.nucleo.pesquisaprevia.cadastrar.empresastore');

Route::get('/painel/pesquisapatrimonial/nucleo/boleto/index', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_boleto_index')->name('Painel.PesquisaPatrimonial.nucleo.boleto.index');
Route::post('/painel/pesquisapatrimonial/nucleo/boleto/programado', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_boleto_programado')->name('Painel.PesquisaPatrimonial.nucleo.boleto.programado');
Route::get('/painel/pesquisapatrimonial/nucleo/boleto/{CPR}/baixarboleto', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_boleto_baixarboleto')->name('Painel.PesquisaPatrimonial.nucleo.boleto.baixarboleto');
Route::get('/painel/pesquisapatrimonial/nucleo/boleto/boletosemandamento', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_boleto_boletosemandamento')->name('Painel.PesquisaPatrimonial.nucleo.boleto.boletosemandamento');
Route::get('/painel/pesquisapatrimonial/nucleo/boleto/{CPR}/baixarnotadebito', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_boleto_baixarnotadebito')->name('Painel.PesquisaPatrimonial.nucleo.boleto.baixarnotadebito');
Route::get('/painel/pesquisapatrimonial/nucleo/boleto/{codigo}/informacoes', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_boleto_informacoes')->name('Painel.PesquisaPatrimonial.nucleo.boleto.informacoes');
Route::post('/painel/pesquisapatrimonial/nucleo/boleto/atualizarinformacoes', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_boleto_storeinformacoes')->name('Painel.PesquisaPatrimonial.nucleo.boleto.storeinformacoes');


Route::get('/painel/pesquisapatrimonial/nucleo/{id}/{id_matrix}/finalizaraba', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_finalizaraba')->name('Painel.PesquisaPatrimonial.nucleo.finalizaraba');

Route::post('/painel/pesquisapatrimonial/store1', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@store1')->name('Painel.PesquisaPatrimonial.store1');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/status', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@outraparte')->name('Painel.PesquisaPatrimonial.outraparte');
Route::post('/painel/pesquisapatrimonial/nucleo/storeoutraparte', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@storeoutraparte')->name('Painel.PesquisaPatrimonial.storeoutraparte');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/{id}/editarstatus', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_editaroutraparte')->name('Painel.PesquisaPatrimonial.nucleo.editaroutraparte');

Route::post('/painel/pesquisapatrimonial/updateoutraparte', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@updateoutraparte')->name('Painel.PesquisaPatrimonial.updateoutraparte');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/imovel', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@imovel')->name('Painel.PesquisaPatrimonial.imovel');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/{id}/editarimovel', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_editarimovel')->name('Painel.PesquisaPatrimonial.nucleo.editarimovel');
Route::post('/painel/pesquisapatrimonial/updateimovel', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@updateimovel')->name('Painel.PesquisaPatrimonial.updateimovel');
Route::post('/painel/pesquisapatrimonial/nucleo/storeimovel', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@storeimovel')->name('Painel.PesquisaPatrimonial.storeimovel');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/veiculo', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@veiculo')->name('Painel.PesquisaPatrimonial.veiculo');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/{id}/editarveiculo', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_editarveiculo')->name('Painel.PesquisaPatrimonial.nucleo.editarveiculo');
Route::post('/painel/pesquisapatrimonial/updateveiculo', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@updateveiculo')->name('Painel.PesquisaPatrimonial.updateveiculo');
Route::post('/painel/pesquisapatrimonial/nucleo/storeveiculo', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@storeveiculo')->name('Painel.PesquisaPatrimonial.storeveiculo');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/empresa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@empresa')->name('Painel.PesquisaPatrimonial.empresa');
Route::get('/painel/pesquisapatrimonial/{Codigo}/{Numero}/{id}/editempresa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_editarempresa')->name('Painel.PesquisaPatrimonial.nucleo.editarempresa');
Route::post('/painel/pesquisapatrimonial/nucleo/storeempresa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@storeempresa')->name('Painel.PesquisaPatrimonial.storeempresa');
Route::post('/painel/pesquisapatrimonial/updateempresa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@updateempresa')->name('Painel.PesquisaPatrimonial.updateempresa');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/infojud', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@infojud')->name('Painel.PesquisaPatrimonial.infojud');
Route::post('/painel/pesquisapatrimonial/nucleo/storeinfojud', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@storeinfojud')->name('Painel.PesquisaPatrimonial.storeinfojud');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/bacenjud', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@bacenjud')->name('Painel.PesquisaPatrimonial.bacenjud');
Route::post('/painel/pesquisapatrimonial/nucleo/storebacenjud', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@storebacenjud')->name('Painel.PesquisaPatrimonial.storebacenjud');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/notas', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@protestos')->name('Painel.PesquisaPatrimonial.notas');
Route::post('/painel/pesquisapatrimonial/nucleo/storenotas', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@storenotas')->name('Painel.PesquisaPatrimonial.storenotas');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/redessociais', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@redessociais')->name('Painel.PesquisaPatrimonial.redessociais');
Route::post('/painel/pesquisapatrimonial/nucleo/storeredessociais', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@storeredessocias')->name('Painel.PesquisaPatrimonial.storeredessocias');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/tribunal', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@tribunal')->name('Painel.PesquisaPatrimonial.tribunal');
Route::post('/painel/pesquisapatrimonial/nucleo/storetribunal', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@storetribunal')->name('Painel.PesquisaPatrimonial.storetribunal');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/comercial', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@comercial')->name('Painel.PesquisaPatrimonial.comercial');
Route::post('/painel/pesquisapatrimonial/nucleo/storecomercial', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@storecomercial')->name('Painel.PesquisaPatrimonial.storecomercial');
Route::get('/painel/pesquisapatrimonial/{Numero}/dados', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@dados')->name('Painel.PesquisaPatrimonial.dados');
Route::get('/painel/pesquisapatrimonial/nucleo/{Numero}/dadosview', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@viewdados')->name('Painel.PesquisaPatrimonial.viewdados');
Route::post('/painel/pesquisapatrimonial/nucleo/storepesquisa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@storepesquisa')->name('Painel.PesquisaPatrimonial.storepesquisa');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/pesquisa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@pesquisa')->name('Painel.PesquisaPatrimonial.pesquisa');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/diversos', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@diversos')->name('Painel.PesquisaPatrimonial.diversos');
Route::post('/painel/pesquisapatrimonial/nucleo/storediversos', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@storediversos')->name('Painel.PesquisaPatrimonial.storediversos');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/moeda', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@moeda')->name('Painel.PesquisaPatrimonial.moeda');
Route::post('/painel/pesquisapatrimonial/nucleo/storemoeda', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@storemoeda')->name('Painel.PesquisaPatrimonial.storemoeda');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/joias', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@joias')->name('Painel.PesquisaPatrimonial.joias');
Route::post('/painel/pesquisapatrimonial/nucleo/storejoias', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@storejoias')->name('Painel.PesquisaPatrimonial.storejoias');

Route::post('dynamicdependent/fetch2', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@fetch2')->name('dynamicdependent.fetch2');
Route::post('dynamicdependent/fetch3', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@fetch3')->name('dynamicdependent.fetch3');
Route::post('dynamicdependent/fetch4', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@fetch4')->name('dynamicdependent.fetch4'); 
Route::post('dynamicdependent/fetch6', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@fetch6')->name('dynamicdependent.fetch6'); 
Route::post('dynamicdependent/fetch7', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@fetch7')->name('dynamicdependent.fetch7'); 
Route::post('dynamicdependent/fetch8', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@fetch8')->name('dynamicdependent.fetch8'); 
Route::post('dynamicdependent/fetch9', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@fetch9')->name('dynamicdependent.fetch9'); 

Route::post('dynamicdependent/fecthtiposservico', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@fecthtiposservico')->name('dynamicdependent.fecthtiposservico');
Route::post('dynamicdependent/fecthtaxaservico', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@fecthtaxaservico')->name('dynamicdependent.fecthtaxaservico');
Route::post('dynamicdependent/buscaunidadecliente', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@buscaunidadecliente')->name('dynamicdependent.buscaunidadecliente');
Route::post('dynamicdependent/buscavalorestotal', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@buscavalorestotal')->name('dynamicdependent.buscavalorestotal');
Route::post('dynamicdependent/buscaemailcliente', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@buscaemailcliente')->name('dynamicdependent.buscaemailcliente');
Route::post('dynamicdependent/fetch5', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@fetch5')->name('dynamicdependent.fetch5');
Route::post('dynamicdependent/fetch50', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@fetch50')->name('dynamicdependent.fetch50');

Route::get('/painel/pesquisapatrimonial/{Numero}/cadastrooutraparte', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@cadastrooutraparte')->name('Painel.PesquisaPatrimonial.cadastrar');
Route::get('/painel/pesquisapatrimonial/{Numero}/editaroutraparte', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@editaroutraparte')->name('Painel.PesquisaPatrimonial.editaroutraparte');
Route::post('/painel/pesquisapatrimonial/storedadosoutraparte', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@storedadosoutraparte')->name('Painel.PesquisaPatrimonial.storedadosoutraparte');

Route::get('/painel/pesquisapatrimonial/exportarsolicitacoes', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@exportarsolicitacoes')->name('Painel.PesquisaPatrimonial.exportarsolicitacoes');
Route::post('/painel/pesquisapatrimonial/storedadosoutraparteedicao', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@storedadosoutraparteedicao')->name('Painel.PesquisaPatrimonial.storedadosoutraparteedicao');
Route::get('/painel/pesquisapatrimonial/{Numero}/timeline', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@timeline')->name('Painel.PesquisaPatrimonial.timeline');
Route::get('/painel/pesquisapatrimonial/{Numero}/anexararquivos', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@anexararquivos')->name('Painel.PesquisaPatrimonial.anexararquivos');
Route::post('/painel/pesquisapatrimonial/storeanexararquivos', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@storeanexararquivos')->name('Painel.PesquisaPatrimonial.storeanexararquivos');
Route::post('/painel/pesquisapatrimonial/storeanexararquivos2', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@storeanexararquivos2')->name('Painel.PesquisaPatrimonial.storeanexararquivos2');

Route::get('/painel/pesquisapatrimonial/preenchetabela', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@preenchetabela')->name('Painel.PesquisaPatrimonial.preenchetabela');
Route::get('/painel/pesquisapatrimonial/{Numero}/buscapesquisa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@buscapesquisa')->name('Painel.PesquisaPatrimonial.buscapesquisa');
Route::get('/painel/pesquisapatrimonial/{Numero}/continuapesquisa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@continuapesquisa')->name('Painel.PesquisaPatrimonial.continuapesquisa');
Route::get('/painel/pesquisapatrimonial/{Numero}/ficha', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@ficha')->name('Painel.PesquisaPatrimonial.ficha');
Route::get('/painel/pesquisapatrimonial/gerarexcelminhassolicitacoes', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@gerarexcelminhassolicitacoes')->name('Painel.PesquisaPatrimonial.gerarexcelminhassolicitacoes');
Route::get('/painel/pesquisapatrimonial/{Numero}/gerarexcelimovel', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@gerarexcelimovel')->name('Painel.PesquisaPatrimonial.gerarexcelimovel');
Route::get('/painel/pesquisapatrimonial/{Numero}/gerarexcelveiculo', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@gerarexcelveiculo')->name('Painel.PesquisaPatrimonial.gerarexcelveiculo');
Route::get('/painel/pesquisapatrimonial/{Numero}/gerarexcelempresa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@gerarexcelempresa')->name('Painel.PesquisaPatrimonial.gerarexcelempresa');
Route::get('/painel/pesquisapatrimonial/{Numero}/gerarexcelinfojud', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@gerarexcelinfojud')->name('Painel.PesquisaPatrimonial.gerarexcelinfojud');
Route::get('/painel/pesquisapatrimonial/{Numero}/gerarexcelbacenjud', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@gerarexcelbacenjud')->name('Painel.PesquisaPatrimonial.gerarexcelbacenjud');
Route::get('/painel/pesquisapatrimonial/{Numero}/gerarexcelnota', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@gerarexcelnota')->name('Painel.PesquisaPatrimonial.gerarexcelnota');
Route::get('/painel/pesquisapatrimonial/{Numero}/gerarexcelredessocial', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@gerarexcelredessocial')->name('Painel.PesquisaPatrimonial.gerarexcelredessocial');
Route::get('/painel/pesquisapatrimonial/{Numero}/gerarexceltribunal', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@gerarexceltribunal')->name('Painel.PesquisaPatrimonial.gerarexceltribunal');
Route::get('/painel/pesquisapatrimonial/{Numero}/gerarexcelcomercial', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@gerarexcelcomercial')->name('Painel.PesquisaPatrimonial.gerarexcelcomercial');
Route::get('/painel/pesquisapatrimonial/{Numero}/gerarexcelpesquisa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@gerarexcelpesquisa')->name('Painel.PesquisaPatrimonial.gerarexcelpesquisa');
Route::get('/painel/pesquisapatrimonial/{Numero}/gerarexceldados', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@gerarexceldados')->name('Painel.PesquisaPatrimonial.gerarexceldados');
Route::get('/painel/pesquisapatrimonial/{Numero}/gerarexceldiversos', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@gerarexceldiversos')->name('Painel.PesquisaPatrimonial.gerarexceldiversos');
Route::get('/painel/pesquisapatrimonial/{Numero}/gerarexcelmoeda', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@gerarexcelmoeda')->name('Painel.PesquisaPatrimonial.gerarexcelmoeda');
Route::get('/painel/pesquisapatrimonial/{Numero}/gerarexceljoias', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@gerarexceljoias')->name('Painel.PesquisaPatrimonial.gerarexceljoias');

Route::get('/painel/pesquisapatrimonial/{Numero}/gerarexceloutraparte', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@gerarexceloutraparte')->name('Painel.PesquisaPatrimonial.gerarexceloutraparte');
Route::get('/painel/pesquisapatrimonial/{Numero}/viewoutraparte', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@viewoutraparte')->name('Painel.PesquisaPatrimonial.viewoutraparte');

Route::get('/painel/pesquisapatrimonial/{Numero}/viewimovel', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@viewimovel')->name('Painel.PesquisaPatrimonial.viewimovel');
Route::get('/painel/pesquisapatrimonial/{Numero}/viewveiculos', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@viewveiculos')->name('Painel.PesquisaPatrimonial.viewveiculos');
Route::get('/painel/pesquisapatrimonial/{Numero}/viewempresas', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@viewempresas')->name('Painel.PesquisaPatrimonial.viewempresas');
Route::get('/painel/pesquisapatrimonial/{Numero}/viewinfojud', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@viewinfojud')->name('Painel.PesquisaPatrimonial.viewinfojud');
Route::get('/painel/pesquisapatrimonial/{Numero}/viewbacenjud', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@viewbacenjud')->name('Painel.PesquisaPatrimonial.viewbacenjud');
Route::get('/painel/pesquisapatrimonial/{Numero}/viewnotas', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@viewnotas')->name('Painel.PesquisaPatrimonial.viewnotas');
Route::get('/painel/pesquisapatrimonial/{Numero}/viewredessociais', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@viewredessociais')->name('Painel.PesquisaPatrimonial.viewredessociais');
Route::get('/painel/pesquisapatrimonial/{Numero}/viewtribunal', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@viewtribunal')->name('Painel.PesquisaPatrimonial.viewtribunal');
Route::get('/painel/pesquisapatrimonial/{Numero}/viewcomercial', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@viewcomercial')->name('Painel.PesquisaPatrimonial.viewcomercial');
Route::get('/painel/pesquisapatrimonial/{Numero}/viewpesquisa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@viewpesquisa')->name('Painel.PesquisaPatrimonial.viewpesquisa');
Route::get('/painel/pesquisapatrimonial/{Numero}/viewdados', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@viewdados')->name('Painel.PesquisaPatrimonial.viewdados');
Route::post('/painel/pesquisapatrimonial/verificaaba', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@verificaaba')->name('Painel.PesquisaPatrimonial.solicitacao.verificaaba');
Route::post('/painel/pesquisapatrimonial/gravarinformacoes', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@gravarinformacoes')->name('Painel.PesquisaPatrimonial.gravarinformacoes');
Route::get('/painel/pesquisapatrimonial/{Numero}/capa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@capa')->name('Painel.PesquisaPatrimonial.capa');
Route::get('/painel/pesquisapatrimonial/{Numero}/alterarforma', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_alterarforma')->name('Painel.PesquisaPatrimonial.nucleo.alterarforma');
Route::post('/painel/pesquisapatrimonial/alteradoforma', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_alteradoforma')->name('Painel.PesquisaPatrimonial.nucleo.alteradoforma');
Route::get('/painel/pesquisapatrimonial/{Numero}/finalizarpesquisa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_finalizarpesquisa')->name('Painel.PesquisaPatrimonial.nucleo.finalizarpesquisa');

Route::get('/painel/pesquisapatrimonial/{Numero}/fichageral', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@ficha')->name('Painel.PesquisaPatrimonial.ficha');


Route::get('/painel/pesquisapatrimonial/financeiro/index', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@financeiro_index')->name('Painel.PesquisaPatrimonial.financeiro.index');
Route::get('/painel/pesquisapatrimonial/financeiro/solicitacoes', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@financeiro_solicitacoes')->name('Painel.PesquisaPatrimonial.financeiro.solicitacoes');
Route::get('/painel/pesquisapatrimonial/financeiro/filtrorelatorio', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@financeiro_filtrorelatorio')->name('Painel.PesquisaPatrimonial.financeiro.filtrorelatorio');
Route::post('/painel/pesquisapatrimonial/financeiro/relatorio', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@financeiro_relatorio')->name('Painel.PesquisaPatrimonial.financeiro.relatorio');
Route::post('/painel/pesquisapatrimonial/financeiro/buscabancoempresa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@financeiro_bancoempresa')->name('Painel.PesquisaPatrimonial.financeiro.buscabancoempresa');
Route::post('/painel/pesquisapatrimonial/financeiro/buscabanconempresa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@financeiro_banconempresa')->name('Painel.PesquisaPatrimonial.financeiro.buscabanconempresa');
Route::get('/painel/pesquisapatrimonial/financeiro/{Numero}/ficha', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@financeiro_ficha')->name('Painel.PesquisaPatrimonial.financeiro.ficha');
Route::post('/painel/pesquisapatrimonial/financeiro/storeficha', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@financeiro_storeficha')->name('Painel.PesquisaPatrimonial.financeiro.storeficha');
Route::get('/painel/pesquisapatrimonial/financeiro/{Numero}/avaliar', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@financeiro_avaliar')->name('Painel.PesquisaPatrimonial.financeiro.avaliar');
Route::post('/painel/pesquisapatrimonial/financeiro/avaliado', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@financeiro_avaliado')->name('Painel.PesquisaPatrimonial.financeiro.avaliado');
Route::get('/painel/pesquisapatrimonial/financeiro/{Numero}/formapagamento', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@financeiro_formapagamento')->name('Painel.PesquisaPatrimonial.financeiro.formapagamento');
Route::post('/painel/pesquisapatrimonial/financeiro/definidoformapagamento', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@financeiro_enviarformapagamento')->name('Painel.PesquisaPatrimonial.financeiro.enviarformapagamento');
Route::get('/painel/pesquisapatrimonial/financeiro/solicitacoes/{Numero}/alterarcobranca', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@financeiro_alterarcobranca')->name('Painel.PesquisaPatrimonial.financeiro.alterarcobranca');
Route::post('/painel/pesquisapatrimonial/financeiro/alteradostatuscobravel', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@financeiro_alteradostatuscobravel')->name('Painel.PesquisaPatrimonial.financeiro.alteradostatuscobravel');

Route::get('/painel/pesquisapatrimonial/financeiro/solicitacoesaguardandomontagemdafichafinanceira', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@financeiro_solicitacoesaguardandoficha')->name('Painel.PesquisaPatrimonial.financeiro.solicitacoesaguardandoficha');
Route::get('/painel/pesquisapatrimonial/financeiro/solicitacoesaguardandocliente', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@financeiro_solicitacoesaguardandocliente')->name('Painel.PesquisaPatrimonial.financeiro.solicitacoesaguardandocliente');
Route::get('/painel/pesquisapatrimonial/financeiro/solicitacoesaguardandorevisaosupervisor', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@financeiro_solicitacoesaguardandorevisaosupervisor')->name('Painel.PesquisaPatrimonial.financeiro.solicitacoesaguardandorevisaosupervisor');
Route::get('/painel/pesquisapatrimonial/financeiro/solicitacoesnaocobravel', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@financeiro_solicitacoesnaocobravel')->name('Painel.PesquisaPatrimonial.financeiro.solicitacoesnaocobravel');
Route::get('/painel/pesquisapatrimonial/financeiro/solicitacoesemandamento', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@financeiro_solicitacoesemandamento')->name('Painel.PesquisaPatrimonial.financeiro.solicitacoesemandamento');
Route::get('/painel/pesquisapatrimonial/financeiro/solicitacoesfinalizadas', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@financeiro_solicitacoesfinalizadas')->name('Painel.PesquisaPatrimonial.financeiro.solicitacoesfinalizadas');


Route::get('/painel/pesquisapatrimonial/supervisao/index', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@supervisao_index')->name('Painel.PesquisaPatrimonial.supervisao.index');
Route::get('/painel/pesquisapatrimonial/supervisao/solicitacoes', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@supervisao_solicitacoes')->name('Painel.PesquisaPatrimonial.supervisao.solicitacoes');
Route::get('/painel/pesquisapatrimonial/supervisao/solicitacoes/{Numero}/avaliar', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@supervisao_avaliar')->name('Painel.PesquisaPatrimonial.supervisao.avaliar');
Route::post('/painel/pesquisapatrimonial/supervisao/solicitacaoavaliada', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@supervisao_avaliada')->name('Painel.PesquisaPatrimonial.supervisao.avaliada');
Route::get('/painel/pesquisapatrimonial/supervisao/solicitacoes/{Numero}/alterarcobranca', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@supervisao_alterarcobranca')->name('Painel.PesquisaPatrimonial.supervisao.alterarcobranca');
Route::post('/painel/pesquisapatrimonial/supervisao/alteradostatuscobravel', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@supervisao_alteradostatuscobravel')->name('Painel.PesquisaPatrimonial.supervisao.alteradostatuscobravel');

Route::get('/painel/pesquisapatrimonial/supervisao/grupos', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@supervisao_grupos_index')->name('Painel.PesquisaPatrimonial.supervisao.grupos.index');


Route::get('/painel/pesquisapatrimonial/supervisao/clientes', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@supervisao_clientes_index')->name('Painel.PesquisaPatrimonial.supervisao.clientes.index');
Route::get('/painel/pesquisapatrimonial/supervisao/clientes/create', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@supervisao_clientes_create')->name('Painel.PesquisaPatrimonial.supervisao.clientes.create');
Route::post('/painel/pesquisapatrimonial/supervisao/clientes/store', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@supervisao_clientes_store')->name('Painel.PesquisaPatrimonial.supervisao.clientes.store');
Route::get('/painel/pesquisapatrimonial/supervisao/clientes/{Numero}/editar', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@supervisao_clientes_editar')->name('Painel.PesquisaPatrimonial.supervisao.clientes.edit');
Route::post('/painel/pesquisapatrimonial/supervisao/clientes/update', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@supervisao_clientes_update')->name('Painel.PesquisaPatrimonial.supervisao.clientes.update');

Route::get('/painel/pesquisapatrimonial/supervisao/equipe', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@supervisao_equipe_index')->name('Painel.PesquisaPatrimonial.supervisao.equipe.index');
Route::get('/painel/pesquisapatrimonial/supervisao/equipe/{Numero}/dashboard', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@supervisao_equipe_dashboard')->name('Painel.PesquisaPatrimonial.supervisao.equipe.dashboard');
Route::get('/painel/pesquisapatrimonial/supervisao/equipe/{Numero}/solicitacoes', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@supervisao_equipe_solicitacoes')->name('Painel.PesquisaPatrimonial.supervisao.equipe.solicitacoes');

Route::get('/painel/pesquisapatrimonial/ti/configurar/tipossolicitacao', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@ti_tipossolicitacoes')->name('Painel.PesquisaPatrimonial.ti.configurar.tipossolicitacoes');
Route::get('/painel/pesquisapatrimonial/ti/configurar/tipossolicitacao/{id}/editar', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@ti_tipossolicitacoes_editar')->name('Painel.PesquisaPatrimonial.ti.configurar.tipossolicitacoes.editar');
Route::post('/painel/pesquisapatrimonial/ti/configurar/tipossolicitacao/atualizar', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@ti_tipossolicitacoes_atualizar')->name('Painel.PesquisaPatrimonial.ti.configurar.tipossolicitacoes.update');

Route::get('/painel/pesquisapatrimonial/ti/configurar/tiposlancamento', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@ti_tiposlancamento')->name('Painel.PesquisaPatrimonial.ti.configurar.tiposlancamento');

Route::get('/painel/pesquisapatrimonial/ti/peso/index', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@ti_peso_index')->name('Painel.PesquisaPatrimonial.ti.peso.index');
Route::post('/painel/pesquisapatrimonial/ti/peso/editado', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@ti_peso_editado')->name('Painel.PesquisaPatrimonial.ti.peso.editado');


Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/tabimovel', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_tabimovel')->name('Painel.PesquisaPatrimonial.nucleo.tabimovel');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/tabveiculo', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_tabveiculo')->name('Painel.PesquisaPatrimonial.nucleo.tabveiculo');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/tabempresa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_tabempresa')->name('Painel.PesquisaPatrimonial.nucleo.tabempresa');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/tabinfojud', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_tabinfojud')->name('Painel.PesquisaPatrimonial.nucleo.tabinfojud');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/tabbacenjud', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_tabbacenjud')->name('Painel.PesquisaPatrimonial.nucleo.tabbacenjud');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/tabprotestos', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_tabprotestos')->name('Painel.PesquisaPatrimonial.nucleo.tabprotestos');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/tabredessociais', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_tabredessociais')->name('Painel.PesquisaPatrimonial.nucleo.tabredessociais');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/tabprocessosjudiciais', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_tabprocessosjudicias')->name('Painel.PesquisaPatrimonial.nucleo.tabprocessosjudiciais');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/tabpesquisacadastral', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_tabpesquisacadastral')->name('Painel.PesquisaPatrimonial.nucleo.tabpesquisa');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/tabdossiecomercial', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_tabdossiecomercial')->name('Painel.PesquisaPatrimonial.nucleo.tabdossiecomercial');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/tabdados', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_tabdados')->name('Painel.PesquisaPatrimonial.nucleo.tabdados');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/tabscorecard', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_tabscorecard')->name('Painel.PesquisaPatrimonial.nucleo.tabscorecard');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/tabdiversos', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_tabdiversos')->name('Painel.PesquisaPatrimonial.nucleo.tabdiversos');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/tabmoeda', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_tabsmoeda')->name('Painel.PesquisaPatrimonial.nucleo.tabmoeda');
Route::get('/painel/pesquisapatrimonial/nucleo/{Codigo}/{Numero}/tabjoias', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_tabsjoias')->name('Painel.PesquisaPatrimonial.nucleo.tabjoias');

Route::post('/painel/pesquisapatrimonial/nucleo/finalizastatus', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_finalizarstatus')->name('Painel.PesquisaPatrimonial.nucleo.finalizarstatus');
Route::post('/painel/pesquisapatrimonial/nucleo/finalizaimovel', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_finalizarimovel')->name('Painel.PesquisaPatrimonial.nucleo.finalizarimovel');
Route::post('/painel/pesquisapatrimonial/nucleo/finalizaveiculo', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_finalizarveiculo')->name('Painel.PesquisaPatrimonial.nucleo.finalizarveiculo');
Route::post('/painel/pesquisapatrimonial/nucleo/finalizaempresa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_finalizarempresa')->name('Painel.PesquisaPatrimonial.nucleo.finalizarempresa');
Route::post('/painel/pesquisapatrimonial/nucleo/finalizainfojud', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_finalizarinfojud')->name('Painel.PesquisaPatrimonial.nucleo.finalizarinfojud');
Route::post('/painel/pesquisapatrimonial/nucleo/finalizabacenjud', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_finalizarbacenjud')->name('Painel.PesquisaPatrimonial.nucleo.finalizarbacenjud');
Route::post('/painel/pesquisapatrimonial/nucleo/finalizaprotesto', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_finalizarprotesto')->name('Painel.PesquisaPatrimonial.nucleo.finalizarprotesto');
Route::post('/painel/pesquisapatrimonial/nucleo/finalizaredesocial', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_finalizarredesocial')->name('Painel.PesquisaPatrimonial.nucleo.finalizarredesocial');
Route::post('/painel/pesquisapatrimonial/nucleo/finalizarprocessojudicial', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_finalizarprocessojudicial')->name('Painel.PesquisaPatrimonial.nucleo.finalizarprocessojudicial');
Route::post('/painel/pesquisapatrimonial/nucleo/finalizarpesquisacadastral', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_finalizarpesquisacadastral')->name('Painel.PesquisaPatrimonial.nucleo.finalizarpesquisacadastral');
Route::post('/painel/pesquisapatrimonial/nucleo/finalizardossiecomercial', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_finalizardossiecomercial')->name('Painel.PesquisaPatrimonial.nucleo.finalizardossiecomercial');
Route::post('/painel/pesquisapatrimonial/nucleo/finalizarscorecard', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_finalizarscorecard')->name('Painel.PesquisaPatrimonial.nucleo.finalizarscorecard');
Route::post('/painel/pesquisapatrimonial/nucleo/finalizardiversos', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_finalizardiversos')->name('Painel.PesquisaPatrimonial.nucleo.finalizardiversos');
Route::post('/painel/pesquisapatrimonial/nucleo/finalizarmoeda', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_finalizarmoeda')->name('Painel.PesquisaPatrimonial.nucleo.finalizarmoeda');
Route::post('/painel/pesquisapatrimonial/nucleo/finalizarjoias', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_finalizarjoias')->name('Painel.PesquisaPatrimonial.nucleo.finalizarjoias');



Route::get('/painel/pesquisapatrimonial/solicitacao/{cpf}/relatoriopesquisa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@relatoriopesquisa')->name('Painel.PesquisaPatrimonial.solicitacao.relatoriopesquisa');
Route::get('/painel/pesquisapatrimonial/nucleo/{cpf}/relatoriopesquisa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@relatoriopesquisa')->name('Painel.PesquisaPatrimonial.solicitacao.relatoriopesquisa');


Route::get('/painel/pesquisapatrimonial/nucleo/{codigo}/{numero}/capa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@nucleo_capa')->name('Painel.PesquisaPatrimonial.nucleo.capa');
Route::get('/painel/pesquisapatrimonial/{Codigo}/pesquisa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_verpesquisastatus')->name('Painel.PesquisaPatrimonial.verpesquisa');

Route::get('/painel/pesquisapatrimonial/{Codigo}/verpesquisa/imovel', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_verpesquisaimovel')->name('Painel.PesquisaPatrimonial.solicitacao.verpesquisaimovel');
Route::get('/painel/pesquisapatrimonial/{Codigo}/verpesquisa/veiculo', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_verpesquisaveiculo')->name('Painel.PesquisaPatrimonial.solicitacao.verpesquisaveiculo');
Route::get('/painel/pesquisapatrimonial/{Codigo}/verpesquisa/empresa', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_verpesquisaempresa')->name('Painel.PesquisaPatrimonial.solicitacao.verpesquisaempresa');
Route::get('/painel/pesquisapatrimonial/{Codigo}/verpesquisa/infojud', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_verpesquisainfojud')->name('Painel.PesquisaPatrimonial.solicitacao.verpesquisainfojud');
Route::get('/painel/pesquisapatrimonial/{Codigo}/verpesquisa/bacenjud', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_verpesquisabacenjud')->name('Painel.PesquisaPatrimonial.solicitacao.verpesquisabacenjud');
Route::get('/painel/pesquisapatrimonial/{Codigo}/verpesquisa/protestos', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_verpesquisaprotesto')->name('Painel.PesquisaPatrimonial.solicitacao.verpesquisaprotestos');
Route::get('/painel/pesquisapatrimonial/{Codigo}/verpesquisa/redesocial', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_verpesquisaredessociais')->name('Painel.PesquisaPatrimonial.solicitacao.verpesquisaredessociais');
Route::get('/painel/pesquisapatrimonial/{Codigo}/verpesquisa/processojudicial', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_verpesquisaprocessosjudiciais')->name('Painel.PesquisaPatrimonial.solicitacao.verpesquisaprocessosjudiciais');
Route::get('/painel/pesquisapatrimonial/{Codigo}/verpesquisa/pesquisacadastral', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_verpesquisapesquisacadastral')->name('Painel.PesquisaPatrimonial.solicitacao.verpesquisapesquisacadastral');
Route::get('/painel/pesquisapatrimonial/{Codigo}/verpesquisa/dossiecomercial', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_verpesquisadossiecomercial')->name('Painel.PesquisaPatrimonial.solicitacao.verpesquisadossiecomercial');
Route::get('/painel/pesquisapatrimonial/{Codigo}/verpesquisa/dados', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_verpesquisadados')->name('Painel.PesquisaPatrimonial.solicitacao.verpesquisadados');
Route::get('/painel/pesquisapatrimonial/{Codigo}/verpesquisa/diversos', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_verpesquisadiversos')->name('Painel.PesquisaPatrimonial.solicitacao.verpesquisadiversos');
Route::get('/painel/pesquisapatrimonial/{Codigo}/verpesquisa/score', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_verpesquisascore')->name('Painel.PesquisaPatrimonial.solicitacao.verpesquisascorecard');
Route::get('/painel/pesquisapatrimonial/{Codigo}/verpesquisa/moeda', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_verpesquisamoeda')->name('Painel.PesquisaPatrimonial.solicitacao.verpesquisamoeda');
Route::get('/painel/pesquisapatrimonial/{Codigo}/verpesquisa/joias', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@solicitacao_verpesquisajoias')->name('Painel.PesquisaPatrimonial.solicitacao.verpesquisajoias');

Route::get('/painel/pesquisapatrimonial/{Codigo}/veranexos', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@ver_anexos')->name('Painel.PesquisaPatrimonial.veranexo');
Route::get('/painel/pesquisapatrimonial/{Codigo}/baixaranexo', 'Painel\PesquisaPatrimonial\PesquisaPatrimonialController@baixaranexo')->name('Painel.PesquisaPatrimonial.baixaranexo');

/*** FIM ROUTES PESQUISA PATRIMONIAL */


/** ROTAS GESTAO */

Route::get('/painel/gestao/{anexo}/anexo', 'Painel\Gestao\GestaoController@anexo')->name('Painel.Gestao.anexo');
Route::get('/painel/gestao/faturamentosemanal/index', 'Painel\Gestao\GestaoController@faturamentosemanal_index')->name('Painel.Gestao.FaturamentoSemanal.index');
Route::get('/painel/gestao/faturamentosemanal/detalhado', 'Painel\Gestao\GestaoController@faturamentosemanal_detalhado')->name('Painel.Gestao.Faturamentosemanal.detalhado');
Route::get('/painel/gestao/faturamentosemanal/detalhado/exportar', 'Painel\Gestao\GestaoController@faturamentosemanal_detalhado_exportar')->name('Painel.Gestao.Faturamentosemanal.detalhado.exportar');
Route::get('/painel/gestao/faturamentosemanal/detalhado/pegarDados', 'Painel\Gestao\GestaoController@PegarDadosFaturamentoSemanalDetalhado')->name('Painel.Gestao.Faturamentosemanal.detalhado.pegar_dados');
Route::get('/painel/gestao/faturamentosemanal/detalhado/mostrarDados/{id}', 'Painel\Gestao\GestaoController@MostrarDadosFaturamentoSemanalDetalhado')->name('Painel.Gestao.Faturamentosemanal.detalhado.mostrar_dados');

Route::get('/painel/gestao/meritocracia/index', 'Painel\Gestao\GestaoController@meritocracia_index')->name('Painel.Gestao.Meritocracia.index');
Route::get('/painel/gestao/meritocracia/minhasnotas', 'Painel\Gestao\GestaoController@meritocracia_minhasnotas')->name('Painel.Gestao.Meritocracia.minhasnotas');
Route::get('/painel/gestao/meritocracia/minhasnotasmes', 'Painel\Gestao\GestaoController@meritocracia_minhasnotasmes')->name('Painel.Gestao.Meritocracia.minhasnotasmes');
Route::get('/painel/gestao/meritocracia/cartasrv/historico', 'Painel\Gestao\GestaoController@meritocracia_cartasrv_historico')->name('Painel.Gestao.Meritocracia.CartasRV.historico');
Route::get('/painel/gestao/meritocracia/detalhamento', 'Painel\Gestao\GestaoController@meritocracia_detalhamento')->name('Painel.Gestao.Meritocracia.detalhamento');
Route::get('/painel/gestao/meritocracia/detalhamento/{id}/mes', 'Painel\Gestao\GestaoController@meritocracia_detalhamentoobjetivo')->name('Painel.Gestao.Meritocracia.detalhamentoobjetivo');

Route::get('/painel/gestao/meritocracia/hierarquia/index', 'Painel\Gestao\GestaoController@meritocracia_hierarquia_index')->name('Painel.Gestao.Meritocracia.Hierarquia.index');

Route::get('/painel/gestao/meritocracia/hierarquia/notasadvogado/{id}/detalha', 'Painel\Gestao\GestaoController@meritocracia_hierarquia_notasadvogado_detalha')->name('Painel.Gestao.Meritocracia.Hierarquia.NotasAdvogado.detalha');
Route::get('/painel/gestao/meritocracia/hierarquia/notasadvogado/{usuario_id}/{id_objetivo}/detalhamento', 'Painel\Gestao\GestaoController@meritocracia_hierarquia_notasadvogado_detalhamentoobjetivo')->name('Painel.Gestao.Meritocracia.Hierarquia.NotasAdvogado.detalhamentoobjetivo');

Route::get('/painel/gestao/meritocracia/hierarquia/setor/index', 'Painel\Gestao\GestaoController@meritocracia_hierarquiasetor_index')->name('Painel.Gestao.Meritocracia.Hierarquia.Setor.index');

Route::get('/painel/gestao/meritocracia/prazos/index', 'Painel\Gestao\GestaoController@meritocracia_prazos_index')->name('Painel.Gestao.Meritocracia.Prazos.index');
Route::get('/painel/gestao/meritocracia/prazos/{ident}/contestacao', 'Painel\Gestao\GestaoController@meritocracia_prazos_contestar')->name('Painel.Gestao.Meritocracia.Prazos.contestar');
Route::post('/painel/gestao/meritocracia/prazos/contestado', 'Painel\Gestao\GestaoController@meritocracia_prazos_contestado')->name('Painel.Gestao.Meritocracia.Prazos.contestado');

Route::get('/painel/gestao/meritocracia/contrato/index', 'Painel\Gestao\GestaoController@meritocracia_contrato_index')->name('Painel.Gestao.Meritocracia.Contrato.index');
Route::post('/painel/gestao/meritocracia/contrato/contratoassinado', 'Painel\Gestao\GestaoController@meritocracia_contrato_assinado')->name('Painel.Gestao.Meritocracia.Contrato.assinado');
Route::get('/painel/gestao/meritocracia/contrato/{id}/visualizar', 'Painel\Gestao\GestaoController@meritocracia_contrato_visualizar')->name('Painel.Gestao.Meritocracia.Contrato.visualizar');
Route::get('/painel/gestao/meritocracia/contrato/{id}/baixar', 'Painel\Gestao\GestaoController@meritocracia_contrato_baixar')->name('Painel.Gestao.Meritocracia.Contrato.baixar');


Route::get('/painel/gestao/controlador/index', 'Painel\Gestao\GestaoController@controlador_index')->name('Painel.Gestao.Controlador.index');

Route::get('/painel/gestao/controlador/cartarv/index', 'Painel\Gestao\GestaoController@controlador_cartarv_index')->name('Painel.Gestao.Controlador.CartaRV.index');
Route::get('/painel/gestao/controlador/cartarv/historico', 'Painel\Gestao\GestaoController@controlador_cartarv_historico')->name('Painel.Gestao.Controlador.CartaRV.historico');
Route::get('/painel/gestao/controlador/cartarv/{id}/editar', 'Painel\Gestao\GestaoController@controlador_cartarv_editar')->name('Painel.Gestao.Controlador.CartaRV.editar');
Route::post('/painel/gestao/controlador/cartarv/editado', 'Painel\Gestao\GestaoController@controlador_cartarv_editado')->name('Painel.Gestao.Controlador.CartaRV.editado');
Route::post('/painel/gestao/controlador/cartarv/gravadoregistro', 'Painel\Gestao\GestaoController@controlador_cartarv_gravar')->name('Painel.Gestao.Controlador.CartaRV.gravar');
Route::get('/painel/gestao/controlador/cartarv/exportarmes', 'Painel\Gestao\GestaoController@controlador_cartarv_exportarmes')->name('Painel.Gestao.Controlador.CartaRV.exportarmes');
Route::get('/painel/gestao/controlador/cartarv/exportarano', 'Painel\Gestao\GestaoController@controlador_cartarv_exportarano')->name('Painel.Gestao.Controlador.CartaRV.exportarano');

Route::get('/painel/gestao/controlador/notasadvogado/index', 'Painel\Gestao\GestaoController@controlador_notasadvogado_index')->name('Painel.Gestao.Controlador.NotasAdvogado.index');
Route::get('/painel/gestao/controlador/notasadvogado/historico', 'Painel\Gestao\GestaoController@controlador_notasadvogado_historico')->name('Painel.Gestao.Controlador.NotasAdvogado.historico');
Route::get('/painel/gestao/controlador/notasadvogado/{id}/editar', 'Painel\Gestao\GestaoController@controlador_notasadvogado_editar')->name('Painel.Gestao.Controlador.NotasAdvogado.editar');
Route::post('/painel/gestao/controlador/notasadvogado/editado', 'Painel\Gestao\GestaoController@controlador_notasadvogado_editado')->name('Painel.Gestao.Controlador.NotasAdvogado.editado');
Route::post('/painel/gestao/controlador/notasadvogado/gravadoregistro', 'Painel\Gestao\GestaoController@controlador_notasadvogado_gravar')->name('Painel.Gestao.Controlador.NotasAdvogado.gravar');
Route::get('/painel/gestao/controlador/notasadvogado/{id}/deletarnota', 'Painel\Gestao\GestaoController@controlador_notasadvogado_deletarnota')->name('Painel.Gestao.Controlador.NotasAdvogado.deletarnota');
Route::get('/painel/gestao/controlador/notasadvogado/exportarnotasmes', 'Painel\Gestao\GestaoController@controlador_notasadvogado_exportarnotasmes')->name('Painel.Gestao.Controlador.NotasAdvogado.exportarnotasmes');
Route::get('/painel/gestao/controlador/notasadvogado/exportarnotasano', 'Painel\Gestao\GestaoController@controlador_notasadvogado_exportarnotasano')->name('Painel.Gestao.Controlador.NotasAdvogado.exportarnotasano');


Route::get('/painel/gestao/controlador/notasconsolidada/index', 'Painel\Gestao\GestaoController@controlador_notasconsolidada_index')->name('Painel.Gestao.Controlador.NotasConsolidada.index');
Route::get('/painel/gestao/controlador/notasconsolidada/historico', 'Painel\Gestao\GestaoController@controlador_notasconsolidada_historico')->name('Painel.Gestao.Controlador.NotasConsolidada.historico');
Route::get('/painel/gestao/controlador/notasconsolidada/{cpf}/detalhar', 'Painel\Gestao\GestaoController@controlador_notasconsolidada_detalhar')->name('Painel.Gestao.Controlador.NotasConsolidada.detalhar');
Route::get('/painel/gestao/controlador/notasconsolidada/{cpf}/{id}/detalharmes', 'Painel\Gestao\GestaoController@controlador_notasconsolidada_detalharmes')->name('Painel.Gestao.Controlador.NotasConsolidada.detalharmes');
Route::get('/painel/gestao/controlador/notasconsolidada/{id}/editar', 'Painel\Gestao\GestaoController@controlador_notasconsolidada_editar')->name('Painel.Gestao.Controlador.NotasConsolidada.editar');
Route::post('/painel/gestao/controlador/notasconsolidada/editado', 'Painel\Gestao\GestaoController@controlador_notasconsolidada_editado')->name('Painel.Gestao.Controlador.NotasConsolidada.editado');
Route::get('/painel/gestao/controlador/notasconsolidada/exportarnotasmes', 'Painel\Gestao\GestaoController@controlador_notasconsolidada_exportarnotasmes')->name('Painel.Gestao.Controlador.NotasConsolidada.exportarnotasmes');
Route::get('/painel/gestao/controlador/notasconsolidada/exportarnotasano', 'Painel\Gestao\GestaoController@controlador_notasconsolidada_exportarnotasano')->name('Painel.Gestao.Controlador.NotasConsolidada.exportarnotasano');
Route::get('/painel/gestao/controlador/notasconsolidada/{cpf}/exportarnotasocio', 'Painel\Gestao\GestaoController@controlador_notasconsolidada_exportarnotasocio')->name('Painel.Gestao.Controlador.NotasConsolidada.exportarnotasocio');
Route::get('/painel/gestao/controlador/notasconsolidada/{cpf}/{id}/exportardetalhamentosocio', 'Painel\Gestao\GestaoController@controlador_notasconsolidada_exportardetalhamentomes')->name('Painel.Gestao.Controlador.NotasConsolidada.exportardetalhamentomes');

Route::get('/painel/gestao/controlador/hierarquia/index', 'Painel\Gestao\GestaoController@controlador_hierarquia_index')->name('Painel.Gestao.Controlador.Hierarquia.index');
Route::get('/painel/gestao/controlador/hierarquia/{id}/advogados', 'Painel\Gestao\GestaoController@controlador_hierarquia_advogados')->name('Painel.Gestao.Controlador.Hierarquia.advogados');
Route::get('/painel/gestao/controlador/hierarquia/{id}/editar', 'Painel\Gestao\GestaoController@controlador_hierarquia_editar')->name('Painel.Gestao.Controlador.Hierarquia.editar');
Route::post('/painel/gestao/controlador/hierarquia/editado', 'Painel\Gestao\GestaoController@controlador_hierarquia_editado')->name('Painel.Gestao.Controlador.Hierarquia.editado');
Route::post('/painel/gestao/controlador/hierarquia/gravar', 'Painel\Gestao\GestaoController@controlador_hierarquia_gravar')->name('Painel.Gestao.Controlador.Hierarquia.gravar');
Route::post('/painel/gestao/controlador/hierarquia/novoresponsavel', 'Painel\Gestao\GestaoController@controlador_hierarquia_novoresponsavel')->name('Painel.Gestao.Controlador.Hierarquia.novoresponsavel');

Route::get('/painel/gestao/controlador/hierarquia/exportarresponsaveis', 'Painel\Gestao\GestaoController@controlador_hierarquia_exportarresponsaveis')->name('Painel.Gestao.Controlador.Hierarquia.exportarresponsaveis');
Route::get('/painel/gestao/controlador/hierarquia/{codigo}/exportaradvogados', 'Painel\Gestao\GestaoController@controlador_hierarquia_exportaradvogados')->name('Painel.Gestao.Controlador.Hierarquia.exportaradvogados');


Route::post('/painel/gestao/controlador/procedure/mediascore', 'Painel\Gestao\GestaoController@procedure_mediascore')->name('Painel.Gestao.Controlador.Procedure.mediascore');
Route::post('/painel/gestao/controlador/procedure/notaconsolidada', 'Painel\Gestao\GestaoController@procedure_notaconsolidada')->name('Painel.Gestao.Controlador.Procedure.notaconsolidada');

Route::get('/painel/gestao/controlador/metas/index', 'Painel\Gestao\GestaoController@controlador_metas_index')->name('Painel.Gestao.Controlador.Metas.index');
Route::post('/painel/gestao/controlador/metas/gravarregistro', 'Painel\Gestao\GestaoController@controlador_metas_gravarregistro')->name('Painel.Gestao.Controlador.Metas.gravarregistro');
Route::get('/painel/gestao/controlador/metas/{id}/editar', 'Painel\Gestao\GestaoController@controlador_metas_editar')->name('Painel.Gestao.Controlador.Metas.editar');
Route::post('/painel/gestao/controlador/metas/editado', 'Painel\Gestao\GestaoController@controlador_metas_editado')->name('Painel.Gestao.Controlador.Metas.editado');
Route::get('/painel/gestao/controlador/metas/exportar', 'Painel\Gestao\GestaoController@controlador_metas_exportar')->name('Painel.Gestao.Controlador.Metas.exportar');

Route::get('/painel/gestao/controlador/objetivos/index', 'Painel\Gestao\GestaoController@controlador_objetivos_index')->name('Painel.Gestao.Controlador.Objetivos.index');
Route::post('/painel/gestao/controlador/objetivos/gravarregistro', 'Painel\Gestao\GestaoController@controlador_objetivos_gravarregistro')->name('Painel.Gestao.Controlador.Objetivos.gravarregistro');
Route::get('/painel/gestao/controlador/objetivos/{id}/editar', 'Painel\Gestao\GestaoController@controlador_objetivos_editar')->name('Painel.Gestao.Controlador.Objetivos.editar');
Route::post('/painel/gestao/controlador/objetivos/editado', 'Painel\Gestao\GestaoController@controlador_objetivos_editado')->name('Painel.Gestao.Controlador.Objetivos.editado');
Route::get('/painel/gestao/controlador/objetivos/exportar', 'Painel\Gestao\GestaoController@controlador_objetivos_exportar')->name('Painel.Gestao.Controlador.Objetivos.exportar');

Route::get('/painel/gestao/controlador/valororcado/index', 'Painel\Gestao\GestaoController@controlador_valororcado_index')->name('Painel.Gestao.Controlador.ValorOrcado.index');
Route::post('/painel/gestao/controlador/valororcado/gravarregistro', 'Painel\Gestao\GestaoController@controlador_valororcado_gravarregistro')->name('Painel.Gestao.Controlador.ValorOrcado.gravarregistro');
Route::get('/painel/gestao/controlador/valororcado/{id}/editar', 'Painel\Gestao\GestaoController@controlador_valororcado_editar')->name('Painel.Gestao.Controlador.ValorOrcado.editar');
Route::post('/painel/gestao/controlador/valororcado/editado', 'Painel\Gestao\GestaoController@controlador_valororcado_editado')->name('Painel.Gestao.Controlador.ValorOrcado.editado');
Route::get('/painel/gestao/controlador/valororcado/exportar', 'Painel\Gestao\GestaoController@controlador_valororcado_exportar')->name('Painel.Gestao.Controlador.ValorOrcado.exportar');

Route::get('/painel/gestao/controlador/valororcado/mensal/index', 'Painel\Gestao\GestaoController@controlador_valororcado_mensal_index')->name('Painel.Gestao.Controlador.ValorOrcado.Mensal.index');
Route::post('/painel/gestao/controlador/valororcado/mensal/gravarregistro', 'Painel\Gestao\GestaoController@controlador_valororcado_mensal_gravarregistro')->name('Painel.Gestao.Controlador.ValorOrcado.Mensal.gravarregistro');
Route::get('/painel/gestao/controlador/valororcado/mensal/{id}/editar', 'Painel\Gestao\GestaoController@controlador_valororcado_mensal_editar')->name('Painel.Gestao.Controlador.ValorOrcado.Mensal.editar');
Route::post('/painel/gestao/controlador/valororcado/mensal/editado', 'Painel\Gestao\GestaoController@controlador_valororcado_mensal_editado')->name('Painel.Gestao.Controlador.ValorOrcado.Mensal.editado');
Route::get('/painel/gestao/controlador/valororcado/mensal/exportar', 'Painel\Gestao\GestaoController@controlador_valororcado_mensal_exportar')->name('Painel.Gestao.Controlador.ValorOrcado.Mensal.exportar');


Route::get('/painel/gestao/controlador/contrato/index', 'Painel\Gestao\GestaoController@controlador_contrato_index')->name('Painel.Gestao.Controlador.Contrato.index');
Route::post('/painel/gestao/controlador/contrato/gravarregistro', 'Painel\Gestao\GestaoController@controlador_contrato_gravarregistro')->name('Painel.Gestao.Controlador.Contrato.gravarregistro');
Route::get('/painel/gestao/controlador/contrato/tipo/{id}/contratos', 'Painel\Gestao\GestaoController@controlador_contrato_detalhatipo')->name('Painel.Gestao.Controlador.Contrato.detalhatipo');
Route::get('/painel/gestao/controlador/contrato/tipo/detalhamento/{id}/visualizar', 'Painel\Gestao\GestaoController@controlador_contrato_visualizar')->name('Painel.Gestao.Controlador.Contrato.visualizar');
Route::post('/painel/gestao/controlador/contrato/tipo/detalhamento/contratoanexado', 'Painel\Gestao\GestaoController@controlador_contrato_anexado')->name('Painel.Gestao.Controlador.Contrato.anexado');
Route::get('/painel/gestao/controlador/contrato/exportar', 'Painel\Gestao\GestaoController@controlador_contrato_exportar')->name('Painel.Gestao.Controlador.Contrato.exportar');
Route::get('/painel/gestao/controlador/contrato/{id}/exportardetalhamento', 'Painel\Gestao\GestaoController@controlador_contrato_exportardetalhamento')->name('Painel.Gestao.Controlador.Contrato.exportardetalhamento');

Route::get('/painel/gestao/controlador/usuarios/index', 'Painel\Gestao\GestaoController@controlador_usuarios_index')->name('Painel.Gestao.Controlador.Usuarios.index');
Route::get('/painel/gestao/controlador/usuarios/exportar', 'Painel\Gestao\GestaoController@controlador_usuarios_exportar')->name('Painel.Gestao.Controlador.Usuarios.exportar');

Route::get('/painel/gestao/controlador/usuarios/setores/index', 'Painel\Gestao\GestaoController@controlador_usuarios_setores_index')->name('Painel.Gestao.Controlador.Usuarios.Setores.index');
Route::get('/painel/gestao/controlador/usuarios/setores/{id}/usuarios', 'Painel\Gestao\GestaoController@controlador_usuarios_setores_usuarios')->name('Painel.Gestao.Controlador.Usuarios.Setores.usuarios');
Route::post('/painel/gestao/controlador/usuarios/setores/relacionamentocriado', 'Painel\Gestao\GestaoController@controlador_usuarios_setores_relacionamentocriado')->name('Painel.Gestao.Controlador.Usuarios.Setores.relacionamentocriado');
Route::post('/painel/gestao/controlador/usuarios/setores/relacionamentocancelado', 'Painel\Gestao\GestaoController@controlador_usuarios_setores_relacionamentocancelado')->name('Painel.Gestao.Controlador.Usuarios.Setores.relacionamentocancelado');
Route::post('/painel/gestao/controlador/usuarios/setores/relacionamentoativo', 'Painel\Gestao\GestaoController@controlador_usuarios_setores_relacionamentoativo')->name('Painel.Gestao.Controlador.Usuarios.Setores.relacionamentoativo');

Route::get('/painel/gestao/controlador/usuarios/nivel/index', 'Painel\Gestao\GestaoController@controlador_usuarios_nivel_index')->name('Painel.Gestao.Controlador.Usuarios.Nivel.index');
Route::post('/painel/gestao/controlador/usuarios/nivel/atualizaregistro', 'Painel\Gestao\GestaoController@controlador_usuarios_nivel_atualizaregistro')->name('Painel.Gestao.Controlador.Usuarios.Nivel.atualizaregistro');
Route::get('/painel/gestao/controlador/usuarios/nivel/novorelacionamento', 'Painel\Gestao\GestaoController@controlador_usuarios_nivel_novorelacionamento')->name('Painel.Gestao.Controlador.Usuarios.Nivel.adicionar');
Route::post('/painel/gestao/controlador/usuarios/nivel/relacionado', 'Painel\Gestao\GestaoController@controlador_usuarios_nivel_relacionado')->name('Painel.Gestao.Controlador.Usuarios.Nivel.relacionado');

/********* Rotas DPRH - FERIAS */

Route::get('/painel/dprh/ferias/advogado/index', 'Painel\DPRH\FeriasController@advogado_index')->name('Painel.DPRH.Ferias.Advogado.index');
Route::post('/painel/dprh/ferias/advogado/solicitacaocriada', 'Painel\DPRH\FeriasController@advogado_solicitacaocriada')->name('Painel.DPRH.Ferias.Advogado.solicitacaocriada');
Route::get('/painel/dprh/ferias/advogado/historico', 'Painel\DPRH\FeriasController@advogado_historico')->name('Painel.DPRH.Ferias.Advogado.historico');

Route::get('/painel/dprh/ferias/subcoordenador/index', 'Painel\DPRH\FeriasController@subcoordenador_index')->name('Painel.DPRH.Ferias.SubCoordenador.index');
Route::get('/painel/dprh/ferias/subcoordenador/{id}/revisarsolicitacao', 'Painel\DPRH\FeriasController@subcoordenador_revisarsolicitacao')->name('Painel.DPRH.Ferias.SubCoordenador.revisarsolicitacao');
Route::post('/painel/dprh/ferias/subcoordenador/solicitacaorevisada', 'Painel\DPRH\FeriasController@subcoordenador_solicitacaorevisada')->name('Painel.DPRH.Ferias.SubCoordenador.solicitacaorevisada');
Route::post('/painel/dprh/ferias/subcoordenador/solicitacaocriada', 'Painel\DPRH\FeriasController@subcoordenador_solicitacaocriada')->name('Painel.DPRH.Ferias.SubCoordenador.solicitacaocriada');

Route::get('/painel/dprh/ferias/coordenador/index', 'Painel\DPRH\FeriasController@coordenador_index')->name('Painel.DPRH.Ferias.Coordenador.index');
Route::get('/painel/dprh/ferias/coordenador/{id}/revisarsolicitacao', 'Painel\DPRH\FeriasController@coordenador_revisarsolicitacao')->name('Painel.DPRH.Ferias.Coordenador.revisarsolicitacao');
Route::post('/painel/dprh/ferias/coordenador/solicitacaorevisada', 'Painel\DPRH\FeriasController@coordenador_solicitacaorevisada')->name('Painel.DPRH.Ferias.Coordenador.solicitacaorevisada');
Route::post('/painel/dprh/ferias/coordenador/solicitacaocriada', 'Painel\DPRH\FeriasController@coordenador_solicitacaocriada')->name('Painel.DPRH.Ferias.Coordenador.solicitacaocriada');

Route::get('/painel/dprh/ferias/superintendente/index', 'Painel\DPRH\FeriasController@superintendente_index')->name('Painel.DPRH.Ferias.Superintendente.index');
Route::get('/painel/dprh/ferias/superintendente/{id}/revisarsolicitacao', 'Painel\DPRH\FeriasController@superintendente_revisarsolicitacao')->name('Painel.DPRH.Ferias.Superintendente.revisarsolicitacao');
Route::post('/painel/dprh/ferias/superintendente/solicitacaorevisada', 'Painel\DPRH\FeriasController@superintendente_solicitacaorevisada')->name('Painel.DPRH.Ferias.Superintendente.solicitacaorevisada');
Route::post('/painel/dprh/ferias/superintendente/solicitacaocriada', 'Painel\DPRH\FeriasController@superintendente_solicitacaocriada')->name('Painel.DPRH.Ferias.Superintendente.solicitacaocriada');

Route::get('/painel/dprh/ferias/ggp/index', 'Painel\DPRH\FeriasController@ggp_index')->name('Painel.DPRH.Ferias.GGP.index');
Route::get('/painel/dprh/ferias/ggp/{id}/revisarsolicitacao', 'Painel\DPRH\FeriasController@ggp_revisarsolicitacao')->name('Painel.DPRH.Ferias.GGP.revisarsolicitacao');
Route::post('/painel/dprh/ferias/ggp/solicitacaorevisada', 'Painel\DPRH\FeriasController@ggp_solicitacaorevisada')->name('Painel.DPRH.Ferias.GGP.solicitacaorevisada');

Route::get('/painel/dprh/ferias/rh/index', 'Painel\DPRH\FeriasController@rh_index')->name('Painel.DPRH.Ferias.RH.index');
Route::post('/painel/dprh/ferias/rh/solicitacaorevisada', 'Painel\DPRH\FeriasController@rh_solicitacaorevisada')->name('Painel.DPRH.Ferias.RH.solicitacaorevisada');


/******************************************************************
 * Rotas Contratação
 ******************************************************************/
Route::get('/painel/contratacao/{candidatonome}/anexos', 'Painel\Contratacao\ContratacaoController@contratacao_anexos')->name('Painel.Contratacao.anexos');
Route::get('/painel/contratacao/{caminho}/baixaranexo', 'Painel\Contratacao\ContratacaoController@contratacao_baixaranexo')->name('Painel.Contratacao.baixaranexo');

Route::get('/painel/contratacao/subcoordenador/index', 'Painel\Contratacao\ContratacaoController@contratacao_subcoordenador_index')->name('Painel.Contratacao.SubCoordenador.index');
Route::post('/painel/contratacao/subcoordenador/solicitacaocriada', 'Painel\Contratacao\ContratacaoController@contratacao_subcoordenador_solicitacaocriada')->name('Painel.Contratacao.SubCoordenador.store');
Route::get('/painel/contratacao/subcoordenador/historico', 'Painel\Contratacao\ContratacaoController@contratacao_subcoordenador_historico')->name('Painel.Contratacao.SubCoordenador.historico');

Route::get('/painel/contratacao/coordenador/index', 'Painel\Contratacao\ContratacaoController@contratacao_coordenador_index')->name('Painel.Contratacao.Coordenador.index');
Route::post('/painel/contratacao/coordenador/solicitacaorevisada', 'Painel\Contratacao\ContratacaoController@contratacao_coordenador_solicitacaorevisada')->name('Painel.Contratacao.Coordenador.solicitacaorevisada');
Route::get('/painel/contratacao/coordenador/novacontratacao', 'Painel\Contratacao\ContratacaoController@contratacao_coordenador_novacontratacao')->name('Painel.Contratacao.Coordenador.NovaContratacao');
Route::post('/painel/contratacao/coordenador/solicitacaocriada', 'Painel\Contratacao\ContratacaoController@contratacao_coordenador_solicitacaocriada')->name('Painel.Contratacao.Coordenador.store');
Route::get('/painel/contratacao/coordenador/historico', 'Painel\Contratacao\ContratacaoController@contratacao_coordenador_historico')->name('Painel.Contratacao.Coordenador.historico');

Route::get('/painel/contratacao/superintendente/index', 'Painel\Contratacao\ContratacaoController@contratacao_superintendente_index')->name('Painel.Contratacao.Superintendente.index');
Route::post('/painel/contratacao/superintendente/solicitacaorevisada', 'Painel\Contratacao\ContratacaoController@contratacao_superintendente_solicitacaorevisada')->name('Painel.Contratacao.Superintendente.solicitacaorevisada');
Route::post('/painel/contratacao/superintendente/solicitacaocriada', 'Painel\Contratacao\ContratacaoController@contratacao_superintendente_solicitacaocriada')->name('Painel.Contratacao.Superintendente.store');
Route::get('/painel/contratacao/superintendente/historico', 'Painel\Contratacao\ContratacaoController@contratacao_superintendente_historico')->name('Painel.Contratacao.Superintendente.historico');

Route::get('/painel/contratacao/gerente/index', 'Painel\Contratacao\ContratacaoController@contratacao_gerente_index')->name('Painel.Contratacao.Gerente.index');
Route::post('/painel/contratacao/gerente/solicitacaorevisada', 'Painel\Contratacao\ContratacaoController@contratacao_gerente_solicitacaorevisada')->name('Painel.Contratacao.Gerente.solicitacaorevisada');
Route::post('/painel/contratacao/gerente/solicitacaocriada', 'Painel\Contratacao\ContratacaoController@contratacao_gerente_solicitacaocriada')->name('Painel.Contratacao.Gerente.store');
Route::get('/painel/contratacao/gerente/historico', 'Painel\Contratacao\ContratacaoController@contratacao_gerente_historico')->name('Painel.Contratacao.Gerente.historico');

Route::get('/painel/contratacao/ceoo/index', 'Painel\Contratacao\ContratacaoController@contratacao_ceoo_index')->name('Painel.Contratacao.CEOO.index');
Route::get('/painel/contratacao/ceoo/{id}/revisarsolicitacao', 'Painel\Contratacao\ContratacaoController@contratacao_ceoo_revisarsolicitacao')->name('Painel.Contratacao.CEOO.revisarsolicitacao');
Route::post('/painel/contratacao/ceoo/solicitacaorevisada', 'Painel\Contratacao\ContratacaoController@contratacao_ceoo_solicitacaorevisada')->name('Painel.Contratacao.CEOO.solicitacaorevisada');
Route::get('/painel/contratacao/ceoo/historico', 'Painel\Contratacao\ContratacaoController@contratacao_ceoo_historico')->name('Painel.Contratacao.CEOO.historico');

Route::get('/painel/contratacao/candidato/index', 'Painel\Contratacao\ContratacaoController@contratacao_candidato_index')->name('Painel.Contratacao.Candidato.index');
Route::get('/painel/contratacao/candidato/{token}/preencherinformacoes', 'Painel\Contratacao\ContratacaoController@contratacao_candidato_preencherinformacoes')->name('Painel.Contratacao.Candidato.preencherinformacoes');
Route::post('/painel/contratacao/candidato/dadosarmazenados', 'Painel\Contratacao\ContratacaoController@contratacao_candidato_store')->name('Painel.Contratacao.Candidato.store');

Route::get('/painel/contratacao/rh/index', 'Painel\Contratacao\ContratacaoController@contratacao_rh_index')->name('Painel.Contratacao.RH.index');
Route::post('/painel/contratacao/rh/revisado', 'Painel\Contratacao\ContratacaoController@contratacao_rh_revisado')->name('Painel.Contratacao.RH.revisado');
Route::get('/painel/contratacao/rh/historico', 'Painel\Contratacao\ContratacaoController@contratacao_rh_historico')->name('Painel.Contratacao.RH.historico');
Route::get('/painel/contratacao/rh/{id}/revisadados', 'Painel\Contratacao\ContratacaoController@contratacao_rh_revisadados')->name('Painel.Contratacao.RH.revisadados');
Route::post('/painel/contratacao/rh/revisadodadoscandidato', 'Painel\Contratacao\ContratacaoController@contratacao_rh_revisadodadoscandidato')->name('Painel.Contratacao.RH.revisadodadoscandidato');
Route::post('/painel/contratacao/rh/anexadorecisao', 'Painel\Contratacao\ContratacaoController@contratacao_rh_anexadorecisao')->name('Painel.Contratacao.RH.anexadorecisao');

/******************************************************************
 * Fim Rotas Contratação
 ******************************************************************/


/******************************************************************
 * Rotas Desligamento
 ******************************************************************/
Route::get('/painel/dprh/desligamento/advogado/index', 'Painel\DPRH\DesligamentoController@advogado_index')->name('Painel.DPRH.Desligamento.Advogado.index');
Route::post('/painel/dprh/desligamento/advogado/solicitacaocriada', 'Painel\DPRH\DesligamentoController@advogado_solicitacaocriada')->name('Painel.DPRH.Desligamento.Advogado.store');

Route::get('/painel/dprh/desligamento/subcoordenador/index', 'Painel\DPRH\DesligamentoController@subcoordenador_index')->name('Painel.DPRH.Desligamento.SubCoordenador.index');
Route::post('/painel/dprh/desligamento/subcoordenador/solicitacaocriada', 'Painel\DPRH\DesligamentoController@subcoordenador_solicitacaocriada')->name('Painel.DPRH.Desligamento.SubCoordenador.store');
Route::post('/painel/dprh/desligamento/subcoordenador/solicitacaoglosada', 'Painel\DPRH\DesligamentoController@subcoordenador_solicitacaoglosada')->name('Painel.DPRH.Desligamento.SubCoordenador.solicitacaoglosada');
Route::post('/painel/dprh/desligamento/subcoordenador/solicitacaoliberada', 'Painel\DPRH\DesligamentoController@subcoordenador_solicitacaoliberada')->name('Painel.DPRH.Desligamento.SubCoordenador.solicitacaoliberada');
Route::get('/painel/dprh/desligamento/subcoordenador/historico', 'Painel\DPRH\DesligamentoController@subcoordenador_historico')->name('Painel.DPRH.Desligamento.SubCoordenador.historico');

Route::get('/painel/dprh/desligamento/coordenador/index', 'Painel\DPRH\DesligamentoController@coordenador_index')->name('Painel.DPRH.Desligamento.Coordenador.index');
Route::post('/painel/dprh/desligamento/coordenador/solicitacaocriada', 'Painel\DPRH\DesligamentoController@coordenador_solicitacaocriada')->name('Painel.DPRH.Desligamento.Coordenador.store');
Route::post('/painel/dprh/desligamento/coordenador/solicitacaoglosada', 'Painel\DPRH\DesligamentoController@coordenador_solicitacaoglosada')->name('Painel.DPRH.Desligamento.Coordenador.solicitacaoglosada');
Route::post('/painel/dprh/desligamento/coordenador/solicitacaoliberada', 'Painel\DPRH\DesligamentoController@coordenador_solicitacaoliberada')->name('Painel.DPRH.Desligamento.Coordenador.solicitacaoliberada');
Route::get('/painel/dprh/desligamento/coordenador/historico', 'Painel\DPRH\DesligamentoController@coordenador_historico')->name('Painel.DPRH.Desligamento.Coordenador.historico');

Route::get('/painel/dprh/desligamento/superintendente/index', 'Painel\DPRH\DesligamentoController@superintendente_index')->name('Painel.DPRH.Desligamento.Superintendente.index');
Route::post('/painel/dprh/desligamento/superintendente/solicitacaocriada', 'Painel\DPRH\DesligamentoController@superintendente_solicitacaocriada')->name('Painel.DPRH.Desligamento.Superintendente.store');
Route::post('/painel/dprh/desligamento/superintendente/solicitacaoglosada', 'Painel\DPRH\DesligamentoController@superintendente_solicitacaoglosada')->name('Painel.DPRH.Desligamento.Superintendente.solicitacaoglosada');
Route::post('/painel/dprh/desligamento/superintendente/solicitacaoliberada', 'Painel\DPRH\DesligamentoController@superintendente_solicitacaoliberada')->name('Painel.DPRH.Desligamento.Superintendente.solicitacaoliberada');
Route::get('/painel/dprh/desligamento/superintendente/historico', 'Painel\DPRH\DesligamentoController@superintendente_historico')->name('Painel.DPRH.Desligamento.Superintendente.historico');

Route::get('/painel/dprh/desligamento/gerente/index', 'Painel\DPRH\DesligamentoController@gerente_index')->name('Painel.DPRH.Desligamento.Gerente.index');
Route::post('/painel/dprh/desligamento/gerente/solicitacaocriada', 'Painel\DPRH\DesligamentoController@gerente_solicitacaocriada')->name('Painel.DPRH.Desligamento.Gerente.store');
Route::post('/painel/dprh/desligamento/gerente/solicitacaoglosada', 'Painel\DPRH\DesligamentoController@gerente_solicitacaoglosada')->name('Painel.DPRH.Desligamento.Gerente.solicitacaoglosada');
Route::post('/painel/dprh/desligamento/gerente/solicitacaoliberada', 'Painel\DPRH\DesligamentoController@gerente_solicitacaoliberada')->name('Painel.DPRH.Desligamento.Gerente.solicitacaoliberada');
Route::get('/painel/dprh/desligamento/gerente/historico', 'Painel\DPRH\DesligamentoController@gerente_historico')->name('Painel.DPRH.Desligamento.Gerente.historico');


Route::get('/painel/dprh/desligamento/rh/index', 'Painel\DPRH\DesligamentoController@rh_index')->name('Painel.DPRH.Desligamento.RH.index');
Route::post('/painel/dprh/desligamento/rh/anexadodocumentacao', 'Painel\DPRH\DesligamentoController@rh_anexadodocumentacao')->name('Painel.DPRH.Desligamento.RH.store');
/******************************************************************
 * Fim Rotas Desligamento 
 ******************************************************************/


 /******************************************************************
 * Rotas Progressão 
 ******************************************************************/

Route::get('/painel/dprh/progressao/advogado/index', 'Painel\DPRH\ProgressaoController@advogado_index')->name('Painel.DPRH.Progressao.Advogado.index');
Route::post('/painel/dprh/progressao/advogado/solicitacaocriada', 'Painel\DPRH\ProgressaoController@advogado_solicitacaocriada')->name('Painel.DPRH.Progressao.Advogado.store');

Route::get('/painel/dprh/progressao/subcoordenador/index', 'Painel\DPRH\ProgressaoController@subcoordenador_index')->name('Painel.DPRH.Progressao.SubCoordenador.index');
Route::post('/painel/dprh/progressao/subcoordenador/solicitacaorevisada', 'Painel\DPRH\ProgressaoController@subcoordenador_solicitacaorevisada')->name('Painel.DPRH.Progressao.SubCoordenador.solicitacaorevisada');

Route::get('/painel/dprh/progressao/coordenador/index', 'Painel\DPRH\ProgressaoController@coordenador_index')->name('Painel.DPRH.Progressao.Coordenador.index');
Route::post('/painel/dprh/progressao/coordenador/solicitacaorevisada', 'Painel\DPRH\ProgressaoController@coordenador_solicitacaorevisada')->name('Painel.DPRH.Progressao.Coordenador.solicitacaorevisada');

Route::get('/painel/dprh/progressao/superintendente/index', 'Painel\DPRH\ProgressaoController@superintendente_index')->name('Painel.DPRH.Progressao.Superintendente.index');
Route::post('/painel/dprh/progressao/superintendente/solicitacaorevisada', 'Painel\DPRH\ProgressaoController@superintendente_solicitacaorevisada')->name('Painel.DPRH.Progressao.Superintendente.solicitacaorevisada');

Route::get('/painel/dprh/progressao/gerente/index', 'Painel\DPRH\ProgressaoController@gerente_index')->name('Painel.DPRH.Progressao.Gerente.index');
Route::post('/painel/dprh/progressao/gerente/solicitacaorevisada', 'Painel\DPRH\ProgressaoController@gerente_solicitacaorevisada')->name('Painel.DPRH.Progressao.Gerente.solicitacaorevisada');

Route::get('/painel/dprh/progressao/ceo/index', 'Painel\DPRH\ProgressaoController@ceo_index')->name('Painel.DPRH.Progressao.CEO.index');
Route::post('/painel/dprh/progressao/ceo/solicitacaorevisada', 'Painel\DPRH\ProgressaoController@ceo_solicitacaorevisada')->name('Painel.DPRH.Progressao.CEO.solicitacaorevisada');

Route::get('/painel/dprh/progressao/rh/index', 'Painel\DPRH\ProgressaoController@rh_index')->name('Painel.DPRH.Progressao.RH.index');
Route::post('/painel/dprh/progressao/rh/solicitacaorevisada', 'Painel\DPRH\ProgressaoController@rh_solicitacaorevisada')->name('Painel.DPRH.Progressao.RH.solicitacaorevisada');

/******************************************************************
 * Fim Rotas Progressão 
 ******************************************************************/


/******************************************************************
 * Rotas Licença 
 ******************************************************************/

Route::get('/painel/dprh/licenca/advogado/index', 'Painel\DPRH\LicencaController@advogado_index')->name('Painel.DPRH.Licenca.Advogado.index');
Route::post('/painel/dprh/licenca/advogado/solicitacaocriada', 'Painel\DPRH\LicencaController@advogado_solicitacaocriada')->name('Painel.DPRH.Licenca.Advogado.store');

Route::get('/painel/dprh/licenca/subcoordenador/index', 'Painel\DPRH\LicencaController@subcoordenador_index')->name('Painel.DPRH.Licenca.SubCoordenador.index');
Route::post('/painel/dprh/licenca/subcoordenador/solicitacaorevisada', 'Painel\DPRH\LicencaController@subcoordenador_solicitacaorevisada')->name('Painel.DPRH.Licenca.SubCoordenador.solicitacaorevisada');

Route::get('/painel/dprh/licenca/coordenador/index', 'Painel\DPRH\LicencaController@coordenador_index')->name('Painel.DPRH.Licenca.Coordenador.index');
Route::post('/painel/dprh/licenca/coordenador/solicitacaorevisada', 'Painel\DPRH\LicencaController@coordenador_solicitacaorevisada')->name('Painel.DPRH.Licenca.Coordenador.solicitacaorevisada');

Route::get('/painel/dprh/licenca/superintendente/index', 'Painel\DPRH\LicencaController@superintendente_index')->name('Painel.DPRH.Licenca.Superintendente.index');
Route::post('/painel/dprh/licenca/superintendente/solicitacaorevisada', 'Painel\DPRH\LicencaController@superintendente_solicitacaorevisada')->name('Painel.DPRH.Licenca.Superintendente.solicitacaorevisada');

Route::get('/painel/dprh/licenca/gerente/index', 'Painel\DPRH\LicencaController@gerente_index')->name('Painel.DPRH.Licenca.Gerente.index');
Route::post('/painel/dprh/licenca/gerente/solicitacaorevisada', 'Painel\DPRH\LicencaController@gerente_solicitacaorevisada')->name('Painel.DPRH.Licenca.Gerente.solicitacaorevisada');

Route::get('/painel/dprh/licenca/ggp/index', 'Painel\DPRH\LicencaController@ggp_index')->name('Painel.DPRH.Licenca.GGP.index');

/******************************************************************
 * Fim Rotas Licença 
 ******************************************************************/


 /******************************************************************
 * Rotas Mudança de Area 
 ******************************************************************/

Route::get('/painel/dprh/mudancaarea/advogado/index', 'Painel\DPRH\MudancaAreaController@advogado_index')->name('Painel.DPRH.MudancaArea.Advogado.index');
Route::post('/painel/dprh/mudancaarea/advogado/solicitacaocriada', 'Painel\DPRH\MudancaAreaController@advogado_solicitacaocriada')->name('Painel.DPRH.MudancaArea.Advogado.store');

Route::get('/painel/dprh/mudancaarea/coordenador/index', 'Painel\DPRH\MudancaAreaController@coordenador_index')->name('Painel.DPRH.MudancaArea.Coordenador.index');
Route::post('/painel/dprh/mudancaarea/coordenador/solicitacaorevisada', 'Painel\DPRH\MudancaAreaController@coordenador_solicitacaorevisada')->name('Painel.DPRH.MudancaArea.Coordenador.store');
Route::post('/painel/dprh/mudancaarea/coordenador/solicitacaodestinorevisada', 'Painel\DPRH\MudancaAreaController@coordenador_solicitacaodestinorevisada')->name('Painel.DPRH.MudancaArea.Coordenador.store2');

Route::get('/painel/dprh/mudancaarea/gerente/index', 'Painel\DPRH\MudancaAreaController@gerente_index')->name('Painel.DPRH.MudancaArea.Gerente.index');
Route::post('/painel/dprh/mudancaarea/gerente/solicitacaorevisada', 'Painel\DPRH\MudancaAreaController@gerente_solicitacaorevisada')->name('Painel.DPRH.MudancaArea.Gerente.store');

Route::get('/painel/dprh/mudancaarea/ceo/index', 'Painel\DPRH\MudancaAreaController@ceo_index')->name('Painel.DPRH.MudancaArea.CEO.index');
Route::post('/painel/dprh/mudancaarea/ceo/solicitacaorevisada', 'Painel\DPRH\MudancaAreaController@ceo_solicitacaorevisada')->name('Painel.DPRH.MudancaArea.CEO.store');

/******************************************************************
 * Fim Rotas Mudança de Area  
 ******************************************************************/

 /******************************************************************
 * Rotas Escritório - Solicitações 
 ******************************************************************/
Route::get('/painel/escritorio/solicitacoes/{id}/anexos', 'Painel\Escritorio\SolicitacoesController@anexos')->name('Painel.Escritorio.Solicitacoes.anexos');


Route::get('/painel/escritorio/solicitacoes/advogado/index', 'Painel\Escritorio\SolicitacoesController@advogado_index')->name('Painel.Escritorio.Solicitacoes.Advogado.index');
Route::post('/painel/escritorio/solicitacoes/advogado/solicitacaocriada', 'Painel\Escritorio\SolicitacoesController@advogado_solicitacaocriada')->name('Painel.Escritorio.Solicitacoes.Advogado.store');
Route::post('/painel/escritorio/solicitacoes/advogado/buscaprodutos', 'Painel\Escritorio\SolicitacoesController@buscaProdutos')->name('Painel.Escritorio.Solicitacoes.Advogado.buscaprodutos');
Route::post('/painel/escritorio/solicitacoes/advogado/solicitacaocancelada', 'Painel\Escritorio\SolicitacoesController@advogado_solicitacaocancelada')->name('Painel.Escritorio.Solicitacoes.Advogado.solicitacacancelada');
Route::get('/painel/escritorio/solicitacoes/advogado/historico', 'Painel\Escritorio\SolicitacoesController@advogado_historico')->name('Painel.Escritorio.Solicitacoes.Advogado.historico');

Route::get('/painel/escritorio/solicitacoes/administrativo/index', 'Painel\Escritorio\SolicitacoesController@administrativo_index')->name('Painel.Escritorio.Solicitacoes.Administrativo.index');
Route::get('/painel/escritorio/solicitacoes/administrativo/{id}/formulario', 'Painel\Escritorio\SolicitacoesController@administrativo_formulario')->name('Painel.Escritorio.Solicitacoes.Administrativo.formulario');
Route::post('/painel/escritorio/solicitacoes/administrativo/formulariopreenchido', 'Painel\Escritorio\SolicitacoesController@administrativo_formulariopreenchido')->name('Painel.Escritorio.Solicitacoes.Administrativo.store');
Route::get('/painel/escritorio/solicitacoes/administrativo/{id}/finalizarsolicitacao', 'Painel\Escritorio\SolicitacoesController@administrativo_finalizarsolicitacao')->name('Painel.Escritorio.Solicitacoes.Administrativo.finalizarsolicitacao');
Route::post('/painel/escritorio/solicitacoes/administrativo/solicitacaofinalizada', 'Painel\Escritorio\SolicitacoesController@administrativo_solicitacaofinalizada')->name('Painel.Escritorio.Solicitacoes.Administrativo.solicitacaofinalizada');
Route::get('/painel/escritorio/solicitacoes/administrativo/historico', 'Painel\Escritorio\SolicitacoesController@administrativo_historico')->name('Painel.Escritorio.Solicitacoes.Administrativo.historico');
Route::post('/painel/escritorio/solicitacoes/administrativo/buscafornecedores', 'Painel\Escritorio\SolicitacoesController@administrativo_buscafornecedores')->name('Painel.Escritorio.Solicitacoes.Administrativo.buscafornecedores');

Route::get('/painel/escritorio/solicitacoes/financeiro/index', 'Painel\Escritorio\SolicitacoesController@financeiro_index')->name('Painel.Escritorio.Solicitacoes.Financeiro.index');
Route::get('/painel/escritorio/solicitacoes/financeiro/{id}/formulario', 'Painel\Escritorio\SolicitacoesController@financeiro_formulario')->name('Painel.Escritorio.Solicitacoes.Financeiro.formulario');
Route::post('/painel/escritorio/solicitacoes/financeiro/formulariopreenchido', 'Painel\Escritorio\SolicitacoesController@financeiro_formulariorevisado')->name('Painel.Escritorio.Solicitacoes.Financeiro.store');
Route::get('/painel/escritorio/solicitacoes/financeiro/historico', 'Painel\Escritorio\SolicitacoesController@financeiro_historico')->name('Painel.Escritorio.Solicitacoes.Financeiro.historico');


 /******************************************************************
 * Fim Rota Escritório - Solicitações 
 ******************************************************************/


/******************************************************************
 * Rotas Propostas 
 ******************************************************************/

Route::get('/painel/financeiro/proposta/{Numero}/anexo', 'Painel\Propostas\PropostaController@anexo')->name('Painel.Proposta.anexo');

Route::get('/painel/financeiro/proposta/solicitacao/index', 'Painel\Propostas\PropostaController@solicitacao_index')->name('Painel.Proposta.solicitacao.index');
Route::get('/painel/financeiro/proposta/solicitacao/novaproposta', 'Painel\Propostas\PropostaController@solicitacao_create')->name('Painel.Proposta.solicitacao.create');
Route::post('/painel/financeiro/proposta/solicitacao/store', 'Painel\Propostas\PropostaController@solicitacao_store')->name('Painel.Proposta.solicitacao.store');
Route::get('/painel/financeiro/proposta/solicitacao/{Numero}/editar', 'Painel\Propostas\PropostaController@solicitacao_editar')->name('Painel.Proposta.solicitacao.editar');
Route::post('/painel/financeiro/proposta/solicitacao/update', 'Painel\Propostas\PropostaController@solicitacao_update')->name('Painel.Proposta.solicitacao.update');

Route::get('/painel/financeiro/proposta/hierarquia/index', 'Painel\Propostas\PropostaController@hierarquia_index')->name('Painel.Proposta.hierarquia.index');
Route::get('/painel/financeiro/proposta/hierarquia/novaproposta', 'Painel\Propostas\PropostaController@hierarquia_create')->name('Painel.Proposta.hierarquia.create');
Route::post('/painel/financeiro/proposta/hierarquia/store', 'Painel\Propostas\PropostaController@hierarquia_store')->name('Painel.Proposta.hierarquia.store');
Route::get('/painel/financeiro/proposta/hierarquia/{Numero}/editar', 'Painel\Propostas\PropostaController@hierarquia_editar')->name('Painel.Proposta.hierarquia.editar');
Route::post('/painel/financeiro/proposta/hierarquia/update', 'Painel\Propostas\PropostaController@hierarquia_update')->name('Painel.Proposta.hierarquia.update');


Route::get('/painel/financeiro/proposta/revisao/index', 'Painel\Propostas\PropostaController@revisao_index')->name('Painel.Proposta.revisao.index');
Route::get('/painel/financeiro/proposta/revisao/listagemrevisar', 'Painel\Propostas\PropostaController@revisao_listagemrevisar')->name('Painel.Proposta.revisao.revisar');
Route::get('/painel/financeiro/proposta/revisao/listagemgeral', 'Painel\Propostas\PropostaController@revisao_listagemgeral')->name('Painel.Proposta.revisao.listagemgeral');
Route::get('/painel/financeiro/proposta/revisao/{id}/revisarproposta', 'Painel\Propostas\PropostaController@revisao_revisarproposta')->name('Painel.Proposta.revisao.revisarproposta');
Route::post('/painel/financeiro/proposta/revisao/update', 'Painel\Propostas\PropostaController@revisao_update')->name('Painel.Proposta.revisao.update');


/******************************************************************
*  FIM ROTAS PROPOSTAS
******************************************************************/
// Route::get('/painel/compras/index', 'Painel\Compras\ComprasController@index')->name('Painel.Compras.index');
// Route::post('/painel/compras/salvar-nova-compra', 'Painel\Compras\ComprasController@salvarNovaCompra')->name('Painel.Compras.salvarNovaCompra');
// Route::get('/painel/compras/{id}/editar-compra', 'Painel\Compras\ComprasController@editarCompra')->name('Painel.Compras.editarCompra');
// Route::get('/painel/compras/{id}/excluir-compra', 'Painel\Compras\ComprasController@excluirCompra')->name('Painel.Compras.excluirCompra');
// Route::get('/painel/compras/{id}/download-anexo', 'Painel\Compras\ComprasController@downloadAnexo')->name('Painel.Compras.downloadAnexo');

//Solicitante
Route::get('/painel/compras/solicitante/index', 'Painel\Compras\ComprasController@index_solicitante')->name('Painel.Compras.Solicitante.index_solicitante');
Route::get('/painel/compras/solicitante/nova-solicitacao', 'Painel\Compras\ComprasController@novasolicitacao')->name('Painel.Compras.Solicitante.novasolicitacao');
Route::post('/painel/compras/solicitante/salvar-nova-solicitacao', 'Painel\Compras\ComprasController@salvarNovaSolicitacao')->name('Painel.Compras.Solicitante.salvarNovaSolicitacao');
Route::post('/painel/compras/solicitante/busca-produtos', 'Painel\Compras\ComprasController@buscaProdutos')->name('Painel.Compras.Solicitante.buscaProdutos');

//Comitê de compras 
Route::get('/painel/compras/comite-compras/index', 'Painel\Compras\ComprasController@index_comite_compras')->name('Painel.Compras.ComiteCompras.index_comite');
Route::get('/painel/compras/comite-compras/{id}/formulario', 'Painel\Compras\ComprasController@formulario')->name('Painel.Compras.ComiteCompras.formulario');
Route::get('/painel/compras/comite-compras/{id}/download', 'Painel\Compras\ComprasController@downloadAnexoSolicitacao')->name('Painel.Compras.ComiteCompras.downloadanexosolicitacao');
Route::post('/painel/compras/comite-compras/envia-para-aprovacao', 'Painel\Compras\ComprasController@enviaParaAprovacao')->name('Painel.Compras.ComiteCompras.enviaParaAprovacao');
Route::get('/painel/compras/comite-compras/historico', 'Painel\Compras\ComprasController@historico_comite_compras')->name('Painel.Compras.ComiteCompras.historico_comite_compras');

//Comitê de aprovação 
Route::get('/painel/compras/comite-aprovacao/index', 'Painel\Compras\ComprasController@index_comite_aprovacao')->name('Painel.Compras.ComiteAprovacao.index_comite_aprovacao');
Route::get('/painel/compras/comite-aprovacao/{anexo}/download-anexo', 'Painel\Compras\ComprasController@downloadAnexoAprovacao')->name('Painel.Compras.ComiteAprovacao.downloadAnexoAprovacao');
Route::get('/painel/compras/comite-aprovacao/{id}/aprovar', 'Painel\Compras\ComprasController@aprovar')->name('Painel.Compras.ComiteAprovacao.aprovar');
Route::get('/painel/compras/comite-aprovacao/{id}/excluir-fornecedor', 'Painel\Compras\ComprasController@excluirFornecedor')->name('Painel.Compras.ComiteAprovacao.excluirFornecedor');
Route::post('/painel/compras/comite-aprovacao/{id}/aprovar-compra', 'Painel\Compras\ComprasController@aprovarCompra')->name('Painel.Compras.ComiteAprovacao.aprovarCompra');
Route::get('/painel/compras/comite-aprovacao/{id}/download', 'Painel\Compras\ComprasController@downloadAnexoAprovacao')->name('Painel.Compras.ComiteAprovacao.downloadanexoaprovacao');
Route::get('/painel/compras/comite-aprovacao/historico', 'Painel\Compras\ComprasController@historico_comite_aprovacao')->name('Painel.Compras.ComiteAprovacao.historico_comite_aprovacao');
Route::get('/painel/compras/comite-aprovacao/{id}/historico-fornecedores', 'Painel\Compras\ComprasController@historico_fornecedores')->name('Painel.Compras.ComiteAprovacao.historico_fornecedores');
Route::get('/painel/compras/comite-aprovacao/{id}/reprovar', 'Painel\Compras\ComprasController@reprovar')->name('Painel.Compras.ComiteAprovacao.reprovar');
// Route::get('/painel/compras/comite-de-compras/{id}/formulario', 'Painel\Compras\ComprasController@formulario')->name('Painel.Compras.ComiteCompras.formulario');
// Route::post('/painel/compras/comite-de-compras/envia-para-aprovacao', 'Painel\Compras\ComprasController@enviaParaAprovacao')->name('Painel.Compras.ComiteCompras.enviaParaAprovacao');

/******************************************************************
 * Rotas do Correspondente
 ******************************************************************/
Route::get('/painel/correspondente/dashboard', 'Painel\Correspondente\PainelController@index')->name('Painel.Correspondente.principal');
Route::get('/painel/correspondente/dashboard2', 'Painel\Correspondente\PainelController@index2')->name('Painel.Correspondente.principal2');
Route::get('/painel/correspondente/index', 'Painel\Correspondente\CorrespondenteController@index')->name('Painel.Correspondente.index');
Route::get('/painel/correspondente/profile', 'Painel\Correspondente\CorrespondenteController@profile')->name('Painel.Correspondente.pages.profile');
Route::get('/painel/correspondente/pagas', 'Painel\Correspondente\CorrespondenteController@pagas')->name('Painel.Correspondente.pagas');
Route::get('/painel/correspondente/canceladas', 'Painel\Correspondente\CorrespondenteController@canceladas')->name('Painel.Correspondente.canceladas');
Route::get('/painel/correspondente/{Numero}/show', 'Painel\Correspondente\CorrespondenteController@show')->name('Painel.Correspondente.show');
Route::post('/painel/correspondente/store', 'Painel\Correspondente\CorrespondenteController@store')->name('Painel.Correspondente.store');
Route::get('/painel/correspondente/{id}/aprovar', 'Painel\Correspondente\CorrespondenteController@edit')->name('Painel.Correspondente.aprovar');
Route::get('/painel/correspondente/{id}/reprovar', 'Painel\Correspondente\CorrespondenteController@edit')->name('Painel.Correspondente.reprovar');
Route::get('/painel/correspondente/create', 'Painel\Correspondente\CorrespondenteController@create')->name('Painel.Correspondente.create');
Route::any('/painel/correspondente/step2', 'Painel\Correspondente\CorrespondenteController@step2')->name('Painel.Correspondente.step2');
Route::any('/painel/correspondente/step3', 'Painel\Correspondente\CorrespondenteController@step3')->name('Painel.Correspondente.step3');
Route::get('/painel/correspondente/{numero}/{tipo}/step4', 'Painel\Correspondente\CorrespondenteController@step4')->name('Painel.Correspondente.step4');
Route::get('/painel/correspondente/{numeroprocesso}/showpesquisa', 'Painel\Correspondente\CorrespondenteController@showpesquisa')->name('Painel.Correspondente.showpesquisa');
Route::post('/painel/correspondente/gravarpesquisa', 'Painel\Correspondente\CorrespondenteController@gravarpesquisa')->name('Painel.Correspondente.gravarpesquisa');
Route::post('/painel/correspondente/anexarArquivo', 'Painel\Correspondente\CorrespondenteController@anexarArquivo')->name('Painel.Correspondente.anexar');
Route::get('/painel/correspondente/{numero}/imprimir', 'Painel\Correspondente\CorrespondenteController@imprimir')->name('Painel.Correspondente.imprimir');
Route::get('/painel/correspondente/DeletarRegistro', 'Painel\Correspondente\CorrespondenteController@DeletarRegistro')->name('Painel.Correspondente.DeletarRegistro');
Route::post('/painel/correspondente/finalizarRegistro', 'Painel\Correspondente\CorrespondenteController@finalizarRegistro')->name('Painel.Correspondente.finalizarRegistro');
Route::get('/painel/correspondente/pagas', 'Painel\Correspondente\CorrespondenteController@pagas')->name('Painel.Correspondente.pagas');
//Route::get('/Painel/Correspondente/autocomplete', 'Painel\Correspondente\PainelController@autocomplete')->name('Painel.Notas.autocomplete');
Route::get('/painel/correspondente/{Numero}/cancelar', 'Painel\Correspondente\CorrespondenteController@cancelar')->name('Painel.Correspondente.cancelar');
Route::post('/painel/correspondente/darbaixa', 'Painel\Correspondente\CorrespondenteController@darbaixa')->name('Painel.Correspondente.darbaixa');
Route::get('/painel/correspondente/pendentes', 'Painel\Correspondente\CorrespondenteController@pendentes')->name('Painel.Correspondente.pendentes');
Route::get('/painel/correspondente/{Numero}/corrigir', 'Painel\Correspondente\CorrespondenteController@corrigir')->name('Painel.Correspondente.corrigir');
Route::post('/painel/correspondente/corrigido', 'Painel\Correspondente\CorrespondenteController@corrigido')->name('Painel.Correspondente.corrigido');
Route::get('/painel/correspondente/gerarExcelAbertas', 'Painel\Correspondente\CorrespondenteController@gerarExcelAbertas')->name('Painel.Correspondente.gerarExcelAbertas');
Route::get('/painel/correspondente/{Numero}/comprovante', 'Painel\Correspondente\CorrespondenteController@comprovante')->name('Painel.Correspondente.comprovante');
Route::get('/painel/correspondente/gerarExcelPagas', 'Painel\Correspondente\CorrespondenteController@gerarExcelPagas')->name('Painel.Correspondente.gerarExcelPagas');
Route::get('/painel/correspondente/{Numero}/anexo', 'Painel\Correspondente\CorrespondenteController@anexo')->name('Painel.Correspondente.anexo');
Route::post('/painel/correspondente/{id}/atualizarusuario', 'Painel\Correspondente\CorrespondenteController@updateUsuario')->name('Painel.Correspondente.update');
Route::get('/painel/correspondente/{Numero}/timeline', 'Painel\Correspondente\CorrespondenteController@timeline')->name('Painel.Correspondente.timeline');

Route::get('/painel/correspondente/{token}/novoprestadorservicos', 'Painel\Correspondente\CorrespondenteController@novoprestador_preencherinformacoes')->name('Painel.Correspondente.NovoPrestador.preencherinformacoes');
Route::post('/painel/correspondente/novoprestadorservicos/gravainformacoes', 'Painel\Correspondente\CorrespondenteController@novoprestador_gravainformacoes')->name('Painel.Correspondente.NovoPrestador.store');
Route::get('/painel/correspondente/politicaprivacidade', 'Painel\Correspondente\CorrespondenteController@politicaprivacidade')->name('Painel.Correspondente.PoliticaPrivacidade');
Route::get('/painel/correspondente/pegarDadosDashboard', 'Painel\Correspondente\CorrespondenteController@pegarDadosDashboard')->name('Painel.Correspondente.pegarDadosDashboard');
Route::get('/painel/correspondente/pegarDadosTabela', 'Painel\Correspondente\CorrespondenteController@pegarDadosTabela')->name('Painel.Correspondente.pegarDadosTabela');
Route::get('/painel/correspondente/listCorrespondentes', 'Painel\Correspondente\CorrespondenteController@listCorrespondentes')->name('Painel.Correspondente.listCorrespondente');
Route::get('/painel/correspondente/getComarca', 'Painel\Correspondente\CorrespondenteController@getComarca')->name('Painel.Correspondente.getComarca');
Route::post('/painel/correspondente/inserirAvaliacao', 'Painel\Correspondente\CorrespondenteController@avaliarCorrespondente')->name('Painel.Correspondente.inserirAvaliacao');
Route::post('/painel/correspondente/contratarCorrespondente', 'Painel\Correspondente\CorrespondenteController@contratarCorrespondente')->name('Painel.Correspondente.contratarCorrespondente');
Route::post('/painel/correspondente/novoCorrespondente', 'Painel\Correspondente\CorrespondenteController@novoCorrespondente')->name('Painel.Correspondente.novoCorrespondente');


/******************************************************************
 * End Rota Correspondente
 ******************************************************************/

/******************************************************************
 * Rotas do Advogado
 ******************************************************************/
Route::get('/painel/advogado/dashboard', 'Painel\Advogado\PainelController@index')->name('Painel.Advogado.principal');
Route::get('/painel/advogado', 'Painel\Advogado\AdvogadoController@index')->name('Painel.Advogado.index');
Route::get('/painel/advogado/pagas', 'Painel\Advogado\AdvogadoController@pagas')->name('Painel.Advogado.pagas');
Route::get('/painel/advogado/{Numero}/show', 'Painel\Advogado\AdvogadoController@show')->name('Painel.Advogado.show');
Route::get('/painel/advogado/{Numero}/imprimir', 'Painel\Advogado\AdvogadoController@imprimir')->name('Painel.Advogado.imprimir');

Route::get('/painel/advogado/novoprestadorservico/index', 'Painel\Advogado\AdvogadoController@novoprestador_index')->name('Painel.Advogado.NovoPrestador.index');
Route::post('/painel/advogado/novoprestadorservico/enviatoken', 'Painel\Advogado\AdvogadoController@novoprestador_enviaautorizacao')->name('Painel.Advogado.NovoPrestador.enviaautorizacao');
Route::post('/painel/advogado/novoprestadorservico/solicitacaoenviada', 'Painel\Advogado\AdvogadoController@novoprestador_solicitacaoenviada')->name('Painel.Advogado.NovoPrestador.solicitacaoenviada');
Route::post('/painel/advogado/novoprestadorservico/editarclassificacao', 'Painel\Advogado\AdvogadoController@novoprestador_editarclassificacao')->name('Painel.Advogado.NovoPrestador.editarclassificacao');

/******************************************************************
 * End Rota Advogado
 ******************************************************************/

/******************************************************************
 * Rotas do Financeiro
 ******************************************************************/
 Route::get('/painel/financeiro/dashboard', 'Painel\Financeiro\PainelController@index')->name('Painel.Financeiro.principal');
 Route::get('/painel/financeiro', 'Painel\Financeiro\FinanceiroController@index')->name('Painel.Financeiro.index');
 Route::get('/painel/financeiro/{Numero}/show', 'Painel\Financeiro\FinanceiroController@show')->name('Painel.Financeiro.show');
 Route::get('/painel/financeiro/{Numero}/imprimir', 'Painel\Financeiro\FinanceiroController@imprimir')->name('Painel.Financeiro.imprimir');
 Route::get('/painel/financeiro/pagas', 'Painel\Financeiro\FinanceiroController@pagas')->name('Painel.Financeiro.pagas');
 Route::get('/painel/financeiro/gerarExcelAguardandoPagamento', 'Painel\Financeiro\FinanceiroController@gerarExcelAguardandoPagamento')->name('Painel.Financeiro.gerarExcelAguardandoPagamento');
 Route::get('/painel/financeiro/{Numero}/aprovar', 'Painel\Financeiro\FinanceiroController@aprovar')->name('Painel.Financeiro.aprovar');
 Route::get('/painel/financeiro/aprovadas', 'Painel\Financeiro\FinanceiroController@aprovadas')->name('Painel.Financeiro.aprovadas');
 Route::get('/painel/financeiro/{Numero}/reprovar', 'Painel\Financeiro\FinanceiroController@reprovar')->name('Painel.Financeiro.reprovar');
 Route::post('/painel/financeiro/pendenciar', 'Painel\Financeiro\FinanceiroController@pendenciar')->name('Painel.Financeiro.pendenciar');
 Route::get('/painel/financeiro/programadas', 'Painel\Financeiro\FinanceiroController@programadas')->name('Painel.Financeiro.programadas');
 Route::get('/painel/financeiro/aguardandopagamento', 'Painel\Financeiro\FinanceiroController@aguardandopagamento')->name('Painel.Financeiro.aguardandopagamento');
 Route::get('/painel/financeiro/{numero}/anexos', 'Painel\Financeiro\FinanceiroController@correspondente_anexos')->name('Painel.Financeiro.Correspondente.anexos');

 Route::get('/painel/financeiro/telefones/index', 'Painel\Financeiro\FinanceiroController@telefones')->name('Painel.Financeiro.telefones');
 Route::get('/painel/financeiro/telefones/create', 'Painel\Financeiro\FinanceiroController@telefonescreate')->name('Painel.Financeiro.Telefones.create');
 Route::get('/painel/financeiro/gerarExcelTelefone', 'Painel\Financeiro\FinanceiroController@gerarExcelTelefone')->name('Painel.Financeiro.Telefones.gerarExcelTelefone');
 Route::get('/painel/financeiro/telefones/{id}/desativarnumero', 'Painel\Financeiro\FinanceiroController@telefones_desativarnumero')->name('Painel.Financeiro.Telefones.desativarnumero');
 Route::get('/painel/financeiro/telefones/salvar-edicao', 'Painel\Financeiro\FinanceiroController@salvarEdicaoTelefones')->name('Painel.Financeiro.Telefones.salvarEdicaoTelefones');
 Route::get('/painel/financeiro/telefones/salvar-novo-telefone', 'Painel\Financeiro\FinanceiroController@salvarNovoTelefone')->name('Painel.Financeiro.Telefones.salvarNovoTelefone');

 Route::get('/painel/financeiro/gerarExcelGuiasFinanceiro', 'Painel\Financeiro\FinanceiroController@gerarExcelGuiasFinanceiro')->name('Painel.Financeiro.gerarExcelGuiasFinanceiro');
 Route::get('/painel/financeiro/gerarExcelGuiasAdvogado', 'Painel\Financeiro\FinanceiroController@gerarExcelGuiasAdvogado')->name('Painel.Financeiro.gerarExcelGuiasAdvogado');
 Route::get('/painel/financeiro/gerarExcelAbertas', 'Painel\Financeiro\FinanceiroController@gerarExcelAbertas')->name('Painel.Financeiro.gerarExcelAbertas');
 Route::get('/painel/financeiro/gerarExcelPagas', 'Painel\Financeiro\FinanceiroController@gerarExcelPagas')->name('Painel.Financeiro.gerarExcelPagas');
 Route::post('/painel/financeiro/telefones/criado', 'Painel\Financeiro\FinanceiroController@telefonecriado')->name('Painel.Financeiro.Telefones.criado');
 Route::post('/painel/financeiro/aprovado', 'Painel\Financeiro\FinanceiroController@aprovado')->name('Painel.Financeiro.aprovado');
 Route::get('/painel/financeiro/{Numero}/recibo', 'Painel\Financeiro\FinanceiroController@recibo')->name('Painel.Financeiro.recibo');
 Route::get('/painel/financeiro/{Numero}/baixar', 'Painel\Financeiro\FinanceiroController@baixar')->name('Painel.Financeiro.baixar');
 Route::post('/painel/financeiro/baixado', 'Painel\Financeiro\FinanceiroController@baixado')->name('Painel.Financeiro.baixado');
 Route::post('/painel/financeiro/comprovantepagamento', 'Painel\Financeiro\FinanceiroController@comprovantepagamento')->name('Painel.Financeiro.comprovantepagamento');
 Route::get('/painel/financeiro/realizarconciliacao', 'Painel\Financeiro\FinanceiroController@realizarconciliacao')->name('Painel.Financeiro.realizarconciliacao');
 Route::get('/painel/financeiro/{Numero}/listasolicitacoespagar', 'Painel\Financeiro\FinanceiroController@listasolicitacoespagar')->name('Painel.Financeiro.listasolicitacoespagar');
 Route::post('/painel/financeiro/solicitacoesbaixadas', 'Painel\Financeiro\FinanceiroController@solicitacoesbaixadas')->name('Painel.Financeiro.solicitacoesbaixadas');
 Route::get('/painel/financeiro/faturamento', 'Painel\Financeiro\FinanceiroController@faturamento')->name('Painel.Financeiro.faturamento');
 Route::post('dynamic_dependent/fetch', 'Painel\Financeiro\FinanceiroController@fetch')->name('dynamicdependent.fetch');
 Route::post('/painel/financeiro/faturamento/pastas', 'Painel\Financeiro\FinanceiroController@faturamento_pastas')->name('Painel.Financeiro.faturamento.pastas');
 Route::post('/painel/financeiro/solicitacoesfaturamento', 'Painel\Financeiro\FinanceiroController@solicitacoesfaturamento')->name('Painel.Financeiro.solicitacoesfaturamento');
 Route::get('/painel/financeiro/gerarexcel', 'Painel\Financeiro\FinanceiroController@gerarExcelFaturamento')->name('Painel.Financeiro.gerarExcelFaturamento');
 Route::get('/painel/financeiro/{contrato}/gerarexcelcontratos', 'Painel\Financeiro\FinanceiroController@gerarExcelFaturamentoContrato')->name('Painel.Financeiro.gerarExcelFaturamentoContrato');
 Route::get('/painel/financeiro/{Numero}/anexo', 'Painel\Financeiro\FinanceiroController@anexo')->name('Painel.Financeiro.anexo');
 Route::get('/painel/financeiro/{Numero}/listasolicitacoespagarexcel', 'Painel\Financeiro\FinanceiroController@listasolicitacoespagarexcel')->name('Painel.Financeiro.listasolicitacoespagarexcel');

 Route::get('/painel/financeiro/relatoriobancario/index', 'Painel\Financeiro\FinanceiroController@relatoriobancario_index')->name('Painel.Financeiro.RelatorioBancario.index');
 Route::get('/painel/financeiro/relatoriobancario/exportar', 'Painel\Financeiro\FinanceiroController@relatoriobancario_exportar')->name('Painel.Financeiro.RelatorioBancario.exportar');

 Route::get('/painel/financeiro/associacaonf/index', 'Painel\Financeiro\FinanceiroController@associacaonf_index')->name('Painel.Financeiro.AssociacaoNF.index');
 Route::get('/painel/financeiro/associacaonf/{cpr}/atualizar', 'Painel\Financeiro\FinanceiroController@associacaonf_atualizar')->name('Painel.Financeiro.AssociacaoNF.atualizar');
 Route::post('/painel/financeiro/associacaonf/atualizado', 'Painel\Financeiro\FinanceiroController@associacaonf_atualizado')->name('Painel.Financeiro.AssociacaoNF.atualizado');
 Route::get('/painel/financeiro/associacaonf/gerarexcel', 'Painel\Financeiro\FinanceiroController@associacaonf_gerarexcel')->name('Painel.Financeiro.AssociacaoNF.gerarexcel');

 Route::get('/painel/financeiro/advogado/guiascusta', 'Painel\Financeiro\FinanceiroController@advogado_guiascusta')->name('Painel.Financeiro.guiascusta');
 Route::get('/painel/financeiro/{numero}/geraranexo', 'Painel\Financeiro\FinanceiroController@gerarAnexoGuia')->name('Painel.Financeiro.gerarAnexoGuia');


 //Reembolso Rotas
 Route::post('/painel/financeiro/reembolso/cancelar', 'Painel\Financeiro\FinanceiroController@reembolso_cancelar')->name('Painel.Financeiro.Reembolso.cancelar');
 Route::get('/painel/financeiro/reembolso/prestacaoconta/index', 'Painel\Financeiro\FinanceiroController@reembolso_prestacaoconta_index')->name('Painel.Financeiro.Reembolso.PrestacaoConta.index');
 Route::post('/painel/financeiro/reembolso/prestacaoconta/relatorio', 'Painel\Financeiro\FinanceiroController@reembolso_prestacaoconta_relatorio')->name('Painel.Financeiro.Reembolso.PrestacaoConta.relatorio');
 Route::post('/painel/financeiro/reembolso/prestacaoconta/buscafatura', 'Painel\Financeiro\FinanceiroController@reembolso_prestacaoconta_buscafatura')->name('Painel.Financeiro.Reembolso.PrestacaoConta.buscafatura');
 Route::post('/painel/financeiro/reembolso/prestacaoconta/gerarboleto', 'Painel\Financeiro\FinanceiroController@reembolso_prestacaoconta_gerarboleto')->name('Painel.Financeiro.Reembolso.PrestacaoConta.gerarboleto');
 Route::get('/painel/financeiro/reembolso/{codigo}/{fatura}/imprimirboleto', 'Painel\Financeiro\FinanceiroController@reembolso_prestacaoconta_baixarboleto')->name('Painel.Financeiro.Reembolso.PrestacaoConta.baixarboleto');
 Route::get('/painel/financeiro/reembolso/prestacaoconta/{fatura}/{banco}/relatoriodireto', 'Painel\Financeiro\FinanceiroController@reembolso_prestacaoconta_direto')->name('Painel.Financeiro.Reembolso.PrestacaoConta.direto');

 Route::get('/painel/financeiro/reembolso/solicitante/dados', 'Painel\Financeiro\FinanceiroController@reembolso_solicitante_dados')->name('Painel.Financeiro.Reembolso.Solicitante.dados');
 Route::get('/painel/financeiro/reembolso/solicitante/index', 'Painel\Financeiro\FinanceiroController@reembolso_solicitante_index')->name('Painel.Financeiro.Reembolso.Solicitante.index');
 Route::get('/painel/financeiro/reembolso/solicitante/historico', 'Painel\Financeiro\FinanceiroController@reembolso_solicitante_historico')->name('Painel.Financeiro.Reembolso.Solicitante.historico');
 Route::post('/painel/financeiro/reembolso/solicitante/editado', 'Painel\Financeiro\FinanceiroController@reembolso_solicitante_editado')->name('Painel.Financeiro.Reembolso.Solicitante.editado');
 Route::post('/painel/financeiro/reembolso/solicitante/buscardados', 'Painel\Financeiro\FinanceiroController@reembolso_solicitante_buscardados')->name('Painel.Financeiro.Reembolso.Solicitante.buscardados');
 Route::post('/painel/financeiro/reembolso/solicitante/buscadadoscontrato', 'Painel\Financeiro\FinanceiroController@reembolso_solicitante_buscadadoscontrato')->name('Painel.Financeiro.Reembolso.Solicitante.buscadadoscontrato');
 Route::post('/painel/financeiro/reembolso/solicitante/buscacomarca', 'Painel\Financeiro\FinanceiroController@reembolso_solicitante_buscacomarca')->name('Painel.Financeiro.Reembolso.Solicitante.buscacomarca');
 Route::post('/painel/financeiro/reembolso/solicitante/buscaduplicidade', 'Painel\Financeiro\FinanceiroController@reembolso_solicitante_buscaduplicidade')->name('Painel.Financeiro.Reembolso.Solicitante.buscaduplicidade');
 Route::post('/painel/financeiro/reembolso/solicitante/novasolicitacao', 'Painel\Financeiro\FinanceiroController@reembolso_solicitante_novasolicitacao')->name('Painel.Financeiro.Reembolso.Solicitante.novasolicitacao');
 Route::post('/painel/financeiro/reembolso/solicitante/store', 'Painel\Financeiro\FinanceiroController@reembolso_solicitante_store')->name('Painel.Financeiro.Reembolso.Solicitante.store');


 Route::get('/painel/financeiro/reembolso/revisao/index', 'Painel\Financeiro\FinanceiroController@reembolso_revisao_index')->name('Painel.Financeiro.Reembolso.Revisao.index');
 Route::get('/painel/financeiro/reembolso/revisao/historico', 'Painel\Financeiro\FinanceiroController@reembolso_revisao_historico')->name('Painel.Financeiro.Reembolso.Revisao.historico');
 Route::get('/painel/financeiro/reembolso/revisao/{codigo}/solicitacoessocio', 'Painel\Financeiro\FinanceiroController@reembolso_revisao_solicitacoessocio')->name('Painel.Financeiro.Reembolso.Revisao.solicitacoessocio');
 Route::get('/painel/financeiro/reembolso/revisao/{numerodebite}/revisarsolicitacao', 'Painel\Financeiro\FinanceiroController@reembolso_revisao_revisar')->name('Painel.Financeiro.Reembolso.Revisao.revisar');
 Route::post('/painel/financeiro/reembolso/revisao/revisado', 'Painel\Financeiro\FinanceiroController@reembolso_revisao_revisado')->name('Painel.Financeiro.Reembolso.Revisao.revisado');
 Route::post('/painel/financeiro/reembolso/revisao/revisaoemmassa', 'Painel\Financeiro\FinanceiroController@reembolso_revisao_revisaoemmassa')->name('Painel.Financeiro.Reembolso.Revisao.revisaoemmassa');

 Route::get('/painel/financeiro/reembolso/financeiro/index', 'Painel\Financeiro\FinanceiroController@reembolso_financeiro_index')->name('Painel.Financeiro.Reembolso.Financeiro.index');
 Route::get('/painel/financeiro/reembolso/financeiro/{id}/revisar', 'Painel\Financeiro\FinanceiroController@reembolso_financeiro_revisar')->name('Painel.Financeiro.Reembolso.Financeiro.revisar');
 Route::post('/painel/financeiro/reembolso/financeiro/revisado', 'Painel\Financeiro\FinanceiroController@reembolso_financeiro_revisado')->name('Painel.Financeiro.Reembolso.Financeiro.revisado');
 Route::get('/painel/financeiro/reembolso/financeiro/historico', 'Painel\Financeiro\FinanceiroController@reembolso_financeiro_historico')->name('Painel.Financeiro.Reembolso.Financeiro.historico');
 Route::get('/painel/financeiro/reembolso/financeiro/exportarexcel', 'Painel\Financeiro\FinanceiroController@reembolso_financeiro_exportarexcel')->name('Painel.Financeiro.Reembolso.Financeiro.exportarexcel');


 Route::get('/painel/financeiro/reembolso/contasapagar/index', 'Painel\Financeiro\FinanceiroController@reembolso_conciliacaobancaria_index')->name('Painel.Financeiro.Reembolso.ConciliacaoBancaria.index');
 Route::get('/painel/financeiro/reembolso/contasapagar/historico', 'Painel\Financeiro\FinanceiroController@reembolso_conciliacaobancaria_historico')->name('Painel.Financeiro.Reembolso.ConciliacaoBancaria.historico');
 Route::get('/painel/financeiro/reembolso/contasapagar/{codigo}/solicitacoessocio', 'Painel\Financeiro\FinanceiroController@reembolso_conciliacaobancaria_solicitacoessocio')->name('Painel.Financeiro.Reembolso.ConciliacaoBancaria.solicitacoessocio');
 Route::post('/painel/financeiro/reembolso/contasapagar/baixado', 'Painel\Financeiro\FinanceiroController@reembolso_conciliacaobancaria_baixado')->name('Painel.Financeiro.Reembolso.ConciliacaoBancaria.baixado');
 Route::get('/painel/financeiro/reembolso/contasapagar/{id}/individual', 'Painel\Financeiro\FinanceiroController@reembolso_conciliacaobancaria_individual')->name('Painel.Financeiro.Reembolso.ConciliacaoBancaria.individual');
 Route::post('/painel/financeiro/reembolso/contasapagar/individual_store', 'Painel\Financeiro\FinanceiroController@reembolso_conciliacaobancaria_individual_store')->name('Painel.Financeiro.Reembolso.ConciliacaoBancaria.individual_store');

 Route::get('/painel/financeiro/reembolso/pagamentodebite/index', 'Painel\Financeiro\FinanceiroController@reembolso_pagamentodebite_index')->name('Painel.Financeiro.Reembolso.PagamentoDebite.index');
 Route::get('/painel/financeiro/reembolso/pagamentodebite/{codigo}/solicitacoes', 'Painel\Financeiro\FinanceiroController@reembolso_pagamentodebite_solicitacoes')->name('Painel.Financeiro.Reembolso.PagamentoDebite.solicitacoes');
 Route::post('/painel/financeiro/reembolso/pagamentodebite/baixado', 'Painel\Financeiro\FinanceiroController@reembolso_pagamentodebite_baixado')->name('Painel.Financeiro.Reembolso.PagamentoDebite.baixado');
 Route::get('/painel/financeiro/reembolso/pagamentodebite/{codigo}/verificasolicitacoes', 'Painel\Financeiro\FinanceiroController@reembolso_pagamentodebite_verificasolicitacoes')->name('Painel.Financeiro.Reembolso.PagamentoDebite.verificasolicitacoes');

 Route::get('/painel/financeiro/reembolso/{numerodebite}/anexos', 'Painel\Financeiro\FinanceiroController@reembolso_anexos')->name('Painel.Financeiro.Reembolso.anexos');
 Route::post('/painel/financeiro/reembolso/importaranexos', 'Painel\Financeiro\FinanceiroController@reembolso_importaranexos')->name('Painel.Financeiro.Reembolso.importaranexos');
 Route::get('/painel/financeiro/reembolso/{anexo}/baixaranexo', 'Painel\Financeiro\FinanceiroController@reembolso_baixaranexo')->name('Painel.Financeiro.Reembolso.baixaranexo');

//Fim Reembolso Rotas

//Fim Guias de Custa Rotas
Route::get('/painel/financeiro/guiasdecusta/{numerodebite}/anexos', 'Painel\Financeiro\FinanceiroController@guiascustas_anenxos')->name('Painel.Financeiro.GuiasCustas.anexos');

Route::get('/painel/financeiro/guiasdecusta/solicitante/index', 'Painel\Financeiro\FinanceiroController@guiascustas_solicitante_index')->name('Painel.Financeiro.GuiasCustas.Solicitante.index');
Route::get('/painel/financeiro/guiasdecusta/solicitante/historico', 'Painel\Financeiro\FinanceiroController@guiascustas_solicitante_historico')->name('Painel.Financeiro.GuiasCustas.Solicitante.historico');
Route::post('/painel/financeiro/guiasdecusta/solicitante/novasolicitacao', 'Painel\Financeiro\FinanceiroController@guiascustas_solicitante_novasolicitacao')->name('Painel.Financeiro.GuiasCustas.Solicitante.novasolicitacao');
Route::post('/painel/financeiro/guiasdecusta/solicitante/editado', 'Painel\Financeiro\FinanceiroController@guiascustas_solicitante_editado')->name('Painel.Financeiro.GuiasCustas.Solicitante.editado');
Route::post('/painel/financeiro/guiasdecusta/solicitante/solicitacaocadastrada', 'Painel\Financeiro\FinanceiroController@guiascustas_solicitante_store')->name('Painel.Financeiro.GuiasCustas.Solicitante.store');
Route::post('/painel/financeiro/guiasdecusta/importaranexos', 'Painel\Financeiro\FinanceiroController@guiascusta_importaranexos')->name('Painel.Financeiro.GuiasCusta.importaranexos');

Route::get('/painel/financeiro/guiasdecusta/revisao/index', 'Painel\Financeiro\FinanceiroController@guiascustas_revisao_index')->name('Painel.Financeiro.GuiasCustas.Revisao.index');
Route::get('/painel/financeiro/guiasdecusta/revisao/{numerodebite}/revisar', 'Painel\Financeiro\FinanceiroController@guiascustas_revisao_revisar')->name('Painel.Financeiro.GuiasCustas.Revisao.revisar');
Route::post('/painel/financeiro/guiasdecusta/revisao/revisado', 'Painel\Financeiro\FinanceiroController@guiascustas_revisao_revisado')->name('Painel.Financeiro.GuiasCustas.Revisao.revisado');
Route::get('/painel/financeiro/guiasdecusta/revisao/historico', 'Painel\Financeiro\FinanceiroController@guiascustas_revisao_historico')->name('Painel.Financeiro.GuiasCustas.Revisao.historico');

Route::get('/painel/financeiro/guiasdecusta/conciliacaobancaria/index', 'Painel\Financeiro\FinanceiroController@guiascustas_conciliacaobancaria_index')->name('Painel.Financeiro.GuiasCustas.ConciliacaoBancaria.index');
Route::get('/painel/financeiro/guiasdecusta/conciliacaobancaria/historico', 'Painel\Financeiro\FinanceiroController@guiascustas_conciliacaobancaria_historico')->name('Painel.Financeiro.GuiasCustas.ConciliacaoBancaria.historico');
Route::get('/painel/financeiro/guiasdecusta/conciliacaobancaria/{numerodebite}/revisar', 'Painel\Financeiro\FinanceiroController@guiascustas_conciliacaobancaria_revisar')->name('Painel.Financeiro.GuiasCustas.ConciliacaoBancaria.revisar');
Route::post('/painel/financeiro/guiasdecusta/conciliacaobancaria/revisado', 'Painel\Financeiro\FinanceiroController@guiascustas_conciliacaobancaria_revisado')->name('Painel.Financeiro.GuiasCustas.ConciliacaoBancaria.revisado');
Route::post('/painel/financeiro/guiasdecusta/conciliacaobancaria/baixado', 'Painel\Financeiro\FinanceiroController@guiascustas_conciliacaobancaria_baixado')->name('Painel.Financeiro.GuiasCustas.ConciliacaoBancaria.baixado');
//Fim Guias de Custa


//Rotas Adiantamento & Prestação de Contas
Route::get('/painel/financeiro/adiantamentoprestacao/{numerodebite}/anexos', 'Painel\Financeiro\AdiantamentoPrestacaoController@anexos')->name('Painel.Financeiro.AdiantamentoPrestacao.anexos');


Route::get('/painel/financeiro/adiantamentoprestacao/financeiro/index', 'Painel\Financeiro\AdiantamentoPrestacaoController@financeiro_index')->name('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.index');
Route::get('/painel/financeiro/adiantamentoprestacao/financeiro/{cpf}/novoadiantamento', 'Painel\Financeiro\AdiantamentoPrestacaoController@financeiro_novoadiantamento')->name('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.novoadiantamento');
Route::post('/painel/financeiro/adiantamentoprestacao/financeiro/novoadiantamento_store', 'Painel\Financeiro\AdiantamentoPrestacaoController@financeiro_novoadiantamento_store')->name('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.novoadiantamento_store');
Route::get('/painel/financeiro/adiantamentoprestacao/financeiro/{cpf}/solicitacoesadiantamento', 'Painel\Financeiro\AdiantamentoPrestacaoController@financeiro_solicitacoesadiantamento')->name('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.solicitacoesadiantamento');
Route::get('/painel/financeiro/adiantamentoprestacao/financeiro/{id}/revisarsolicitacao', 'Painel\Financeiro\AdiantamentoPrestacaoController@financeiro_revisarsolicitacao')->name('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.revisarsolicitacao');
Route::post('/painel/financeiro/adiantamentoprestacao/financeiro/solicitacaorevisada', 'Painel\Financeiro\AdiantamentoPrestacaoController@financeiro_solicitacaorevisada')->name('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.revisado');
Route::post('/painel/financeiro/adiantamentoprestacao/financeiro/movimentacaobancaria', 'Painel\Financeiro\AdiantamentoPrestacaoController@financeiro_movimentacaobancaria')->name('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.movimentacaobancaria');
Route::get('/painel/financeiro/adiantamentoprestacao/financeiro/{cpf}/{id}/revisarprestacaodeconta', 'Painel\Financeiro\AdiantamentoPrestacaoController@financeiro_revisarprestacaodeconta')->name('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.revisarprestacaodeconta');
Route::post('/painel/financeiro/adiantamentoprestacao/financeiro/revisarprestacaodeconta', 'Painel\Financeiro\AdiantamentoPrestacaoController@financeiro_revisarprestacaodeconta_store')->name('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.revisarprestacaodeconta_store');
Route::get('/painel/financeiro/adiantamentoprestacao/financeiro/{id}/transferirvalor', 'Painel\Financeiro\AdiantamentoPrestacaoController@financeiro_transferencia')->name('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.transferencia');
Route::post('/painel/financeiro/adiantamentoprestacao/financeiro/transferenciastore', 'Painel\Financeiro\AdiantamentoPrestacaoController@financeiro_transferenciastore')->name('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.transferenciastore');
Route::get('/painel/financeiro/adiantamentoprestacao/financeiro/{cpf}/{id}/baixar', 'Painel\Financeiro\AdiantamentoPrestacaoController@financeiro_baixar')->name('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.baixa');
Route::post('/painel/financeiro/adiantamentoprestacao/financeiro/baixado', 'Painel\Financeiro\AdiantamentoPrestacaoController@financeiro_baixado')->name('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.baixado');
Route::get('/painel/financeiro/adiantamentoprestacao/financeiro/{cpf}/historico', 'Painel\Financeiro\AdiantamentoPrestacaoController@financeiro_historico')->name('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.historico');

Route::get('/painel/financeiro/adiantamentoprestacao/solicitante/index', 'Painel\Financeiro\AdiantamentoPrestacaoController@solicitante_index')->name('Painel.Financeiro.AdiantamentoPrestacao.Solicitante.index');
Route::get('/painel/financeiro/adiantamentoprestacao/solicitante/{id}/realizarprestacao', 'Painel\Financeiro\AdiantamentoPrestacaoController@solicitante_realizarprestacao')->name('Painel.Financeiro.AdiantamentoPrestacao.Solicitante.realizarprestacao');
Route::post('/painel/financeiro/adiantamentoprestacao/solicitante/realizarprestacao', 'Painel\Financeiro\AdiantamentoPrestacaoController@solicitante_realizarprestacao_store')->name('Painel.Financeiro.AdiantamentoPrestacao.Solicitante.realizarprestacao_store');
Route::get('/painel/financeiro/adiantamentoprestacao/solicitante/novasolicitacao', 'Painel\Financeiro\AdiantamentoPrestacaoController@solicitante_novasolicitacao')->name('Painel.Financeiro.AdiantamentoPrestacao.Solicitante.novasolicitacao');
Route::post('/painel/financeiro/adiantamentoprestacao/solicitante/novoadiantamento_store', 'Painel\Financeiro\AdiantamentoPrestacaoController@solicitante_novoadiantamento_store')->name('Painel.Financeiro.AdiantamentoPrestacao.Solicitante.novoadiantamento_store');
Route::get('/painel/financeiro/adiantamentoprestacao/solicitante/cadastrarbanco', 'Painel\Financeiro\AdiantamentoPrestacaoController@solicitante_cadastrarbanco')->name('Painel.Financeiro.AdiantamentoPrestacao.Solicitante.cadastrarbanco');
Route::post('/painel/financeiro/adiantamentoprestacao/solicitante/cadastrarbanco_store', 'Painel\Financeiro\AdiantamentoPrestacaoController@solicitante_cadastrarbanco_store')->name('Painel.Financeiro.AdiantamentoPrestacao.Solicitante.cadastrarbanco_store');

Route::post('/painel/financeiro/adiantamentoprestacao/solicitante/buscarpastas', 'Painel\Financeiro\AdiantamentoPrestacaoController@solicitante_buscarpastas')->name('Painel.Financeiro.AdiantamentoPrestacao.Solicitante.buscarpastas');


//Fim Adiantamento & Prestação de Contas

 Route::get('/painel/financeiro/novoprestador/index', 'Painel\Financeiro\FinanceiroController@novoprestador_index')->name('Painel.Financeiro.NovoPrestador.index');
 Route::get('/painel/financeiro/novoprestador/{id}/revisar', 'Painel\Financeiro\FinanceiroController@novoprestador_revisar')->name('Painel.Financeiro.NovoPrestador.revisar');
 Route::post('/painel/financeiro/novoprestador/revisado', 'Painel\Financeiro\FinanceiroController@novoprestador_revisado')->name('Painel.Financeiro.NovoPrestador.revisado');

 /******************************************************************
 * End Rota Financeiro
 ******************************************************************/
 
 /******************************************************************
 * Rotas do Coordenador
 ******************************************************************/
 Route::get('/painel/revisao/dashboard', 'Painel\Coordenador\PainelController@index')->name('Painel.Coordenador.principal');
 Route::get('/painel/revisao', 'Painel\Coordenador\CoordenadorController@index')->name('Painel.Coordenador.index');
 Route::get('/painel/revisao/acompanharSolicitacoes', 'Painel\Coordenador\CoordenadorController@acompanharSolicitacoes')->name('Painel.Coordenador.acompanharSolicitacoes');
 Route::get('/painel/revisao/{Numero}/aprovar', 'Painel\Coordenador\CoordenadorController@aprovar')->name('Painel.Coordenador.aprovar');
 Route::get('/painel/revisao/{Numero}/anexos', 'Painel\Coordenador\CoordenadorController@anexos')->name('Painel.Coordenador.anexos');
 Route::get('/painel/revisao/{Numero}/show', 'Painel\Coordenador\CoordenadorController@show')->name('Painel.Coordenador.show');
 Route::get('/painel/revisao/{Numero}/reprovar', 'Painel\Coordenador\CoordenadorController@reprovar')->name('Painel.Coordenador.reprovar');
 Route::post('/painel/revisao/pendenciar', 'Painel\Coordenador\CoordenadorController@pendenciar')->name('Painel.Coordenador.pendenciar');
 Route::get('/painel/revisao/{Numero}/cancelar', 'Painel\Coordenador\CoordenadorController@cancelar')->name('Painel.Coordenador.cancelar');
 Route::post('/painel/revisao/darbaixa', 'Painel\Coordenador\CoordenadorController@darbaixa')->name('Painel.Coordenador.darbaixa');
 Route::get('/painel/revisao/{Numero}/imprimir', 'Painel\Coordenador\CoordenadorController@imprimir')->name('Painel.Coordenador.imprimir');
 Route::get('/painel/revisao/{Numero}/pasta/{pasta}/anexo', 'Painel\Coordenador\CoordenadorController@anexo')->name('Painel.Coordenador.anexo');
 Route::post('/painel/revisao/aprovado', 'Painel\Coordenador\CoordenadorController@aprovado')->name('Painel.Coordenador.aprovado');
 Route::get('/painel/revisao/gerarExcelAbertas', 'Painel\Coordenador\CoordenadorController@gerarExcelAbertas')->name('Painel.Coordenador.gerarExcelAbertas');
 Route::get('/painel/revisao/gerarExcelAcompanhamento', 'Painel\Coordenador\CoordenadorController@gerarExcelAcompanhamento')->name('Painel.Coordenador.gerarExcelAcompanhamento');
 Route::get('/painel/revisao/solicitacao', 'Painel\Coordenador\CoordenadorController@solicitacaoAbertura')->name('Painel.Coordenador.Contratacao');
 Route::post('/painel/revisao/solicitado', 'Painel\Coordenador\CoordenadorController@solicitado')->name('Painel.Contratacao.solicitado');
 Route::get('/painel/revisao/{Numero}/anexo', 'Painel\Coordenador\CoordenadorController@anexo')->name('Painel.Coordenador.anexo');

 /******************************************************************
 * End Rota Coordenador
 ******************************************************************/
 
 /******************************************************************
 * Rotas do Marketing
 ******************************************************************/
Route::get('/painel/marketing/dashboard', 'Painel\Marketing\PainelController@index')->name('Painel.Marketing.principal');
Route::get('/painel/marketing', 'Painel\Marketing\MarketingController@index')->name('Painel.Marketing.index');
Route::get('/painel/marketing/informativos', 'Painel\Marketing\MarketingController@informativos')->name('Painel.Marketing.informativos');
Route::get('/painel/marketing/desativados', 'Painel\Marketing\MarketingController@desativados')->name('Painel.Marketing.desativados');
Route::get('/painel/marketing/{id}/show', 'Painel\Marketing\MarketingController@show')->name('Painel.Marketing.show');
Route::get('/painel/marketing/create', 'Painel\Marketing\MarketingController@create')->name('Painel.Marketing.create');
Route::post('/painel/marketing/store', 'Painel\Marketing\MarketingController@store')->name('Painel.Marketing.store');
Route::get('/painel/marketing/{id}/update', 'Painel\Marketing\MarketingController@update')->name('Painel.Marketing.update');
Route::get('/painel/marketing/{id}/editar', 'Painel\Marketing\MarketingController@editar')->name('Painel.Marketing.editar');
Route::post('/painel/marketing/editado', 'Painel\Marketing\MarketingController@editado')->name('Painel.Marketing.editado');
});
/******************************************************************
 * End Routes Painel
 ******************************************************************/


/******************************************************************
 * Rotas de Autenticação
 ******************************************************************/
// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');


Route::get('sorteioindex', 'Sorteio\SorteioController@index')->name('sorteioindex');
Route::get('$2y$12$iIsIaKNRMd8UF71m1a8TGemSD80g6Fu26iTcEhe3zANzAE.65/PIu', 'Sorteio\SorteioController@gerarsorteio1')->name('gerarsorteio1');
Route::post('primeirosorteiogerado', 'Sorteio\SorteioController@primeirosorteiogerado')->name('primeirosorteiogerado');

Route::get('$2y$12$9U3PhD2GYZeYCt4WxCxb7eQn/LYS5kyVr3H7mC5RsvCy903pxhX92', 'Sorteio\SorteioController@gerarsorteio2')->name('gerarsorteio2');
Route::post('segundosorteiogerado', 'Sorteio\SorteioController@segundosorteiogerado')->name('segundosorteiogerado');

Route::get('$2y$12$ttWk1ufMBGV8R74IsLfZeOn/Zm4jZXT0bL1qjwG.pIswi8nbGv1ke', 'Sorteio\SorteioController@gerarsorteio3')->name('gerarsorteio3');
Route::post('terceirosorteiogerado', 'Sorteio\SorteioController@terceirosorteiogerado')->name('terceirosorteiogerado');

Route::get('$2y$12$70soXjVpQ.PvnUmKNnq8M.FyI0FHWEynbXFpO1YVr/c6/x.iK3Vu6', 'Sorteio\SorteioController@gerarsorteio4')->name('gerarsorteio4');
Route::post('quartosorteiogerado', 'Sorteio\SorteioController@quartosorteiogerado')->name('quartosorteiogerado');

Route::get('$2y$12$fn4d7/9r7XqVGM/T8pC8nu4CrIhlNXcYdSprCxeVgaegH/ECCHr0G', 'Sorteio\SorteioController@gerarsorteio5')->name('gerarsorteio5');
Route::post('quintosorteiogerado', 'Sorteio\SorteioController@quintosorteiogerado')->name('quintosorteiogerado');

Route::get('$2y$12$osdO.oM9o2YyVtv8TduDkeh7Py3x2K/Pqnhh.raRylFXHa.3KeFHu', 'Sorteio\SorteioController@gerarsorteio6')->name('gerarsorteio6');
Route::post('sextosorteiogerado', 'Sorteio\SorteioController@sextosorteiogerado')->name('sextosorteiogerado');

Route::get('$2y$12$WTdHqFviz/JMcjM/KZZ9bOjreoBol9JC1UWFTB9yAzRtlaqmaXMXO', 'Sorteio\SorteioController@gerarsorteio7')->name('gerarsorteio7');
Route::post('setimosorteiogerado', 'Sorteio\SorteioController@setimosorteiogerado')->name('setimosorteiogerado');

Route::get('$2y$12$aEZocrbvlvHHZN.99SIUmuqMrd2coCsMKQjg8OAiAKMcfasT3z9U2', 'Sorteio\SorteioController@gerarsorteio8')->name('gerarsorteio8');
Route::post('oitavosorteiogerado', 'Sorteio\SorteioController@oitavosorteiogerado')->name('oitavosorteiogerado');

Route::get('$2y$12$jtS2x9eDZm.LyvMj2i9yhOXZUhdI5jy.LnpdxF6BDFDs1PHdbwqxa', 'Sorteio\SorteioController@gerarsorteio9')->name('gerarsorteio9');
Route::post('nonosorteiogerado', 'Sorteio\SorteioController@nonosorteiogerado')->name('nonosorteiogerado');

Route::get('$2y$12$.8RR1qILsI9SlOnzeodaye5qSSFiu9/ZxuWDTnLwJ0qp1ahhZkUK6', 'Sorteio\SorteioController@gerarsorteio10')->name('gerarsorteio10');
Route::post('decimosorteiogerado', 'Sorteio\SorteioController@decimosorteiogerado')->name('decimosorteiogerado');

// Password Reset Routes...
Route::get('senha/recuperarsenha', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('recuperarsenha');
Route::post('senha/enviadoemail', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('enviarsenha');
Route::get('senha/redefinirsenha/{token}', 'Auth\ResetPasswordController@showResetForm')->name('redefinirsenha');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('reset');
/******************************************************************
 * Rotas de Autenticação
 ******************************************************************/


/******************************************************************
 * Rotas do Site (Menos Correspondente)
 ******************************************************************/

Route::group(['namespace' => 'Home'], function () {
    //Rota de Home Externo
    Route::get('/', 'HomeController@Show')->name('Home.Principal.Show');
    Route::get('/painel', 'HomeController@Show')->name('Home.Principal.Show');
    Route::get('/testehome', 'HomeController@TesteHome')->name('Home.Principal.Teste');
    // Route::get('/novahome', 'HomeController@teste')->name('Home.Principal.teste');
    Route::get('/{Numero}/anexo', 'HomeController@anexo')->name('Home.Principal.anexo');
    Route::get('/{Numero}/treinamento', 'HomeController@treinamento')->name('Home.Principal.treinamento');
    Route::get('/{Numero}/software', 'HomeController@software')->name('Home.Principal.softwares');
    Route::get('/{id}/updateTable', 'HomeController@updateTable')->name('Home.Principal.updateTable');

});
/******************************************************************
 * End Routes Site
 ******************************************************************/





Route::get('/download/{anexo}/', 'Painel\Marketing\MarketingController@anexo')->name('Painel.Marketing.anexo');





