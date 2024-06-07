
var btnGetUsuarios = document.querySelector('#getUsuarios');
var btnSetUsuarios = document.querySelector('#setUsers');



btnGetUsuarios.addEventListener('click',function(){
  //abrimos la ventana de usuarios
  window.location = 'verUsuarios.php';
});

btnSetUsuarios.addEventListener('click',function(){
  window.location = 'newUser.php';
});
