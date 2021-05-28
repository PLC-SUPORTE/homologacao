async function montaGraficoValor(data = null){
    $.each(graficos, function(index, value){
      graficos[index].destroy();
    });

    if(!data)
      data = await pegarDadosDashboard();

    // --montar os dados dos menus--
    var count_cidades = []; var count_cidade_uf = []; var cidade_uf = [];
    var uf_comarca = []; var uf_projeto = []; var abrangenciaUF = 0; var valor_uf = []; var count_uf_projeto = []; var count_uf_correspondente = [];
    var tipo_pasta = {'contencioso': 0, 'consultivo': 0};
    var valor_total = 0; 
    var correspondente = []; var valor_correspondente = [];
    var processos_ativos = 0; var processos_encerrados = 0; var processos_suspensos = 0; var processosN = 0;
    var comarca = []; var abrangenciaComarca = 0; var count_comarca = [];
    var reembolsavel = 0; var nreembolsavel = 0;
    var equipe = []; var abrangenciaEquipe = 0; var valor_setor = []; var count_setor = [];
    var cliente = []; var count_clientes = 0; var valor_clientes = [];
    var gcliente = []; var count_gclientes = 0; var valor_gclientes = [];
    var seguimento = []; var count_seguimento = 0; var causa_seguimento = []; var valor_seguimento = [];
    var unidades = []; var unidades_count = []; var unidades_polo = []; var unidades_causa = []; var unidades_valor = [];
    var tipo_servico = []; var servico_count = [];
    var fase_processo = []; var processo_count = [];
    var esfera = []; var esfera_count = []; var esfera_valor = [];
    var rito = []; var rito_count = [];
    var datas = []; var valor_data = [];
    var polo_passivo = 0; var polo_ativo = 0; var solicitacoes_validas = 0;

    $.each(data, function(idx, value){ // juntar valores em um unico array
      // pegar abrangencia de UFs
      if(uf_projeto.indexOf(value['UF PROJETO']) == -1){
        uf_projeto.push(value['UF PROJETO']); // armazena todos os ufs passados
        
        count_uf_projeto.push(1);
        abrangenciaUF++; // abrangencia de ufs
      }
      else{
        var posic = uf_projeto.indexOf(value['UF PROJETO']);
        count_uf_projeto[posic] += 1;
      }

      if(uf_comarca.indexOf(value['UF CORRESPONDENTE']) == -1){
        uf_comarca.push(value['UF CORRESPONDENTE']); // armazena todos os ufs passados
        valor_uf.push(parseFloat((value['Valor'] ? value['Valor'] : 0)));
        count_uf_correspondente.push(1);
        abrangenciaUF++; // abrangencia de ufs
      }
      else{
        var posic = uf_comarca.indexOf(value['UF CORRESPONDENTE']);
        valor_uf[posic] += parseFloat((value['Valor'] ? value['Valor'] : 0));
        count_uf_correspondente[posic] += 1;
      }

      // Cliente
      if(cliente.map(function(e) { return e[0] }).indexOf(value['Cliente']) == -1){
        cliente.push([value['Cliente'], parseFloat((value['Valor'] ? value['Valor'] : 0))]);
        count_clientes++;
        // valor_clientes.push(parseFloat((value['Valor'] ? value['Valor'] : 0)));
      }
      else{
        var posic = cliente.map(function(e) { return e[0] }).indexOf(value['Cliente'])
        cliente[posic][1] += parseFloat((value['Valor'] ? value['Valor'] : 0));
        // valor_clientes[posic] += parseFloat((value['Valor'] ? value['Valor'] : 0));
      }

      // Data de solicitação
      if(datas.indexOf(value['Mês']) == -1){
        datas.push(value['Mês'])
        valor_data.push(parseFloat((value['Valor'] ? value['Valor'] : 0)))
      }
      else{
        var posic = datas.indexOf(value['Mês'])
        valor_data[posic] += parseFloat((value['Valor'] ? value['Valor'] : 0));
      }

      // Grupo de cliente
      if(gcliente.map(function(e) { return e[0] }).indexOf(value['Grupo Cliente']) == -1){
        gcliente.push([value['Grupo Cliente'], parseFloat((value['Valor'] ? value['Valor'] : 0))]);
        count_gclientes++;
        // valor_gclientes.push(parseFloat((value['Valor'] ? value['Valor'] : 0)))
      }
      else{
        var posic = gcliente.map(function(e) { return e[0] }).indexOf(value['Grupo Cliente'])
        gcliente[posic][1] += parseFloat((value['Valor'] ? value['Valor'] : 0));
      }

      // Seguimento
      if(seguimento.map(function(e) { return e[0] }).indexOf(value['Seguimento']) == -1){
        seguimento.push([value['Seguimento'], parseFloat((value['Valor'] ? value['Valor'] : 0)), parseFloat((value['Valor da Causa']) ? value['Valor da Causa'] : 0)]);
        count_seguimento++;
        // valor_seguimento.push(parseFloat((value['Valor'] ? value['Valor'] : 0)));
        // causa_seguimento.push(parseFloat((value['Valor da Causa']) ? value['Valor da Causa'] : 0));
      }
      else{
        var posic = seguimento.map(function(e) { return e[0] }).indexOf(value['Seguimento'])
        seguimento[posic][1] += parseFloat((value['Valor'] ? value['Valor'] : 0));
        seguimento[posic][2] += parseFloat((value['Valor da Causa'] ? value['Valor da Causa'] : 0));
        // valor_seguimento[posic] += parseFloat((value['Valor'] ? value['Valor'] : 0));
        // causa_seguimento[posic] += parseFloat((value['Valor da Causa']) ? value['Valor da Causa'] : 0);
      }

      // Correspondente
      if(correspondente.map(function(e) { return e[0] }).indexOf(value['Correspondente']) == -1){
        correspondente.push([value['Correspondente'], parseFloat((value['Valor'] ? value['Valor'] : 0))]);
        // valor_correspondente.push(parseFloat((value['Valor'] ? value['Valor'] : 0)));  
      }
      else{
        var posic = correspondente.map(function(e) { return e[0] }).indexOf(value['Correspondente'])
        correspondente[posic][1] += parseFloat((value['Valor'] ? value['Valor'] : 0));
      }

      // Tipo de serviço
      if(tipo_servico.indexOf(value['Tipo de Serviço prestado']) == -1){
        tipo_servico.push(value['Tipo de Serviço prestado']);
        servico_count.push(1);
      }
      else{
        var posic = tipo_servico.indexOf(value['Tipo de Serviço prestado']);
        servico_count[posic] += 1;
      }

      if(value['Polo Pasta'] == "Passivo")
        polo_passivo++;
      else
        polo_ativo++;

      // Esfera
      if(esfera.map(function(e) { return e[0] }).indexOf(value['Esfera']) == -1){
        esfera.push([value['Esfera'], parseFloat((value['Valor'] ? value['Valor'] : 0)), 1]);
      }
      else{
        var posic = esfera.map(function(e) { return e[0] }).indexOf(value['Esfera'])
        esfera[posic][1] += parseFloat((value['Valor'] ? value['Valor'] : 0));
        esfera[posic][2] += 1;
      }

      // Rito
      if(rito.indexOf(value['Rito']) == -1){
        rito.push(value['Rito'])
        rito_count.push(1);
      }
      else{
        var posic = rito.indexOf(value['Rito']);
        rito_count[posic] += 1;
      }

      // Fase processo
      if(fase_processo.indexOf(value['Fase do Projeto/Processo']) == -1){
        fase_processo.push(value['Fase do Projeto/Processo']);
        processo_count.push(1);
      }
      else{
        var posic = fase_processo.indexOf(value['Fase do Projeto/Processo']);
        processo_count[posic] += 1;
      }

      // quantidade de solicitações reembolsaveis e não reembolsaveis
      if(value['Reembolsavel-SN'] == 'Reembolsável')
        reembolsavel += parseFloat(value['Valor'] ? value['Valor'] : 0);
      else
        nreembolsavel += parseFloat(value['Valor'] ? value['Valor'] : 0);

      // pegar abrangencia de UFs
      if(comarca.indexOf(value['COMARCA PROJETO']) == -1){
        comarca.push(value['COMARCA PROJETO']); // armazena todos as comarcas passados
        abrangenciaComarca++; // abrangencia de comarcas
        count_comarca.push(1);
      }
      else{
        var posic = comarca.indexOf(value['COMARCA PROJETO']);
        count_comarca[posic] += 1;
      }

      // pegar quantidade de solicitações por unidade
      if(unidades.indexOf(value['Unidade de Alocação solicitação']) == -1){
        unidades.push(value['Unidade de Alocação solicitação']);
        unidades_count.push(1);
        unidades_causa.push(parseFloat((value['Valor da Causa'] ? value['Valor da Causa'] : 0)));
        unidades_valor.push(parseFloat((value['Valor'] ? value['Valor'] : 0)));

        if(value['Polo Pasta'])
          unidades_polo.push(1);
      }
      else{
        var posic = unidades.indexOf(value['Unidade de Alocação solicitação']);
        unidades_count[posic] += 1;
        unidades_causa[posic] += parseFloat((value['Valor da Causa'] ? value['Valor da Causa'] : 0));
        unidades_valor[posic] += parseFloat((value['Valor'] ? value['Valor'] : 0));

        if(value['Polo Pasta'])
          unidades_polo[posic] += 1;
      }

      // pegar abrangencia de equipes(count do setor)
      if(equipe.map(function(e) { return e[0] }).indexOf((value['Setor'] ? value['Setor'] : '-')) == -1){
        equipe.push([(value['Setor'] ? value['Setor'] : '-'), parseFloat((value['Valor'] ? value['Valor'] : 0)), 1])
        // equipe.push((value['Setor'] ? value['Setor'] : '-')); // armazena todos os setores passados
        abrangenciaEquipe++; // abrangencia de setor
        // valor_setor.push(parseFloat((value['Valor'] ? value['Valor'] : 0)));
        // count_setor.push(1);
      }
      else{
        var posic = equipe.map(function(e) { return e[0] }).indexOf((value['Setor'] ? value['Setor'] : '-'));
        equipe[posic][1] += parseFloat((value['Valor'] ? value['Valor'] : 0));
        equipe[posic][2] += 1
        // var posic = equipe.indexOf((value['Setor'] ? value['Setor'] : '-'));
        // valor_setor[posic] += parseFloat((value['Valor'] ? value['Valor'] : 0));
        // count_setor[posic] += 1;
      }

      // fazer count por tipo de status
      if(value['Status Processo'] == 'ATIVO')
        processos_ativos++;
      else if(value['Status Processo'] == 'SUSPENSO')
        processos_suspensos++;
      else if(value['Status Processo'] == 'ENCERRADO')
        processos_encerrados++;
      else
        processosN++;
      
      //valor total correspondentes
      valor_total += parseFloat((value['Valor'] ? value['Valor'] : 0));
      //Numero por tipo de pasta
      (value['Tipo Pasta'] == 'Contencioso' ? tipo_pasta['contencioso'] += 1 : tipo_pasta['consultivo'] += 1);

      //pega o total por cidade
      if(cidade_uf.indexOf(removerAcentos(value['UF CORRESPONDENTE'])+" : "+removerAcentos(value['CIDADE CORRESPONDENTE'])) == -1){
        cidade_uf.push(removerAcentos(value['UF CORRESPONDENTE'])+" : "+removerAcentos(value['CIDADE CORRESPONDENTE']));
        count_cidade_uf.push(1);
      }
      else{
        var posic = cidade_uf.indexOf(removerAcentos(value['UF CORRESPONDENTE'])+" : "+removerAcentos(value['CIDADE CORRESPONDENTE']));
        count_cidade_uf[posic] += 1;
      }

      if(value['Status Debite'] != 'Cancelado')
        solicitacoes_validas++;
    })

    var $bhz = (unidades.indexOf('BHZ') != -1 ? parseFloat(unidades_valor[unidades.indexOf('BHZ')]).toFixed(2) : 0);
    var $spo = (unidades.indexOf('SPO') != -1 ? parseFloat(unidades_valor[unidades.indexOf('SPO')]).toFixed(2) : 0);
    var $for = (unidades.indexOf('FOR') != -1 ? parseFloat(unidades_valor[unidades.indexOf('FOR')]).toFixed(2) : 0);
    var $rjo = (unidades.indexOf('RJO') != -1 ? parseFloat(unidades_valor[unidades.indexOf('RJO')]).toFixed(2) : 0);
    var $mao = (unidades.indexOf('MAO') != -1 ? parseFloat(unidades_valor[unidades.indexOf('MAO')]).toFixed(2) : 0);

    $("#count-bh").text(parseFloat($bhz).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
    $("#count-ft").text(parseFloat($for).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
    $("#count-mn").text(parseFloat($mao).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
    $("#count-rj").text(parseFloat($rjo).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
    $("#count-sp").text(parseFloat($spo).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));

    // submenu linha 1
    $("#total_correspondente").text(data[0]['Total Correspondentes'])
    $("#solicitacao_pagamento").text(data[0]['Solicitações de Pagamento'])
    $("#solicitacao_valida").text(solicitacoes_validas); // falta fazer
    $("#valor_correspondente").text(valor_total.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
    $("#consultivo").text((tipo_pasta['consultivo'] ? tipo_pasta['consultivo'] : 0))
    $("#contencioso").text((tipo_pasta['contencioso'] ? tipo_pasta['contencioso'] : 0))

    // submenu linha 2
    $("#projetos_ativos").text(parseFloat(processos_ativos + processos_encerrados + processos_suspensos + processosN)); //falta fazer
    $("#processos_ativos").text(processos_ativos);
    $("#processos_encerrados").text(processos_encerrados);
    $("#processos_suspensos").text(processos_suspensos);
    $("#total_abrangencia").text(abrangenciaUF)
    $("#abrangencia_comarca").text(abrangenciaComarca);

    // submenu linha 3
    $("#valores_reembolsaveis").text(parseFloat(reembolsavel.toFixed(2)).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }))
    $("#nao_reembolsaveis").text(parseFloat(nreembolsavel.toFixed(2)).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }))
    $("#total_equipe").text(abrangenciaEquipe)
    $("#total_clientes").text(count_clientes)
    $("#grupo_clientes").text(count_gclientes)
    $("#total_segmento").text(count_seguimento)

    // Graficos

    var pieChart = '';
    var donutChart = '';
    var lineChart = '';
    var barChart = '';
    var areaChart = '';
    var columnChart = '';

    var pieChart = {
      chart: {
        type: 'pie',
        // height: '200px',
        // width: '300px'
      },
      dataLabels: {
        enabled: true,
        textAnchor: 'start',
        style: {
          colors: ['#fff'],
          fontSize: '8px'
        },
        formatter: function (val, opt) {
          return formatMoney(opt.w.globals.series[opt.seriesIndex])
        },
        offsetX: 0,
      },
      tooltip: {
        theme: 'dark',
        y: {
          formatter: function(val, opt){
            return parseFloat(opt.globals.seriesPercent[opt.dataPointIndex]).toFixed(2).replace('.', ',')+'%'
          },
        }
      },
      responsive: [{
        breakpoint: undefined,
        options: {},
      }],
      series: ['teste'],
      labels: [1],
      title: {

      },
      legend: {
        show: true,
        showForSingleSeries: false,
        showForNullSeries: true,
        showForZeroSeries: true,
        position: 'bottom',
        horizontalAlign: 'left', 
        // floating: true,
        fontSize: '11px',
        fontFamily: 'Helvetica, Arial',
        fontWeight: 400,
        formatter: undefined,
        inverseOrder: false,
        width: undefined,
        height: undefined,
        tooltipHoverFormatter: undefined,
        offsetX: 0,
        offsetY: 0,
        labels: {
          colors: undefined,
          useSeriesColors: false
        },
        markers: {
          width: 12,
          height: 12,
          strokeWidth: 0,
          strokeColor: '#fff',
          fillColors: undefined,
          radius: 12,
          customHTML: undefined,
          onClick: undefined,
          offsetX: 0,
          offsetY: 0
        },
        itemMargin: {
          horizontal: 5,
          vertical: 0
        },
        onItemClick: {
          toggleDataSeries: true
        },
        onItemHover: {
          highlightDataSeries: true
        },
      }
    }

  var donutChart = {
    chart: {
      type: 'donut',
      // height: '200px',
      // width: '300px'
    },
    responsive: [{
      breakpoint: undefined,
      options: {},
    }],
    series: ['teste'],
    labels: [1],
    title: {

    },
    dataLabels: {
      enabled: true,
      textAnchor: 'start',
      style: {
        colors: ['#fff'],
        fontSize: '8px'
      },
      formatter: function (val, opt) {
        return formatMoney(opt.w.globals.series[opt.seriesIndex])
      },
      offsetX: 0,
    },
    tooltip: {
      theme: 'dark',
      y: {
        formatter: function(val, opt){
          return parseFloat(opt.globals.seriesPercent[opt.dataPointIndex]).toFixed(2).replace('.', ',')+'%'
        },
      }
    },
    legend: {
      show: true,
      showForSingleSeries: false,
      showForNullSeries: true,
      showForZeroSeries: true,
      position: 'bottom',
      horizontalAlign: 'left', 
      // floating: true,
      fontSize: '11px',
      fontFamily: 'Helvetica, Arial',
      fontWeight: 400,
      formatter: undefined,
      inverseOrder: false,
      width: undefined,
      height: undefined,
      tooltipHoverFormatter: undefined,
      offsetX: 0,
      offsetY: 0,
      labels: {
        colors: undefined,
        useSeriesColors: false
      },
      markers: {
        width: 12,
        height: 12,
        strokeWidth: 0,
        strokeColor: '#fff',
        fillColors: undefined,
        radius: 12,
        customHTML: undefined,
        onClick: undefined,
        offsetX: 0,
        offsetY: 0
      },
      itemMargin: {
        horizontal: 5,
        vertical: 0
      },
      onItemClick: {
        toggleDataSeries: true
      },
      onItemHover: {
        highlightDataSeries: true
      },
    }
  }

    // GRAFICOS LINHA 1
    // Total de solicitações reembolsaveis e não reembolsaveis
    pieChart['series'] = [parseFloat(reembolsavel.toFixed(2)), parseFloat(nreembolsavel.toFixed(2))]; pieChart['labels'] = ['Reembolsável', 'Não Reembolsável'];
    graficos.push(new ApexCharts(document.querySelector("#reembolsavel"), pieChart))
    graficos[graficos.length - 1].render();

    // Total de solicitações consultivas e contenciosas
    pieChart['series'] = [tipo_pasta['consultivo'], tipo_pasta['contencioso']]; pieChart['labels'] = ['Consultivo', 'Contencioso'];
    graficos.push(new ApexCharts(document.querySelector("#pasta"), pieChart))
    graficos[graficos.length - 1].render();

    // Total de solicitações por unidade
    pieChart['series'] = unidades_count; pieChart['labels'] = unidades; 
    // pieChart['legend']['position'] = 'right';
    graficos.push(new ApexCharts(document.querySelector("#solicitacao_unidade"), pieChart))
    graficos[graficos.length - 1].render();

    // Status dos processos
    pieChart['series'] = [processos_ativos, processos_suspensos, processos_encerrados, processosN]; pieChart['labels'] = ['Ativo', 'Encerrado', 'Suspenso', '(Vazio)'];
    graficos.push(new ApexCharts(document.querySelector("#status_processo"), pieChart))
    graficos[graficos.length - 1].render();

    // Esfera
    var esfera_count = esfera.map(item => item[2]);
    var esfera_pie = esfera.map(item => item[0]);
    // donutChart['legend']['width'] = undefined; donutChart['legend']['position'] = 'bottom';
    // radialBar['legend']['width'] = 100;
    donutChart['series'] = esfera_count; donutChart['labels'] = esfera_pie;
    graficos.push(new ApexCharts(document.querySelector("#esfera"), donutChart))
    graficos[graficos.length - 1].render();

    // Rito
    pieChart['series'] = rito_count; pieChart['labels'] = rito;
    graficos.push(new ApexCharts(document.querySelector("#rito"), pieChart))
    graficos[graficos.length - 1].render();

    // donutChart['chart']['height'] = 350;
    // donutChart['chart']['width'] = 350;

    // Tipos de servico
    // pieChart['legend']['position'] = 'right';
    donutChart['series'] = servico_count; donutChart['labels'] = tipo_servico;
    graficos.push(new ApexCharts(document.querySelector("#tipo_servico"), donutChart))
    graficos[graficos.length - 1].render();

    // Fase do projeto
    donutChart['legend']['position'] = 'bottom';
    donutChart['series'] = processo_count; donutChart['labels'] = fase_processo;
    graficos.push(new ApexCharts(document.querySelector("#fase_projeto"), donutChart))
    graficos[graficos.length - 1].render();

    var lineChart = {
      chart: {
        height: 250,
        width: "100%",
        type: "line",
        // dropShadow: {
        //   enabled: true,
        //   color: '#000',
        //   top: 18,
        //   left: 7,
        //   blur: 10,
        //   opacity: 0.2
        // },
        toolbar: {
          show: false
        }
      },
      markers: {
        size: 8,
      },
      stroke: {
        dashArray: 5
      },
      title: {
        text: 'Valor por UF',
        align: 'left'
      },
      series: [
        {
          name: "Valor",
          data: []
        }
      ],
      xaxis: {
        categories: [
          
        ]
      },
      legend: {
        position: 'top',
        horizontalAlign: 'right',
        floating: true,
        offsetY: -25,
        offsetX: -5
      },
      dataLabels: {
        enabled: true,
        offsetX: -7,
        offsetY: -10,
        textAnchor: 'start',
        formatter: function (val, opt) {
          console.log('DataLabels', opt);
          return formatMoney(opt.w.globals.series[opt.seriesIndex])
        },
        style: {
          fontSize: '9px',
          colors: ['#000']
        }
      },
      tooltip: {
        theme: 'dark',
        y: {
          show: true,
          formatter: function(val, opt){
            return formatMoney(val);
          }
        },
        x: {
          show: false,
        },
        z: {
            show: true,
            formatter: function(val, opt){
            console.log(val, opt)
            return '';
          }
        }
      },
    };

    // Valor UF
    // valor_uf.push(100000, 550000, 300000, 45000); // valores teste
    // UFs.push('MG', 'SP', 'OO', 'BA') // valores teste
    valor_uf = valor_uf.map(a => a.toFixed(2)); // converter para duas casas decimais
    lineChart['series'][0]['data'] = valor_uf; lineChart['xaxis']['categories'] = uf_comarca;
    graficos.push(new ApexCharts(document.querySelector("#valor_uf"), lineChart))
    graficos[graficos.length - 1].render();

    // Valor por data
    // valor_data.push(150000, 300000, 400000, 500000) // teste
    // var datas = ['Jan', 'Feb', 'Mar', 'Apr', 'May'];
    var valor_data = valor_data.map(a => a.toFixed(2)); // converter para duas casas decimais
    lineChart['series'][0]['data'] = valor_data; lineChart['xaxis']['categories'] = datas;
    lineChart['title']['text'] = 'Valor por data de solicitação'; lineChart['stroke']['dashArray'] = 0;
    // areaChart['series'][0]['data'] = valor_data; areaChart['labels'] = teste_data;
    graficos.push(new ApexCharts(document.querySelector("#valor_data_solicitacao"), lineChart));
    graficos[graficos.length - 1].render();

    pieChart['chart']['height'] = 360;
    // Valor por polo pasta
    pieChart['series'] = [polo_ativo, polo_passivo]; pieChart['labels'] = ['Ativo', 'Passivo'];
    graficos.push(new ApexCharts(document.querySelector("#valor_polo_pasta"), pieChart))
    graficos[graficos.length - 1].render();

    // Total de polo pasta por unidade
    pieChart['series'] = unidades_polo; pieChart['labels'] = unidades;
    graficos.push(new ApexCharts(document.querySelector("#total_polo"), pieChart))
    graficos[graficos.length - 1].render();

    var barChart = {
      series: [{
        name: "Porcentagem",
        data: []
      }],
      chart: {
        type: 'bar',
      //   height: 250,
      //   maxHeight: 250
      },
      title: {
        text: '',
        align: 'left'
      },
      plotOptions: {
        bar: {
          borderRadius: 4,
          horizontal: true,
        }
      },
      dataLabels: {
        enabled: true,
        offsetX: 0,
        textAnchor: 'start',
        style: {
          fontSize: '11px',
          colors: ['#000']
        },
        formatter: function (val, opt) {
          return formatMoney(opt.w.globals.series[opt.seriesIndex])
        },
      },
      tooltip: {
        theme: 'dark',
        x: {
          show: false,
        },
        y: {
          show: true,
          formatter: function(val, opt){
            var array = opt.w.globals.series[0];
            var total = array.reduce((total, currentElement) => total + currentElement)
            var perc = (parseFloat(val) / parseFloat(total)) * 100
            return perc.toFixed(2).replace('.', ',')+"%"
            // return parseFloat(opt.w.globals.seriesPercent[opt.dataPointIndex]).toFixed(2).replace('.', ',')+'%'
          },
        }
      },
      xaxis: {
        categories: [],
      }
    };

    // Valor por cliente
    // valor_clientes = valor_clientes.map(a => a.toFixed(2));
    cliente.sort(function(a, b){ return b[1]-a[1]; });
    var valor_clientes = cliente.map(item => item[1].toFixed(2))
    var cliente = cliente.map(item => item[0]);

    barChart['series'][0]['data'] = valor_clientes; barChart['xaxis']['categories'] = cliente;
    barChart['chart']['height'] = (cliente.length * 20 >= 250 ? cliente.length * 20 : 250);
    graficos.push(new ApexCharts(document.querySelector("#valor_cliente"), barChart))
    graficos[graficos.length - 1].render();

    // Valor por grupo de cliente
    barChart['title']['text'] = '';
    // valor_gclientes = valor_gclientes.map(a => a.toFixed(2));
    gcliente.sort(function(a, b){ return b[1]-a[1]; });
    var valor_gclientes = gcliente.map(item => item[1].toFixed(2))
    var gcliente = gcliente.map(item => item[0]);

    barChart['series'][0]['data'] = valor_gclientes; barChart['xaxis']['categories'] = gcliente;
    barChart['chart']['height'] = (gcliente.length * 20 >= 250 ? gcliente.length * 20 : 250);
    graficos.push(new ApexCharts(document.querySelector("#valor_grupo"), barChart))
    graficos[graficos.length - 1].render();

    // % por seguimento
    var valor_seguimento = seguimento.map(item => parseFloat(item[1].toFixed(2)))
    var seguimento_pie = seguimento.map(item => item[0]);
    pieChart['legend']['position'] = 'right'; pieChart['legend']['width'] = 100;
    pieChart['series'] = valor_seguimento; pieChart['labels'] = seguimento_pie;
    graficos.push(new ApexCharts(document.querySelector("#porc_segmento"), pieChart))
    graficos[graficos.length - 1].render();

    // % por valor da causa/seguimento
    // causa_seguimento = causa_seguimento.map(a => a.toFixed(2));
    var causa_seguimento = seguimento.map(item => parseFloat(item[2].toFixed(2)))
    var causa_pie = seguimento.map(item => item[0]);

    pieChart['series'] = causa_seguimento; pieChart['labels'] = causa_pie;
    graficos.push(new ApexCharts(document.querySelector("#porc_causa_segmento"), pieChart))
    graficos[graficos.length - 1].render();

    // % por valor da causa/unidade
    var unidades_causa = unidades_causa.map(a => parseFloat(a.toFixed(2)));
    pieChart['series'] = unidades_causa; pieChart['labels'] = unidades;
    // pieChart['legend']['position'] = 'right'; 
    pieChart['legend']['width'] = undefined;
    graficos.push(new ApexCharts(document.querySelector("#porc_causa_unidade"), pieChart))
    graficos[graficos.length - 1].render();

    var columnChart = {
      series: [{
        name: 'Porcentagem',
        data: []
      }],
      chart: {
        height: 250,
        type: 'bar',
      },
      plotOptions: {
        bar: {
          dataLabels: {
            position: 'top', // top, center, bottom
          },
        }
      },
      dataLabels: {
        enabled: true,
        formatter: function (val) {
          return formatMoney(val);
        },
        offsetY: -20,
        style: {
          fontSize: '8px',
          colors: ["#304758"]
        }
      },
      tooltip: {
        theme: 'dark',
        x: {
          show: false,
        },
        y: {
          show: true,
          formatter: function(val, opt){
            var array = opt.w.globals.series[0];
            var total = array.reduce((total, currentElement) => total + currentElement)
            var perc = (parseFloat(val) / parseFloat(total)) * 100
            return perc.toFixed(2).replace('.', ',')+"%"
            // return parseFloat(opt.w.globals.seriesPercent[opt.dataPointIndex]).toFixed(2).replace('.', ',')+'%'
          },
        }
      },
      xaxis: {
        categories: [],
        position: 'bottom',
        // axisBorder: {
        //   show: false
        // },
        // axisTicks: {
        //   show: false
        // },
        crosshairs: {
          fill: {
            type: 'gradient',
            gradient: {
              colorFrom: '#D8E3F0',
              colorTo: '#BED1E6',
              stops: [0, 100],
              opacityFrom: 0.4,
              opacityTo: 0.5,
            }
          }
        },
        // tooltip: {
        //   // enabled: true,
        // }
      },
      yaxis: {
        // axisBorder: {
        //   show: false
        // },
        // axisTicks: {
        //   show: false,
        // },
        labels: {
          show: false,
          formatter: function (val) {
            return val;
          }
        }
      },
      title: {
        text: 'Total Valor por Setor',
        // floating: true,
        // offsetY: 330,
        align: 'left',
      }
    };

    // Total valor por setor
    equipe.sort(function(a, b){ return b[1]-a[1]; });
    var equipes = equipe.map(item => item[0]);
    var valor_setor = equipe.map(item => item[1].toFixed(2))

    // var valor_setor = valor_setor.map(a => a.toFixed(2));
    columnChart['series'][0]['data'] = valor_setor; columnChart['xaxis']['categories'] = equipes;
    graficos.push(new ApexCharts(document.querySelector("#total_valor_setor"), columnChart))
    graficos[graficos.length - 1].render();

    // Total por setor
    // equipe.sort(function(a, b){ return b[2]-a[2]; });
    // var equipes = equipe.map(item => item[0]);
    // var count_setor = equipe.map(item => item[2])

    // columnChart['series'][0]['data'] = count_setor; columnChart['xaxis']['categories'] = equipes; columnChart['title']['text'] = 'Total por Setor';
    // graficos.push(new ApexCharts(document.querySelector("#total_setor"), columnChart))
    // graficos[graficos.length - 1].render();

    // Valor por esfera
    barChart['title']['text'] = 'Valor por Esfera';
    // esfera_valor = esfera_valor.map(a => a.toFixed(2));
    esfera.sort(function(a, b){ return b[1]-a[1]; });
    esfera_valor = esfera.map(item => item[1].toFixed(2))
    $esfera = esfera.map(item => item[0]);

    barChart['series'][0]['data'] = esfera_valor; barChart['xaxis']['categories'] = $esfera;
    barChart['chart']['height'] = ($esfera.length * 20 >= 250 ? $esfera.length * 20 : 250);
    $("#valor_esfera").html("");
    graficos.push(new ApexCharts(document.querySelector("#valor_esfera"), barChart))
    graficos[graficos.length - 1].render();

    // Valor por Seguimento
    barChart['title']['text'] = 'Valor por Seguimento';
    seguimento.sort(function(a, b){ return b[1]-a[1]; });
    var valor_seguimento = seguimento.map(item => item[1].toFixed(2))
    var seguimento = seguimento.map(item => item[0]);

    barChart['series'][0]['data'] = valor_seguimento; barChart['xaxis']['categories'] = seguimento;
    barChart['chart']['height'] = (seguimento.length * 20 >= 250 ? seguimento.length * 20 : 250);
    graficos.push(new ApexCharts(document.querySelector("#valor_seguimento"), barChart))
    graficos[graficos.length - 1].render();

    // Valor por correspondente
    barChart['title']['text'] = '';
    // valor_correspondente = valor_correspondente.map(a => a.toFixed(2));
    correspondente.sort(function(a, b){ return b[1]-a[1]; });
    var valor_correspondente = correspondente.map(item => item[1].toFixed(2))
    var correspondente = correspondente.map(item => item[0]);

    barChart['series'][0]['data'] = valor_correspondente; barChart['xaxis']['categories'] = correspondente;
    barChart['chart']['height'] = (correspondente.length * 20 >= 250 ? correspondente.length * 20 : 250);
    graficos.push(new ApexCharts(document.querySelector("#valor_p_correspondente"), barChart))
    graficos[graficos.length - 1].render();

    // Recriar o pieChart como donutChart
    // var donutChart = pieChart;
    // donutChart['chart']['height'] = 350;
    // donutChart['chart']['width'] = 350;
    // donutChart['legend']['position'] = 'right';

    // Correspondentes - Serviços Prestados por uf projeto
    donutChart['legend']['position'] = 'right';
    donutChart['title'] = {'text': 'Correspondentes - Serviços Prestados por UF', 'align': 'left'}
    donutChart['series'] = count_uf_projeto; donutChart['labels'] = uf_projeto;
    graficos.push(new ApexCharts(document.querySelector("#projetos_uf"), donutChart))
    graficos[graficos.length - 1].render();

    // Correspondentes - Serviços Prestados por uf projeto
    donutChart['title']['text'] = 'Contagem de UF Correspondente por UF Correspondente'; donutChart['legend']['height'] = undefined; donutChart['legend']['position'] = 'right';
    donutChart['series'] = count_uf_correspondente; donutChart['labels'] = uf_comarca;
    graficos.push(new ApexCharts(document.querySelector("#correspondente_uf"), donutChart))
    graficos[graficos.length - 1].render();

    // Correspondentes - Serviços Prestados por Comarca
    donutChart['legend']['height'] = 70; donutChart['legend']['position'] = 'bottom';
    donutChart['title']['text'] = 'Correspondente - Serviços Prestados por Comarca';
    donutChart['series'] = count_comarca; donutChart['labels'] = comarca;
    graficos.push(new ApexCharts(document.querySelector("#projetos_comarca"), donutChart))
    graficos[graficos.length - 1].render();

    // Correspondentes - Serviços Prestados por uf projeto
    donutChart['legend']['height'] = 70; donutChart['legend']['position'] = 'bottom';
    donutChart['title']['text'] = 'Contagem de Cidade Correspondente por UF Correspondente e Cidade Correspondente';
    donutChart['series'] = count_cidade_uf; donutChart['labels'] = cidade_uf;
    graficos.push(new ApexCharts(document.querySelector("#correspondente_cidade"), donutChart))
    graficos[graficos.length - 1].render();
  }
  // chart.render();

  // js para o input file modificado
  var inputs = document.querySelectorAll( '.inputfile' );
  Array.prototype.forEach.call( inputs, function( input ){
    var label	 = input.nextElementSibling,
    labelVal = label.innerHTML;

    input.addEventListener( 'change', function( e ){
      var fileName = '';
      if( this.files && this.files.length > 1 ){
        // fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
        fileName = this.files.length+" Arquivos selecionados"
      }
      else{
        var teste = '\\\\';
        teste = teste.slice(0, -1);
        fileName = e.target.value.split(teste).pop();
      }

      if( fileName ){
        label.querySelector('strong').innerHTML = fileName;
      }
      else{
        label.innerHTML = labelVal;
      }
    });
  });