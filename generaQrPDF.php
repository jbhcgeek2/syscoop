<?php 
// create new PDF document
require_once('TCPDF-main/tcpdf_import.php');
class newFicha extends TCPDF{
    public function Header(){ 

    }
  }//fin de la class
  $pageLayout = array('250', '390');
  $pageLayout = new newFicha('p', 'mm', $pageLayout, true, 'UTF-8', false);
  $pdf = new newFicha(PDF_PAGE_ORIENTATION,PDF_UNIT,PDF_PAGE_FORMAT,true,'UTF-8',false);


// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('QR Masivos');
$pdf->SetSubject('Generacion de QR Masivi');
$pdf->SetKeywords('Qr, PDF, Masivo, Caja Tepic');

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
$pdf->SetFont('times', '', 20);

// add a page
$pdf->AddPage();
// set cell padding
$pdf->setCellPaddings(3, 3, 3, 3);
$pdf->SetFillColor(255, 255, 255);
$pdf->MultiCell(100, 5, 'Etiquetado masivo por area', 0, 'C', 1, 1, '60', '20', true);
// set cell margins
$pdf->SetFont('times', '', 10);
$pdf->setCellMargins(1, 1, 10, 1);
// set color for background
$pdf->SetFillColor(255, 255, 255);
// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
include('phpqrcode/phpqrcode.php');
// set some text for example
//$txt = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
include('includes/_con.php');
//consultamos los articulos

$sql = "SELECT * FROM inventario a INNER JOIN empleados b ON a.resguardo_empleado = b.id_empleado
WHERE a.sucursal_resguardo = 'Matriz' AND a.clasificacion = 'Equipo' AND a.lugar_resguardo = 'En mi casa'";
$query = mysqli_query($conexion, $sql);
$i = 1;
$x = 28;
$x2 = 68;
$y = 55;
$y2 = 55;
while($fetch = mysqli_fetch_assoc($query)){
  $codigo = $fetch['codigo'];
  $idObjeto = $fetch['id_inventario'];
  $nombreEmpleado = $fetch['paterno']." ".$fetch['materno']." ".$fetch['nombre'];
  $nombreObjeto = $fetch['nombre_objeto']." Modelo: ".$fetch['modelo_objeto'];

  $rutaBarcode = "php-barcode-master/barcode.php?text=".$codigo."&size=50&orientation=horizontal&codetype=Code39&print=true&sizefactor=2";

  $rutaDirQr = "docs/qrImg/";
  $fileQr = $rutaDirQr."temp_inv_".$idObjeto.".png";
  $tamanioMatriz = 5;
  $errorCorectionLevel = 'L';
  $content = "https://".$serverId."/ver-obj-ind.php?obj=".$idObjeto;
  //$fileName = $PNG_TEMP_DIR.'test'.md5($)
  QRcode::png($content,$fileQr,"L",$tamanioMatriz,4);

  $img = imagecreatefrompng($fileQr);

  $txt = $codigo;
  $fontFile = "includes/arial.ttf";
  $fontSize = 10;
  $fontColor = imagecolorallocate($img, 0, 0, 0);
  $posX = 60;
  $posY = 183;
  $angle = 0;
  $iWidth = imagesx($img);
  $tSize = imagettfbbox($fontSize, $angle, $fontFile, $txt);
  $tWidth = max([$tSize[2], $tSize[4]]) - min([$tSize[0], $tSize[6]]);
  $tHeight = max([$tSize[5], $tSize[7]]) - min([$tSize[1], $tSize[3]]);
  $centerX = ceil(($iWidth - $tWidth) / 2);
  $centerX = $centerX<0 ? 0 : $centerX;
  imagettftext($img, $fontSize, $angle, $centerX, $posY, $fontColor, $fontFile, $txt);
  $quality = 100;
  $des2 = $rutaDirQr."temp_inv_".$codigo."_.jpg";
  imagejpeg($img, $des2, $quality);
  
  if(($i % 2) == 0){
    //es par
    $pdf->Image($des2, $x, $y,35, 35, 'jpg', '', '', false, 300, '', false, false, 1, 'L', false, false);
    $pdf->Image($des2, $x2, $y2, 15, 15, 'jpg', '', '', false, 300, '', false, false, 0, 'L', false, false);
    $pdf->MultiCell(80, 60, $nombreEmpleado."\n".$nombreObjeto."\n".$codigo, 1, 'C', 1, 1, '', '', true);
    //$x = $x+93;
    $x = 28;//reseteamos el eje X
    $x2 = 68;//reseteamos el eje x2
    //reasignamos el nuevo valor del eje Y
    $y = $y+62;
    $y2 = $y;
  }else{
    //es impar
    $pdf->Image($des2, $x, $y, 35, 35, 'jpg', '', '', false, 300, '', false, false, 1, 'L', false, false);
    $pdf->Image($des2, $x2, $y2, 15, 15, 'jpg', '', '', false, 300, '', false, false, 0, 'L', false, false);
    $pdf->MultiCell(80, 60, $nombreEmpleado."holi\n".$nombreObjeto."\n".$codigo, 1, 'C', 1, 0, '', '', true);
    //$x = 28;
    $x = $x+93;
    $x2 = $x2+93;
  }

  $i++;
}//fin del while
// Multicell test
//$pdf->MultiCell(80, 5, '[LEFT] '.$txt, 1, 'L', 1, 1, '', '', true);
//$pdf->MultiCell(80, 5, '[LEFT] '.$txt, 1, 'L', 1, 1, '', '', true);
//$pdf->MultiCell(80, 5, '[LEFT] '.$txt, 1, 'L', 1, 1, '', '', true);



$pdf->Ln(4);
//$pdf->Image('docs/qrImg/temp_inv_8.png', '', '', 50, 50, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
// set color for background
$pdf->SetFillColor(220, 255, 220);

// Vertical alignment
//$pdf->MultiCell(55, 40, '[VERTICAL ALIGNMENT - TOP] '.$txt, 1, 'J', 1, 0, '', '', true, 0, false, true, 40, 'T');
//$pdf->MultiCell(55, 40, '[VERTICAL ALIGNMENT - MIDDLE] '.$txt, 1, 'J', 1, 0, '', '', true, 0, false, true, 40, 'M');
//$pdf->MultiCell(55, 40, '[VERTICAL ALIGNMENT - BOTTOM] '.$txt, 1, 'J', 1, 1, '', '', true, 0, false, true, 40, 'B');

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
$nombrePDF = "masivo_".$date.".pdf";
$rutaCompleta = "docs/pdfQr/".$nombrePDF;
if(!$pdf->Output($_SERVER['DOCUMENT_ROOT'].'docs/pdfQr/'.$nombrePDF, 'F')){
  echo "OperationSuccess|".$rutaCompleta;
}else{
  echo "DataError|Ocurrio un error al generar el QR, contacte a sistemas";
}
//$pdf->Output('docs/pdfQr/QrMasivo.pdf', 'F');

?>