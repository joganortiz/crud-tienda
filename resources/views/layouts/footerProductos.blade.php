 <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

 <script src="{{ asset('js/jquery/dist/jquery.min.js') }}"></script>

 <script src="{{ asset('js/datatables.net/js/jquery.dataTables.min.js') }}"></script>
 <script src="{{ asset('js/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>

 <script src="{{ asset('sweetalert2/sweetalert2.min.js') }}"></script>

 <script src="{{ asset('js/productos.js') }}"></script>

 <script>
    let url = "<?php echo  url('/'); ?>"

    $(document).ready(function() {

       ProcesosProductos.initProductos();
       $(".crear").click(function(e) {
          e.preventDefault();
          document.getElementById("formProductos").reset()
       });
    })
 </script>
 </body>

 </html>