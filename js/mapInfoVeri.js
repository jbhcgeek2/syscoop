var map, infoWindow, marker;

function initMapVerif(){
  
        map = new google.maps.Map(document.getElementById("mapVeri"), {
          center: { lat: 21.509472, lng: -104.896112 },
          zoom: 9,
        });
        infoWindow = new google.maps.InfoWindow();
      
        if(navigator.geolocation){
          navigator.geolocation.getCurrentPosition(
            (position) => {
              const posi = {
                lat: position.coords.latitude,
                lng: position.coords.longitude,
              };
              map.setCenter(posi);

              let socLat = document.getElementById('latPer').value;
              let socLng = document.getElementById('lngPer').value;
              let socUbi = new google.maps.LatLng(socLat, socLng);

              marker = new google.maps.Marker({
                position: socUbi,
                map: map,
                draggable: false,
                label: 'SOCIO',
                animation: google.maps.Animation.DROP,
              })// fin del marker


              
              let nAv = document.getElementById('avalesNum').value;
              for(let aval = 1; aval < nAv; aval++){
                  let latAv = document.getElementById('latAval'+aval).value;
                  let lngAv = document.getElementById('lngAval'+aval).value;

                  let coords = new google.maps.LatLng(latAv, lngAv);

                  mar = new google.maps.Marker({
                    position: coords,
                    map: map,
                    draggable: false,
                    label: 'Aval: '+aval,
                    animation: google.maps.Animation.DROP,
                  })
                
              }//fin del for


              //agregamos evento por si mueven el marker
              google.maps.event.addListener(marker, 'dragend', function(e){
                //console.log(e.latLng.lat().toFixed(6));
                //console.log(e.latLng.lng().toFixed(6));
                document.querySelector('#latDomPer').value = e.latLng.lat().toFixed(6);
                document.querySelector('#lngDomPer').value = e.latLng.lat().toFixed(6);
                //document.getElementById('lbllat').classList.add('active');
                //document.getElementById('lblLng').classList.add('active');
                map.panTo(e.latLng);
              });
            }
          )
        }else{
          //no se tienen los permisos o el navegador no es compatible
          handleLocationError(false, infoWindow, map.getCenter());
        }
      
        //agregamos un marcador
       
        //marker.addEventListener("click", toggleBounce());
      
        
      }// fin funcion initMap
