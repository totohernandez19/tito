<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<title>BELMONT TITO SERVICES</title>
</head>
<body>
<!-- LEFT SIDE BAR MENU -->
  <nav id="left_panel">
  	<ul class="left_panel_text"> SITES 
  		<li> ------------- </li>
  		<li><a href="index.php"> HOME </a></li>
      <li><a href="https://app.propertymeld.com/1885/v/42787/melds/melding/?status%5B%5D=COMPLETED&status%5B%5D=MAINTENANCE_COULD_NOT_COMPLETE&status%5B%5D=MANAGER_CANCELED&status%5B%5D=TENANT_CANCELED&status%5B%5D=VENDOR_COULD_NOT_COMPLETE&ordering=-created" target="_blank"> MELD </a></li>
  		<li><a href="https://www.belmontmanagementgroup.com/" target="_blank"> BELMONT </a></li>
  		<br><br>
  		<li> MAIN MENU </li>
  		<li> ------------- </li>
			<li><a href="joblist.php"> MELD JOBS </a></li>
			<li><a href="report.php"> REPORT </a></li>
	  	<br><br>	
  		<li> SUB MENU </li>
  		<li> ------------- </li>
			<li><a href="pricelist.php"> PRICE LIST </a></li>
	  	<li><a href="markuplist.php"> MARKUP LIST </a></li> 
  	</ul>
  </nav>

  <!-- TOP CENTER -->
  <p id="topcenter_panel">
    <h1 class="topcenter_h1">  HANDYMAN SERVICES </h1>
  </p>

<!-- MAIN CENTER PANEL -->

	<div id="center_panel">   
		<img src='img/welcome.png' style="margin-left: 27%; margin-top: 5%;">
	</div>
	<script>
		function isvalid(){			
			var user = document.form.user.value;
			var pass = document.form.pass.value;	
			if(user.length == "" && pass.length==""){
				alert("Username and Password field are empty!")
			}else{
				if(user.length == ""){
					alert("Username is empty!");
				}
				if(pass.length == ""){
					alert("Password is empty!");
				}
			}
		}
	</script>
</div> 

  <!-- LEFT SIDE BAR MENU -->
  <p id="right_panel" class="right_panel_text">
  	TITO SERVICES
  	<br><br>
  	Our business strives to uphold great communication with our tenants, owners, and prospects. 
  	<br><br>
  	YOEL HERNANDEZ <br>
	5150 Boggy Creek, J33<br>
	St. Cloud, Fl 34771 <br><br>
	Phone: (407) 953-3297 <br>
	Fax: (407) 953-3297 <br>
	Email: pincol2222@gmail.com <br><br>
  </p>

</body>
<footer id="footer">Maybelle Hernandez 2022 &copy; Copyright</footer>
</html>