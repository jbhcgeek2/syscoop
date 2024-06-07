
let btnSol = document.getElementById('btnSolicita');

btnSol.addEventListener('click', function(){
  let datos = new FormData();
  datos.append("key","921212127|403243543|40011212127|NO|NO");
  datos.append("username","superusertest");
  datos.append("password","1234");
  datos.append("confirm_password","1234");

  let envio = new XMLHttpRequest();
  envio.open('POST','https://api.condusef.gob.mx/auth/users/create-super-user/',false);
  // envio.open('POST','http://api.condusef.gob.mx/auth/user/create-super-user/',false);
  envio.send(datos);

  if(envio.status == 200){
    console.log(envio.responseText);
  }else{
    //fallo en la consulta
    console.log("Error en la peticion");
  }

})