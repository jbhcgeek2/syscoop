function editSuc(sucData){
    console.log(sucData);
    let auxData = sucData.split("|");
    let idSuc = auxData[0];
    let nombreSuc = auxData[1];
    let estatusSuc = auxData[2];

    //ahora mandamos llamar al modal de editar

    var elems = document.querySelectorAll('.modal');
    var instances = M.Modal.init(elems, options);
}