<?php
// error_reporting(E_ALL); ini_set('display_errors', '1'); 
session_start();
require_once('db.php');
require_once('correos.php');

//primer nivel de validacion (SESION DE USUARIO)
if(isset($_SESSION['individuo'])){
    //se encontro una sesion, verificamos si esta la conexion
    if($conexion){
        if(!empty($_POST['dataOk'])){

            $fecha = date('M-d-y');

            $campos = ['check1','check2','check3','check4','check5','check6','check7','check8',
            'check9','check10','check11','check12','check13','check14','check15','check16','check17','check18',
            'check19','check20','check21','check22','check23','check24','check25','check26','check27',
            'check28','check29','check30','check31','check32','check33','check34','check35',
            'check36','check37','check38','check39','radio1','check40','commentscheck40','radio2',
            'check41','commentscheck41','radio3','check42','commentscheck42','radio4','check43','commentscheck43',
            'radio5','check44','commentscheck44','check45','commentscheck45'];
            
            function check($a,$b){
                if($a == 1){
                    //es un campo marcado
                    if($b == 'check'){
                        return '../../img/checked.png';
                    }else{
                        //es un radio
                    }
                }else{
                    if($b == 'check'){
                        return '../../img/unChecked.png';
                    }else{
                        //es un radio
                    }
                }
            }//fin function check

            function radioCheck($a,$b){
                //$a = valor del radio
                //$b = posicion del radio 1 o 2
                
                switch ($a) {
                    case 'radio1':
                    case 'radio3':
                    case 'radio5':
                    case 'radio7':
                    case 'radio9':
                        if($b == 1){
                            return '../../img/checked.png';
                        }else{
                            return '../../img/unChecked.png';
                        }
                        break;
                    case 'radio2':
                    case 'radio4':
                    case 'radio6':
                    case 'radio8':
                    case 'radio10':
                        if($b == 2){
                            return '../../img/checked.png';
                        }else{
                            return '../../img/unChecked.png';
                        }
                        break;
                }//fin del switch
            }//funcion radioCheck

            require_once('../../tcpdf/tcpdf_import.php');

            class documento extends TCPDF{
                public function header(){}
            }//fin del class documento

            $pageLayout = array('150', '190');
            $pageLayout = new documento('p', 'mm', $pageLayout, true, 'UTF-8', false);
            $doc = new documento(PDF_PAGE_ORIENTATION,PDF_UNIT,PDF_PAGE_FORMAT,true,'UTF-8',false);
            $doc->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $doc->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

            $doc->setPrintFooter(true);
            $doc->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $doc->SetHeaderMargin(0);
            $doc->SetFooterMargin(0);

            $doc->SetMargins(5, 5, 5);

            $doc->SetAutoPageBreak(TRUE, 0);
            $doc->setImageScale(PDF_IMAGE_SCALE_RATIO);
            if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                require_once(dirname(__FILE__).'/lang/eng.php');
                $doc->setLanguageArray($l);
            }
            // Comenzamos a crear el contenbido del PDF, definimos la letra y tamanho
            $doc->SetFont('dejavusans', '', 9);
            //creamos una pagina nueva
            $doc->AddPage();
            $doc->Ln(4);
            // set cell padding
            $doc->setCellPaddings(1, 1, 1, 1);
            // set cell margins
            $doc->setCellMargins(1, 1, 1, 1);
            $doc->Ln(1);
            // $imgURL = 'https://4.bp.blogspot.com/-ROkFeOf1NFA/WZk6sfmVyzI/AAAAAAAAEpk/eMtf5HsSdTYt_RMVHGatO5TGay3px6l9QCLcBGAs/s1600/escudo%2Buan.png';
            // Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
            $imgURL = '../../img/Logo.png';
            $imgs = ['../../img/checked.png','../../img/unChecked.png','',''];
            
            
            // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
            $doc->SetFont('dejavusans', 'B', 10);
            // GUIA
            $doc->SetFillColor(255, 255, 255);//BLANCO
            $doc->MultiCell(185, 10, ' ', 1, 'C', 1, 0, '10', '', true);
            // IMAGEN RECUADRO
            
            $doc->MultiCell(60, 10, ' ', 1, 'C', 1, 0, '10', '', true);
            $doc->setCellPaddings(1, 1, 1, 0);
            $doc->SetFont('dejavusans', 'B', 9);
            $doc->MultiCell(85, 6, 'IMI Work Order Roter for Optical Tower', 1, 'C', 1, 0, '70', '', true);
            $doc->SetFont('dejavusans', 'B', 5);
            $doc->MultiCell(40, 6, 'Page 1 of 31', 1, 'C', 1, 0, '155', '', true);
            $doc->MultiCell(50, 4, 'Numero de Documento: FMI-IMI-WOR-002', 1, 'C', 1, 0, '70', '16', true);
            $doc->MultiCell(35, 4, 'Rev. 7', 1, 'C', 1, 0, '120', '16', true);
            $doc->MultiCell(40, 4, 'Fecha de Liberacion: '.$fecha, 1, 'C', 1, 0, '155', '16', true);

            $doc->SetXY(18,12);
            $doc->Image($imgURL,'','',50,8,'', '', 'T', false, 300, '', false, false, 1, false, false, false);

            $doc->setCellPaddings(1, 1, 1, 1);
            $doc->SetFont('dejavusans', 'B', 9);
            $doc->MultiCell(185, 5, 'Numero de Lote / Orden de Trabajo _________________________________', 0, 'L', 0, 0, '10', '25', true);
            $doc->SetFont('dejavusans', 'B', 8);
            $doc->MultiCell(62, 5, 'Numero de Parte: _________________', 0, 'L', 0, 0, '10', '31', true);
            $doc->MultiCell(62, 5, 'FM-IMI-CPS-MP-02792 Rev: ___________', 0, 'L', 0, 0, '72', '31', true);
            $doc->MultiCell(61, 5, 'Producto/Subensamble: ____________', 0, 'L', 0, 0, '134', '31', true);

            $doc->SetFont('dejavusans', 'B', 10);
            $doc->MultiCell(37, 5, '1: DHR Checklist', 0, 'L', 0, 0, '10', '37', true);
            $doc->SetFont('dejavusans', 'B', 6);
            $doc->setCellPaddings(1, 2, 1, 1);
            $doc->MultiCell(148, 6, '(Documents 1-6 and 9 are from FM-IMI-WOR-002)', 0, 'L', 0, 0, '47', '37', true);
            

            $doc->SetFont('dejavusans', 'B', 8);
            $doc->setCellPaddings(1, 1, 1, 1);
            $doc->SetFillColor(214, 214, 214);//GRIS
            // $doc->MultiCell(185, 10, '', 1, 'C', 0, 0, '10', '', true);
            $doc->MultiCell(25, 10, 'Document Available?', 1, 'C', 0, 0, '10', '45', true);
            $doc->MultiCell(68, 10, 'Documents', 1, 'C', 0, 0, '35', '45', true);
            $doc->MultiCell(25, 10, 'Document Available?', 1, 'C', 0, 0, '103', '45', true);
            $doc->MultiCell(67, 10, 'Documents', 1, 'C', 0, 0, '128', '45', true);
            
            $doc->SetFillColor(255, 255, 255);//BLANCO
            $doc->MultiCell(25, 5, '', 1, 'C', 1, 0, '10', '55', true);
            
            $doc->SetXY(22,57);
            $doc->Image(check($_POST['check1'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            // $doc->MultiCell(3, 3, '', 1, 'C', 1, 0, '21', '56', true);//CHECK
            $doc->MultiCell(68, 5, '1: DHR Checklist', 1, 'L', 1, 0, '35', '55', true);
            $doc->SetFillColor(214, 214, 214);//GRIS
            $doc->MultiCell(25, 5, '', 1, 'C', 1, 0, '103', '55', true);
            $doc->MultiCell(67, 5, '', 1, 'C', 1, 0, '128', '55', true);

            // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
            
            $doc->SetFillColor(255, 255, 255);//BLANCO
            $doc->MultiCell(25, 5, '', 1, 'C', 1, 0, '10', '60', true);
            $doc->SetXY(22,62);
            $doc->Image(check($_POST['check2'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            // $doc->MultiCell(3, 3, '', 1, 'C', 1, 0, '21', '61', true);//CHECK
            $doc->MultiCell(68, 5, '2: Lot Release', 1, 'L', 1, 0, '35', '60', true);
            $doc->SetFillColor(214, 214, 214);//GRIS
            $doc->MultiCell(25, 5, '', 1, 'C', 1, 0, '103', '60', true);
            $doc->MultiCell(67, 5, '', 1, 'C', 1, 0, '128', '60', true);

            $doc->SetFillColor(255, 255, 255);//BLANCO
            $doc->MultiCell(25, 5, '', 1, 'C', 1, 0, '10', '65', true);
            $doc->SetXY(22,67);
            $doc->Image(check($_POST['check3'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(68, 5, '3: Line Clearance', 1, 'L', 1, 0, '35', '65', true);
            $doc->SetFillColor(214, 214, 214);//GRIS
            $doc->MultiCell(25, 5, '', 1, 'C', 1, 0, '103', '65', true);
            $doc->MultiCell(67, 5, '', 1, 'C', 1, 0, '128', '65', true);

            $doc->SetFillColor(255, 255, 255);//BLANCO
            $doc->MultiCell(25, 5, '', 1, 'C', 1, 0, '10', '70', true);
            $doc->SetXY(22,72);
            $doc->Image(check($_POST['check4'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(68, 5, '4: Procedures List', 1, 'L', 1, 0, '35', '70', true);
            $doc->SetFillColor(214, 214, 214);//GRIS
            $doc->MultiCell(25, 5, '', 1, 'C', 1, 0, '103', '70', true);
            $doc->MultiCell(67, 5, '', 1, 'C', 1, 0, '128', '70', true);

            $doc->SetFillColor(255, 255, 255);//BLANCO
            $doc->MultiCell(25, 5, '', 1, 'C', 1, 0, '10', '75', true);
            $doc->SetXY(22,77);
            $doc->Image(check($_POST['check5'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(68, 5, '5: Work Order Router', 1, 'L', 1, 0, '35', '75', true);
            $doc->SetFillColor(214, 214, 214);//GRIS
            $doc->MultiCell(25, 5, '', 1, 'C', 1, 0, '103', '75', true);
            $doc->MultiCell(67, 5, '', 1, 'C', 1, 0, '128', '75', true);




            $doc->MultiCell(25, 5, '', 1, 'C', 1, 0, '10', '80', true);
            $doc->MultiCell(160, 5, '6: Inspection Sheets:', 1, 'L', 1, 0, '35', '80', true);



            $doc->SetFont('dejavusans', '', 7);
            $doc->SetFillColor(255, 255, 255);//BLANCO
            $doc->SetXY(26,87);
            $doc->Image('../../img/asterisco.png','','',2,2,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(22,87);
            $doc->Image(check($_POST['check6'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'L', 1, 0, '10', '85', true);//CHECK
            $doc->MultiCell(68, 5, 'Op 200.1 (Orientation: Adhesive Application)', 1, 'L', 1, 0, '35', '85', true);
            $doc->SetXY(115,87);
            $doc->Image(check($_POST['check13'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'C', 1, 0, '103', '85', true);//CHECK
            $doc->MultiCell(67, 5, 'Op 235B (Adhesive Application & Curing)', 1, 'L', 1, 0, '128', '85', true);

            $doc->SetXY(26,92);
            $doc->Image('../../img/asterisco.png','','',2,2,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(22,92);
            $doc->Image(check($_POST['check7'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'L', 1, 0, '10', '90', true);//ASTERISCO
            $doc->MultiCell(68, 5, 'Op 200.2 (Orientation: Adhesive Application)', 1, 'L', 1, 0, '35', '90', true);
            $doc->SetXY(115,92);
            $doc->Image(check($_POST['check14'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'C', 1, 0, '103', '90', true);//ASTERISCO
            $doc->MultiCell(67, 5, 'Op 240 (Dimensional & Dent Score)', 1, 'L', 1, 0, '128', '90', true);

            $doc->SetXY(26,97);
            $doc->Image('../../img/asterisco.png','','',2,2,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(22,97);
            $doc->Image(check($_POST['check8'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'L', 1, 0, '10', '95', true);//ASTERISCO
            $doc->MultiCell(68, 5, 'Op 220A.1 (Adhesive Application & Curing)', 1, 'L', 1, 0, '35', '95', true);
            $doc->SetXY(115,97);
            $doc->Image(check($_POST['check15'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'C', 1, 0, '103', '95', true);//ASTERISCO
            $doc->MultiCell(67, 5, 'Op 240 (Leak Test & Push Force Test)', 1, 'L', 1, 0, '128', '95', true);
            
            $doc->SetXY(26,102);
            $doc->Image('../../img/asterisco.png','','',2,2,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(22,102);
            $doc->Image(check($_POST['check9'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'L', 1, 0, '10', '100', true);//ASTERISCO
            $doc->MultiCell(68, 5, 'Op 220A.2 (Adhesive Application & Curing)', 1, 'L', 1, 0, '35', '100', true);
            $doc->SetXY(115,102);
            $doc->Image(check($_POST['check16'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'C', 1, 0, '103', '100', true);//ASTERISCO
            $doc->MultiCell(67, 5, 'Op 250 (Cosmetic Appearance & Gap Height)', 1, 'L', 1, 0, '128', '100', true);

            $doc->SetXY(26,107);
            $doc->Image('../../img/asterisco.png','','',2,2,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(22,107);
            $doc->Image(check($_POST['check10'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'L', 1, 0, '10', '105', true);//ASTERISCO
            $doc->MultiCell(68, 5, 'Op 220B.1 (Adhesive Application & Curing)', 1, 'L', 1, 0, '35', '105', true);
            $doc->SetXY(115,107);
            $doc->Image(check($_POST['check17'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'C', 1, 0, '103', '105', true);//ASTERISCO
            $doc->MultiCell(67, 5, 'Op 265 (Ahesive Application)', 1, 'L', 1, 0, '128', '105', true);

            $doc->SetXY(26,112);
            $doc->Image('../../img/asterisco.png','','',2,2,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(22,112);
            $doc->Image(check($_POST['check11'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'L', 1, 0, '10', '110', true);//ASTERISCO
            $doc->MultiCell(68, 5, 'Op 220B.2 (Adhesive Application & Curing)', 1, 'L', 1, 0, '35', '110', true);
            $doc->SetXY(119,112);
            $doc->Image('../../img/asterisco.png','','',2,2,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(115,112);
            $doc->Image(check($_POST['check18'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'C', 1, 0, '103', '110', true);//ASTERISCO
            $doc->MultiCell(67, 5, 'Op 260.1 (Ahesive Application & Curing)', 1, 'L', 1, 0, '128', '110', true);

            $doc->SetXY(22,117);
            $doc->Image(check($_POST['check12'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'L', 1, 0, '10', '115', true);//ASTERISCO
            $doc->MultiCell(68, 5, 'Op 230B (Adhesive Application & Curing)', 1, 'L', 1, 0, '35', '115', true);
            $doc->SetXY(119,117);
            $doc->Image('../../img/asterisco.png','','',2,2,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(115,117);
            $doc->Image(check($_POST['check19'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'C', 1, 0, '103', '115', true);//ASTERISCO
            $doc->MultiCell(67, 5, 'Op 260.2 (Ahesive Application & Curing)', 1, 'L', 1, 0, '128', '115', true);



            $doc->SetFillColor(214, 214, 214);//GRIS
            $doc->MultiCell(25, 5, '', 1, 'L', 1, 0, '10', '120', true);//ASTERISCO
            $doc->MultiCell(160, 5, '7: Equipment Parameter Sheet:', 1, 'L', 1, 0, '35', '120', true);
            $doc->SetFillColor(255, 255, 255);//BLANCO
            //185 --->

            $doc->SetXY(22,127);
            $doc->Image(check($_POST['check20'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'L', 1, 0, '10', '125', true);//ASTERISCO
            $doc->MultiCell(25, 5, 'Op 210', 1, 'L', 1, 0, '35', '125', true);
            $doc->MultiCell(43, 5, 'FM-IMI-EPS-008', 1, 'L', 1, 0, '60', '125', true);
            $doc->SetXY(115,127);
            $doc->Image(check($_POST['check29'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'C', 1, 0, '103', '125', true);//ASTERISCO
            $doc->MultiCell(25, 5, 'Op 230B', 1, 'L', 1, 0, '128', '125', true);
            $doc->MultiCell(42, 5, 'FM-IMI-EPS-002', 1, 'L', 1, 0, '153', '125', true);

            $doc->SetXY(26,132);
            $doc->Image('../../img/asterisco.png','','',2,2,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(22,132);
            $doc->Image(check($_POST['check21'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'L', 1, 0, '10', '130', true);//ASTERISCO
            $doc->MultiCell(25, 5, 'Op 200.1', 1, 'L', 1, 0, '35', '130', true);
            $doc->MultiCell(43, 5, 'FM-IMI-EPS-002', 1, 'L', 1, 0, '60', '130', true);
            $doc->SetXY(115,132);
            $doc->Image(check($_POST['check30'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'C', 1, 0, '103', '130', true);//ASTERISCO
            $doc->MultiCell(25, 5, 'Op 235B', 1, 'L', 1, 0, '128', '130', true);
            $doc->MultiCell(42, 5, 'FM-IMI-EPS-002', 1, 'L', 1, 0, '153', '130', true);

            $doc->SetXY(26,137);
            $doc->Image('../../img/asterisco.png','','',2,2,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(22,137);
            $doc->Image(check($_POST['check22'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'L', 1, 0, '10', '135', true);//ASTERISCO
            $doc->MultiCell(25, 5, 'Op 200.2', 1, 'L', 1, 0, '35', '135', true);
            $doc->MultiCell(43, 5, 'FM-IMI-EPS-002', 1, 'L', 1, 0, '60', '135', true);
            $doc->SetXY(115,137);
            $doc->Image(check($_POST['check31'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'C', 1, 0, '103', '135', true);//ASTERISCO
            $doc->MultiCell(25, 5, 'Op 240', 1, 'L', 1, 0, '128', '135', true);
            $doc->MultiCell(42, 5, 'FM-IMI-EPS-007', 1, 'L', 1, 0, '153', '135', true);

            $doc->SetXY(26,142);
            $doc->Image('../../img/asterisco.png','','',2,2,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(22,142);
            $doc->Image(check($_POST['check23'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'L', 1, 0, '10', '140', true);//ASTERISCO
            $doc->MultiCell(25, 5, 'Op 220A.1', 1, 'L', 1, 0, '35', '140', true);
            $doc->MultiCell(43, 5, 'FM-IMI-EPS-002', 1, 'L', 1, 0, '60', '140', true);
            $doc->SetXY(115,142);
            $doc->Image(check($_POST['check32'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'C', 1, 0, '103', '140', true);//ASTERISCO
            $doc->MultiCell(25, 5, 'Op 250', 1, 'L', 1, 0, '128', '140', true);
            $doc->MultiCell(42, 5, 'FM-IMI-EPS-006', 1, 'L', 1, 0, '153', '140', true);

            $doc->SetXY(26,147);
            $doc->Image('../../img/asterisco.png','','',2,2,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(22,147);
            $doc->Image(check($_POST['check24'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'L', 1, 0, '10', '145', true);//ASTERISCO
            $doc->MultiCell(25, 5, 'Op 220A.2', 1, 'L', 1, 0, '35', '145', true);
            $doc->MultiCell(43, 5, 'FM-IMI-EPS-002', 1, 'L', 1, 0, '60', '145', true);
            $doc->SetXY(115,147);
            $doc->Image(check($_POST['check33'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'C', 1, 0, '103', '145', true);//ASTERISCO
            $doc->MultiCell(25, 5, 'Op 265', 1, 'L', 1, 0, '128', '145', true);
            $doc->MultiCell(42, 5, 'FM-IMI-EPS-001', 1, 'L', 1, 0, '153', '145', true);

            $doc->SetXY(26,152);
            $doc->Image('../../img/asterisco.png','','',2,2,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(22,152);
            $doc->Image(check($_POST['check25'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'L', 1, 0, '10', '150', true);//ASTERISCO
            $doc->MultiCell(25, 5, 'Op 220B.1', 1, 'L', 1, 0, '35', '150', true);
            $doc->MultiCell(43, 5, 'FM-IMI-EPS-002', 1, 'L', 1, 0, '60', '150', true);
            $doc->SetXY(119,152);
            $doc->Image('../../img/asterisco.png','','',2,2,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(115,152);
            $doc->Image(check($_POST['check34'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'C', 1, 0, '103', '150', true);//ASTERISCO
            $doc->MultiCell(25, 5, 'Op 260.1', 1, 'L', 1, 0, '128', '150', true);
            $doc->MultiCell(42, 5, 'FM-IMI-EPS-002', 1, 'L', 1, 0, '153', '150', true);

            $doc->SetXY(26,157);
            $doc->Image('../../img/asterisco.png','','',2,2,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(22,157);
            $doc->Image(check($_POST['check26'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'L', 1, 0, '10', '155', true);//ASTERISCO
            $doc->MultiCell(25, 5, 'Op 220B.2', 1, 'L', 1, 0, '35', '155', true);
            $doc->MultiCell(43, 5, 'FM-IMI-EPS-002', 1, 'L', 1, 0, '60', '155', true);
            $doc->SetXY(119,157);
            $doc->Image('../../img/asterisco.png','','',2,2,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(115,157);
            $doc->Image(check($_POST['check35'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'C', 1, 0, '103', '155', true);//ASTERISCO
            $doc->MultiCell(25, 5, 'Op 260.2', 1, 'L', 1, 0, '128', '155', true);
            $doc->MultiCell(42, 5, 'FM-IMI-EPS-002', 1, 'L', 1, 0, '153', '155', true);

            $doc->SetXY(22,162);
            $doc->Image(check($_POST['check27'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'L', 1, 0, '10', '160', true);//ASTERISCO
            $doc->MultiCell(25, 5, 'Op 225', 1, 'L', 1, 0, '35', '160', true);
            $doc->MultiCell(43, 5, 'FM-IMI-EPS-008', 1, 'L', 1, 0, '60', '160', true);
            $doc->SetXY(115,162);
            $doc->Image(check($_POST['check36'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'C', 1, 0, '103', '160', true);//ASTERISCO
            $doc->MultiCell(67, 5, 'EPS-013 (Att. 2)', 1, 'L', 1, 0, '128', '160', true);

            $doc->SetXY(22,167);
            $doc->Image(check($_POST['check28'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'L', 1, 0, '10', '165', true);//ASTERISCO
            $doc->MultiCell(25, 5, 'Op 230A', 1, 'L', 1, 0, '35', '165', true);
            $doc->MultiCell(43, 5, 'FM-IMI-EPS-009', 1, 'L', 1, 0, '60', '165', true);
            $doc->SetFillColor(214, 214, 214);//GRIS
            $doc->MultiCell(25, 5, '', 1, 'C', 1, 0, '103', '165', true);//ASTERISCO
            $doc->MultiCell(67, 5, '', 1, 'L', 1, 0, '128', '165', true);



            $doc->SetFillColor(255, 255, 255);//BLANCO
            $doc->SetXY(22,174);
            $doc->Image(check($_POST['check37'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 10, '', 1, 'L', 1, 0, '10', '170', true);//ASTERISCO
            $doc->SetFont('dejavusans', 'B', 7);
            $doc->MultiCell(160, 5, '8: Materials', 0, 'L', 1, 0, '35', '170', true);
            $doc->MultiCell(160, 10, '', 1, 'L', 1, 0, '35', '170', true);
            $doc->SetFont('dejavusans', '', 7);
            $doc->MultiCell(160, 5, 'List for entire build (FM-DCM-2006-03 (includes P/N, Rev, Qty Issues and Lot#, Qty Required, UOM))', 0, 'L', 0, 0, '35', '175', true);
            
            $doc->SetXY(22,182);
            $doc->Image(check($_POST['check38'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'L', 1, 0, '10', '180', true);//ASTERISCO
            $doc->SetFont('dejavusans', 'B', 7);
            $doc->MultiCell(160, 5, '9: Consumibles List', 1, 'L', 1, 0, '35', '180', true);

            $doc->SetXY(22,187);
            $doc->Image(check($_POST['check39'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 5, '', 1, 'L', 1, 0, '10', '185', true);//ASTERISCO
            $doc->SetFont('dejavusans', '', 7);
            $doc->MultiCell(60, 5, 'used during WO build (FM-IMI-MFL-001)', 0, 'L', 1, 0, '61', '185', true);
            $doc->SetFont('dejavusans', 'B', 7);
            $doc->MultiCell(160, 5, '10: Fixtures List - ', 1, 'L', 1, 0, '35', '185', true);
            


            $doc->SetXY(22,192);
            $doc->Image(radioCheck($_POST['radio1'],1),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(26,192);
            $doc->Image('../../img/yes.png','','',5,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(22,197);
            $doc->Image(radioCheck($_POST['radio1'],2),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(26,197);
            $doc->Image('../../img/na.png','','',5,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 10, '', 1, 'L', 1, 0, '10', '190', true);//ASTERISCO
            $doc->MultiCell(68, 10, '', 1, 'L', 1, 0, '35', '190', true);
            $doc->MultiCell(68, 5, '11: CAPA No.', 0, 'L', 0, 0, '35', '190', true);
            $doc->SetFont('dejavusans', '', 7);
            $doc->MultiCell(68, 5, '(list all applicable CAPAs)', 0, 'L', 0, 0, '35', '195', true);
            $doc->SetXY(109,192);
            $doc->Image('../../img/na.png','','',5,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(105,192);
            $doc->Image(check($_POST['check40'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(15, 5, '', 0, 'C', 1, 0, '103', '190', true);//NA COMMENTSS
            $doc->setCellPaddings(0, 0, 1, 0);
            $doc->SetFont('dejavusans', '', 7);
            $doc->MultiCell(77, 10, $_POST['commentscheck40'], 1, 'L', 1, 0, '118', '190', true);//NA COMMENTSS PUNTO 11
            $doc->setCellPaddings(1, 1, 1, 1);
            $doc->MultiCell(92, 10, '', 1, 'C', 1, 0, '103', '190', true);


            $doc->SetXY(22,202);
            $doc->Image(radioCheck($_POST['radio2'],1),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(26,202);
            $doc->Image('../../img/yes.png','','',5,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(22,207);
            $doc->Image(radioCheck($_POST['radio2'],2),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(26,207);
            $doc->Image('../../img/na.png','','',5,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 10, '', 1, 'L', 1, 0, '10', '200', true);//ASTERISCO
            $doc->MultiCell(68, 10, '', 1, 'L', 1, 0, '35', '200', true);
            $doc->SetFont('dejavusans', 'B', 7);
            $doc->MultiCell(68, 5, '12: Quality Alert No.', 0, 'L', 0, 0, '35', '200', true);
            $doc->SetFont('dejavusans', '', 7);
            $doc->MultiCell(68, 5, '(list all applicable Quality Alerts)', 0, 'L', 0, 0, '35', '205', true);
            $doc->SetXY(109,202);
            $doc->Image('../../img/na.png','','',5,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(105,202);
            $doc->Image(check($_POST['check41'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(15, 5, '', 0, 'C', 1, 0, '103', '200', true);//NA COMMENTSS
            $doc->setCellPaddings(0, 0, 1, 0);
            $doc->SetFont('dejavusans', '', 7);
            $doc->MultiCell(77, 10, $_POST['commentscheck41'], 1, 'L', 1, 0, '118', '200', true);//NA COMMENTSS PUNTO 12
            $doc->setCellPaddings(1, 1, 1, 1);
            $doc->MultiCell(92, 10, '', 1, 'C', 1, 0, '103', '200', true);


            $doc->SetXY(22,212);
            $doc->Image(radioCheck($_POST['radio3'],1),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(26,212);
            $doc->Image('../../img/yes.png','','',5,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(22,217);
            $doc->Image(radioCheck($_POST['radio3'],2),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(26,217);
            $doc->Image('../../img/na.png','','',5,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(25, 10, '', 1, 'L', 1, 0, '10', '210', true);//ASTERISCO
            $doc->MultiCell(68, 10, '', 1, 'L', 1, 0, '35', '210', true);
            $doc->SetFont('dejavusans', 'B', 7);
            $doc->MultiCell(68, 5, '13: Deviation(s) No.', 0, 'L', 0, 0, '35', '210', true);
            $doc->SetFont('dejavusans', '', 7);
            $doc->MultiCell(68, 5, '(list all applicable Deviations)', 0, 'L', 0, 0, '35', '215', true);
            $doc->SetXY(109,212);
            $doc->Image('../../img/na.png','','',5,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(105,212);
            $doc->Image(check($_POST['check42'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(15, 5, '', 0, 'C', 1, 0, '103', '210', true);//NA COMMENTSS
            $doc->setCellPaddings(0, 0, 1, 0);
            $doc->SetFont('dejavusans', '', 7);
            $doc->MultiCell(77, 10, $_POST['commentscheck42'], 1, 'L', 1, 0, '118', '210', true);//NA COMMENTSS PUNTO 13
            $doc->setCellPaddings(1, 1, 1, 1);
            $doc->MultiCell(92, 10, '', 1, 'C', 1, 0, '103', '210', true);

            
            
            $doc->SetXY(22,222);
            $doc->Image(radioCheck($_POST['radio4'],1),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(26,222);
            $doc->Image('../../img/yes.png','','',5,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(22,227);
            $doc->Image(radioCheck($_POST['radio4'],2),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(26,227);
            $doc->Image('../../img/na.png','','',5,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);$doc->MultiCell(25, 10, '', 1, 'L', 1, 0, '10', '220', true);//ASTERISCO
            $doc->MultiCell(68, 10, '', 1, 'L', 1, 0, '35', '220', true);
            $doc->SetFont('dejavusans', 'B', 7);
            $doc->MultiCell(68, 5, '14: NCMR(s) No.', 0, 'L', 0, 0, '35', '220', true);
            $doc->SetFont('dejavusans', '', 7);
            $doc->MultiCell(68, 5, '(list all applicable NCMRs)', 0, 'L', 0, 0, '35', '225', true);
            $doc->SetXY(109,222);
            $doc->Image('../../img/na.png','','',5,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(105,222);
            $doc->Image(check($_POST['check43'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(15, 5, '', 0, 'C', 1, 0, '103', '220', true);//NA COMMENTSS
            $doc->setCellPaddings(0, 0, 1, 0);
            $doc->SetFont('dejavusans', '', 7);
            $doc->MultiCell(77, 10, $_POST['commentscheck43'], 1, 'L', 1, 0, '118', '220', true);//NA COMMENTSS PUNTO 14
            $doc->setCellPaddings(1, 1, 1, 1);
            $doc->MultiCell(92, 10, '', 1, 'C', 1, 0, '103', '220', true);


            $doc->SetXY(22,232);
            $doc->Image(radioCheck($_POST['radio5'],1),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(26,232);
            $doc->Image('../../img/yes.png','','',5,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(22,237);
            $doc->Image(radioCheck($_POST['radio5'],2),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(26,237);
            $doc->Image('../../img/na.png','','',5,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);$doc->MultiCell(25, 10, '', 1, 'L', 1, 0, '10', '220', true);//ASTERISCO
            $doc->MultiCell(25, 10, '', 1, 'L', 1, 0, '10', '230', true);//ASTERISCO
            $doc->SetFont('dejavusans', 'B', 7);
            $doc->MultiCell(68, 10, '15: Rework Documents', 1, 'L', 0, 0, '35', '230', true);
            $doc->SetXY(109,232);
            $doc->Image('../../img/na.png','','',5,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(105,232);
            $doc->Image(check($_POST['check44'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->MultiCell(15, 5, '', 0, 'C', 1, 0, '103', '230', true);//NA COMMENTSS
            $doc->setCellPaddings(0, 0, 1, 0);
            $doc->SetFont('dejavusans', '', 7);
            $doc->MultiCell(77, 10, $_POST['commentscheck44'], 1, 'L', 1, 0, '118', '230', true);//NA COMMENTSS PUNTO 15
            $doc->setCellPaddings(1, 1, 1, 1);
            $doc->MultiCell(92, 10, '', 1, 'C', 1, 0, '103', '230', true);


            // $doc->MultiCell(185, 10, '', 1, 'L', 1, 0, '10', '235', true);//ASTERISCO
            $doc->SetFont('dejavusans', 'B', 7);
            $doc->MultiCell(185, 10, 'Comments:', 1, 'L', 0, 0, '10', '240', true);
            $doc->SetXY(34,242);
            $doc->Image('../../img/na.png','','',5,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetXY(30,242);
            $doc->Image(check($_POST['check45'],'check'),'','',3,3,'', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $doc->SetFont('dejavusans', '', 7);
            $doc->MultiCell(155, 10, $_POST['commentscheck45'], 0, 'L', 0, 0, '40', '240', true);//COMMENTS PUNTO FIONAL PRIMER HOJA

            $doc->SetFont('dejavusans', 'B', 7);
            $doc->SetFont('dejavusans', '', 6);
            $doc->MultiCell(185, 5, '* if box is not checked, operation live was not used / Si la casilla no esta marcada, la linea de opracion no se uso.', 0, 'L', 0, 0, '10', '250', true);

            $doc->SetFont('dejavusans', 'B', 7);
            //ancho = 185 total
            $doc->SetFillColor(181, 212, 255);//Azul
            $doc->MultiCell(25, 10, '', 1, 'C', 1, 0, '10', '260', true);//puro cuadro
            $doc->MultiCell(25, 5, 'Reviewed by', 0, 'C', 0, 0, '10', '262', true);//lo pusimos separado para darle un estilo de VALIGN
            $doc->MultiCell(60, 5, 'Name:', 1, 'C', 1, 0, '35', '260', true);
            $doc->MultiCell(60, 5, 'Signature:', 1, 'C', 1, 0, '95', '260', true);
            $doc->MultiCell(39, 5, 'Date:', 1, 'C', 1, 0, '155', '260', true);
            // PONEMOS LOS DATOS
            $doc->SetFont('dejavusans', '', 7);
            $doc->MultiCell(60, 5, 'Nombre de la persona', 1, 'C', 0, 0, '35', '265', true);
            $doc->MultiCell(60, 5, 'Signature ???', 1, 'C', 0, 0, '95', '265', true);
            $doc->MultiCell(39, 5, $fecha, 1, 'C', 0, 0, '155', '265', true);

            
            
            
            

            
            $doc->lastPage();
            if(!$doc->Output($_SERVER['DOCUMENT_ROOT'].'/testPDFoxconn.pdf','F')){
                //ahora tratamos de enviar el documento por correo electronico
                $asunto = "ENVIO FORMATO";
                $subAsunto = "Sub-Asunto";
                $cuerpo = "ENVIO DE FORMATO EN PDF, SE ENCONTRARA ADJUNTO";
                $mensajeOk = "mailSended";
                $archivo = $_SERVER['DOCUMENT_ROOT'].'/testPDFoxconn.pdf';

                $enviaCorreo = enviaCorreo('joelbh92@gmail.com',$asunto,$cuerpo,$subAsunto,$mensajeOk,$archivo);
                if($enviaCorreo == 'mailSended'){
                    //se cumpleto todo correctamente, ahora limpiamos el localStorage
                    echo "operationSuccess";
                }else{
                    //le indicamos que intente mas tarde o reporte el error
                    echo "ErrorSendingMail";
                }
            }else{
                $resultFinal = "NO";
            }

            echo $resultFinal;

        }else{
        
            echo "nel";
        }
    }else{
        echo "DataError&*|&*Sin acceso a la base de datos";
    }
}else{

}

?>