let ProcesosCategorias = {
    initCategorias: function(url) {
        this.ListCategories()
    },

    /**
     * Funcion que se encarga de listar a todas la categorias
     */
    ListCategories: function() {
        let self = this
        var table = $('#tablaListarCategorias').DataTable({
            "autoWidth": !1,
            "responsive": !0,
            order: [],
            "ajax": {
                "url": url + '/api/category',
                "dataSrc": ""
            },
            "columns": [
                { "data": "id" },
                { "data": "name" },
                { "data": "description" },
                {
                    "data": function(data) {
                        return (data.status == 1) ? '<span class="badge bg-success"><i class="uil uil-check-circle" ></i> Activo</span>' : '<span class="badge bg-danger"><i class="uil uil-ban" ></i> Inactivo</span>'
                    }
                },
                {
                    "data": function(data) {
                        return '<div class="btn-group">\
                           <button class="btn btn btn-info btn-sm EditarCategoria" data-control="' + data.id + '">\
                               <i class="uil uil-pen"></i> Editar\
                           </button>\
                           <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\
                              <span class="sr-only"></span>\
                           </button>\
                           <div class="dropdown-menu dropdown-menu-right">\
                              <button class="dropdown-item EliminarCategoria" data-control="' + data.id + '" data-name="' + data.name + '"> <i class="uil uil-glass-tea"></i> Eliminar</button>\
                           </div></div>'
                    }
                }
            ],
            "initComplete": function() {
                self.AddEditCategoria(table)
            },
            pageLength: 10,
            columnDefs: [{
                orderable: !1,
                targets: [2, 4]
            }]
        })
        table.on('draw', function() {
            $(".EliminarCategoria").on("click", function() {
                let id = $(this).attr('data-control');
                let name = $(this).attr('data-name');
                self.DeleteCategory(table, id, name);
            });

            $(".EditarCategoria").on("click", function() {
                var id = $(this).attr('data-control');
                self.ListCategory(id);
            });
        })
    },

    /**
     * Funcion que se encarga de eliminar una Categoria por ID
     */
    DeleteCategory: function(table, id = "", name) {
        let self = this
        if (id != '') {
            swal({
                html: "<b>¿Realmente quiere eliminar la categoria <br><strong class='text-info'>" + name + "</strong></b>?",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "red",
                confirmButtonText: "Si, eliminar!",
                cancelButtonText: "No, cancelar!",
                closeOnConfirm: !1,
                closeOnCancel: !1,
            }).then(function(result) {
                if (result.value) {
                    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                    let ajaxUrl = url + '/api/category/' + id;
                    request.open("DELETE", ajaxUrl, true);
                    request.setRequestHeader("Content-Type", "application/json");
                    request.send();
                    request.onreadystatechange = function() {
                        if (request.readyState == 4 && request.status == 200) {
                            let objData = JSON.parse(request.responseText);
                            table.ajax.reload(function() {
                                self.DeleteCategory(table);
                            });

                            swal({
                                title: "Exitoso!",
                                html: objData.mensaje,
                                type: "success",
                                confirmButtonText: 'Ok!'
                            })
                        } else {
                            let objData = JSON.parse(request.responseText);
                            swal({
                                title: "Aviso!",
                                html: objData.mensaje,
                                type: "danger",
                                confirmButtonText: 'Ok!'
                            })
                        }
                    }
                }
            })
        }
    },

    /**
     * Creamos o actualizamos una categoria 
     */
    AddEditCategoria: function(table) {
        $("form[name=formCategorias]").submit(function(e) {
            e.preventDefault();

            var nombre = $("input[name=nombre]").val();
            let id = $("input[name=idCategoria]").val()

            if (nombre == "") {
                $("#mjNombre").html("Nombre es requerido");
                return;
            } else {
                $("#mjNombre").html("");
            }

            //Creamos el array que vamos a enviar
            let formData = {
                "id": id,
                "name": $("input[name=nombre]").val(),
                "description": $("#descripcion").val(),
                "status": $("select[name=estado]").val()
            }

            let method = (id) ? "PUT" : "POST";

            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = url + '/api/category' + ((id != '') ? '/' + id : '') + '';
            request.open(method, ajaxUrl, true);
            request.setRequestHeader("Content-Type", "application/json");
            request.send(JSON.stringify(formData));
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    table.ajax.reload(function() {
                        self.EliminarCliente(table);
                    });

                    swal({
                        title: "Exitoso!",
                        html: objData.mensaje,
                        type: "success",
                        confirmButtonText: 'Ok!'
                    })

                    document.getElementById("formCategorias").reset()
                    $("input[name=idCategoria]").val('');
                } else {
                    let objData = JSON.parse(request.responseText);
                    swal({
                        title: "Aviso!",
                        html: objData.mensaje,
                        type: "warning",
                        confirmButtonText: 'Ok!'
                    })
                    document.getElementById("formCategorias").reset()

                }
            }

        });
    },

    /**
     * Listamos la categoria por ID
     */
    ListCategory: function(id) {
        if (id != '') {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = url + '/api/category/' + id;
            request.open('GET', ajaxUrl, true);
            request.setRequestHeader("Content-Type", "application/json");
            request.send();
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    //console.log(objData.id);
                    $("input[name=idCategoria]").val(objData.id)
                    $("input[name=nombre]").val(objData.name)
                    $("#descripcion").val(objData.description)
                    $("#estado").val(((objData.status == '1') ? 1 : 2))
                        //$("#estado option[value=" + ((objData.status) ? 1 : 2) + "]").attr("selected", true);
                }
            }
        }
    }
}