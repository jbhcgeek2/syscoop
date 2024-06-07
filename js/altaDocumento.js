let btnGuardar = document.getElementById("enviaForm");
let consejoActa = document.getElementById("autorizadoPor");

let tipoDoc = document.getElementById("tipoDoc");

tipoDoc.addEventListener('change', function(){
  //verificamos el tipo de documento para indicar los campos a llenar
  let camposManual = ["auxResult1"];
  let camposAnexo = ["departamentoDoc","puestoEncargado","autorizadoPor","actaNumAutoriza"];
  let camposFormato = ["codiDoc","autorizadoPor","actaNumAutoriza"];
  let camposInforme = ["versionDoc","codiDoc","autorizadoPor","actaNumAutoriza"];
  let camposCredito = ["versionDoc","codiDoc","ContfechaActualizacion"];
  switch (tipoDoc.value) {
    case "Anexo":
        //verificamos los campos requeridos para un anexo
        let completo = new FormData(document.getElementById('formNewDoc'));
        for(let [name, value] of completo){
          let campoAnex = "Cont"+name;
          if(camposAnexo.includes(name)){
            //campo no permitido
            document.getElementById(campoAnex).classList.add("hide");
          }else{
            //el campo esta permitido, pero verificamos que este visible
            if(document.getElementById(campoAnex).classList.contains("hide")){
              //mostrampos el campo
              document.getElementById(campoAnex).classList.remove("hide");
            }
          }
        }
        

        //mandamos una consulta para obtener los manuales
        let datos = new FormData();
        datos.append("getManualesAnexo","anexos");
        let envio = new XMLHttpRequest();

        envio.open("POST","../includes/operations/documentos.php",false);
        envio.send(datos);

        if(envio.status == 200){
          let datosCombo = JSON.parse(envio.responseText);
          let contentManual = "<div class='input-field col s6 m8'><select name='manualAnexo' id='manualAnexo'>"+
          "<option value='' selected>Seleccione...</option>";
          for(let i = 0; i < datosCombo.length; i++){
            let nombreMan = datosCombo[i]['nombre_man'];
            let idMan = datosCombo[i]['iden'];
            // console.log(nombreMan);
            contentManual += "<option value='"+idMan+"'>"+nombreMan+"</option>";
          }//fin del for
          contentManual += "</select><label for='manualAnexo'>Manual</label></div>";
          document.getElementById('auxResult1').innerHTML = contentManual;
        }else{
          //indicamos error de comunicacion
          Swal.fire(
            'Erro de comunicacion',
            'Verifica tu conexion a internet',
            'error'
          )
        }

      break;
    case "Formato":
        //un formato contiene  unicamente nombre, version, y departamento
        //podra contar con fecha de publicacion en manera ed controlinterno y fecha de
        //actualizacion, ademas de un responsable
        document.getElementById("auxResult1").innerHTML = "";
        let completo3 = new FormData(document.getElementById('formNewDoc'));
        for(let [name, value] of completo3){
          let campoAnex = "Cont"+name;
          if(camposFormato.includes(name)){
            //campo no permitido
            document.getElementById(campoAnex).classList.add("hide");
          }else{
            //el campo esta permitido, pero verificamos que este visible
            if(document.getElementById(campoAnex).classList.contains("hide")){
              //mostrampos el campo
              document.getElementById(campoAnex).classList.remove("hide");
            }
          }
        }
      break;
    case "Manual":
        //si es manual regresamos todos los campos a su normalidad natural
        document.getElementById("auxResult1").innerHTML = "";
        let completo2 = new FormData(document.getElementById('formNewDoc'));
        for(let [name, value] of completo2){
          let campoAnex = "Cont"+name;
          if(document.getElementById(campoAnex).classList.contains("hide")){
            document.getElementById(campoAnex).classList.remove("hide");
          }
        }
      break;
    case "Informe":
    case "Presentacion":
        document.getElementById("auxResult1").innerHTML = "";
        let completo4 = new FormData(document.getElementById('formNewDoc'));
        for(let [name, value] of completo4){
          let campoAnex = "Cont"+name;
          if(camposInforme.includes(name)){
            //campo no permitido
            document.getElementById(campoAnex).classList.add("hide");
          }else{
            //el campo esta permitido, pero verificamos que este visible
            if(document.getElementById(campoAnex).classList.contains("hide")){
              //mostrampos el campo
              document.getElementById(campoAnex).classList.remove("hide");
            }
          }
        }
      break;
    case "Autorizacion Credito":
    case "Cotizacion":
    case "Anexo Acta":
      document.getElementById("auxResult1").innerHTML = "";
      let completo5 = new FormData(document.getElementById('formNewDoc'));
      for(let [name, value] of completo5){
        let campoAnex = "Cont"+name;
        if(camposCredito.includes(name)){
          //campo no permitido
          document.getElementById(campoAnex).classList.add("hide");
        }else{
          //el campo esta permitido, pero verificamos que este visible
          if(document.getElementById(campoAnex).classList.contains("hide")){
            //mostrampos el campo
            document.getElementById(campoAnex).classList.remove("hide");
          }
        }
      }

      break;
  
    default:
      break;
  }
  var elemSel = document.querySelectorAll('select');
  var options = "";
  var instanceSel = M.FormSelect.init(elemSel, options);

});

