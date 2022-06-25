@include('layouts.header')

<div class="container-fluid px-4">
    <div class="row p-3 productos">

    </div>
</div>

{{session('carrito')}}

@include('layouts.footer')

<div class="modal fade" id="carrito" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Carrito</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="tablaListarCategorias" class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="">Nombre</th>
                            <th class="">cantidad</th>
                            <th style="width: 20px;"></th>
                        </tr>
                    </thead>
                    <tbody class="text-center datos">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-accion btn-primary btn-sm " name="vender">Vender</button>
            </div>
        </div>
    </div>
</div>