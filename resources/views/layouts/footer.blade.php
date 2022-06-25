  <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('js/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('js/tienda.js') }}"></script>
  <script>
     let url = "<?php echo  url('/'); ?>"

     $(document).ready(function() {

        ProcesosTienda.initTienda();
     })
  </script>
  </body>

  </html>