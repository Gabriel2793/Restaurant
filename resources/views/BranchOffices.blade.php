<!Doctype>
<html>
    <head>
        <title>Branch Offices</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ asset('css/leaflet.css') }}">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
        <script src="{{ asset('js/leaflet.js') }}"></script>
        <script src="{{ asset('js/leaflet.geometryutil.js') }}"></script>
        <style>
            #mapid { height: 30rem; }
        </style>
    </head>
    <body>
    <script>
        var branch_offices = [];
    </script>
        <div class="container-fluid">
            @include( 'shared.header' )
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="row mt-2" id="locations">
                    <div class="offset-lg-3"></div>
                        @foreach( $points as $point)
                            <?php $coordinates = json_decode( $point->point )->coordinates; ?>
                            <div class="col-sm-4 col-lg-2">
                                <div class="card" style="width: auto;">
                                    <img width="auto" height="180rem" src="data:{{ $point->type }};base64,<?php echo (base64_encode($point->image)); ?>" alt="">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $point->restaurant_name }}</h5>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                        <button class="btn btn-primary col-12" onclick="updateMarker({{ $coordinates[0] }},{{ $coordinates[1] }})">See this restaurant in the map</button>
                                    </div>
                                </div>
                            </div>
                            <script>
                                branch_offices.push(new L.LatLng( {{ $coordinates[0] }}, {{ $coordinates[1] }} ));
                            </script>
                        @endforeach
                    </div>
                </div>

                <div class="col-sm-12 col-lg-6">
                    <div id="mapid" class="col-sm-8 offset-sm-2 col-lg-12 offset-lg-0 mt-2">
                    </div>
                </div>

                <div class="col-sm-12 col-lg-6">
                    <button class="btn btn-primary m-2 col-12" onclick="searchCloseRestaurant()">search close restaurant</button>
                </div>
            </div>
        </div>
        @include('shared.footer')
        <script>
            if (navigator.geolocation) {
                var marker;
                var mymap;
                var currentLocation, close_branch_office;
                navigator.geolocation.getCurrentPosition(function ( position ) {
                    mymap = L.map('mapid').setView([ position.coords.latitude, position.coords.longitude ], 10);
                    currentLocation = new L.LatLng( position.coords.latitude, position.coords.longitude );

                    $("#locations").append('<div class="col-sm-4 col-lg-2"><div class="card" style="width: auto;"><img width="auto" height="180rem" src="https://thumbs.dreamstime.com/z/your-current-location-pointer-29429678.jpg" alt=""> <div class="card-body"> <h5 class="card-title">Current Location</h5> <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card\'s content.</p><button class="btn btn-primary col-12" onclick="updateMarker('+position.coords.latitude+','+position.coords.longitude+')">Current location</button></div></div></div>');
                    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=sk.eyJ1IjoiZ2FicmllbDI3OTMiLCJhIjoiY2tlazZoaDB1MDN2bjJybnFyMWtrY24waCJ9.aKhBlKEySAg3fT4J-iOtgA', {
                        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                        maxZoom: 400,
                        id: 'mapbox/streets-v11',
                        tileSize: 512,
                        zoomOffset: -1,
                        accessToken: 'sk.eyJ1IjoiZ2FicmllbDI3OTMiLCJhIjoiY2tlazZoaDB1MDN2bjJybnFyMWtrY24waCJ9.aKhBlKEySAg3fT4J-iOtgA'
                    }).addTo(mymap);
                    marker = L.marker([ position.coords.latitude, position.coords.longitude ]).addTo(mymap);
                } );
            } else {
                x.innerHTML = "Geolocation is not supported by this browser.";
            }

            function updateMarker(latitude, longitude) {
                var newLatLng = new L.LatLng(latitude, longitude);
                marker.setLatLng(newLatLng);
                mymap.setView(newLatLng, 15);
            };

            function searchCloseRestaurant() {
                let distance1, distance_2;
                for( i=0; i < branch_offices.length-1; i++ ) {
                    close_branch_office = branch_offices[i];
                    distance1 = L.GeometryUtil.distance(mymap, currentLocation, close_branch_office );
                    distance2 = L.GeometryUtil.distance(mymap, currentLocation, branch_offices[i+1] );
                    console.log(distance1)
                    console.log(distance2)
                    if( distance2 < distance1 ) {
                        close_branch_office = branch_offices[i+1];
                    }
                }

                var marker2 = L.marker( close_branch_office ).addTo( mymap );
                mymap.setView( close_branch_office, 15 );
            }
        </script>
    </body>
</html>
