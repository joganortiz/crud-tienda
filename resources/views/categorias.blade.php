@include('layouts.headerCategorias')
<div class="container-fluid px-4">
   <div class="row p-3">
      <div class="col-lg-4 col-md-12 col-sm-12">
         <form name="formCategorias" id="formCategorias">
            <input type="hidden" name="idCategoria" id="idCategoria" value="">
            <div class="mb-1">
               <label class="form-label">Nombre:</label>
               <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre">
               <div id="mjNombre" class="form-text text-danger"></div>
            </div>
            <div class="mb-1">
               <label class="form-label">Descripción:</label>
               <br />
               <textarea rows="5" style="width: 100%" class="form-control" name="descripcion" placeholder="Descripcion" id="descripcion"></textarea>
            </div>
            <div class="mb-3">
               <label class="form-label">Estado:</label>
               <select name="estado" id="estado" class="form-select">
                  <option value="1" selected>Activo</option>
                  <option value="2">Inactivo</option>
               </select>
            </div>
            <button type="submit" class="btn btn-primary"> Guardar</button>
         </form>
      </div>
      <div class="col-lg-8 col-md-12 col-sm-12">
         <div class="card ">
            <div class="card-header ">
               <div class="row">
                  <div class="col-lg-6 ">
                     <h5 class=""><small>Listado de Categorias</small></h5>
                  </div>
               </div>
            </div>
            <div class="card-body">
               <table id="tablaListarCategorias" class="table table-bordered">
                  <thead>
                     <tr>
                        <th class="">id</th>
                        <th class="all">Nombre</th>
                        <th class="">Descripicion</th>
                        <th class="">Estado</th>
                        <th class="all" style="width: 150px;">Acciones</th>
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
@include('layouts.footerCategorias')