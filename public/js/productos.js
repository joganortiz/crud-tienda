let ProcesosProductos = {
    initProductos: function() {
        this.ListProductos()
        this.seleccionarImagen()
    },

    ListProductos: function() {
        let self = this
        var table = $('#tablaListarProductos').DataTable({
            "autoWidth": !1,
            "responsive": !0,
            order: [],
            "ajax": {
                "url": url + '/api/product',
                "dataSrc": ""
            },
            "columns": [
                { "data": "id" },
                { "data": "name" },
                { "data": "reference" },
                { "data": function(data) { return data.price + " COP" } },
                { "data": "stock" },
                {
                    "data": function(data) {
                        let imagen = (data.image) ? 'img/' + data.image : 'img/defaul.jpg';
                        return '<img src="' + url + '/' + imagen + '" class="img-fluid" alt="" width="80px" />'
                    }
                },
                {
                    "data": function(data) {
                        return (data.status == 1) ? '<span class="badge bg-success"><i class="uil uil-check-circle" ></i> Activo</span>' : '<span class="badge bg-danger"><i class="uil uil-ban" ></i> Inactivo</span>'
                    }
                },
                {
                    "data": function(data) {
                        return '<div class="btn-group">\
                           <button class="btn btn btn-info btn-sm EditarProducto" data-control="' + data.id + '">\
                               <i class="uil uil-pen"></i> Editar\
                           </button>\
                           <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\
                              <span class="sr-only"></span>\
                           </button>\
                           <div class="dropdown-menu dropdown-menu-right">\
                              <button class="dropdown-item EliminarProducto" data-control="' + data.id + '" data-name="' + data.name + '"> <i class="uil uil-glass-tea"></i> Eliminar</button>\
                           </div></div>'
                    }
                }
            ],
            "initComplete": function() {
                self.AddEditProduct(table)
                self.ListCategories()
            },
            pageLength: 10,
            columnDefs: [{
                orderable: !1,
                targets: [5, 7]
            }]
        })
        table.on('draw', function() {
            $(".EliminarProducto").on("click", function() {
                let id = $(this).attr('data-control');
                let name = $(this).attr('data-name');
                self.DeleteProduct(table, id, name);
            });

            $(".EditarProducto").on("click", function() {
                var id = $(this).attr('data-control');
                $("#CrearEditarProducto").modal('show');
                self.ListProduct(id);
            });
        })
    },

    DeleteProduct: function(table, id = '', name = '') {
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
                    let ajaxUrl = url + '/api/product/' + id;
                    request.open("DELETE", ajaxUrl, true);
                    request.setRequestHeader("Content-Type", "application/json");
                    request.send();
                    request.onreadystatechange = function() {
                        if (request.readyState == 4 && request.status == 200) {
                            let objData = JSON.parse(request.responseText);
                            table.ajax.reload(function() {
                                self.DeleteProduct(table);
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

    AddEditProduct: function(table) {
        let self = this
        $("form[name=formProductos]").submit(function(e) {
            e.preventDefault();

            let id = $("input[name=id]").val();
            let name = $("input[name=name]").val();
            let reference = $("input[name=referencie]").val();
            let price = $("input[name=price]").val();
            let stock = $("input[name=stock]").val();
            let weight = $("input[name=weight]").val();
            let category = $("select[name=category]").val();
            let status = $("select[name=status]").val();
            let image = document.querySelector('.imgProduct');

            if (name == '' || referencie == '' || price == '' || stock == '' || weight == '' || category == '') {
                $("#mjAelert").html("Los campos con * son requeridos");
                return;
            } else {
                $("#mjAelert").html("");
            }

            image = self.convertirImagenBase64(image);
            //Creamos el array que vamos a enviar
            let formData = {
                id,
                name,
                reference,
                price,
                stock,
                weight,
                category,
                status,
                image
            }

            let method = (id) ? "PUT" : "POST";

            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = url + '/api/product' + ((id != '') ? '/' + id : '') + '';
            request.open(method, ajaxUrl, true);
            request.setRequestHeader("Content-Type", "application/json");
            request.send(JSON.stringify(formData));
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    table.ajax.reload(function() {
                        self.DeleteProduct(table);
                    });
                    swal({
                        title: "Exitoso!",
                        html: objData.mensaje,
                        type: "success",
                        confirmButtonText: 'Ok!'
                    })
                    document.getElementById("formProductos").reset()
                    $("#CrearEditarProducto").modal('hide');
                } else {
                    let objData = JSON.parse(request.responseText);
                    swal({
                        title: "Aviso!",
                        html: objData.mensaje,
                        type: "warning",
                        confirmButtonText: 'Ok!'
                    })
                    document.getElementById("formProductos").reset()
                    $("#CrearEditarProducto").modal('hide');

                }
            }
        });
    },

    ListCategories: function() {
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = url + '/api/category';
        request.open('GET', ajaxUrl, true);
        request.setRequestHeader("Content-Type", "application/json");
        request.send();
        request.onreadystatechange = function() {
            if (request.readyState == 4 && request.status == 200) {
                let objData = JSON.parse(request.responseText);
                var html = '<option value="" id="">***Seleccione***</option>';

                var option = [];
                objData.forEach(function(elem) {
                    if (elem.status == '1') {
                        option.push('<option value="' + elem.id + '" >' + elem.name + '</option>')
                    }

                })
                $("select[name=category]").html(html += option)
            }
        }
    },

    ListProduct: function(id) {
        if (id != '') {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = url + '/api/product/' + id;
            request.open('GET', ajaxUrl, true);
            request.setRequestHeader("Content-Type", "application/json");
            request.send();
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    //console.log(objData.id);
                    $("input[name=id]").val(objData.id)
                    $("input[name=name]").val(objData.name)
                    $("input[name=referencie]").val(objData.reference)
                    $("input[name=price]").val(objData.price)
                    $("input[name=stock]").val(objData.stock)
                    $("input[name=weight]").val(objData.weight)
                    $("select[name=category]").val(objData.category)

                    $("select[name=status]").val(((objData.status == '1') ? 1 : 2))
                }
            }
        }
    },

    convertirImagenBase64: function(imgen = '') {
        let imagen = (imgen != null) ? imgen.src : null;
        if (imagen != null) {
            var canvas = document.createElement("canvas");
            canvas.width = imgen.width;
            canvas.height = imgen.height;
            var ctx = canvas.getContext("2d");
            ctx.drawImage(imgen, 0, 0, imgen.width, imgen.height);
            var dataURL = canvas.toDataURL("image/png");
        } else {
            dataURL = ''
        }

        return dataURL;
    },

    seleccionarImagen: function() {
        let imagen = document.querySelector("#image");
        imagen.onchange = function(e) {
            let uploadImagen = this.value;
            let fileimg = this.files;
            let nav = window.URL || window.webkitURL;
            if (uploadImagen != '') {
                let type = fileimg[0].type;
                let name = fileimg[0].name;
                if (type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png') {
                    return false;
                } else {
                    let objeto_url = nav.createObjectURL(this.files[0]);
                    document.querySelector('.imagenProducto').innerHTML = "<img id='img' src=" + objeto_url + " class='imgProduct'>";
                }
            }
        }
    }
}