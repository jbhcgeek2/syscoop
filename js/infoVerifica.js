var options;
var elems = document.querySelectorAll(".collapsible");
var instances = M.Collapsible.init(elems, options);

var combo = document.querySelectorAll("select");
var instaCombo = M.FormSelect.init(combo, options);


var campoComent = document.getElementById('newComent');
campoComent.addEventListener('keyup', function(e){
    let keycode = e.keyCode || e.which;
    if(keycode == 13){
        //se envia el comentario
        let comentario = campoComent.value;
        let verificacion = document.getElementById('idenVeri').value;

        let formComent = new FormData();
        formComent.append('newComent',comentario);
        formComent.append('idenVeriComent',verificacion);

        let envio = new XMLHttpRequest();
        envio.open('POST','../includes/operations/updateVerifica.php',false);
        envio.send(formComent);

        if(envio.status == 200){
            let resComent = envio.responseText;
            if(resComent.split('dataError|').length == 1){
                swal.fire(
                    "Comentario Insertado",
                    "Se guardo correctamente el comentario",
                    "sucess"
                ).then(function(){
                    //podemos actualizar la seccion de comentarios
                    location.reload();
                })
            }else{
                //error del lado del servidor
                let error = resComent.split("dataError|")[1];
                swal.fire(
                    "A ocurrido un error inesperado",
                    "Mensaje del servidor: " + error,
                    "error"
                );
            }
        }else{
            swal.fire(
                "Verifica tu conexion a Internet",
                "Al parecer ocurrio un error de comunicacion con el servidor, si el problema persiste reporta a sistemas.",
                "error"
              );
        }

    }
})


var campoEstatus = document.getElementById("estatusVerifica");
var btnUpdate = document.getElementById("btnAsignaVeri");

campoEstatus.addEventListener("change", function () {
  let optionEstatus = document.getElementById("estatusVerifica").value;
  if (optionEstatus == "ACEPTADA") {
    //si se acepta la verificacion mostramos los verificadores
    document.getElementById("campouserVeri").classList.remove("hide");
    document.getElementById("inputCancela").classList.add("hide");
  } else {
    document.getElementById("campouserVeri").classList.add("hide");
    document.getElementById("inputCancela").classList.remove("hide");
  }
});

//accio click para guardar
btnUpdate.addEventListener("click", function () {
  let optionEstatus = document.getElementById("estatusVerifica").value;
  let userAsignado = document.getElementById("userVerifica").value;
  let motivoCancela = document.getElementById("motivoCancela").value;
  let idenVeri = document.getElementById("idenVeri").value;
  let pasaFiltro = 0;

  let formSend = new FormData();
  if (optionEstatus == "ACEPTADA" && userAsignado != "") {
    //el usuario esta correctamente asignado
    //verificamos si realmente el usuario esta asignado
    formSend.append("usuarioVerifica", userAsignado);
    formSend.append("EstatusVerifica", "ACEPTADA");
    pasaFiltro = 1;
  } else if (optionEstatus == "CANCELADA" && motivoCancela.length > 5) {
    formSend.append("EstatusVerifica", "CANCELADA");
    formSend.append("motivoCancela", motivoCancela);
    pasaFiltro = 1;
  } else {
    swal.fire(
      "Error",
      "Verifica que los campos se encuentren correctaente capturados",
      "error"
    );
  }

  //si los filtros anteriores pasan, enviaremos el formulario
  if (pasaFiltro == 1) {
    let envio = new XMLHttpRequest();
    formSend.append("envioFormulario", "completeOperations");
    formSend.append("idenVeri", idenVeri);
    envio.open("POST", "../includes/operations/updateVerifica.php", false);
    envio.send(formSend);

    if (envio.status == 200) {
      let res = envio.responseText;
      if (res.split("dataError|").length == 1) {
        swal
          .fire(
            "Operacion Realizada",
            "Se actualizo correctamente la verificacion",
            "success"
          )
          .then(function () {
            location.reload();
          });
      } else {
        //ocurrio un error al procesar la peticion
        let error = res.split("dataError|")[1];
        swal.fire(
          "A ocurrido un error inesperado",
          "Mensaje del servidor: " + error,
          "error"
        );
      }
    } else {
      //ocurrio un error de comunicacion
      swal.fire(
        "Verifica tu conexion a Internet",
        "Al parecer ocurrio un error de comunicacion con el servidor, si el problema persiste reporta a sistemas.",
        "error"
      );
    }
  }
});



