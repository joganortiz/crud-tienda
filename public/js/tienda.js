let ProcesosTienda = {
    initTienda: function() {
        this.ListTienda()
        this.ListCar()
        this.actualizarCantidad()
        this.EliminarProductoCar()
        this.venderProductos()
    },

    ListTienda: function() {
        let self = this
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = url + '/api/product';
        request.open('GET', ajaxUrl, true);
        request.setRequestHeader("Content-Type", "application/json");
        request.send();
        request.onreadystatechange = function() {
            if (request.readyState == 4 && request.status == 200) {
                let objData = JSON.parse(request.responseText);
                var products = [];
                objData.forEach(function(elem) {
                    if (elem.status == '1') {
                        products.push('<div class="col-lg-3 mb-3">\
                                 <div class="card" style="width: 18rem;">\
                                    <img src="' + url + '/img/' + ((elem.image) ? elem.image : 'defaul.jpg') + '" class="card-img-top p-1" alt="..." style="height: 250px">\
                                    <div class="card-body">\
                                       <h5 class="card-title">' + elem.name + ' <small style="font-size: 12px">' + elem.stock + ' Stock</small></h5>\
                                       <div class="row">\
                                             <div class="col-md-6">\
                                                <input type="number" name="amount' + elem.id + '" id="amount" min="1" value="1" class="form-control" placeholder="1">\
                                             </div>\
                                             <div class="col-6 text-end">\
                                                <a href="#" class="btn btn-primary agregarCarrito" data-control="' + elem.id + '">Agregar</a>\
                                             </div></div></div></div></div>')
                    }

                })
                $(".productos").html(products)
                self.AgregarCarrito()
            }
        }
    },

    AgregarCarrito: function() {
        let self = this
        $(".agregarCarrito").click(function(e) {
            e.preventDefault();
            let idProduct = $(this).attr("data-control");
            let amount = $("input[name=amount" + idProduct + "]").val();

            if (amount < 1) {
                swal({
                    title: "Aviso!",
                    html: "La cantidad debe ser mayor a 0",
                    type: "warning",
                    confirmButtonText: 'Ok!'
                })
                return;
            }

            let formData = {
                idProduct,
                amount
            }

            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = url + '/api/agregarCarrito';
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-Type", "application/json");
            request.send(JSON.stringify(formData));
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    swal({
                        title: "Aviso!",
                        html: objData.mensaje,
                        type: "success",
                        confirmButtonText: 'Ok!'
                    })
                    $(".cantidad").html(objData.cantidad);
                    self.ListCar()
                } else {
                    let objData = JSON.parse(request.responseText);
                    swal({
                        title: "Aviso!",
                        html: objData.mensaje,
                        type: "warning",
                        confirmButtonText: 'Ok!'
                    })
                }
            }
        });
    },

    ListCar: function() {
        let self = this;
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = url + '/api/ListCart';
        request.open("GET", ajaxUrl, true);
        request.setRequestHeader("Content-Type", "application/json");
        request.send();
        request.onreadystatechange = function() {
            if (request.readyState == 4 && request.status == 200) {
                let objData = JSON.parse(request.responseText);
                var option = [];
                if (objData.length > 0) {
                    objData.forEach(function(elem) {

                        option.push('<tr><td><a class="media">\
							<a href="javascript:void(0)" class="text-dark"><img src="' + url + '/img/' + elem.image + '" alt="iMac" width="80" /> ' + elem.name + '</a>\
						</a></td><td><div><input type="number" name="amount_car' + elem.id + '" id="amount_car" min="1" value="' + elem.amount + '" class="form-control" placeholder="1" data-control="' + elem.id + '" > <button type="button" class="btn btn-info d-none buton' + elem.id + '" name="update" id="update" data-control="' + elem.id + '"><i class="uil  uil-sync"></i></button><div></td><td ><i class="uil uil-trash-alt text-danger eliminarProducto" data-control="' + elem.id + '" style="cursor: pointer"> </i></td></tr>')


                    })
                } else {
                    option = '<tr><td colspan="3">No hay productos agregados</td></tr>';
                }
                self.mostrarBoton()

                $(".datos").html(option)
            }
        }
    },


    mostrarBoton: function() {
        $("body").on("click", "#amount_car", function(e) {
            e.preventDefault();
            let id = $(this).attr("data-control");
            //console.log(id)
            $(".buton" + id).removeClass("d-none").addClass("d-block");
        });
    },

    actualizarCantidad: function() {
        $("body").on("click", "button[name=update]", function(e) {
            e.preventDefault();
            let id = $(this).attr("data-control");
            let cantidad = $("input[name=amount_car" + id).val();
            console.log(cantidad);

            let formData = {
                cantidad
            }

            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = url + '/api/actualizarCantidad/' + id;
            request.open("PUT", ajaxUrl, true);
            request.setRequestHeader("Content-Type", "application/json");
            request.send(JSON.stringify(formData));
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    swal({
                        title: "Aviso!",
                        html: objData.mensaje,
                        type: "success",
                        confirmButtonText: 'Ok!'
                    }).then(function(result) {
                        if (result.value) {
                            window.location.reload();
                        }
                    })
                } else {
                    let objData = JSON.parse(request.responseText);
                    swal({
                        title: "Aviso!",
                        html: objData.mensaje,
                        type: "warning",
                        confirmButtonText: 'Ok!'
                    })
                }
            }
        });
    },

    EliminarProductoCar: function() {
        $("body").on("click", ".eliminarProducto", function(e) {
            let id = $(this).attr("data-control");
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = url + '/api/eliminarProductoCarro/' + id;
            request.open("DELETE", ajaxUrl, true);
            request.setRequestHeader("Content-Type", "application/json");
            request.send();
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    swal({
                        title: "Aviso!",
                        html: objData.mensaje,
                        type: "success",
                        confirmButtonText: 'Ok!'
                    }).then(function(result) {
                        if (result.value) {
                            window.location.reload();
                        }
                    })
                } else {
                    let objData = JSON.parse(request.responseText);
                    swal({
                        title: "Aviso!",
                        html: objData.mensaje,
                        type: "warning",
                        confirmButtonText: 'Ok!'
                    })
                }
            }
        })
    },

    venderProductos: function() {
        $("button[name=vender]").click(function(e) {
            e.preventDefault();
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = url + '/api/venderProductos';
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-Type", "application/json");
            request.send();
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    swal({
                        title: "Aviso!",
                        html: objData.mensaje,
                        type: "success",
                        confirmButtonText: 'Ok!'
                    }).then(function(result) {
                        if (result.value) {
                            window.location.reload();
                        }
                    })
                }
            }
        });
    }
}