@extends('layouts.app')

@section('content')
<div class="container-fluid">
    @if ( sizeof( $saucers ) === 0 )
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>There is no saucers in the database</strong>, , please populate it with something delicious.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @else
        <div class="row">
            <div class="col-sm-12 col-md-6 offset-md-2">
                <div class="row" id="saucers">
                    @foreach( $saucers as $saucer )
                        <div class="card mb-3 p-0 col-sm-12 col-md-6 col-lg-4 text-center" style="width: 100%;">
                            <div class="card-header">
                                <h5 class="card-title">{{ $saucer->name }}</h5>
                            </div>
                            <div class="row no-gutters">
                                <div class="col-md-6 offset-md-3">
                                    <img width="100%" class="mt-3 mb-lg-3" height="180rem" src="data:{{ $saucer->type }};base64,<?php echo (base64_encode($saucer->image)); ?>" alt="">
                                </div>
                            </div>
                            <div class="card-footer text-muted">
                                <button class="btn btn-info" onclick="addSaucer( {{ $saucer->id }}, '{{ $saucer->name }}', {{ $saucer->price }}, this )">Agregar platillo</button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="col-12">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            @for($i=0; $i < $pages; $i++)
                                <li class="page-item"><button class="page-link" onclick="getSaucers({{$i}})">{{ $i }}</button></li>
                            @endfor
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="col-sm-12 col-md-2">
                <div class="card">
                    <img class="card-img-top" height="240rem" src="{{ asset('imgs/pedido.jpeg' ) }}" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title">Pedido</h5>
                        @if( sizeof( $order ) === 0 )
                            <p class="card-text">La siguiente lista muestra los platillos que solicitara al dar click en el botón enviar. Cualquier duda favor de indicarla con un mesero, Gracias.</p>
                        @else
                            <p class="card-text">Muchas gracias por elegir Quetzalcóatl, esperamos que la comida sea de su agrado.</p>
                        @endif
                    </div>
                    <form id="pedido">
                        <ul class="list-group list-group-flush" id="list">
                            @if( sizeof( $order ) !== 0 )
                                @foreach( $order as $item )
                                    <li class="list-group-item">
                                        {{ $item->name }}
                                        <span class="ml-3 mr-1" style="background-color:white; display:inline-block;">cant.</span>
                                        <input id="itemcant{{$item->saucer_id}}" name="saucercant{{$item->saucer_id}}" size="2" type="number" onchange="addOneMore({{$item->price}}, {{$item->saucer_id}})" value="{{ $item->count }}" placeholder="cantidad">
                                        <span class="ml-3 mr-1" style="background-color:white; display:inline-block;">$</span>
                                        <input id="itemprice{{$item->saucer_id}}" size="3" type="number" value="{{$item->total}}" placeholder="precio" disabled>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                        <div class="card-body">
                            @if( sizeof( $order ) === 0 )
                                <button type="button" onclick="enviarPedido( this )" class="btn btn-secondary">Enviar</button>
                            @else
                                <button type="button" onclick="actualizarPedido()" class="btn btn-secondary">Actualizar</button>
                                <button type="button" onclick="orderPayed()" class="btn btn-primary">El cliente pago</button>
                                <button type="button" onclick="cancelOrder()" class="btn btn-danger">Cancelar</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
<script>
    function addSaucer( id, saucerName, price, boton ) {
        boton.remove();
        $( '#list' ).append( '<li class="list-group-item">'+saucerName+'<span class="ml-3 mr-1" style="background-color:white; display:inline-block;">cant.</span><input id="itemcant'+id+'" name="saucercant'+id+'" size="2" type="number" onchange="addOneMore('+price+', '+id+')" value="1" placeholder="cantidad"><span class="ml-3 mr-1" style="background-color:white; display:inline-block;">$</span><input id="itemprice'+id+'" size="3" type="number" value="'+price+'" placeholder="precio" disabled></li>' );
    }

    function addOneMore( price, item ){
        // alert(Number.isInteger(item))
        // alert( $('#itemcant'+item).val()*price )
        $('#itemprice'+item).val( $('#itemcant'+item).val()*price )
    }

    function enviarPedido( boton ) {
        // alert( $( '#pedido' ).serialize() )
        $.post({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:'<?php echo route('order.add'); ?>',
            data: $('#pedido').serialize(),
            success: function(datos) {
                boton.remove()
                alert( JSON.stringify( datos ) )
            }
        });
    }

    function getSaucers( offset ){
        $.post({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:'<?php echo route('saucers.get'); ?>',
            data: { 'offset':offset },
            success: function(datos) {
                $('#saucers').empty();
                saucers = datos.saucers;
                saucers.forEach(saucer => $('#saucers').append('<div class="card mb-3 p-0 col-sm-12 col-md-6 col-lg-4 text-center" style="width: 100%;"> <div class="card-header"> <h5 class="card-title">'+saucer.name+'</h5> </div> <div class="row no-gutters"> <div class="col-md-6 offset-md-3"> <img width="100%" class="mt-3 mb-lg-3" height="180rem" src="data:'+saucer.type+';base64,'+saucer.image+'" alt=""> </div> </div> <div class="card-footer text-muted"> <button class="btn btn-info" onclick="addSaucer( '+ saucer.id +', \''+ saucer.name +'\', '+ saucer.price +', this )">Agregar platillo</button> </div> </div>'));
            }
        });
    }

    @if( sizeof($order)!==0 )
        function actualizarPedido() {
            $.post({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url:'<?php echo route('order.update', [ 'orderid'=>$order[0]->order_id ]); ?>',
                data: $('#pedido').serialize(),
                success: function(datos) {
                    alert( JSON.stringify( datos ) )
                }
            });
        }

        function orderPayed() {
            $.post({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url:'<?php echo route('order.payed', [ 'orderid'=>$order[0]->order_id ]); ?>',
                success: function(datos) {
                    alert( JSON.stringify( datos ) )
                }
            });
        }

        function cancelOrder() {
            $.post({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url:'<?php echo route('order.cancel', [ 'orderid'=>$order[0]->order_id ]); ?>',
                success: function(datos) {
                    alert( JSON.stringify( datos ) )
                }
            });
        }
    @endif
</script>
@include('shared.footer')
@endsection
