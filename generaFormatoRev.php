<?php 
// create new PDF document

session_start();
include('includes/_con.php');
if(!empty($_SESSION['usNamePlataform'])){
  include('includes/operations/usuarios.php');  
  $uu = $_SESSION['usNamePlataform'];
  $permUser = getPermisos($uu);
  $permiso = json_decode($permUser);

  if($permiso->auditar_inventario == 1){
    //verificamos el estatus de la auditoria
    $auditoria = $_GET['infoVeri'];
    $sql = "SELECT * FROM auditoria_inventario a INNER JOIN usuarios b 
    ON a.usuario_inicia = b.id_usuario INNER JOIN empleados c ON b.empleado_id = c.id_empleado 
    WHERE a.id_auditoria = '$auditoria'";
    $query = mysqli_query($conexion, $sql);
    if(mysqli_num_rows($query) > 0){
      $fetch = mysqli_fetch_assoc($query);
      $fechaInicio = $fetch['fecha_inicio'];
      $fechaFin = $fetch['fecha_fin'];
      $tipoRev = $fetch['tipo_auditoria'];
      $nombrePersona = $fetch['paterno']." ".$fetch['materno']." ".$fetch['nombre'];
      $nombrePersona = ucfirst($nombrePersona);
      if($fechaFin == ""){
        $primerTexto = $tipoRev." iniciada el ".$fechaInicio. " por ".$nombrePersona;
      }else{
        $primerTexto = $tipoRev." realizada del ".$fechaInicio." al ".$fechaFin;
      }
      

      
      
      
        require_once('TCPDF-main/tcpdf_import.php');
        class newFicha extends TCPDF{
            public function Header(){

            }
          }//fin de la class
          //$pageLayout = array('250', '390');
          //$pageLayout = new newFicha('p', 'mm', $pageLayout, true, 'UTF-8', false);
          $pdf = new newFicha("L",PDF_UNIT,PDF_PAGE_FORMAT,true,'UTF-8',false);


        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('SysCoop By TecuaniSoft');
        $pdf->SetTitle('Listo de Articulos, Mobiliario y Equipo');
        $pdf->SetSubject('Listo de Articulos, Mobiliario y Equipo');
        $pdf->SetKeywords('Caja Tepic');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 005', PDF_HEADER_STRING);

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }


        // ---------------------------------------------------------
        // set font
        $pdf->SetFont('times', '', 15);

        // add a page
        $pdf->AddPage();
        // set cell padding
        $pdf->setCellPaddings(3, 3, 3, 3);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->MultiCell(285, 10, 'Listado de articulos, Mobiliario y Equipo', 0, 'C', 1, 1, '5', '10', true);
        $pdf->SetFont('times', '', 10);
        $pdf->MultiCell(285, 10, 'Caja tepic S.C. de A.P. de R.L. de C.V.', 0, 'C', 1, 1, '5', '17', true);
        $pdf->MultiCell(285, 10, $primerTexto, 0, 'C', 1, 1, '5', '22', true);
        // set cell margins
        $pdf->SetFont('times', '', 10);
        $pdf->setCellMargins(1, 1, 10, 1);
        // set color for background
        $pdf->SetFillColor(255, 255, 255);
        // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
        // set some text for example
        //$txt = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';

        //consultamos los articulos
        if($fechaFin == ""){
          $sql2 = "SELECT * FROM inventario a INNER JOIN empleados b ON a.resguardo_empleado = b.id_empleado 
          WHERE a.articulo_activo = '1' ORDER BY a.sucursal_resguardo,a.lugar_resguardo ASC";
          $query2 = mysqli_query($conexion, $sql2);
          $i = 1;
          $rows = 1;
          $maxRows = 22;
          $y = 53;
          $y2 = 55;
          //maximo de anch 285
          $pdf->SetFillColor(222, 213, 211);
          $pdf->setCellPaddings(2, 2, 2, 2);
          $pdf->MultiCell(15, 5, 'No.', 1, 'L', 1, 1, '5', 45, true);
          $pdf->MultiCell(13, 5, 'ID', 1, 'L', 1, 1, '20', 45, true);
          $pdf->MultiCell(25, 5, 'Codigo', 1, 'L', 1, 1, '33', 45, true);
          $pdf->MultiCell(60, 5, 'Nombre', 1, 'L', 1, 1, '55', 45, true);
          $pdf->MultiCell(25, 5, 'Sucursal', 1, 'L', 1, 1, '115', 45, true);
          $pdf->MultiCell(50, 5, 'Lugar Resguardo', 1, 'L', 1, 1, '140', 45, true);
          $pdf->MultiCell(60, 5, 'Responsable', 1, 'L', 1, 1, '190', 45, true);
          $pdf->MultiCell(38, 5, 'Verificado', 1, 'L', 1, 1, '250', 45, true);
          $pdf->SetFont('times', '', 9);
          $pdf->SetFillColor(255, 255, 255);
          $pdf->setCellPaddings(1, 1, 1, 2);

          while($fetch2 = mysqli_fetch_assoc($query2)){
            $codigo = $fetch2['codigo'];
            $idObjeto = $fetch2['id_inventario'];
            //$nombreEmpleado = $fetch['paterno']." ".$fetch['materno']." ".$fetch['nombre'];
            $nombreObjeto = $fetch2['nombre_objeto'];
            $lugarRes = $fetch2['lugar_resguardo'];
            $sucursalRes = $fetch2['sucursal_resguardo'];
            $nombreEmpleado2 = $fetch2['nombre']." ".$fetch2['paterno']." ".$fetch2['materno'];
            $nombreEmpleado2 = ucfirst($nombreEmpleado2);
            $revisado = "";
            //verificamos si el objeto ya se inventario
            $sql3 = "SELECT * FROM auditoria_objeto WHERE inventario_id = '$idObjeto' 
            AND auditoria_id  = '$auditoria'";
            $query3 = mysqli_query($conexion, $sql3);
            if(mysqli_num_rows($query3) == 1){
              //ya esta revisado
              $fetch3 = mysqli_fetch_assoc($query3);
              $revisado = $fetch3['fecha_inventario'];
              $pdf->SetFillColor(244, 252, 183);
            }else{
              //aun no esta inventariado
              $pdf->SetFillColor(255, 255, 255);
            }



            
            $pdf->MultiCell(15, 4, $i, 1, 'L', 1, 1, '5', $y, true);
            $pdf->MultiCell(13, 4, $idObjeto, 1, 'L', 1, 1, '20', $y, true);
            $pdf->MultiCell(25, 4, $codigo, 1, 'L', 1, 1, '33', $y, true);
            $pdf->MultiCell(60, 4, $nombreObjeto, 1, 'L', 1, 1, '55', $y, true);
            $pdf->MultiCell(25, 4, $sucursalRes, 1, 'L', 1, 1, '115', $y, true);
            $pdf->MultiCell(50, 4, $lugarRes, 1, 'L', 1, 1, '140', $y, true);
            $pdf->MultiCell(60, 4, $nombreEmpleado2, 1, 'L', 1, 1, '190', $y, true);
            $pdf->MultiCell(38, 4, $revisado, 1, 'L', 1, 1, '250', $y, true);
            $y = $y+6;
            $i++;
            $rows++;
            if(($i % 22) == 0){
              $pdf->AddPage();
              $pdf->SetFont('times', '', 15);
              $pdf->MultiCell(285, 10, 'Listado de articulos, Mobiliario y Equipo', 0, 'C', 1, 1, '5', '10', true);
              $pdf->SetFont('times', '', 10);
              $pdf->MultiCell(285, 10, 'Caja tepic S.C. de A.P. de R.L. de C.V.', 0, 'C', 1, 1, '5', '17', true);
              $pdf->MultiCell(285, 10, $primerTexto, 0, 'C', 1, 1, '5', '22', true);

              $pdf->SetFillColor(222, 213, 211);
              $pdf->setCellPaddings(2, 2, 2, 2);
              $pdf->MultiCell(15, 5, 'No.', 1, 'L', 1, 1, '5', 45, true);
              $pdf->MultiCell(13, 5, 'ID', 1, 'L', 1, 1, '20', 45, true);
              $pdf->MultiCell(25, 5, 'Codigo', 1, 'L', 1, 1, '33', 45, true);
              $pdf->MultiCell(60, 5, 'Nombre', 1, 'L', 1, 1, '55', 45, true);
              $pdf->MultiCell(25, 5, 'Sucursal', 1, 'L', 1, 1, '115', 45, true);
              $pdf->MultiCell(50, 5, 'Lugar Resguardo', 1, 'L', 1, 1, '140', 45, true);
              $pdf->MultiCell(60, 5, 'Responsable', 1, 'L', 1, 1, '190', 45, true);
              $pdf->MultiCell(38, 5, 'Verificado', 1, 'L', 1, 1, '250', 45, true);
              $pdf->SetFillColor(255, 255, 255);
              $pdf->setCellPaddings(1, 1, 1, 2);
              $pdf->SetFont('times', '', 9);

              $y = 53;
            }
          }//fin del while
        }else{
          //ya se encuentra finalizada, generamos el formato de resumen
          $textoIntroduccion = "Por medio del presente documento se da el resumen de la ".$tipoRev.
          " realizada en el periodo del ".$fechaInicio." al ".$fechaFin. " llevada acabo por ".
          $nombrePersona.". en continuacion se muestra el resultado de esta.";
          $pdf->SetFont('times', '', 10);
          $pdf->MultiCell(285, 20, $textoIntroduccion, 0, 'L', 1, 1, 5, 45, true, 0, false, true, 20, 'M', true);

          $sql2 = "SELECT * FROM auditoria_objeto WHERE auditoria_id = '$auditoria'";
          $query2 = mysqli_query($conexion, $sql2);
          $y = 75;
          $i = 1;
          $y2 = 85;
          while($fetch2 = mysqli_fetch_assoc($query2)){
            $idObj = $fetch2['inventario_id'];
            $sql3 = "SELECT * FROM inventario WHERE id_inventario = '$idObj'";
            $query3 = mysqli_query($conexion, $sql3);
            $fetch3 = mysqli_fetch_assoc($query3);
            $nombreObj = strtolower($fetch3['nombre_objeto']);
            $nombreObj = ucfirst($nombreObj);
            $fechaVeri = $fetch2['fecha_inventario'];
            $idUser = $fetch2['usuario_inventariado'];
            $lugarRes = $fetch2['lugar_resguardo_inv'];
            $sucurRes = $fetch2['sucur_resguardo_inv'];
            $sql4 = "SELECT * FROM usuarios a INNER JOIN empleados b ON a.empleado_id = b.id_empleado 
            WHERE a.id_usuario = '$idUser'";
            $query4 = mysqli_query($conexion, $sql4);
            $fetch4 = mysqli_fetch_assoc($query4);

            $nombreVerificador = ucfirst($fetch4['paterno'])." ".ucfirst($fetch4['materno'])." ".ucfirst($fetch4['nombre']);
            // $nombreVerificador = strtolower($nombreVerificador);
            // $nombreVerificador = ucfirst($nombreVerificador);
            $descripcion = "Se valida la existencia del objeto ubicado en ".$lugarRes." de la sucursal ".
            $sucurRes." y se dictamina lo siguiente: ".$fetch2['observaciones'].". hora de verificacion: ".
            $fetch2['hora_inventario'].". ";
            $descripcion = strtolower($descripcion);
            

            $pdf->SetFont('times', '', 10);
            $pdf->SetFillColor(212, 212, 212);
            $pdf->MultiCell(13, 25, 'No.', 1, 'L', 1, 1, 5, $y, true, 0, false, true, 25, 'M', true);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->MultiCell(13, 25, $i, 1, 'L', 1, 1, 18, $y, true, 0, false, true, 25, 'M', true);
            //$pdf->MultiCell(13, 25, $i, 1, 'L', 1, 1,18, $y, true);
            $pdf->SetFillColor(231, 231, 231);
            $pdf->MultiCell(35, 10, 'Nombre del Objeto:', 1, 'L', 1, 1, 31, $y, true, 0, false, true, 10, 'M', true);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->MultiCell(50, 10, $nombreObj, 1, 'L', 1, 1, 66, $y, true, 0, false, true, 10, 'M', true);
            $pdf->SetFillColor(231, 231, 231);
            $pdf->MultiCell(30, 10, "Fecha ".$tipoRev.":", 1, 'L', 1, 1, 116, $y, true, 0, false, true, 10, 'M', true);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->MultiCell(30, 10, $fechaVeri, 1, 'L', 1, 1, 146, $y, true, 0, false, true, 10, 'M', true);
            $pdf->SetFillColor(231, 231, 231);
            $pdf->MultiCell(30, 10, "Validador:", 1, 'L', 1, 1, 176, $y, true, 0, false, true, 10, 'M', true);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->MultiCell(84, 10, $nombreVerificador, 1, 'L', 1, 1, 206, $y, true, 0, false, true, 10, 'M', true);
            $pdf->SetFillColor(231, 231, 231);
            $pdf->MultiCell(35, 15, "Descripcion:", 1, 'L', 1, 1, 31, $y2, true, 0, false, true, 10, 'M', true);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->MultiCell(224, 15, $descripcion, 1, 'L', 1, 1, 66, $y2, true, 0, false, true, 10, 'M', true);
            $y = $y+25;
            $y2 = $y2+25;
            $i++;
            if($i==9){

            }else{
              
            }
            if(($y % 175) == 0 || ($y % 170) == 0){
              $pdf->AddPage();
              $pdf->SetFont('times', '', 15);
              $pdf->MultiCell(285, 10, 'Listado de articulos, Mobiliario y Equipo', 0, 'C', 1, 1, '5', '10', true);
              $pdf->SetFont('times', '', 10);
              $pdf->MultiCell(285, 10, 'Caja tepic S.C. de A.P. de R.L. de C.V.', 0, 'C', 1, 1, '5', '17', true);
              $pdf->MultiCell(285, 10, $primerTexto, 0, 'C', 1, 1, '5', '22', true);


              $y = 45;
              $y2 = 55;
            }
          }// fin while tabla auditoria inventario
          //mostramos el resumen de la validacion
          $pdf->AddPage();
          $pdf->SetFont('times', '', 15);
          $pdf->MultiCell(285, 10, 'Listado de articulos, Mobiliario y Equipo', 0, 'C', 1, 1, '5', '10', true);
          $pdf->SetFont('times', '', 10);
          $pdf->MultiCell(285, 10, 'Caja tepic S.C. de A.P. de R.L. de C.V.', 0, 'C', 1, 1, '5', '17', true);
          $pdf->MultiCell(285, 10, $primerTexto, 0, 'C', 1, 1, '5', '22', true);
          $pdf->SetFont('times', '', 20);
          $pdf->MultiCell(285, 10, 'Resumen', 0, 'C', 1, 1, '5', '35', true);
          $pdf->SetFont('times', '', 10);

          $pdf->SetFont('times', '', 15);
          $pdf->setCellPaddings(1, 0, 1, 0);
          $pdf->SetFillColor(255, 255, 255);
          $pdf->MultiCell(60, 10, 'Articulos Por Sucursal', 1, 'C', 1, 1, 30, 50, true, 0, false, true, 10, 'M', true);
          //mostramos las sucursales verificadas
          $sql5 = "SELECT DISTINCT(sucur_resguardo_inv) FROM auditoria_objeto WHERE auditoria_id = '$auditoria'
          ORDER BY sucur_resguardo_inv ASC";
          $query5 = mysqli_query($conexion, $sql5);
          $pdf->SetFont('times', '', 9);
          $iSuc = 67;
          $pdf->SetFillColor(231, 231, 231);
          $pdf->setCellPaddings(1, 0, 1, 0);
          $pdf->MultiCell(25, 7, 'Nombre', 1, 'C', 1, 1, 30, 60, true, 0, false, true, 7, 'M');
          $pdf->MultiCell(15, 7, "Total \n Articulos", 1, 'C', 1, 1, 55, 60, true, 0, false, true, 7, 'M', true);
          $pdf->MultiCell(20, 7, 'Revisados', 1, 'C', 1, 1, 70, 60, true, 0, false, true, 7, 'M');
          $pdf->SetFillColor(255, 255, 255);
          $totArti = 0;
          $totalBySuc = 0;
          while($fetch5 = mysqli_fetch_assoc($query5)){
            $suc = $fetch5['sucur_resguardo_inv'];
            //contamos los articulos que se validaron de esa sucursal
            $sql6 = "SELECT COUNT(*) AS numObj FROM auditoria_objeto WHERE sucur_resguardo_inv = '$suc' 
            AND auditoria_id = '$auditoria'";
            $query6 = mysqli_query($conexion, $sql6);
            $fetch6 = mysqli_fetch_assoc($query6);
            $numeroArti = $fetch6['numObj'];
            $sql6Ext = "SELECT COUNT(*) AS numObjSuc FROM inventario WHERE sucursal_resguardo = '$suc' AND 
            fecha_registro <= '$fechaFin' AND articulo_activo = '1'";
            $query6Ext = mysqli_query($conexion, $sql6Ext);
            $fetch6Ext = mysqli_fetch_assoc($query6Ext);
            $numOriginal = $fetch6Ext['numObjSuc'];

            $pdf->MultiCell(25, 7, $suc, 1, 'L', 1, 1, 30, $iSuc, true, 0, false, true, 7, 'M', true);
            $pdf->MultiCell(15, 7, $numOriginal, 1, 'C', 1, 1, 55, $iSuc, true, 0, false, true, 7, 'M');
            $pdf->MultiCell(20, 7, $numeroArti, 1, 'C', 1, 1, 70, $iSuc, true, 0, false, true, 7, 'M');
            $iSuc=$iSuc+7;
            $totArti = $totArti + $numeroArti;
            $totalBySuc = $totalBySuc + $numOriginal;

          }//fin del while sucursales auditadas
          $pdf->MultiCell(25, 7, 'Total', 1, 'R', 1, 1, 30, $iSuc, true, 0, false, true, 7, 'M');
          $pdf->MultiCell(15, 7, $totalBySuc, 1, 'C', 1, 1, 55, $iSuc, true, 0, false, true, 7, 'M');
          $pdf->MultiCell(20, 7, $totArti, 1, 'C', 1, 1, 70, $iSuc, true, 0, false, true, 7, 'M');


          //articulos por estatus
          $pdf->SetFont('times', '', 15);
          $pdf->setCellPaddings(1, 0, 1, 0);
          $pdf->SetFillColor(255, 255, 255);
          $pdf->MultiCell(50, 10, 'Articulos Por Estatus', 1, 'C', 1, 1, 100, 50, true, 0, false, true, 10, 'M', true);
          $pdf->SetFont('times', '', 9);
          $pdf->SetFillColor(231, 231, 231);
          $pdf->MultiCell(25, 7, 'Estatus', 1, 'C', 1, 1, 100, 60, true, 0, false, true, 7, 'M');
          $pdf->MultiCell(25, 7, 'No. Articulos', 1, 'C', 1, 1, 125, 60, true, 0, false, true, 7, 'M');
          $pdf->SetFillColor(255, 255, 255);
          $sql7 = "SELECT DISTINCT(estado_objeto) FROM auditoria_objeto WHERE auditoria_id = '$auditoria' 
          ORDER BY estado_objeto ASC";
          $query7 = mysqli_query($conexion, $sql7);
          $iEst = 67;
          $totalEstatus = 0;
          while($fetch7 = mysqli_fetch_assoc($query7)){
            $estatus = $fetch7['estado_objeto'];
            $sql8 = "SELECT COUNT(*) AS numEstatus FROM auditoria_objeto WHERE estado_objeto = '$estatus' AND 
            auditoria_id = '$auditoria'";
            $query8 = mysqli_query($conexion, $sql8);
            $fetch8 = mysqli_fetch_assoc($query8);
            $numEstatus = $fetch8['numEstatus'];
            $pdf->MultiCell(25, 7, $estatus, 1, 'L', 1, 1, 100, $iEst, true, 0, false, true, 7, 'M', true);
            $pdf->MultiCell(25, 7, $numEstatus, 1, 'C', 1, 1, 125, $iEst, true, 0, false, true, 7, 'M');
            $iEst = $iEst+7;
            $totalEstatus = $totalEstatus + $numEstatus;
          }
          $pdf->MultiCell(25, 7, 'Total', 1, 'R', 1, 1, 100, $iEst, true, 0, false, true, 7, 'M');
          $pdf->MultiCell(25, 7, $totalEstatus, 1, 'C', 1, 1, 125, $iEst, true, 0, false, true, 7, 'M');
          
          $pdf->SetFont('times', '', 15);
          $pdf->setCellPaddings(1, 0, 1, 0);
          $pdf->MultiCell(50, 10, 'Articulos Dado de Baja', 1, 'C', 1, 1, 160, 50, true, 0, false, true, 10, 'M', true);
          $pdf->SetFillColor(231, 231, 231);
          $pdf->SetFont('times', '', 9);
          $pdf->MultiCell(30, 7, 'Sucursal', 1, 'C', 1, 1, 160, 60, true, 0, false, true, 7, 'M');
          $pdf->MultiCell(20, 7, 'No. Bajas', 1, 'C', 1, 1, 190, 60, true, 0, false, true, 7, 'M');
          $pdf->SetFillColor(255, 255, 255);

          $sql9 = "SELECT DISTINCT(sucursal_resguardo) FROM inventario WHERE fecha_registro <= '$fechaFin'";
          $query9 = mysqli_query($conexion, $sql9);
          $iBaja = 67;
          $totBajas = 0;
          while($fetch9 = mysqli_fetch_assoc($query9)){
            $sucur = $fetch9['sucursal_resguardo'];
            //ahora consultamos las bajas de esa sucursal
            $sql10 = "SELECT COUNT(*) AS numBajas FROM inventario WHERE sucursal_resguardo = '$sucur' AND articulo_activo = '2' AND (fecha_baja BETWEEN '$fechaInicio' AND '$fechaFin')";
            $query10 = mysqli_query($conexion, $sql10);
            $fetch10 = mysqli_fetch_assoc($query10);
            $numBaja = $fetch10['numBajas'];
            $pdf->MultiCell(30, 7, $sucur, 1, 'L', 1, 1, 160, $iBaja, true, 0, false, true, 7, 'M', true);
            $pdf->MultiCell(20, 7, $numBaja, 1, 'C', 1, 1, 190, $iBaja, true, 0, false, true, 7, 'M');
            $iBaja = $iBaja+7;
            $totBajas = $totBajas+$numBaja;
          }//fin baja por sucursales

          $pdf->MultiCell(30, 7, 'Total', 1, 'R', 1, 1, 160, $iBaja, true, 0, false, true, 7, 'M');
          $pdf->MultiCell(20, 7, $totBajas, 1, 'C', 1, 1, 190, $iBaja, true, 0, false, true, 7, 'M');


          //mostramos las clasificaciones
          $pdf->SetFont('times', '', 15);
          $pdf->setCellPaddings(1, 0, 1, 0);
          $pdf->MultiCell(60, 10, 'Articulos por Clasificacion', 1, 'C', 1, 1, 220, 50, true, 0, false, true, 10, 'M', true);
          $pdf->SetFillColor(231, 231, 231);
          $pdf->SetFont('times', '', 9);
          $pdf->MultiCell(25, 7, 'Clasificacion', 1, 'C', 1, 1, 220, 60, true, 0, false, true, 7, 'M');
          $pdf->MultiCell(15, 7, 'Total Articulos', 1, 'C', 1, 1, 245, 60, true, 0, false, true, 7, 'M', true);
          $pdf->MultiCell(20, 7, 'Validados', 1, 'C', 1, 1, 260, 60, true, 0, false, true, 7, 'M', true);
          $pdf->SetFillColor(255, 255, 255);

          $sql11 = "SELECT DISTINCT(clasificacion) FROM inventario WHERE fecha_registro <= '$fechaFin' AND 
          articulo_activo = '1'";
          $query11 = mysqli_query($conexion, $sql11);
          $iClasi = 67;
          $totClasi = 0;
          $totByClasi = 0;
          while($fetch11 = mysqli_fetch_assoc($query11)){
            $clasi = $fetch11['clasificacion'];
            $sql12 = "SELECT COUNT(*) AS numClasi FROM inventario WHERE clasificacion = '$clasi' AND fecha_registro 
            <= '$fechaFin' AND articulo_activo = '1'";
            $query12 = mysqli_query($conexion, $sql12);
            $fetch12 = mysqli_fetch_assoc($query12);
            $numClasi = $fetch12['numClasi'];
            $sql13 = "SELECT COUNT(b.clasificacion) AS numByClasi FROM auditoria_objeto a INNER JOIN inventario b 
            ON a.inventario_id = b.id_inventario WHERE a.auditoria_id = '$auditoria' AND b.clasificacion = '$clasi'";
            $query13 = mysqli_query($conexion, $sql13);
            $fetch13 = mysqli_fetch_assoc($query13);
            $numByClasi = $fetch13['numByClasi'];

            $pdf->MultiCell(25, 7, $clasi, 1, 'L', 1, 1, 220, $iClasi, true, 0, false, true, 7, 'M', true);
            $pdf->MultiCell(15, 7, $numClasi, 1, 'C', 1, 1, 245, $iClasi, true, 0, false, true, 7, 'M');
            $pdf->MultiCell(20, 7, $numByClasi, 1, 'C', 1, 1, 260, $iClasi, true, 0, false, true, 7, 'M');
            $iClasi = $iClasi+7;
            $totClasi = $totClasi+$numClasi;
            $totByClasi = $totByClasi+$numByClasi;
          }//fin del while clasificaciones
          $pdf->MultiCell(25, 7, 'Total', 1, 'R', 1, 1, 220, $iClasi, true, 0, false, true, 7, 'M');
          $pdf->MultiCell(15, 7, $totClasi, 1, 'C', 1, 1, 245, $iClasi, true, 0, false, true, 7, 'M');
          $pdf->MultiCell(20, 7, $totByClasi, 1, 'C', 1, 1, 260, $iClasi, true, 0, false, true, 7, 'M');

          $palabraClave = "";
          if($tipoRev == "Conciliacion"){
            $palabraClave = "conciliaron";
          }else{
            $palabraClave = "auditaron";
          }
          $textoFinal = "En total se ".$palabraClave." ".$totArti." articulos de un total de ".$totalBySuc.
          " distribuidos en las diferentes sucursales, de igual manera se dieron de baja ".$totBajas." articulos en el".
          " periodo de realizacion de la misma.";

          $y3 = $iEst + 25;
          $y4 = $y3 +30;
          $y5 = $y4-1;
          $pdf->SetFont('times', '', 10);
          $pdf->MultiCell(285, 15, $textoFinal, 0, 'C', 1, 1, 5, $y3, true, 0, false, true, 15, 'M');
          $pdf->MultiCell(80, 0, '', 1, 'C', 1, 1, 110, $y5, true, 0, false, true, 1, 'M');
          $pdf->SetFont('times', '', 13);
          $pdf->MultiCell(80, 15, $nombrePersona, 0, 'C', 1, 1, 110, $y4, true, 0, false, true, 15, 'T');
          $pdf->SetFont('times', '', 9);
          

        }
        




        $pdf->Ln(4);
        //$pdf->Image('docs/qrImg/temp_inv_8.png', '', '', 50, 50, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
        // set color for background
        $pdf->SetFillColor(255, 255, 255);
        $pdf->MultiCell(285, 10, 'Formato generado automaticamente a través de SysCoop.', 0, 'C', 1, 1, '5', '175', true);
        //$pdf->Ln(4);

        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

        // set color for background
        //$pdf->SetFillColor(215, 235, 255);

        // set some text for example
        // print a blox of text using multicell()
        //$pdf->MultiCell(80, 5, $txt."\n", 1, 'J', 1, 1, '' ,'', true);

        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

        // AUTO-FITTING

        // set color for background
        //$pdf->SetFillColor(255, 235, 235);

        // Fit text on cell by reducing font size
        //$pdf->MultiCell(55, 60, '[FIT CELL] '.$txt."\n", 1, 'J', 1, 1, 125, 145, true, 0, false, true, 60, 'M', true);

        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // CUSTOM PADDING
        // set color for background
        //$pdf->SetFillColor(255, 255, 215);
        // set font
        //$pdf->SetFont('helvetica', '', 8);
        // set cell padding
        //$pdf->setCellPaddings(2, 4, 6, 8);
        //$txt = "CUSTOM PADDING:\nLeft=2, Top=4, Right=6, Bottom=8\nLorem ipsum dolor sit amet, consectetur adipiscing elit. In sed imperdiet lectus. Phasellus quis velit velit, non condimentum quam. Sed neque urna, ultrices ac volutpat vel, laoreet vitae augue.\n";
        //$pdf->MultiCell(55, 5, $txt, 1, 'J', 1, 2, 125, 210, true);
        // move pointer to last page
        $pdf->lastPage();
        //Close and output PDF document
        $date = date('Ymd-Gis');
        $nombrePDF = "rev_".$date.".pdf";
        $rutaCompleta = "docs/pdfQr/".$nombrePDF.".pdf";
        if(!$pdf->Output($_SERVER['DOCUMENT_ROOT'].$rutaCompleta, 'FI')){
          echo "OperationSuccess|".$rutaCompleta;
        }else{
          echo "DataError|Ocurrio un error al generar el QR, contacte a sistemas";
        }
        //$pdf->Output('docs/pdfQr/QrMasivo.pdf', 'F');     

    }else{
      //no existe la auditoria
    }
  }else{
    header('location: control.php');
    echo "<script>window.location='index.php'</script>";
  }

}

?>