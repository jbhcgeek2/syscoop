<?php
/*
include_once "infophp.php";

$consulta = "SELECT * FROM tepicCierre.dbo.socios WHERE SOCIO = '7303'";

$sentencia = $con->query($consulta);
$socios = $sentencia->fetchAll(PDO::FETCH_OBJ);
print_r($socios);



$puesto2 = 'JEFE DE SISTEMAS2';
$socio = '7303';
$consulta2 = "UPDATE tepicCierre.dbo.SOCIOS SET PUESTO = ? WHERE SOCIO = ?";

$sql = $con->prepare($consulta2);
$query = $sql->execute([$puesto2, $socio]);
if($query){
  echo "Se actualizo";
}else{
  echo "no se actualizo";
}
*/
phpinfo();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>
    .video-wrap{
      position: relative;
    }
    .video{
      display: block;
    }
    .imagenmarco{
      width: 500px;
      height: 500px;
    }
    .video-wrap .imagenmarco{
      position:absolute;
    }
  </style>
</head>
<body>

  <!-- Stream video via webcam -->
  <div class="video-wrap">
      <img src="img/foto-marco.png" alt="" class="imagenmarco">
      <div class="video">
        <video id="video" playsinline autoplay></video>
      </div>
  </div>

  <!-- Trigger canvas web API -->
  <div class="controller">
      <button id="snap">Capture</button>
  </div>
  <div class="save-image">
    <button id="guardar">Guardar</button>
  </div>

  <!-- Webcam video snapshot -->
  <canvas id="canvas" width="640" height="480"></canvas>
  
</body>
</html>

<script>
  'use strict';

const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const snap = document.getElementById("snap");
const errorMsgElement = document.querySelector('span#errorMsg');

const constraints = {
  audio: false,
  video: {
    width: 500, height: 500
  }
};

// Access webcam
async function init() {
  try {
    const stream = await navigator.mediaDevices.getUserMedia(constraints);
    handleSuccess(stream);
  } catch (e) {
    errorMsgElement.innerHTML = `navigator.getUserMedia error:${e.toString()}`;
  }
}

// Success
function handleSuccess(stream) {
  window.stream = stream;
  video.srcObject = stream;
}

// Load init
init();

// Draw image
var context = canvas.getContext('2d');
snap.addEventListener("click", function() {
        context.drawImage(video, 0, 0, 500, 500);
});

  const descarga = document.getElementById('guardar');
  let canvita = document.getElementById('canvas');
/*
  descarga.addEventListener('click', function(e){
    const link = document.createElement('a');
    link.descarga = 'fotito.png';
    link.href = canvita.toDataURL();

    link.click();
    link.delete;
  })*/

  descarga.addEventListener('click', function(e){
    let canvasUrl = canvita.toDataURL("image/png");
    const createEl = document.createElement('a');
    createEl.href = canvasUrl;

    let newCanv = canvasUrl;
    

    createEl.download = "download-this-canvas";

    //createEl.click();
    //createEl.remove();
/*
    let formCanva = new FormData();

    formCanva.append("imagenCanva",newCanv);
    let enviar = new XMLHttpRequest();
    enviar.open('POST','saveCanvafile.php',false);
    enviar.send(formCanva);

    if(enviar.status == 200){
      let resp = enviar.responseText;
      console.log(resp);
    }*/
    var b64Image = canvita.toDataURL("image/png");
    let formPic = new FormData();
    let soc = document.getElememntById();

    formPic.append("imgBase64",b64Image);
    formPic.append("numSoc",);

    /*fetch("saveCanvafile.php", {
        method: "POST",
        mode: "no-cors",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: b64Image
    })  .then(response => response.text())
        .then(success => console.log(success))
        .catch(error => console.log(error));*/


  })

    //enviamos el formulario
    
    

</script>