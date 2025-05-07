<?php 
require_once('../Connections/DOPConnMysql.php'); 
mysqli_select_db($RGCDOPDBConnMysql, $database_RGCDOPDBConnMysql);//databse connection

$sotosearch = "80836005";//80836005 80109832 80132045
	
$sopadded= str_pad($sotosearch, 10, '0', STR_PAD_LEFT);
$myxmlstring = '
	{
	  "vbeln": "'.$sopadded.'", 
	  "typeoutput" :"S" ,
	  "vbakSet" :[
		{
		  "vbeln": "",
		  "vbapSet" : [
			{
			}
		  ]
		}
	 ],
	  "vbakheadertextSet" : [
	   {
		 "vbeln": ""
	   }
	 ],
	 "appresultSet" : [
	   {
		 "vbeln": ""
	   }
	  ]

	}
 ';
//////End Constructing xml format to be sent


require_once('../RGCSAPOdataAPI/RGCOdataAPIJson.php');

$myapipost = new jdeodataapi();
$myapipost->jdepostparam("ZSD_SALESINFO_JPD_SRV","MainsoSet");
$myapipost->jdexml($myxmlstring);
$myapipost->jdepost(" "," ");
$res = $myapipost->sappostres; //call post response	

//$a = json_encode($myapipost, JSON_PRETTY_PRINT);
$a = json_decode($res, true);

//echo $a['d']['vbakSet']['results'][0]['vbeln'];

$resulttype = $a['d']['appresultSet']['results'][0]['result_type'];
$docstat = preg_replace('/\s+/', '', $a['d']['appresultSet']['results'][0]['result_message']);


