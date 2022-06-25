@include('layouts.header')

<div class="container row p-3">
    <div class="col-lg-3 mb-3">
        <div class="card" style="width: 18rem;">
            <img src="{{ asset('img/defaul.jpg') }}" class="card-img-top p-1" alt="...">
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <div class="row">
                    <div class="col-md-6">
                        <input type="number" name="" id="" min="1" value="1" class="form-control" placeholder="1">
                    </div>
                    <div class="col-6 text-end">
                        <a href="#" class="btn btn-primary ">Agregar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.footer')