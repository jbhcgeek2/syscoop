var elems = document.querySelectorAll('select');
var options = "";
var instances = M.FormSelect.init(elems, options);
let modals = document.querySelectorAll('.modal');
let modalInstance = M.Modal.init(modals, options);

var btnRegNewUSer = document.querySelector('#newUserPerson');

let valueOptions = document.getElementById('valueOfOptions').value;
let idenPerdonActive = document.getElementById('idenPerson').value;

btnRegNewUSer.addEventListener('click',function(){
  let content = `<div class="modal modal-fixed-footer" id="modalAsignUser">
    <div class="modal-content">
      <div class="row">
        <h5 class="center-align">Asignar nuevo Usuario<h5>

        <div class="input-field col s12 m4">
          <input type="text" id="nameNewUser">
          <label for="nameNewUser">Nombre Usuario</label>
        </div>
        <div class="input-field col s12 m4">
          <input type="password" id="passNewUser">
          <label for="passNewUser">Contraseña</label>
        </div>
        <div class="input-field col s12 m4">
          <input type="password" id="passNewUser2">
          <label for="">Confirma Contraseña</label>
        </div>

        <div class="input-field col s12 m4 offset-m4">
          <select id="typeNewUser">
            <option value="">Seleccione</option>
            ${valueOptions}
          </select>
          <label>Tipo de Usuario</label>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <a href="#!" class="btn waves waves-effect blue" id="sendDataNewUSer">
        Guardar
      </a>
      <a href="#!" class="btn waves-effect modal-close red">
        Cancelar
      </a>
    </div>
  </div>`;

  document.getElementById('contentModalUser').innerHTML = content;
  
  //inicializamos el modal
  let modals = document.querySelectorAll('.modal');
  let options = "";
  let modalInstances = M.Modal.init(modals, options);
  let modal2 = document.getElementById('modalAsignUser');
  let modalTest = M.Modal.getInstance(modal2);
  modalTest.open();
  var elems3 = document.querySelectorAll('select');
  var optionSelect = "";
  var instanceSelect = M.FormSelect.init(elems3, optionSelect);

  //verificamos si se activa el envio del formulario
  let btnSaveData = document.getElementById('sendDataNewUSer');

  btnSaveData.addEventListener('click',function(){
    guardarNewUser();
  })
});//fin registrar nuevo usuario a persona

function guardarNewUser(){
  let newUserName = document.getElementById('nameNewUser').value;
  let newPassUser = document.getElementById('passNewUser').value;
  let newPassUser2 = document.getElementById('passNewUser2').value;
  let newUserType = document.getElementById('typeNewUser').value;
  let idPerson = document.getElementById('idenPerson').value;

  //verificamos que el person este catoruado
  if(idPerson > 0){
    if(newPassUser == newPassUser2){
      //procedemos a enviar la informacion
      let dataSend = new FormData();
      let envio = new XMLHttpRequest();

      dataSend.append('newAsignUser',newUserName);
      dataSend.append('newPassAsign',newPassUser);
      dataSend.append('newPassAsign2',newPassUser2);
      dataSend.append('newTipoAsign',newUserType);
      dataSend.append('newUserPersonId',idPerson);

      envio.open('POST','../includes/operations/setUsuarios.php',false);
      envio.send(dataSend);

      if(envio.status == 200){
        let res = envio.responseText;
        if(res.split('DataError|') == 1){
          if(res == "OperationsSuccess"){
            swal.fire(
              'Proceso realizado',
              'Se guardo correctamente el usuario',
              'success'
            ).then(function(){
              location.reload();
            })
          }else{
            //error desconocido
            swal.fire(
              'Error desconocido',
              'A ocurrido un erro inesperado, favor de reportar a sistemas',
              'error'
            )
          }
        }else{
          //ocurrio un error al guardar
          let error = res.split('DataError|')[1];
          swal.fire(
            'Error al guardar',
            'Ocurrio un error al guardar la informacion: '+error,
            'error'
          )
        }
      }else{
        //error al envio del formulario
        swal.fire(
          'Error de comunicación',
          'Verifica tu conexion a internet',
          'error'
        )
      }
    }else{
      //las constraseñas no coinciden
      swal.fire(
        'Error',
        'Las contraseñas no coinciden',
        'error'
      )
    }
  }else{
    //error al obtener la persona
    swal.fire(
      'Error',
      'Persona no identificada',
      'error'
    )
  }


}

var btnChangeProfile = document.getElementById('imgChange');
let personaUpdate = document.getElementById('idenPerson').value;

btnChangeProfile.addEventListener('click',function(){
  let domResult = document.getElementById('resulModalProfile');
  let contentModal = `
  <div class="modal" id="modalChangeProfile">
    <div class="modal-content">
      <div class="row">
        <h5 class="center-align">Actualizar Imagen</h5>

        <div class="col s12">
          <form id="updateFormImg" enctype="multipart/form-data">
          <div class="file-field input-field">
            <div class="btn orange">
              <span>Imagen</span>
              <input type="file" id="newImagenProfile" name="newImagenProfile">
            </div>
            <div class="file-path-wrapper">
              <input class="file-path validate" type="text" name="nameImgUrl" id="nameImgUrl">
              <input type="hidden" id="personUpdate" name="personUpdate" value="${personaUpdate}">
            </div>
          </div>
          </form>

        </div>
      </div>
    </div>
    <div class="modal-footer">
      <a href="#!" class="btn waves-effect green" id="updateImageProfile">
        Actualizar
      </a>
      <a href="#!" class="btn waves-effect modal-close red">
        Cancelar
      </a>
    </div>
  </div>
  `;

  domResult.innerHTML = contentModal;
  let modals = document.querySelectorAll('.modal');
  let options = "";
  let modalInstances = M.Modal.init(modals, options);
  let modal2 = document.getElementById('modalChangeProfile');
  let modalTest = M.Modal.getInstance(modal2);
  modalTest.open();

  let btnSend = document.getElementById('updateImageProfile');
  btnSend.addEventListener('click',function(){
    let form = document.getElementById('updateFormImg');
    let formImg = new FormData(form);

    let enviar = new XMLHttpRequest();
    enviar.open('POST','../includes/operations/setUsuarios.php',false);

    enviar.send(formImg);

    if(enviar.status == 200){
      let respuesta = enviar.responseText;
      if(respuesta.split('DataError|').length == 1){
        if(respuesta == "operationSuccess"){
          swal.fire(
            'Imagen Actualizada',
            'Se actualizo correctamente la imagen de perfil',
            'success'
          ).then(function(){
            location.reload();
          })
        }else{
          swal.fire(
            'Error',
            'Ocurrió un error inesperado, reporta a sistemas',
            'error'
          )
        }
      }else{
        let error = respuesta.split('DataError|')[1];
        swal.fire(
          'Error',
          'Ocurrió un error: '+error,
          'error'
        )
      }
      console.log(respuesta);
    }else{
      swal.fire(
        'Error',
        'Verifica tu conexión a internet',
        'error'
        )
    }
  })

})//fin funcion cambiar imagen de perfil