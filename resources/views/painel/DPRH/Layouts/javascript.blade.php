<script src="{{ asset('/public/AdminLTE/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('/public/AdminLTE/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('/public/AdminLTE/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/public/AdminLTE/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('/public/AdminLTE/bower_components/fastclick/lib/fastclick.js') }}"></script>
<script src="{{ asset('/public/AdminLTE/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('/public/AdminLTE/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{ asset('/public/AdminLTE/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<script src="{{ asset('/public/AdminLTE/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('/public/AdminLTE/bower_components/chart.js/Chart.js') }}"></script>
<script src="{{ asset('/public/AdminLTE/dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('/public/AdminLTE/dist/js/demo.js') }}"></script>



</script>

<script>
    $(function () {
        $('#example1').DataTable({
            'paging'      : true,
            'lengthChange': true,
            'searching'   : true,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : false,
            'responsive'  : true,
            
        "language": {
                "lengthMenu": "Mostrando _MENU_ registros por página",
                "zeroRecords": "Nada encontrado",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "search": "Pesquisar&nbsp;:",
                "infoEmpty": "Nenhum registro disponível",
                "infoFiltered": "(filtrado de _MAX_ registros no total)",
            
         "oPaginate": {
           "sNext": "Próxima Página",
           "sPrevious": "Voltar Página", 
       }
       
   }
       
    })
    $('#example2').DataTable({
            'paging'      : true,
            'lengthChange': true,
            'searching'   : true,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : false,
            'responsive'  : true,

            
          "language": {
                "lengthMenu": "Mostrando _MENU_ registros por página",
                "zeroRecords": "Nada encontrado",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "search": "Pesquisar&nbsp;:",
                "infoEmpty": "Nenhum registro disponível",
                "infoFiltered": "(filtrado de _MAX_ registros no total)",
                
             "oPaginate": {
           "sNext": "Próxima Página",
           "sPrevious": "Voltar Página", 
       }
   }
  
    })
    
    })
  </script>        

  <script>

function mascara(i){
   
   var v = i.value;
   
   if(isNaN(v[v.length-1])){ // impede entrar outro caractere que não seja número
      i.value = v.substring(0, v.length-1);
      return;
   }
   
   i.setAttribute("maxlength", "14");
   if (v.length == 3 || v.length == 7) i.value += ".";
   if (v.length == 11) i.value += "-";

}
  </script>