//check if SAP get so details is ok
if($resulttype!="1"){
	echo '
		<script>alert("Sales Order not found...");</script>
	';
} else{
	//*********************QR generation
	require_once("../phpqrcode/qrlib.php");

	$filepath = '../UploadFolder/qrtmp/'.$sotosearch.'.png';
	$codecontents = $sotosearch;

	QRcode::png($codecontents, $filepath, QR_ECLEVEL_H, 20); // generate qr without logo

	//read image path convert to base64 encoding
	$imgData = base64_encode(file_get_contents($filepath));

	//format data SRC: data:{mime};base64, {data};
	$src = 'data: '.mime_content_type($filepath).';base64,'.$imgData;


	//******* delete QRcode files older than 2 minutes
	function delete_older_than($dir, $max_age) {
	  $list = array();
	  $limit = time() - $max_age;
	  $dir = realpath($dir);
	  if (!is_dir($dir)) {
		return;
	  }

	  $dh = opendir($dir);
	  if ($dh === false) {
		return;
	  }

	  while (($file = readdir($dh)) !== false) {
		$file = $dir . '/' . $file;
		if (!is_file($file)) {
		  continue;
		}

		if (filemtime($file) < $limit) {
		  $list[] = $file;
		  unlink($file);
		}  
	  }
	  closedir($dh);
	  //return $list;
	}

	// An example of how to use:
	$dir = "../UploadFolder/qrtmp";

	// Delete 
	$deleted = delete_older_than($dir, 2*60);
	//******* delete QRcode files older than 2 minutes

	//*********************End QR generation


	//Construct Html
	$myheader = "
	<html>
	<head>
	<style>
		/*body {
		  font-family: 'Times New Roman', Times, serif;
		}*/
		.tdordconf {
		  border: 1px solid black;
		  background-color:#B7B7B7;
		}
		.tdordconfdet {
		  border: 1px solid black;
		}
		.itemtr {
		  border-top: 1px solid black;
		  border-bottom: 1px solid black;
		  size: -3;
		}
		.itemend {
		  border-top: 1px solid black;
		}
		h3 { font-weight:normal; }
	</style>
	</head>
	<body style='font-family: Times New Roman'>
	";

	//get QR image
	$myheader .="
	 <img src='".$src."' style='widht: 100px; height: 100px'/>
	 ";

	$myheader .="
	<table width='100%' border='0' cellpadding='3' cellspacing='0'>
	<tr>
	<th></th>
	<th align='left'>";
	$myheader .= $a['d']['vbakSet']['results'][0]['custname']."<br />";
	$myheader .="
	</th>
	<th width='50%' align='left' class='tdordconf'>Order Confirmation</th>
	</tr>
	<tr>
	<td  width='15px'></td>
	<td valign='top'>
		<address>
	";
	$myheader .= ltrim($a['d']['vbakSet']['results'][0]['name4'], "0")."<br />".
		$a['d']['vbakSet']['results'][0]['soldto_street']."<br />".
		$a['d']['vbakSet']['results'][0]['soldto_city']."<br /><br />".
		"TEL: ". $a['d']['vbakSet']['results'][0]['soldto_tel']."<br />".
		"FAX: ". $a['d']['vbakSet']['results'][0]['soldto_fax']."<br /><br />".

		"<font size='1'>Ship-To-Party</font><br />". 
		$a['d']['vbakSet']['results'][0]['shipto_name']."<br />".
		$a['d']['vbakSet']['results'][0]['shipto_street']."<br />".
		$a['d']['vbakSet']['results'][0]['shipto_city']
		;
	$myheader .="
		</address>
	</td>
	<td valign='top' class='tdordconfdet'>
		<address>
		Number / Date / Time Encoded<br />
	";
	$myheader .= ltrim($a['d']['vbakSet']['results'][0]['vbeln'], "0")." / ".
		date("Y-m-d", strtotime($a['d']['vbakSet']['results'][0]['doc_date']))." / ".
		date("H:i:s", strtotime($a['d']['vbakSet']['results'][0]['doc_time']))."<br />".
		$a['d']['vbakSet']['results'][0]['ordertype']." ".$a['d']['vbakSet']['results'][0]['ordertypename']."<br />".
		"Reference No. / Date <br />".
		$a['d']['vbakSet']['results'][0]['soldto_po']." / ".date("Y-m-d", strtotime($a['d']['vbakSet']['results'][0]['creation_date']))."<br />".
		"Delivery Date <br />".
		date("Y-m-d", strtotime($a['d']['vbakSet']['results'][0]['doc_rdd']))."<br />".
		"Cust Number: ".ltrim($a['d']['vbakSet']['results'][0]['custcode'], "0")."<br />".
		"ROUTE: ".$a['d']['vbakSet']['results'][0]['vbapSet']['results'][0]['routecode']." ".
		$a['d']['vbakSet']['results'][0]['vbapSet']['results'][0]['routename']
		;
	$myheader .="
		</address>
	</td>
	</tr>
	</table>

	<br>
	Total CBM: "; 

	//compute for total CBM
	$linectr = count($a['d']['vbakSet']['results'][0]['vbapSet']['results']);
	$totcbm = 0;
	for($x=0; $x<$linectr; $x++){
		//$totcbm += round(($a['d']['vbakSet']['results'][0]['vbapSet']['results'][$x]['volume']/61023.378),3);
		$totcbm += $a['d']['vbakSet']['results'][0]['vbapSet']['results'][$x]['volume'];
	}

	$myheader .= number_format($totcbm, 3, '.', ',');

	$mybody .="
	<table width='100%' border='0' cellpadding='3' cellspacing='0'>
	<tr>
	<td class='itemtr' align='left'>Item</td>
	<td class='itemtr' align='left'>CBM</td>
	<td class='itemtr' align='left'>Material</td>
	<td class='itemtr' align='left'>Description</td>
	<td class='itemtr' align='right'>Qty</td>
	<td class='itemtr' ></td>
	</tr>
	<tr>
	<th></th>
	</tr>
	";

	//Construct line item
	$linectr = count($a['d']['vbakSet']['results'][0]['vbapSet']['results']);
	$mybody2 = "
	<table width='100%' border='0' cellpadding='3' cellspacing='0'>
	<tr>
		<td align='left' width='50%'>" . 
			$a['d']['vbakSet']['results'][0]['custname']."<br />".
			$a['d']['vbakSet']['results'][0]['soldto_street']."<br />".
			$a['d']['vbakSet']['results'][0]['soldto_city'].
		"</td>
		<td valign='top'>" . 
			"<font size='1'>Doc. number / Date</font><br />". 
			ltrim($a['d']['vbakSet']['results'][0]['vbeln'], "0")." / ". date("Y-m-d", strtotime($a['d']['vbakSet']['results'][0]['doc_date'])).
		"</td>
	</tr>
	</table> 
	<br />";
		
	
	$mybody2 .= $mybody;
	for($x=0; $x<$linectr; $x++){
		if($x>14){
			$mybody2 .="
			<tr>
			<td align='top' cellpadding='3'>".ltrim($a['d']['vbakSet']['results'][0]['vbapSet']['results'][$x]['posnr'], "0")."</td>
			<td align='top'>".round(($a['d']['vbakSet']['results'][0]['vbapSet']['results'][$x]['volume']/61023.378),3)."</td>
			<td align='top'>".ltrim($a['d']['vbakSet']['results'][0]['vbapSet']['results'][$x]['itemnumber'], "0")."</td>
			<td align='top'>".$a['d']['vbakSet']['results'][0]['vbapSet']['results'][$x]['itemname']."</td>
			<td align='right'>".
				$a['d']['vbakSet']['results'][0]['vbapSet']['results'][$x]['orderqty'].
				$a['d']['vbakSet']['results'][0]['vbapSet']['results'][$x]['salesunit']
			."</td>
			<td align='top'>____</td>
			</tr>
			";
		} else{
			$mybody .="
			<tr>
			<td align='top' cellpadding='3'>".ltrim($a['d']['vbakSet']['results'][0]['vbapSet']['results'][$x]['posnr'], "0")."</td>
			<td align='top'>".round(($a['d']['vbakSet']['results'][0]['vbapSet']['results'][$x]['volume']/61023.378),3)."</td>
			<td align='top'>".ltrim($a['d']['vbakSet']['results'][0]['vbapSet']['results'][$x]['itemnumber'], "0")."</td>
			<td align='top'>".$a['d']['vbakSet']['results'][0]['vbapSet']['results'][$x]['itemname']."</td>
			<td align='right'>".
				$a['d']['vbakSet']['results'][0]['vbapSet']['results'][$x]['orderqty'].
				$a['d']['vbakSet']['results'][0]['vbapSet']['results'][$x]['salesunit']
			."</td>
			<td align='top'>____</td>
			</tr>
			";
		}

	}

	$mybody .="<br/>
	<tr>
	<th></th>
	</tr>
	<tr>
	<th class='itemend' ></th>
	<th class='itemend' ></th>
	<th class='itemend' ></th>
	<th class='itemend' ></th>
	<th class='itemend' ></th>
	<th class='itemend' ></th>
	</tr>
	</table>
	";

	$mybody2 .="<br/>
	<tr>
	<th></th>
	</tr>
	<tr>
	<th class='itemend' ></th>
	<th class='itemend' ></th>
	<th class='itemend' ></th>
	<th class='itemend' ></th>
	<th class='itemend' ></th>
	<th class='itemend' ></th>
	</tr>
	</table>
	";

	$myfooter .="
	<div align='center'>***Nothing Follows***</div>
	<div style='position: fixed; left: 0mm; bottom: 25mm; rotate: 0;'>
		<h3><hr>REMARKS:<br/>
	";

	//construct remarks
	$myfooter .= $a['d']['vbakSet']['results'][0]['remarks']."<br />";

	$remctr = count($a['d']['vbakheadertextSet']['results']);
	for($x=0; $x<$remctr; $x++){
		$myfooter .= $a['d']['vbakheadertextSet']['results'][$x]['notes']."<br />";
	}

	$myfooter .="</h3>
	</div>

	<div style='position: fixed; left: 0mm; bottom: 0mm; rotate: 0;'>
		<h3>Prepared by: <br><br></h3>
		______________________
		<!--<p>Printed from dealer portal</p>-->
	</div>

	<div style='position: fixed; right: 0mm; bottom: 0mm; rotate: 0;'>
		<!--<barcode code='978-0-9542246-0' class='barcode' />-->
		<h3>Confirmed by: <br><br></h3>
		______________________
		<!--<p>.</p>-->
	</div>

	</body>
	</html>

	";


	//load output
	require_once 'vendor/autoload.php';
	$mpdf = new \Mpdf\Mpdf(['format' => 'Letter','tempDir' => '../UploadFolder']); 
	//$mpdf->SetProtection(array(), 'UserPassword', '1234'); //password protected

	// Set a simple Footer including the page number
	//$mpdf->setFooter('Page {PAGENO} of {nb}');
	if($docstat== preg_replace('/\s+/', '', 'Doc Already Printed')) {
		$footmsg = 'Re-Printed from Ordering Portal.';
	} else{
		$footmsg = 'Printed from Ordering Portal.';
	}

	$mpdf->SetFooter(array(
		  'C' => array(
			'content' => $footmsg.' / Page {PAGENO} of {nb} Printed @ {DATE j-m-Y H:m}',
			'font-family' => '',
			'font-style' => '',
			'font-size' => '',
		   ),
		   'line' => 0,            /* 1 to include line below header/above footer */
	  ), 'O'      /* defines footer for Even Pages */
	  );

	if($linectr>=15){
		$mpdf->WriteHTML($myheader.$mybody);
		$mpdf->AddPage('','','','','off'); // Turn off (suppress) page numbering from the start of the document
		$mpdf->WriteHTML($mybody2.$myfooter);
	} else{
		$mpdf->WriteHTML($myheader.$mybody.$myfooter);
	}

	//$mpdf->defaultfooterline = 0;

	//$mpdf->WriteHTML('<h1>Hello world!</h1>');
	//$mpdf->Output('../UploadFolder/testpass.pdf'); // save output to file
	$mpdf->Output(); //display on browser

	//Delete QR Code immediately after viewing
	//unlink($filepath);
}
?>