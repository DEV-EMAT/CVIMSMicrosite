@extends('layouts.without_sidebar')
@section('location')
{{$title}}
@endsection
@section('style')
<style>
	body { margin: 0; padding: 0; }
	#map { position: absolute; top: 0; bottom: 0; width: 100%; }
    #addressDiv p{
        margin: 0px;
        padding: 0px;
        color: red;
    }

    #addressDiv strong{
        margin: 0px;
        padding: 0px;
        color: red;
    }

</style>

<style>
    #fly {
    display: block;
    position: relative;
    margin: 0px auto;
    width: 50%;
    height: 40px;
    padding: 10px;
    border: none;
    border-radius: 3px;
    font-size: 12px;
    text-align: center;
    color: #fff;
    background: #ee8a65;
    }
    #tableIncident{
        width: 17%;
        display: block;
        position: relative;
        margin: 0px auto;
        padding: 10px;
        border: 1px solid;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        background: #ffffff;
        float: left;
    }
</style>    
@endsection
@section('content')
    <!-- Display All Data -->
    <div class="content">
     
        <div id="map"></div>
       
                <div id="tableIncident" class="table-responsive">
                    <table id="datatable" class="table table-hover">
                        <!--Table head-->
                        <thead>
                            <tr>
                                <th colspan="2" style="text-align: center;"><b>Locator</b></th>
                            </tr>
                            <tr>
                                <th>Requestant</th>
                                <th >Actions</th>
                            </tr>
                        </thead>
                        <!--Table head-->
                        <!--Table body-->
                        <tbody>
                        </tbody>
                        <!--Table body-->
                    </table>
                </div>
         
        
            
        {{-- <button id="fly">Fly</button> --}}
    </div>
    <!-- End Display All Data -->

@endsection

@section('js')
<script>
    let myGeocoding = new GeocodingClass();
    var varjson = getJSON();
    function getJSON() {
        var resp;
        var resultArray = [];
        $.ajax({
            url: '/emergency/response-request/find-all-incident-request',
            type: 'GET',
            dataType: 'json',
            async: false,
            success : function(data) {
                resp = data;
            }, error : function(req, err) {
                console.log(err);
            }
        })

        resp.forEach(function(resps){
            var value = {
                        'type': 'Feature',
                        'properties': {
                            'description': myGeocoding.styList(resps.description,resps.first_name,resps.created_at,resps.contact_number,JSON.parse(resps.incident_location).longitude, JSON.parse(resps.incident_location).latitude)
                        },
                        'geometry': {
                            'type': 'Point',
                            'coordinates': [JSON.parse(resps.incident_location).longitude, JSON.parse(resps.incident_location).latitude]
                        }
                    };
            resultArray.push(value);
        });
        return resultArray;
    }
        
	mapboxgl.accessToken = 'pk.eyJ1IjoiZW1hdC1tYXBib3giLCJhIjoiY2tpc2o2ZG0zMjJhbjMzcDNmemRkbWYyaCJ9.AOfgLpKNRICdZkXGgEvTSQ';
    var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v11',
        center: [121.098695, 14.313854],
        zoom: 12
    });

    map.on('load', function () {
        map.loadImage("{{ asset('images/ecabs/emergency/pointer.gif') }}",
            // Add an image to use as a custom marker
           
            function (error, image) {
                if (error) throw error;
                map.addImage('custom-marker', image);
                map.addSource('places', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': varjson
                    }
                });
                
                // Add a layer showing the places.
                map.addLayer({
                    'id': 'places',
                    'type': 'symbol',
                    'source': 'places',
                    'layout': {
                        'icon-image': 'custom-marker',
                        'icon-allow-overlap': true,
                        'icon-size': 0.20
                    }
                });

                $('#datatable').DataTable({
                    "lengthChange": false,
                    "searching": false,
                    // "bPaginate": false,
                    "info": false,
                    "processing": false,
                    "serverSide": true,
                    "ajax":{
                        "url": '{{ route('response-request.locator-data') }}',
                        "dataType": "json",
                        "type": "POST",
                        "data":{ _token: "{{csrf_token()}}"}
                    },
                    "columns": [
                        { "data": "requestant" },
                        { "data": "actions" },
                    ],
                    "columnDefs": [
                        { "orderable": false, "targets": [ 1 ] }, 
                    ]	 	 
                });
            }
        );

        // Create a popup, but don't add it to the map yet.
        var popup = new mapboxgl.Popup({
            closeButton: false,
            closeOnClick: false
        });

        map.on('mouseenter', 'places', function (e) {
            // Change the cursor style as a UI indicator.
            map.getCanvas().style.cursor = 'pointer';

            var coordinates = e.features[0].geometry.coordinates.slice();
            var description = e.features[0].properties.description;

            // Ensure that if the map is zoomed out such that multiple
            // copies of the feature are visible, the popup appears
            // over the copy being pointed to.
            while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
                coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
            }

            // Populate the popup and set its coordinates
            // based on the feature found.
            popup.setLngLat(coordinates).setHTML(description).addTo(map);
        });

        map.on('mouseleave', 'places', function () {
            map.getCanvas().style.cursor = '';
            popup.remove();
        });
    });

</script>
@endsection
