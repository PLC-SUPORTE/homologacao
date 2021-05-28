$(document).ready(function(){
    graficos = [];
    (function($){ // função de padrão jquery: $("#campo").criaFiltros($data, $text, $value);
      $.fn.criaFiltros = function($data, $text, $value){ // responsavel por colocar os valores base no filtro
        // var options = '<option value="" disabled>Selecione uma opção</option>';
        var options = '';
        var dados = [];

        $.each($data, function(i, v){
          if(dados.indexOf(v[$value]) == -1){
            if(v[$value]){
              dados.push(v[$value]);
              options += '<option value="'+v[$value]+'">'+v[$text]+'</option>'
            }
          }
        });
        
        $(this).html(options);
      }
    })(jQuery);
    // star_rate
    initializeRating();

    $(".modal").modal();
    $(".select2").select2()

    $("#correspondente-uf").on('change', function(){
      if($("#correspondente-uf").val() != []){
        $.ajax({
          method: 'GET',
          url: '/portal/painel/correspondente/getComarca?'+$("#correspondente-uf").serialize(),
          async: true,
        }).done(function(data){
          // montar os dados do dashboard
          var option = "";
          $.each(data, function(index, value){
            option += "<option value='"+value['Cidade']+"'>"+value['Cidade']+"</option>";
          });
          
          $("#correspondente-comarca").html(option);
          $("#correspondente-comarca").removeAttr('disabled');
          $("#correspondente-comarca").formSelect();
        });
      }
      else{
        $("#correspondente-comarca").attr('disabled', true);
        $("#correspondente-comarca").formSelect();
      }
    });
  });

  montaTela();

  function contratarCorrespondente(){
    addLoading()
    var formData = new FormData();
    var formData = new FormData(document.getElementById("form-contratar-correspondente"));

    $.ajax({
      method: 'POST',
      url: '/portal/painel/correspondente/contratarCorrespondente',
      data: formData,
      async: false,
      success: function(data){
        $("#modal-contratar_correspondente").modal('close');
        removeLoading() 
      },
      error: function(data){
        
      },
      cache: false,
      contentType: false,
      processData: false,
      xhr: function() { // Custom XMLHttpRequest
          var myXhr = $.ajaxSettings.xhr();
          if (myXhr.upload) { // Avalia se tem suporte a propriedade upload
              myXhr.upload.addEventListener('progress', function() {
                  /* faz alguma coisa durante o progresso do upload */
              }, false);
          }
          return myXhr;
      }
    });
  }

  function novoCorrespondente(){
    addLoading();
    $.ajax({
      method: 'POST',
      url: '/portal/painel/correspondente/novoCorrespondente?'+$("#form-novo-correspondente").serialize(),
      async: true,
    }).done(function(data){
      // montar os dados do dashboard
      $("#modal-novo_correspondente").modal('close');
      removeLoading();
    });
  }

  function avaliarCorrespondente(){
    addLoading();
    $.ajax({
      method: 'POST',
      url: '/portal/painel/correspondente/inserirAvaliacao?'+$("#form-avaliar-correspondente").serialize(),
      async: true,
    }).done(function(data){
      // montar os dados do dashboard
      buscarCorrespondentes();
      setTimeout(() => {
        $("#modal-avaliar_correspondente").modal('close');
        removeLoading(); 
      }, 500);
    });
  }

  // function validaCampos(form){
  //   $.each($(form).find('input[required], select[required]'), function(index, value){
  //     if($(value).val() == ''){

  //     }
  //   });
  // }

  function initializeRating() {
    const ratingStars = [...document.getElementsByClassName("rating-star")];
    const starClassActive = "star_rate";
    const starClassInactive = "star_outline";
    const starsLength = ratingStars.length;
    let i;

    ratingStars.map((star) => {
      star.onclick = () => {
        i = ratingStars.indexOf(star);
        $("#rating-value").val(i+1);

        if ($(star).html() === starClassInactive) {
          for (i; i >= 0; --i) 
            $(ratingStars[i]).html(starClassActive).addClass('active');
        } else {
          for (i; i < starsLength; ++i) 
            $(ratingStars[i]).html(starClassInactive).removeClass('active');
        }
      };

      $(star).hover(function(){
        i = ratingStars.indexOf(star);
        var point = i;
        
        if ($(star).html() === starClassInactive) {
          for (i; i >= 0; --i) 
            $(ratingStars[i]).html(starClassActive).addClass('active');
        } else {
          for (i; i < starsLength; ++i)
            if(i+1 > $("#rating-value").val() && point != i)
              $(ratingStars[i]).html(starClassInactive).removeClass('active');
        }
      });

      $(star).mouseout(function(){
        var point = $("#rating-value").val();
        
        if(point == 0)
          $(".rating-star").html(starClassInactive).removeClass("active");
        else{
          for (point-1; point < starsLength; ++point)
            $(ratingStars[point]).html(starClassInactive).removeClass('active');
        }
      });
    });
  }

  function addLoading(){
    document.getElementById("loadingdiv2").style.display = "";
  }

  function removeLoading(){
    document.getElementById("loadingdiv2").style.display = "none";
  }

  function openModalContratar(id){
    $("#modal-contratar_correspondente").modal('open');
    $("#contratar-arquivo").val("").change();
    $("#label-contratar-arquivo").html('<i class="material-icons left">file_upload</i><strong>Escolher Arquivo(s)</strong>');
    $("#contratar-pasta").val("").change();
    $("#contratar-descricao").val("").change();
    $("#contratar-codigo").val(id).change();
    $("#contratar-tipo_servico").val("").change().formSelect();
  }

  function openModalAvaliar(id){
    $("#modal-avaliar_correspondente").modal('open');
    $("#codigo-correspondente").val(id).change();
    $("#rating-value").val(0).change();
    $("#avaliar-descricao").val("").change();
    $(".rating-star").html('star_outline').removeClass("active");
  }

  function openModalNovoCorrespondente(){
    $("#modal-novo_correspondente").modal('open');
    $("#novo_correspondente-email").val("").change();
    $("#novo_correspondente-tipo_servico").val("").change();
    $("#novo_correspondente-pasta").val("").change();
  }

  function limparFiltro(){
    $("#correspondente-uf").val("").change().formSelect();
    $("#correspondente-comarca").val("").change();
    $("#correspondente-comarca").attr('disabled', true).formSelect();
    $("#correspondente-valor").val("").change().formSelect();
    $("#correspondente-classificacao").val("").change().formSelect();
    $("#correspondente-tipo_servico").val("").change().formSelect();
  }

  async function buscarCorrespondentes(){
    addLoading();
    var data = await pegarCorrespondentes();
    removeLoading();
    var correspondentes = "";

    $.each(data, function(index, value){
      correspondentes +=
      '<div class="div-correspondente col">'
        +'<ul id="dropdown" class="dropdown-content">'
          +'<li><a href="#" onclick="openModalContratar(\''+value['codigo']+'\')"><i class="material-icons left">person_add</i> Contratar</a></li>'
          +'<li><a href="#" onclick="openModalAvaliar(\''+value['codigo']+'\')"><i class="material-icons left">thumbs_up_down</i> Avaliar</a></li>'
        +'</ul>'
        +'<ul class="dds icon-config">'
          +'<li><a class="dropdown-trigger" data-target="dropdown"><i class="material-icons">settings</i></a>'
          +'</li>'
        +'</ul>'
        +'<div class="header">'
          +'<i class="material-icons icon-rating" style="color: yellow">star_rate</i>'
          +'<span class="rating">'+parseFloat(value['nota']).toFixed(1)+'</span>'
          +'<label class="name ellipsed">'+value['nome']+'</label>'
        +'</div>'
        +'<div class="body">'
          // +'<label class="service ellipsed">Principal Serviço: <b>'+value['servico']+'</b></label>'
          +'<label class="value">Valor Médio: </b>'+parseFloat(parseFloat(value['valor']).toFixed(2)).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })+'</b></label>'
          +'<label class="location ellipsed"><b>'+value['UF']+'</b>, <span>'+value['cidade']+'</span></label>'
        +'</div>'
      +'</div>'
      
    });

    if(correspondentes == "")
      correspondentes = '<h5 style="width: 100%; text-align: center;">Não foram encontrados correspondentes.</h5>'

    $("#list-correspondentes").html(correspondentes)
    setTooltipEllipsis();
    $(".dropdown-trigger").dropdown();
  }

  async function pegarCorrespondentes(id = null){
    return new Promise(resolve => {
      var url = "/portal/painel/correspondente/listCorrespondentes?"+$("#form-buscar_correspondentes").serialize();
        
      $.ajax({
        method: 'GET',
        url: url,
        async: true,
      }).done(function(data){
        // montar os dados do dashboard
        resolve(data);
      })
    });
  }
  
  function openFilter(){
    $("#modal-filtros").modal('open');
    // $("#modal-filtros").sidenav('open')
  }

  async function filtrarDashboard(){
    addLoading();
    var data = await pegarDadosDashboard();
    
    montaGraficoValor(data);
    montaTabela(data);
    removeLoading() 

    $('#modal-filtros').modal('close');
  }

  async function filtrarTabela(){
    addLoading();

    var data = await pegarDadosTabela();

    montaTabela(data);
    removeLoading() 
  }

  async function pegarDadosDashboard(){
    return new Promise(resolve => {
      $.ajax({
        method: 'GET',
        url: "/portal/painel/correspondente/pegarDadosDashboard?"+$("#form-filtro").serialize(),
        async: true,
      }).done(function(data){
        // montar os dados do dashboard
        resolve(data);
      })
    });
  }

  async function pegarDadosTabela(){
    return new Promise(resolve => {
      $.ajax({
        method: 'GET',
        url: "/portal/painel/correspondente/pegarDadosTabela?"+$("#form-filtro").serialize()+"&"+$("#form-tabela").serialize(),
        async: true,
      }).done(function(data){
        // montar os dados do dashboard
        resolve(data);
      });
    });
  }

  function removerAcentos(string){
    return string.normalize('NFD').replace(/[\u0300-\u036f]/g, "");
  }

  async function montaTela(){
    addLoading();
    var data = await pegarDadosDashboard();

    montaGraficoValor(data); // montar os graficos do dashboard
    montaFiltro(data); // montar os filtros do dashboard
    montaTabela(data); // montar a tabela do dashboard
    removeLoading();
  }

  async function montaFiltro($data = null){
    if(!$data)
      $data = await pegarDadosDashboard();

    $("#form-ano").criaFiltros($data, 'Ano', 'Ano');
    $("#form-cliente").criaFiltros($data, 'Cliente', 'Cliente');
    $("#form-grupo_cliente").criaFiltros($data, 'Grupo Cliente', 'Grupo Cliente')
    $("#form-negocio").criaFiltros($data, 'Negocio', 'Negocio');
    $("#form-criacao").criaFiltros($data, 'Mês', 'Descrição Mês Criação');
    $("#form-polo_pasta").criaFiltros($data, 'Polo Pasta', 'Polo Pasta');
    $("#form-alocacao").criaFiltros($data, 'Unidade de Alocação solicitação', 'Unidade de Alocação solicitação');
    $("#form-setor").criaFiltros($data, 'Setor', 'Setor');
    $("#form-tipo_pasta").criaFiltros($data, 'Tipo Pasta', 'Tipo Pasta');
    $("#form-status").criaFiltros($data, 'Status Processo', 'Status Processo');
    $("#form-rito").criaFiltros($data, 'Rito', 'Rito');
    $("#form-reembolsavel").criaFiltros($data, 'Reembolsavel-SN', 'Reembolsavel-SN');

    //filtro tabela
    $("#table-uf_correspondente").criaFiltros($data, 'UF CORRESPONDENTE', 'UF CORRESPONDENTE')
    $("#table-cidade_correspondente").criaFiltros($data, 'CIDADE CORRESPONDENTE', 'CIDADE CORRESPONDENTE')
    $("#table-uf_projeto").criaFiltros($data, 'UF PROJETO', 'UF PROJETO')
    $("#table-comarca_projeto").criaFiltros($data, 'COMARCA PROJETO', 'COMARCA PROJETO')

    // filtro lista correspondentes
    $("#table-uf_correspondente").criaFiltros($data, 'UF CORRESPONDENTE', 'UF CORRESPONDENTE')
    $("#table-cidade_correspondente").criaFiltros($data, 'CIDADE CORRESPONDENTE', 'CIDADE CORRESPONDENTE')

    $('select').formSelect('destroy');

    setTimeout(() => {
      $('select').formSelect();  
    }, 500);
  }

  async function montaTabela(data = null){ // Montar a tabela
    if(!data)
      data = await pegarDadosDashboard();

    var tabela = '';

    $.each(data, function(idx, value){
      // Montar a tabela dos correspondentes
      tabela += 
        "<tr>"
          +"<td style='font-size: 11px;'>"+value['Numero']+"</td>"
          +"<td style='font-size: 11px;'>"+value['Correspondente']+"</td>"
          +"<td style='font-size: 11px;'>"+value['UF CORRESPONDENTE']+"</td>"
          +"<td style='font-size: 11px;'>"+value['CIDADE CORRESPONDENTE']+"</td>"
          +"<td style='font-size: 11px;'>"+formatarData(value['Data de Solicitação'])+"</td>"
          +"<td style='font-size: 11px;'>"+parseFloat(parseFloat(value['Valor']).toFixed(2)).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })+"</td>"
          +"<td style='font-size: 11px;'>"+value['Tipo de Serviço prestado']+"</td>"
          +"<td style='font-size: 11px;'>"+value['UF PROJETO']+"</td>"
          +"<td style='font-size: 11px;'>"+value['COMARCA PROJETO']+"</td>"
        +"</tr>";
    });

    // colocar os dados na tabela
    $("#table-correspondente tbody").html(tabela);
  }

  function formatMoney(valor){
    return parseFloat(parseFloat(valor).toFixed(2)).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
  }