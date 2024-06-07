// Initialize and add the map
//function initMap() {
    // The location of Uluru
//    const uluru = { lat: -25.344, lng: 131.036 };
    // The map, centered at Uluru
//    const map = new google.maps.Map(document.getElementById("map"), {
//      zoom: 4,
//      center: uluru,
//    });
    // The marker, positioned at Uluru
//    const marker = new google.maps.Marker({
//      position: uluru,
//      map: map,
//    });
//  }

//creamos las variable globales

//let map, infoWindow;
let map, infoWindow, marker;

function initMap(){
  
  map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: -34.397, lng: 150.644 },
    zoom: 10,
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

        marker = new google.maps.Marker({
          position: {
            lat: position.coords.latitude,
            lng: position.coords.longitude
          },
          map: map,
          draggable: true,
          animation: google.maps.Animation.DROP,
        })// fin del marker
        //agregamos evento por si mueven el marker
        google.maps.event.addListener(marker, 'dragend', function(e){
          //console.log(e.latLng.lat().toFixed(6));
          //console.log(e.latLng.lng().toFixed(6));
          document.querySelector('#latDomPer').value = e.latLng.lat().toFixed(6);
          document.querySelector('#lngDomPer').value = e.latLng.lng().toFixed(6);
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


function initMapAval(avales){
  //n = numero de identificador de aval
  
  map = new google.maps.Map(document.getElementById("mapAvales"), {
    center: { lat: -34.397, lng: 150.644 },
    zoom: 10,
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

        for(x = 1; x <= avales; x++){
          marker = new google.maps.Marker({
            position: {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            },
            map: map,
            label: 'Aval '+x,
            draggable: true,
            animation: google.maps.Animation.DROP,
          })// fin del marker
  
  
          //agregamos evento por si mueven el marker
          google.maps.event.addListener(marker, 'dragend', function(e){
            // let avalMarker = e.domEvent.path[1].ariaLabel;
            let avalMarker = e.domEvent.path[1].name;
            console.log(e.latLng);
            avalMarker = avalMarker.split('gmimap')[1];
            // console.log(avalMarker);
            // console.log(e.domEvent.path[1].name);
            let avalNumSelected = 0;
            switch (avalMarker) {
              case '1':
                avalNumSelected = 1;
                break;
              case '2':
                avalNumSelected = 2;
                break;
              case '3':
                avalNumSelected = 3;
                break;
              case '4':
                avalNumSelected = 4;
                break;
              default:
                break;
            }//fin del witch
            // console.log(e.domEvent.path[1].ariaLabel);
            document.querySelector('#latDomAval'+avalNumSelected).value = e.latLng.lat().toFixed(6);
            document.querySelector('#lngDomAval'+avalNumSelected).value = e.latLng.lng().toFixed(6);
            // document.getElementById('lbllat').classList.add('active');
            // document.getElementById('lblLng').classList.add('active');
            map.panTo(e.latLng);
          });
        }//fin del for para agregar marcadores

        
      }
    )
  }else{
    //no se tienen los permisos o el navegador no es compatible
    handleLocationError(false, infoWindow, map.getCenter());
  }

  //agregamos un marcador
 
  //marker.addEventListener("click", toggleBounce());

  
}// fin funcion initMap

function toggleBounce(){
  if(marker.getAnimation() !== null){
    marker.setAnimation(null);
  }else{
    marker.setAnimation(google.maps.Animation.BOUNCE);
  }
}

function getCordenadas(){
  if(navigator.geolocation){
    //pasamos las coordenadas a un arreglo
    navigator.geolocation.getCurrentPosition(
      (position) => {
        const pos = {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
        };
        map.setCenter(pos);
      }
    );

  }else{
    handleLocationError(false, infoWindow, map.getCenter());
  }
}

function handleLocationError(browserHasGeolocation, infoWindow, pos){
  infoWindow.setPosition(pos);
  infoWindow.setContent(
    browserHasGeolocation
    ? "Error: El servicio de geolocalizacion fallo"
    : "Error: Tu navegador no soporta geolocalizacion."
  );
  infoWindow.open(map);
}

function clickpaGetCord(){
  const locationButton = document.createElement("button");
  locationButton.textContent = "Obtener Coordenadas";
  locationButton.classList.add("custom-map-control-button");
  map.controls[google.maps.ControlPosition.TOP_CENTER].push(locationButton);
  locationButton.addEventListener("click", () => {
    //intentamos con geolocalizacion html5
    if(navigator.geolocation){
      navigator.geolocation.getCurrentPosition(
        (position) => {
          const pos = {
            lat: position.coords.latitude,
            lng: position.coords.longitude,
          };
          //console.log(position);
          infoWindow.setPosition(pos);
          infoWindow.setContent("Location found.");
          infoWindow.open(map);
          map.setCenter(pos);
        },
        () => {
          handleLocationError(true, infoWindow, map.getCenter());
        }
      );
    } else {
      //el navegador no soporta la geolocalizacion
      handleLocationError(false, infoWindow, map.getCenter());
    }
  });
}