@extends('layouts.app')

@section('content')
<script>
    $( document ).ready(function() {
        $( '#secondColumn' ).hide()
        $( '#thirdColumn' ).hide()
        $( '#firstColumn' ).addClass('offset-md-4')
    });
</script>
<div class="container-fluid">
    @if ( $saucers === 0 )
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>There is no saucers in the database</strong>, please populate it with something delicious.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @else
        <div class="row">
            <div class="col-sm-12 col-md-3 text-center" id="firstColumn">
                <img width="100%" src="{{asset('imgs/orden.jpg')}}" alt="" usemap="#mesas">
                <map name="mesas">
                    <area onclick="getClientOrder(1)" shape="circle" coords="150,60,33" href="#" title="StumbleUpon" alt="StumbleUpon" />
                    <area onclick="getClientOrder(2)" shape="circle" coords="150,145,20" href="#" title="StumbleUpon" alt="StumbleUpon" />
                    <area onclick="getClientOrder(3)" shape="circle" coords="150,230,20" href="#" title="StumbleUpon" alt="StumbleUpon" />
                    <area onclick="getClientOrder(4)" shape="circle" coords="150,315,20" href="#" title="StumbleUpon" alt="StumbleUpon" />
                </map>
            </div>
            <div class="col-sm-12 col-md-6" id="secondColumn">
                <div class="row" id="saucers">
                </div>
                <div class="col-12">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination" id="pages">
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="col-sm-12 col-md-3" id="thirdColumn">
                <div class="card">
                    <img class="card-img-top" height="240rem" src="{{ asset('imgs/pedido.jpeg' ) }}" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title">Pedido</h5>
                        <p class="card-text">La siguiente lista muestra los platillos que se solicitará al dar click en el botón enviar.</p>
                    </div>
                    <form id="pedido">
                        <ul class="list-group list-group-flush" id="list">
                        </ul>
                        <div class="card-body" id="buttons">
                            <button type="button" onclick="enviarPedido( this )" class="btn btn-secondary">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
@include('homescript')
@include('shared.footer')
@endsection
