<script>
    $('map').imageMapResize();
    var orderList;
    var order_id;

    function getClientOrder(table_number){
        if($('#searchedSaucer').length === 0){
            $('#navbarSupportedContent ul').append('<div class="form-inline my-2 my-lg-0"> <input id="searchedSaucer" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search"> <button class="btn btn-outline-success my-2 my-sm-0" type="button" onclick="searchForSaucer()">Search</button> </div>')

            $("#searchedSaucer").keyup(function(event) {
                if (event.keyCode === 13) {
                    searchForSaucer()
                }
            });
        }

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
                orderList = datos.order;
                saucers.forEach(saucer => $('#saucers').append('<div class="card mb-3 p-0 col-sm-12 col-md-6 col-lg-4 text-center" style="width: 100%;"> <div class="card-header"> <h5 class="card-title">'+saucer.name+'</h5> </div> <div class="row no-gutters"> <div class="col-md-6 offset-md-3"> <img width="100%" class="mt-3 mb-lg-3" height="180rem" src="data:'+saucer.type+';base64,'+saucer.image+'" alt=""> </div> </div> <div class="card-footer text-muted"> <button class="btn btn-info" onclick="addSaucer( '+ saucer.id +', \''+ saucer.name +'\', '+ saucer.price +', this )">Agregar platillo</button> </div> </div>'));
                orderList.forEach( saucer => $('#list').append('<li class="list-group-item" id="item'+saucer.saucer_id+'"> '+saucer.name+' <span class="ml-3 mr-1" style="background-color:white; display:inline-block;">cant.</span> <input id="itemcant'+saucer.saucer_id+'" name="saucercant'+saucer.saucer_id+'" size="2" type="number" onchange="addOneMore('+saucer.price+', '+saucer.saucer_id+')" value="'+saucer.count+'" placeholder="cantidad"> <span class="ml-3 mr-1" style="background-color:white; display:inline-block;">$</span> <input id="itemprice'+saucer.saucer_id+'" size="3" type="number" value="'+saucer.total+'" placeholder="precio" disabled> <button onclick="deleteItem(\'item'+saucer.saucer_id+'\')" type="button" class="btn btn-danger">x</button> </li>'))

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
        saucer = item.split("item")[0];
        $('#'+item).remove();
        orderList = orderList.filter( saucer => {
            saucer.saucer_id !== saucer
        });
    }

    function addSaucer( id, saucerName, price, boton ) {
        saucerExist = false;
        orderList.forEach( saucer => {
            console.log(saucer.saucer_id, id)
            if( saucer.saucer_id === id ) {
                saucerExist = true;
            }
        });

        if( !saucerExist ) {
            orderList.push({saucer_id:id})
            $( '#list' ).append( '<li class="list-group-item" id="item'+id+'">'+saucerName+'<span class="ml-3 mr-1" style="background-color:white; display:inline-block;">cant.</span><input id="itemcant'+id+'" name="saucercant'+id+'" size="2" type="number" onchange="addOneMore('+price+', '+id+')" value="1" placeholder="cantidad"><span class="ml-3 mr-1" style="background-color:white; display:inline-block;">$</span><input id="itemprice'+id+'" size="3" type="number" value="'+price+'" placeholder="precio" disabled> <button onclick="deleteItem(\'item'+id+'\')" type="button" class="btn btn-danger">x</button> </li>' );
        }else{
            alert('this saucer is in the order')
        }
    }

    function addOneMore( price, item ){
        $('#itemprice'+item).val( $('#itemcant'+item).val()*price )
    }

    function enviarPedido( boton, table_number ) {
        $.post({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:'<?php echo url('/addOrder/'); ?>/'+table_number,
            data: $('#pedido').serialize(),
            success: function(datos) {
                if(datos.success){
                    getClientOrder(table_number)
                    order_id = datos.order_id;
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
                getClientOrder(datos.table_number)
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

    function searchForSaucer() {
        $.post({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:'<?php echo route('saucers.search'); ?>',
            data: { saucer:$('#searchedSaucer').val(), order_id },
            success: function(datos) {
                $('#saucers').empty();
                saucers = datos.saucers;
                saucers.forEach(saucer => $('#saucers').append('<div class="card mb-3 p-0 col-sm-12 col-md-6 col-lg-4 text-center" style="width: 100%;"> <div class="card-header"> <h5 class="card-title">'+saucer.name+'</h5> </div> <div class="row no-gutters"> <div class="col-md-6 offset-md-3"> <img width="100%" class="mt-3 mb-lg-3" height="180rem" src="data:'+saucer.type+';base64,'+saucer.image+'" alt=""> </div> </div> <div class="card-footer text-muted"> <button class="btn btn-info" onclick="addSaucer( '+ saucer.id +', \''+ saucer.name +'\', '+ saucer.price +', this )">Agregar platillo</button> </div> </div>'));
            }
        });
    }
</script>
