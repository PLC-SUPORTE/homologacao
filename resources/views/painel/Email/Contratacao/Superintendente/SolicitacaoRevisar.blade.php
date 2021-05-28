<!DOCTYPE html>
<html>
<head>
<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
th, td {
  padding: 5px;
  text-align: left;
}
</style>
</head>
<body>

<p>Prezado(a) <?php echo mb_convert_case($superintendente_name, MB_CASE_TITLE, "UTF-8")?>, </p>
<p>Verificar mensagem com o Marketing.</p>
<br>

@foreach($datas as $data)

<table style="width:100%">
  <tr>
    <td>Número da solicitação: {{ $data->id }}</td>
  </tr>
  <tr>
  <td>Data da solicitação: {{ date('d/m/Y', strtotime($data->data)) }}</td>
  </tr>

  <tr>
    <td>Solicitante: <?php echo mb_convert_case($data->solicitantenome, MB_CASE_TITLE, "UTF-8")?></td>
  </tr>

  <tr>
    <td>Candidato nome: <?php echo mb_convert_case($data->candidatonome, MB_CASE_TITLE, "UTF-8")?></td>
  </tr>

   <tr>
    <td>Setor descrição: {{$data->SetorDescricao}}</td>
  </tr>

  <tr>  
    <td>Unidade descrição: {{$data->UnidadeDescricao}}</td>
  </tr>

  <tr>  
    <td>Distribuição mensal: R$ <?php echo number_format($data->salario,2,",",".") ?></td>
  </tr>

  <tr>
    <td>Tipo da vaga: {{$data->tipovaga}}</td>
   </tr>

   <tr>
    <td>Função: {{$data->cargo}}</td>
   </tr>

   <tr>
    <td>Classificação: {{$data->classificacao}}</td>
   </tr>
   
   <tr> 
    <td>Observação: {{$data->observacao}}</td>
  </tr>
  
  
</table>

<br>
<a href="{{route('Painel.Contratacao.Superintendente.index')}}" target="_blank" >CLIQUE AQUI para acessar está solicitação em nosso Portal.</a>
<p>Você também pode está acessando o portal e visualizando o histórico e o fluxo de acompanhamento da solicitação.</p>


@endforeach

<!--
<center>
    <p>
        A equipe de TI do PLC Advogados agradece seu contato e coloca-se a disposição 
        para qualquer eventualidade. 
    </p>
</center>
-->

<img src="{{url('./public/imgs/logo.png')}}" alt="Logo PLC" height="156" width="276">


</body>
</html>