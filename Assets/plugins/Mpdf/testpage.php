<?php 
require_once('../Connections/DOPConnMysql.php'); 
mysqli_select_db($RGCDOPDBConnMysql, $database_RGCDOPDBConnMysql);//databse connection
	
$myxmlstring = '
	{
	  "vbeln": "0080109832",
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
echo $res = $myapipost->sappostres; //call post response	

//$a = json_encode($myapipost, JSON_PRETTY_PRINT);
$a = json_decode($res, true);

//echo $a['d']['vbakSet']['results'][0]['vbeln'];
?>


<html>
<head>
<style>
/*body
{
font-size:90%;
}
table, td, th, address, p
{
font-size:100%;
}
th, td
{
border: 1px solid #aaaaaa;
}
table
{
border: none;
border-collapse:collapse;
}
*/
	
	.tdordconf {
	  border: 1px solid black;
	}
	
	.tdordconfdet {
	  border: 1px solid black;
	}
</style>
</head>
<body>


<br />

<h2>QR Code Here</h2>

<table width="100%" border="0" cellpadding="3" cellspacing="0">

<tr>
<th width="1px"></th>
<th align="left">Customer Name Here</th>
<th width="45%" align="left" class="tdordconf">Order Confirmation</th>
</tr>
<tr>
<td></td>	
<td valign="top">
	<address>
	Erick Fabian Olguin Bautista<br />
	erick.olguin@masclicks.com.mx<br />
	</address>
</td>
<td valign="top" class="tdordconfdet">
	<address>
	Number/Date/Time Encoded<br />
	<?php echo $a['d']['vbakSet']['results'][0]['vbeln'];?><br />
	4326 SANDNES<br />
	NORWAY<br />
	</address>
</td>
</tr>
</table>

<br />
<h3>Description</h3>
<table width="600" border="1" cellpadding="3" cellspacing="0">
<tr>
<th align="left">Exam</th>
<th align="left">Register date</th>
<th align="left">Paid amount (in USD)</th>
</tr>
<tr>
<td align="top">
	CSS<br />
</td>
<td align="top">
	January 4. 2013
</td>
<td align="top">
	$ 95<br />
</td>
</tr>
</table>

<br />
<p>Kai Jim Refsnes<br>
REFSNES DATA AS</p>

</body>
</html>