@include('layouts.headerProductos')

<div class="container-fluid px-4">
   <div class="row p-3">
      <div class="col-lg-12 col-md-12 col-sm-12">
         <div class="card ">
            <div class="card-header ">
               <div class="row">
                  <div class="col-lg-6 ">
                     <h5 class=""><small>Listado de Productos</small></h5>
                  </div>
                  <div class="col-lg-6 text-end">
                     <button class="btn btn-primary btn-sm crear" type="" data-bs-toggle="modal" data-bs-target="#CrearEditarProducto"><i class="uil uil-plus"></i> Crear Producto</button>
                  </div>
               </div>
            </div>
            <div class="card-body">
               <table id="tablaListarProductos" class="table table-bordered">
                  <thead>
                     <tr class="text-center">
                        <th class="all">ID</th>
                        <th class="">Nombre</th>
                        <th class="">Referencia</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Imagen</th>
                        <th>Estado</th>
                        <th class="all" style="width: 120px;">Acciones</th>
                     </tr>
                  </thead>
                  <tbody class="text-center">
                  </tbody>
               </table>

            </div>
         </div>
      </div>
   </div>
</div>
@include('layouts.footerProductos')

<!-- Modal -->
<div class="modal fade" id="CrearEditarProducto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Productos</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <form name="formProductos" id="formProductos">
            <div class="modal-body">
               <div id="mjAelert" class="form-text text-danger"></div>
               <input type="hidden" name="id" id="id" value="">
               <div class="row">
                  <div class="col-12">
                     <div class="row g-3">
                        <div class="col-md-6">
                           <label class="form-label">Nombre <span class="text-danger">*</span>:</label>
                           <input type="text" class="form-control" id="name" name="name" placeholder="Nombre del producto">
                        </div>
                        <div class="col-6">
                           <label class="form-label">referencia <span class="text-danger">*</span>:</label>
                           <input type="text" class="form-control" id="referencie" name="referencie" placeholder="Referencia del producto">
                        </div>
                        <div class="col-4">
                           <label class="form-label">precio <span class="text-danger">*</span>:</label>
                           <input type="text" class="form-control" id="price" name="price" placeholder="Precio del producto">
                        </div>
                        <div class="col-4">
                           <label class="form-label">Stock <span class="text-danger">*</span>:</label>
                           <input type="text" class="form-control" id="stock" name="stock" placeholder="Stock del producto">
                        </div>
                        <div class="col-4">
                           <label class="form-label">Peso <span class="text-danger">*</span>:</label>
                           <input type="text" class="form-control" id="weight" name="weight" placeholder="Peso del producto en gramos">
                        </div>
                        <div class="col-6">
                           <label class="form-label">Categoria <span class="text-danger">*</span>:</label>
                           <select class="form-select" id="category" name="category">
                              <option value="">**Seleccione**</option>
                           </select>
                        </div>
                        <div class="col-6">
                           <label class="form-label">Estado:</label>
                           <select name="status" id="status" class="form-select">
                              <option value="1" selected>Activo</option>
                              <option value="2">Inactivo</option>
                           </select>
                        </div>
                        <div class="imagenProducto d-none">

                        </div>
                        <div class="col-6">
                           <label class="form-label">Imagen</label><br>
                           <input type="file" name="image" id="image" accept="image/*">
                        </div>
                     </div>
                  </div>
               </div>
            </div>

            <div class="modal-footer justify-content-between">
               <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
               <button class="btn btn-accion btn-primary btn-sm">Guardar</button>
            </div>
         </form>
      </div>
   </div>
</div>