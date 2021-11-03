class GeocodingClass {

    styList(category,requestant,time,contact,lat,long){
    return  `<table class="table">
                <thead>
                    <tr>
                        <th colspan="2" style="text-align: center"><b>Incident Details</b></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><i class="fa fa-map-signs" aria-hidden="true"></i></td>
                        <td>`+this.locationByGeocoding(lat,long)+`</td>
                    </tr>
                    <tr>
                        <td><i class="fa fa-warning" aria-hidden="true"></i></td>
                        <td>`+category+`</td>
                    </tr>
                    <tr>
                        <td><i class="fa fa-user" aria-hidden="true"></i></td>
                        <td>`+requestant+`</td>
                    </tr>
                    <tr>
                        <td><i class="fa fa-phone-square" aria-hidden="true"></i></td>
                        <td style="color:red;"><b>`+contact+`</b></td>
                    </tr>
                    <tr>
                        <td><i class="fa fa-calendar-check-o" aria-hidden="true"></i></td>
                        <td>`+time+`</td>
                    </tr>
                </tbody>
            </table>`;
    }

    locationByGeocoding(lat,long){
        //geocoding code
        var rtn = "";
        $.ajax({
                url: 'https://api.mapbox.com/geocoding/v5/mapbox.places/'+lat+','+long+'.json?access_token=pk.eyJ1IjoiZW1hdC1tYXBib3giLCJhIjoiY2tpc2o2ZG0zMjJhbjMzcDNmemRkbWYyaCJ9.AOfgLpKNRICdZkXGgEvTSQ',
                type: 'GET',
                dataType: 'json',
                async: false,
                success : function(data) {
                    //return JSON.parse(data.features[0].place_name);
                    //console.log(data.features[0].place_name);
                    rtn = data.features[0].place_name;
                }, error : function(req, err) {
                    console.log(err);
                }
            });
        return rtn;
    }


    
     flyToArea(longLat){
        var lat = longLat.latitude;
        var long = longLat.longitude;
        var arrLatLong = [long,lat];

        map.flyTo({
            center: arrLatLong,
            zoom: 18,
            essential: true // this animation is considered essential with respect to prefers-reduced-motion
            });
    }

    contactPerson(contact){
        alert(contact);
    }

  }
  