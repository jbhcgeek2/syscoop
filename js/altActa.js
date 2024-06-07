let actaFecha = document.getElementById("actaFecha");
var manuArray = [];

actaFecha.addEventListener('focusout', function(){
  let campoNumero = document.getElementById("numeroActa").value;
  let fecha = actaFecha.value;
  if(fecha == null || fecha == ""){
  }else{
    let fecha2 = new Date(fecha);
    fecha2 = fecha2.getMonth()+1;

    document.getElementById("numeroActa").value = fecha2;
    document.getElementById("numeroActaLabel").classList.add("active");
  }


})



let btnAddDot = document.getElementById("addDot");

btnAddDot.addEventListener("click", function(){
  let puntos = document.getElementById("numControl").value;
  let newDot = Number(puntos)+1;
  let nexDoc1 = newDot+1;
  let nexDod = "contenedor"+nexDoc1;

  let contenidos = document.getElementById("contenedor"+newDot);
  
  let newCampo = `<div class="input-field col s12">
  <input type="text" id="puntoActa${newDot}" name="puntoActa${newDot}">
  <label>Punto a tratar ${newDot}</label>
  </div><div id="contenedor${nexDoc1}"></div>`;

  puntos = newDot;
  document.getElementById("numControl").value = newDot;
  // let camposNuevos = contenidos+newCampo;
  contenidos.innerHTML = newCampo;
});


let btnDelDot = document.getElementById("delDot");

btnDelDot.addEventListener("click", function(){

  let numPuntos = document.getElementById("numControl").value;
  
  // alert(numPuntos);
  if(numPuntos == 2){
    document.getElementById("contenedor"+numPuntos).innerHTML = " ";
    document.getElementById("numControl").value = 1;
  }else{
    
    document.getElementById("contenedor"+numPuntos).innerHTML = " ";
    let newPuntos = numPuntos-1;
    document.getElementById("numControl").value = newPuntos;
  }
  
})

function getNumerales(){
  let consejo = document.getElementById("actaConsejo").value;
  let tipoActa = document.getElementById("tipoActa").value;
  let fechAc = document.getElementById("actaFecha").value;
  

  if(consejo != "" && tipoActa != "" && fechAc != ""){
    //solicitud para obtener la informacion de los numerales de esas actas
    let numeral = document.getElementById("numeroActa").value;
    let datos = new FormData();
    datos.append("cons",consejo);
    datos.append("tipo", tipoActa);
    datos.append("fechAc",fechAc);

    let enviar = new XMLHttpRequest();
    enviar.open("POST","../includes/operations/consejos.php",false);
    enviar.send(datos);

    if(enviar.status == 200){
      let sel = document.getElementById("numeralExistente");
      let resultado = enviar.responseText;
      sel.innerHTML = resultado;

      var elemSel = document.querySelectorAll('select');
      var options = "";
      var instanceSel = M.FormSelect.init(elemSel, "");
    }else{

    }
  }
}


let btnGuardar = document.getElementById("enviaForm");
btnGuardar.addEventListener('click', function(){
  //enviamos los datos del formulario
  
  let datos = new FormData(document.getElementById("formNewAct"));
  let manualesAutori = "";
  let camposMal = 0;
  //verificamos los datos
  for(let [name, value] of datos){
    if(value.length == 0 || value == ""){
      //no pasa
      console.log(`${name}`);
      camposMal++;
    }
  }// fin del for de validacion

  //verificamos si contamos con manuales a autorizar
  if(manuArray.length > 0){
    //se autorizan manuales
    for(let x = 0; x < manuArray.length; x++){
      if(x == 0){
        manualesAutori = manuArray[x];
      }else{
        manualesAutori += "|"+manuArray[x];
      }
    }//fin del for
  }else{

  }
  datos.append("manualesAutori",manualesAutori);


  if(camposMal == 0){
    let envio = new XMLHttpRequest();

    envio.open("POST","../includes/operations/consejos.php",false);
    envio.send(datos);

    if(envio.status == 200){
      if(envio.responseText == "DataSucess"){
        Swal.fire(
          'Registro correcto',
          'Se guardaron correctamente los valores',
          'success'
        ).then(function(){
          window.location = 'ver-actas.php';
        })
      }else{
        let error = envio.responseText.split("DataError|");
        Swal.fire(
          'Error',
          'Verificar: '+error[1],
          'error'
        )
      }
    }else{
      //no se envio
      Swal.fire(
        'Error de Conexion',
        'Verifica tu conexion a internet',
        'error'
      )
    }
  }else{
    //algun campo esta incorrecto
    Swal.fire(
      'Verifica la informacion',
      'Algunos campos se encuentran vacios',
      'error'
    )
  }
  
})

function updateAutori(a){
  //funcion para indicar la autorizacion de un documento
  let datos = a.split("|");
  let doc = datos[1];
  // let valoresActuales = document.getElementById("manuAutorizados").value;
  let valoresActuales = manuArray;
  //let arrayValores = valoresActuales.split("|");
  //let nombredoc = "nombreDoc|"+doc;
  //nombredoc = document.getElementById(nombredoc).value;

  //buscamos el id del documento en el array actual
  let esta = valoresActuales.indexOf(doc);
  // console.log(esta);
  if(esta == -1){
    //el valos no se encuentra en el arreglo, lo agregamos
    manuArray.push(doc);
  }else{
    //el valor si se encuentra en el arreglo, lo eliminamos
    manuArray.splice(esta,1);
  }

  // console.log(manuArray);
  


  
}