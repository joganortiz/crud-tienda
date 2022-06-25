<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Document</title>
   <link rel="stylesheet" href="{{ asset('css/iconos/css/unicons.css')}}">
   <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

   <!-- Datatable -->
   <link rel="stylesheet" type="text/css" href="{{ asset('js/datatables.net-bs4/css/dataTables.bootstrap4.css') }}">
   <link rel="stylesheet" type="text/css" href=" {{ asset('js/datatables.net-bs4/css/responsive.dataTables.min.css') }}">

   <link rel="stylesheet" type="" href="{{ asset('sweetalert2/sweetalert2.min.css') }}">
</head>

<body>
   <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
         <a class="navbar-brand" href="{{url('/')}}">Tienda</a>
         <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
         </button>
         <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
               <li class="nav-item">
                  <a class="nav-link active" aria-current="page" href="{{url('/categorias')}}">Categorias</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link active" aria-current="page" href="{{url('/productos')}}">Productos</a>
               </li>
            </ul>
            
         </div>
      </div>
   </nav>