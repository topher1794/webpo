<?php 
require_once("../phpqrcode/qrlib.php");

$filepath = '../phpqrcode/temp/done.png';
$logopath = '../img/uratex-logo.png';
$codecontents = 'Junriel Epinosa';

QRcode::png($codecontents, $filepath, QR_ECLEVEL_H, 20); // generate qr without logo

$QR = imagecreatefrompng($filepath);//calling generated qr file

//start draw image to qrcode
$logo = imagecreatefromstring(file_get_contents($logopath));
$QR_width = imagesx($QR);
$QR_height = imagesy($QR);

$logo_width = imagesx($logo);
$logo_height = imagesy($logo);

//scale logo to fit in the qr code
$logo_qr_width = $QR_width/3;
$scale = $logo_width/$logo_qr_width;
$logo_qr_width = $QR_width/3;
$logo_qr_height = $QR_height/$scale;
imagecopyresampled($QR, $logo, $QR_width/3, $QR_height/3, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);

//save qr again with logo
imagepng($QR, $filepath);

//read image path convert to base64 encoding
$imgData = base64_encode(file_get_contents($filepath));

//format data SRC: data:{mime};base64, {data};
$src = 'data: '.mime_content_type($filepath).';base64,'.$imgData;
	
//output to browser
echo '<img src="'.$src.'" style="widht: 300px; height: 300px"/>';
?>
<div class="QR"></div>
	