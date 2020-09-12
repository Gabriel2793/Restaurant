<!Doctype>
<html>
    <head>
        <title>Home</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ asset('css/leaflet.css') }}">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
        <script src="{{ asset('js/leaflet.js') }}"></script>
        <script src="{{ asset('js/leaflet.geometryutil.js') }}"></script>
    </head>
    <body>
        @include( 'shared.header' )
        <div class="container-fluid">
            <div class="row m-4">
                <div class="col-lg-4 col-sm-12">
                    <div class="card text-white bg-secondary mt-3 mb-3 col-lg-8 offset-lg-2 col-sm-12 offset-sm-0">
                        <div class="card-header"><h5 class="card-title">Bienvenido al restaurante Quetzalcóatl,</h5></div>
                        <div class="card-body">
                            <p class="card-text">Este lugar es para tu deguste, aquí probaras delicias, las cuales te harán recobrar los buenos momentos.</p>
                        <img src="{{ asset('imgs/quetzalcoatl.jpg') }}" width="100%" height="auto" alt="">
                        </div>
                    </div>
                    <img src="{{ asset('imgs/restaurant.png') }}" class="mb-3" width="100%" height="auto" alt="">
                </div>
                <div class="col-lg-8 col-sm-12 ">
                    @if( sizeof( $saucers ) !== 0 )
                        <div class="row">
                            <div class="col-12" style="background-color:#697179;color:white;">
                                <div class="card-header text-center mb-3">
                                    <h5>Especialidades</h5>
                                </div>
                            </div>
                            @foreach( $saucers as $saucer )
                                <div class="card mb-3 col-sm-12 col-md-6 col-xl-4" style="width: 100%;">
                                    <div class="row no-gutters">
                                        <div class="col-md-6">
                                           <img width="100%" class="mt-3 mb-lg-3" height="180rem" src="data:{{ $saucer->type }};base64,<?php echo (base64_encode($saucer->image)); ?>" alt="">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $saucer->name }}</h5>
                                                <p class="card-text">{{ $saucer->description }}</p>
                                                <p class="card-text"><small class="text-muted">{{ $saucer->created_at }}</small></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @include('shared.footer')
    </body>
</html>
