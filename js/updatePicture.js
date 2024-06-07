
if(document.getElementById("noSocio")){

  let noSocio = document.getElementById("noSocio");
  noSocio.addEventListener('change', function(){
    //verificamos que contenga informacion
    if(noSocio.value.length >=1 && !isNaN(noSocio.value)){
      //enviamos los datos
      let form = new FormData();
      form.append("socioUpdatePic",noSocio.value);

      let enviar = new XMLHttpRequest();
      enviar.open('POST','includes/operations/updateFotos.php',false);
      enviar.send(form);

      if(enviar.status == 200){
        let result = enviar.responseText 
        
        if(result.split("DataSuccess|").length > 1){
          let nombreCompleto = result.split("DataSuccess|")[1];
          
          document.getElementById('nombreSocio').value = nombreCompleto;
          document.getElementById('botonConfirma').classList.remove('hide');
          document.getElementById("labelNombreSoc").classList.add('active');
        }else{
          //error al consultar la base de datos
          swal.fire(
            'Error',
            'Ha ocurrido un error al consultar la base de datos',
            'error'
          )
        }
      }else{
        //error al enviar la informacion

      }
    }else{
      //verifa la informacion
    }
    
  });
  
}




  let btnConfirmSocio = document.getElementById('btnConfirmaSocio');
  btnConfirmSocio.addEventListener('click', function(){
    document.getElementById('noSocio').setAttribute("readonly", true);
    document.getElementById('camView').classList.remove('hide');
    document.getElementById('botonConfirma').classList.add('hide');


    
    //COSAS PARA QUE FUNCIONE LA CAMARA
    const videoFoto = document.getElementById('liveVideoCamFoto');
    const videoFirma = document.getElementById('liveVideoCamFirma');
    const canvas = document.getElementById('canvaFoto');
    const snap = document.getElementById("snap");
    const errorMsgElement = document.querySelector('span#errorMsg');

    const constraints = {
      audio: false,
      video: {
        width: 500, height: 500
      }
    };
    //acceso a la camara web
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
      videoFoto.srcObject = stream;
      document.getElementById('marcoVid').classList.remove('hide');
      document.getElementById('snap').classList.remove('hide');
    }
    // Load init
    init();
    
    //Dibuyjamos la Foto
    var context = canvas.getContext('2d');
    snap.addEventListener("click", function() {
      context.drawImage(videoFoto, 0, 0, 500, 500);
      //ocultamos el video y el marco
      document.getElementById("marcoVid").classList.add("hide");
      document.getElementById("liveVideoCamFoto").classList.add("hide");
      document.getElementById("canvaFoto").classList.remove("hide");
      document.getElementById("snap").classList.add("hide");
      document.getElementById("reintentarFoto").classList.remove("hide");
      document.getElementById("saveFoto").classList.remove("hide");

      document.getElementById("reintentarFoto").addEventListener("click", function(){
        document.getElementById("snap").classList.remove("hide");
        document.getElementById("reintentarFoto").classList.add("hide");
        document.getElementById("marcoVid").classList.remove("hide");
        document.getElementById("canvaFoto").classList.add("hide");
        document.getElementById("liveVideoCamFoto").classList.remove("hide");
        document.getElementById("saveFoto").classList.add("hide");
      })
    });

    let btnSaveFoto = document.getElementById("saveFoto");
    btnSaveFoto.addEventListener("click", function(){
      let canvaImg = document.getElementById("canvaFoto");
      let canvaImgUrl = canvaImg.toDataURL("image/png");
      const createImg = document.createElement('a');
      
      let formPic = new FormData();
      let soc = document.getElementById("noSocio").value;

      formPic.append("imgBase64",canvaImgUrl);
      formPic.append("numSoc",soc);
      formPic.append("tipoPic","Foto");

      let enviar = new XMLHttpRequest();
      enviar.open("POST","includes/operations/guardaFotoFirma.php",false);
      enviar.send(formPic);

      if(enviar.status == 200){
        if(enviar.responseText == "updatePicture"){
          //se actualizo correctamente
          swal.fire(
            'Foto actualizada',
            'La foto se ha actualizado correctamente',
            'success',
          ).then(function(){
            location.reload();
          })
        }else{
          //ocurrio un error al actualizar la foto
          swal.fire(
            'Error',
            'Ha ocurrido un error inesperado, si el problema persiste contacte a sistemas.',
            'error',
          )
        }
      }else{

      }


      /*fetch("includes/operations/guardaFotoFirma.php", {
        method: "POST",
        mode: "no-cors",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: canvaImgUrl
      }).then(response => response.text)*/
    })
    //FIN FOTO SOCIOS
  })





  let btnConfirmaFirma = document.getElementById("btnConfirmaFirma");
  btnConfirmaFirma.addEventListener('click', function(){
    
    document.getElementById('noSocio').setAttribute("readonly", true);
    document.getElementById('camView').classList.remove('hide');
    document.getElementById('botonConfirma').classList.add('hide');
    
    //COSAS PARA QUE FUNCIONE LA CAMARA
    const videoFoto = document.getElementById('liveVideoCamFoto');
    const canvas = document.getElementById('canvaFotoFirma');
    const snap = document.getElementById("snapFirma");
    const errorMsgElement = document.querySelector('span#errorMsg');

    const constraints = {
      audio: false,
      video: {
        width: 500, height: 150
      }
    };
    //acceso a la camara web
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
      videoFoto.srcObject = stream;
      document.getElementById('snapFirma').classList.remove('hide');
    }
    // Load init
    init();
    
    //Dibuyjamos la Foto
    var context = canvas.getContext('2d');
    snap.addEventListener("click", function() {
      context.drawImage(videoFoto, 0, 0, 500, 150);
      //ocultamos el video y el marco
      //document.getElementById("marcoVid").classList.add("hide");
      document.getElementById("liveVideoCamFoto").classList.add("hide");
      document.getElementById("canvaFotoFirma").classList.remove("hide");
      document.getElementById("snapFirma").classList.add("hide");
      document.getElementById("reintentarFoto").classList.remove("hide");
      document.getElementById("saveFirma").classList.remove("hide");

      document.getElementById("reintentarFoto").addEventListener("click", function(){
        document.getElementById("snapFirma").classList.remove("hide");
        document.getElementById("reintentarFoto").classList.add("hide");
        //document.getElementById("marcoVid").classList.remove("hide");
        document.getElementById("canvaFotoFirma").classList.add("hide");
        document.getElementById("liveVideoCamFoto").classList.remove("hide");
        document.getElementById("saveFirma").classList.add("hide");
      })
    });


      //Accion para guardar la firma
      let btnSaveFoto = document.getElementById("saveFirma");
      btnSaveFoto.addEventListener("click", function(){
      let canvaImg = document.getElementById("canvaFotoFirma");
      let canvaImgUrl = canvaImg.toDataURL("image/png");
      const createImg = document.createElement('a');
      
      let formPic = new FormData();
      let soc = document.getElementById("noSocio").value;

      formPic.append("imgBase64",canvaImgUrl);
      formPic.append("numSoc",soc);
      formPic.append("tipoPic","Firma");

      let enviar = new XMLHttpRequest();
      enviar.open("POST","includes/operations/guardaFotoFirma.php",false);
      enviar.send(formPic);

      if(enviar.status == 200){
        if(enviar.responseText == "updatePicture"){
          //se actualizo correctamente
          swal.fire(
            'Firma actualizada',
            'La firma se ha actualizado correctamente',
            'success',
          ).then(function(){
            location.reload();
          })
        }else{
          //ocurrio un error al actualizar la foto
          swal.fire(
            'Error',
            'Ha ocurrido un error inesperado, si el problema persiste contacte a sistemas.',
            'error',
          )
        }
      }else{

      }


      /*fetch("includes/operations/guardaFotoFirma.php", {
        method: "POST",
        mode: "no-cors",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: canvaImgUrl
      }).then(response => response.text)*/
    })//////////////

  })