consejoActa.addEventListener('change', function(){
  let consejo = consejoActa.value;
  let dato = new FormData();

  dato.append('verActasConsejo',consejo);
  let enviar = new XMLHttpRequest();

  enviar.open("POST","../includes/operations/consejos.php",false);
  enviar.send(dato);

  if(enviar.status == 200){
    let valores = JSON.parse(enviar.responseText);
    
    //console.log(valores.length);
    let nuevoCombo = "<option value=''>Seleccione...</option><option value='P'>Acta Pendiente</option>";
    for(let x =0; x < valores.length; x++){
      console.log(valores[x][1]);
      let valor = valores[x][0];
      let texto = valores[x][1];
      nuevoCombo += "<option value='"+valor+"'>"+texto+"</option>";
    }//fin for
    document.getElementById("actaNumAutoriza").innerHTML = nuevoCombo;
    var elemSel = document.querySelectorAll('select');
    var options = "";
    var instanceSel = M.FormSelect.init(elemSel, options);

    let selActa = document.getElementById('actaNumAutoriza');
    selActa.addEventListener('change', function(){
      let valorActa = selActa.options[selActa.selectedIndex].text;
      if(valorActa != "Acta Pendiente"){
        let valorActa2 = valorActa.split(" - ");
        valorActa3 = valorActa2[2];
        document.getElementById("ContfechaPublicacion").classList.remove("hide");
        document.getElementById("ContfechaActualizacion").classList.remove("hide");
        document.getElementById("fechaPublicacion").value = valorActa3;
        document.getElementById("fechaActualizacion").value = valorActa3;
       
      }else{
        //se indico que el acta esta pendiente de autorizar, por lo que ocultamos las fechas
        document.getElementById("fechaPublicacion").value = "";
        document.getElementById("fechaActualizacion").value = "";
        document.getElementById("ContfechaPublicacion").classList.add("hide");
        document.getElementById("ContfechaActualizacion").classList.add("hide");

      }
       
    })

  }else{

  }
  
})

btnGuardar.addEventListener('click', function(){
  //hacemos una validacion de campos
  let datosDoc = new FormData(document.getElementById('formNewDoc'));
  let valida = 0;
  //verificamos que tipo de documento se almacenara para indicar que tipio de
  //campos seran los requeridos
  let tipoDoc = document.getElementById("tipoDoc").value;
  let camposOb;
  switch (tipoDoc) {
    case "Anexo":
      camposOb = ["tipoDoc","nombreDoc","versionDoc","codiDoc","manualAnexo",
      "fechaPublicacion","fechaActualizacion","docLectura","docEditable"];
      break;
    case "Manual":
      camposOb = ["tipoDoc","nombreDoc","versionDoc","codiDoc","departamentoDoc",
      "puestoEncargado","autorizadoPor","actaNumAutoriza","fechaPublicacion","fechaActualizacion",
      "docLectura","docEditable"];
      break;
    case "Formato":
      camposOb = ["tipoDoc","nombreDoc","versionDoc","departamentoDoc",
      "fechaPublicacion","fechaActualizacion","docLectura","docEditable"];
      break;
    case "Informe":
    case "Presentacion":
      camposOb = ["tipoDoc","nombreDoc","departamentoDoc","puestoEncargado","fechaPublicacion","fechaActualizacion",
      "docLectura","docEditable"];
    case "Autorizacion Credito":
    case "Cotizacion":
    case "Anexo Acta":
      camposOb = ["tipoDoc","nombreDoc","departamentoDoc","puestoEncargado","autorizadoPor",
      "actaNumAutoriza","fechaPublicacion"];
  
    default:
      break;
  }
  for(let [name, value] of datosDoc){
    if(camposOb.includes(name)){
      if(value.length == 0 || value == " "){
        if(name == "fechaActualizacion"){
          //este campo puede estar vacio
        }else{
          if(name == "fechaPublicacion"){
            //este campo puede estar vacio, pero con condiciones
            Swal.fire({
              title:'No se recomienda dejar la fecha de publicacion vacia',
              showDenyButton: true,
              showCancelButton: true,
              confirmButtonText: 'Dejar Vacia',
              denyButtonText: `Indicar fecha`,
            }).then((result)=>{
              if(result.isConfirmed){
                //el usuario dejo vacia la fecha, continuamos
              }else if(result.isDenied){
                document.getElementById(`${name}`).classList.add('campoMal');
                valida++;
              }
            })
          }else{
            document.getElementById(`${name}`).classList.add('campoMal');
            valida++;
          }
        }
      }
    }//fin existe el campo requerido
    
  }
  if(valida == 0){
    //hacemos el envio
    let envio = new XMLHttpRequest();
    envio.open("POST","../includes/operations/documentos.php",false);
    envio.send(datosDoc);
    //iniciamos un poreloader, para indicar la espera
    
    if(envio.status == 200){
      if(envio.responseText == "ProcessComplete" || envio.responseText == "ProcessComplete2"){
        Swal.fire(
          'Documento Registrado',
          'Se registro correctamente la documentacion',
          'success'
        ).then(function(){
          window.location = "ver-manuales.php";
        })
      }else{
        let error = envio.responseText.split("DataError|")[1];
        Swal.fire(
          'Error en el proceso',
          'Ha ocurrido un error inesperado: '+error,
          'error'
        )
      }
    }else{
      //error d comunicaciones
      Swal.fire(
        'Servidor Inalcansable',
        'Verifica tu conexion a internet.',
        'error'
      )
    }
  }else{
    Swal.fire(
      'Datos Incompletos',
      'Verifica que los campos esten correctamente capturados.',
      'warning'
    )
  }
})