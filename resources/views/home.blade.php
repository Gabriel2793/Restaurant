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
                        <p class="card-text">La siguiente lista muestra los platillos que solicitara al dar click en el botón enviar. Cualquier duda favor de indicarla con un mesero, Gracias.</p>
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
<script>
    $('map').imageMapResize()
    function getClientOrder(table_number){
        $.post({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:'<?php echo route('order.get'); ?>'+'?table_number='+table_number,
            data: $('#pedido').serialize(),
            success: function(datos) {
                $('#saucers').empty()
                $('#list').empty()

                saucers = datos.saucers;
                items = datos.order;
                saucers.forEach(saucer => $('#saucers').append('<div class="card mb-3 p-0 col-sm-12 col-md-6 col-lg-4 text-center" style="width: 100%;"> <div class="card-header"> <h5 class="card-title">'+saucer.name+'</h5> </div> <div class="row no-gutters"> <div class="col-md-6 offset-md-3"> <img width="100%" class="mt-3 mb-lg-3" height="180rem" src="data:'+saucer.type+';base64,'+saucer.image+'" alt=""> </div> </div> <div class="card-footer text-muted"> <button class="btn btn-info" onclick="addSaucer( '+ saucer.id +', \''+ saucer.name +'\', '+ saucer.price +', this )">Agregar platillo</button> </div> </div>'));
                items.forEach( item => $('#list').append('<li class="list-group-item" id="item'+item.saucer_id+'"> '+item.name+' <span class="ml-3 mr-1" style="background-color:white; display:inline-block;">cant.</span> <input id="itemcant'+item.saucer_id+'" name="saucercant'+item.saucer_id+'" size="2" type="number" onchange="addOneMore('+item.price+', '+item.saucer_id+')" value="'+item.count+'" placeholder="cantidad"> <span class="ml-3 mr-1" style="background-color:white; display:inline-block;">$</span> <input id="itemprice'+item.saucer_id+'" size="3" type="number" value="'+item.total+'" placeholder="precio" disabled> <button onclick="deleteItem(\'item'+item.saucer_id+'\')" type="button" class="btn btn-danger">x</button> </li>'))

                if( datos.order.length > 0 ) {
                    $('#buttons').empty()
                    $('#buttons').append('<button type="button" onclick="actualizarPedido('+datos.order[0].order_id+')" class="btn btn-secondary">Actualizar</button> <button type="button" onclick="orderPayed('+ datos.order[0].order_id +')" class="btn btn-primary">El cliente pago</button> <button type="button" onclick="cancelOrder('+ datos.order[0].order_id +')" class="btn btn-danger">Cancelar</button>');
                }else{
                    $('#buttons').empty()
                    $('#buttons').append('<button type="button" onclick="enviarPedido( this, '+ table_number +' )" class="btn btn-secondary">Enviar</button>')
                }

                $('#pages').empty()
                for(i=0; i<datos.pageCount; i++) {
                    page = '<li onclick="getSaucers('+i+', '+table_number+')" class="page-item"><a class="page-link">'+i+'</a></li>';
                    $('#pages').append(page);
                }

                $('#firstColumn').removeClass('offset-md-4')
                $( '#secondColumn' ).show()
                $( '#thirdColumn' ).show()
            }
        });
    }

    function deleteItem( item ) {
        $('#'+item).remove()
    }

    function addSaucer( id, saucerName, price, boton ) {
        $(boton).prop('disabled', true);
        $( '#list' ).append( '<li class="list-group-item" id="item'+id+'">'+saucerName+'<span class="ml-3 mr-1" style="background-color:white; display:inline-block;">cant.</span><input id="itemcant'+id+'" name="saucercant'+id+'" size="2" type="number" onchange="addOneMore('+price+', '+id+')" value="1" placeholder="cantidad"><span class="ml-3 mr-1" style="background-color:white; display:inline-block;">$</span><input id="itemprice'+id+'" size="3" type="number" value="'+price+'" placeholder="precio" disabled> <button onclick="deleteItem(\'item'+id+'\')" type="button" class="btn btn-danger">x</button> </li>' );
    }

    function addOneMore( price, item ){
        // alert(Number.isInteger(item))
        // alert( $('#itemcant'+item).val()*price )
        $('#itemprice'+item).val( $('#itemcant'+item).val()*price )
    }

    function enviarPedido( boton, table_number ) {
        // alert( $( '#pedido' ).serialize() )
        $.post({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:'<?php echo url('/addOrder/'); ?>/'+table_number,
            data: $('#pedido').serialize(),
            success: function(datos) {
                if(datos.success){
                    boton.remove()
                    $('#buttons').append('<button type="button" onclick="actualizarPedido('+datos.order_id+')" class="btn btn-secondary">Actualizar</button> <button type="button" onclick="orderPayed('+ datos.order_id +')" class="btn btn-primary">El cliente pago</button> <button type="button" onclick="cancelOrder('+ datos.order_id +')" class="btn btn-danger">Cancelar</button>');
                }else{
                    alert('Your order is empty')
                }
            }
        });
    }

    function getSaucers( offset, table_number ){
        $.post({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:'<?php echo route('saucers.get'); ?>',
            data: { offset, table_number },
            success: function(datos) {
                $('#saucers').empty();
                saucers = datos.saucers;
                saucers.forEach(saucer => $('#saucers').append('<div class="card mb-3 p-0 col-sm-12 col-md-6 col-lg-4 text-center" style="width: 100%;"> <div class="card-header"> <h5 class="card-title">'+saucer.name+'</h5> </div> <div class="row no-gutters"> <div class="col-md-6 offset-md-3"> <img width="100%" class="mt-3 mb-lg-3" height="180rem" src="data:'+saucer.type+';base64,'+saucer.image+'" alt=""> </div> </div> <div class="card-footer text-muted"> <button class="btn btn-info" onclick="addSaucer( '+ saucer.id +', \''+ saucer.name +'\', '+ saucer.price +', this )">Agregar platillo</button> </div> </div>'));
            }
        });
    }

    function actualizarPedido(order_id) {
        $.post({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:'<?php echo url('updateOrder'); ?>/'+order_id,
            data: $('#pedido').serialize(),
            success: function(datos) {
                alert( JSON.stringify( datos ) )
            }
        });
    }

    function orderPayed(order_id) {
        $.post({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:'<?php echo url('orderPayed'); ?>/'+order_id,
            success: function(datos) {
                alert( JSON.stringify( datos ) )
            }
        });
    }

    function cancelOrder(order_id) {
        $.post({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:'<?php echo url('cancelOrder'); ?>/'+order_id,
            success: function(datos) {
                alert( JSON.stringify( datos ) )
            }
        });
    }
</script>
@include('shared.footer')
@endsection
