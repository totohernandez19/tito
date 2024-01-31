<?php
	// Start the session
session_start();
include_once dirname(__FILE__).'/inc/config.php'; 
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="ajax-script.js" type="text/javascript"></script>

	<title>BELMONT TITO SERVICES</title>
	<style type="text/css">
		.ui-class {
			top: 50%;
			left: 50%;
		}

		.form-group{
			padding: 2px;
		}
	</style>
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
		<h1 class="topcenter_h1" > COMPLETED JOBS REPORT </h1>
	</p>

	<!-- MAIN CENTER PANEL -->	
	<div id="center_panel">  
		<div> 
			<div class="select" style="margin: 10px 5px 10px 15px; 
			     float:left; width:280px; height: 50px;">
			  <select id="sel-search" onchange="selectSearch()">
			    <option value="0|Search for Address"> Search By : </option>
			    <option value="1|Search for Date Created"> Date Created </option>
			    <option value="2|Search for Display Date"> Display Date </option>
			    <option value="3|Search for Meld Number"> Meld Number </option>
			    <option value="4|Search for Address"> Address </option>
			    <option value="5|Search for Pending Previous Melds"> Pending Previous Melds </option>
			    <option value="6|Search for Pending Current Melds"> Pending Current Melds </option>
			  </select>
			</div> 
			<input type="text" style="margin: 10px 10px; float: left;" id="reportInput" onkeyup="searchFunction()" placeholder="Search for Address" title="Type in Address">
		</div>
		<div id="table-wrapper">
			<div id="table-scroll">
				<table style="margin-right: 10px;" id='tbl_report'>
					<tr style="top: 0px; position: sticky;">
						<th style='text-align:center'>
							  <input id='mainCB' type="checkbox" onClick="toggleSel()"><br/>
						</th>							
						<th style='display:none;'>ID</th>
						<th onclick="sortTable(2)">DATE CREATED</th>	
						<th onclick="sortTable(3)">DISPLAY DATE</th>
						<th style='text-align:center' onclick="sortTable(4)">MELD #</th>
						<th style='text-align:center' onclick="sortTable(5)">ADDRESS</th>
						<th style='display:none;'>ADDRESS 2</th>
						<th onclick="sortTable(7)">MATERIALS</th>					
						<th onclick="sortTable(8)">MARKUP</th>				
						<th onclick="sortTable(9)">LABOR</th>					
						<th onclick="sortTable(10)">TOTAL</th>	
					</tr>
					<?php	    
						include_once("classMain.php");

						$db   = new dbObj();
						$con  = $db->getConnstring();

						$qry  = " SELECT id,meldnum,addr1,addr2,bill,
										(SELECT SUM(price*qty) FROM jobdet WHERE jobid = job.id AND paid = 'y') AS total,
										DATE(datecreated) AS datec,
										DATE(datedisplay) AS dispdate		
										FROM job WHERE paid = 'y' ORDER BY timestamp DESC; ";
						$res  = $con->query($qry);
						$n    = $tot = $gtot =$bill = $totBill = $totRow = $profit = $gprofit = $markup = 0;

						$dateArr = array();
						$dateStr = "";

						$dateDispArr = array();
						$dateDispStr = "";
						while ($row = $res->fetch_row()) {	

							$markup   = 0;
							$bill     = $row[4];
							$totBill += $row[4];

							// -- MARK UP CALCULATION --
						    if($bill>=0 && $bill<=80){
						        $bill  += $bill * (40/100); 
						        $markup = $row[4] * (40/100);
						    }else if($bill>=80.01 && $bill<=100){
						        $bill  += $bill * (30/100);
						        $markup = $row[4] * (30/100);
						    }else if($bill>=100.01){
						        $bill  += $bill * (25/100);
						        $markup = $row[4] * (25/100);
						    }

						    $tot     = $bill+$row[5];
						    $gtot   += $tot;

						    $profit   = $tot-$row[4];
						    $gprofit += $profit;

						    $dateArr = explode("-",$row[6]);
						    $dateStr = $dateArr[0]."-".$dateArr[1]."-".$dateArr[2];

						    $dateDispArr = explode("-",$row[7]);
						    $dateDispStr = $dateDispArr[0]."-".$dateDispArr[1]."-".$dateDispArr[2];

							echo "<tr ondblclick='doubleClickEdit(this)'>";
							echo "<td class='cb_job' style='text-align:center'>
							      <input class='childCB' name='checkbox[]' type='checkbox' value='".$row[0]."' 
							      data-total='".$tot."' data-profit='".$profit."' 
							      onclick='calcTotal()''></td>";
							echo "<td class='id' style='display:none;'>$row[0]</td>";	
							echo "<td class='date' style='text-align:center'>$dateStr</td>";
							echo "<td class='dispdate' style='text-align:center'>$dateDispStr</td>";			    		    	  
							echo "<td class='meldnum'>$row[1]</td>";
							echo "<td class='addr1'>$row[2]</td>";
							echo "<td class='addr2' style='display:none;'>$row[3]</td>";
							echo "<td class='bill' style='text-align:right'>".number_format($row[4], 2)."</td>";
							echo "<td class='markup' style='text-align:right'>".number_format($markup, 2)."</td>";
							echo "<td class='labor' style='text-align:right'>".(($row[5]==NULL||$row[5]==0) ? '0.00':
								    number_format($row[5], 2))."</td>";
							echo "<td class='total' style='text-align:right'>".number_format($tot, 2)."</td>";
							echo "</tr>";
							$n++;
							$totRow++;
						}
						echo "<tr>
							<td style='color:red' colspan=5>TOTAL COLLECTIBLE (".$totRow.")</td>
							<td class='grandTotal' id='grandTotal' style='color:red; text-align:right' colspan=3 style='text-align:right'>".number_format($gtot, 2)."</td>
							</tr>";
						$con->close();
					?>
				</table>
			</div>
		</div>
		<div id="div-tot-addback">			
			<div class="btn-group">			
				<button id="btn-back" onclick = "history.back();">BACK</button>
				<button id="btn-print" onclick = "print();">PRINT</button>
			</div>
			<div id="div-total">
				<p id="p-total"></p>
			</div>
		</div>		
	</div>

	<script>
		window.onload = function(){
		    dispTotFooter();
		    clearAllCB();
		};
		
		let tableRowElement;
		var ajax_url  = "<?php echo APPURL;?>/ajax.php" ;
		var action    = "";

		$(document).on('click','#btn-print',function(e){
		      $.ajax({    
		        type: "GET",
		        url: "showData.php",             
		        dataType: "html",                  
		        success: function(data){                    
		            $("#table-wrapper").html(data); 		           
		        }
		    });
		});				

		function dispTotFooter(){
			var t = "&nbsp; MATERIAL : 0.00" + 
	            "&nbsp; &nbsp; &nbsp; PROFIT : 0.00" +
	            "&nbsp; &nbsp; &nbsp; TOTAL : 0.00";
	    document.getElementById('p-total').innerHTML = t;
		}

		function sortTable(n) {
			var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
			table = document.getElementById("tbl_report");
			switching = true;
		  //Set the sorting direction to ascending:
			dir = "asc"; 
		  /*Make a loop that will continue until
		  no switching has been done:*/
			while (switching) {
		    //start by saying: no switching is done:
				switching = false;
				rows = table.rows;
		    /*Loop through all table rows (except the
		    first, which contains table headers):*/
				for (i = 1; i < (rows.length - 2); i++) {
		      //start by saying there should be no switching:
					shouldSwitch = false;
		      /*Get the two elements you want to compare,
		      one from current row and one from the next:*/

					x = rows[i].getElementsByTagName("TD")[n];
					y = rows[i + 1].getElementsByTagName("TD")[n];

					x1 = x.innerHTML;
					y1 = y.innerHTML;

					x1 = isNaN(x.innerHTML) ? x.innerHTML.split(',').join(''):x.innerHTML;
					y1 = isNaN(y.innerHTML) ? y.innerHTML.split(',').join(''):y.innerHTML;

					var xContent =  (isNaN(x1)) ? (x1.toLowerCase() === '-')
					? 0 : x1.toLowerCase() : parseFloat(x1);
					var yContent =  (isNaN(y1)) ? (y1.toLowerCase() === '-')
					? 0 : y1.toLowerCase() : parseFloat(y1);

		      /*check if the two rows should switch place,
		      based on the direction, asc or desc:*/
					if (dir == "asc") {
						if (xContent > yContent) {
		          //if so, mark as a switch and break the loop:
							shouldSwitch= true;
							break;
						}
					} else if (dir == "desc") {
						if (xContent < yContent) {
		          //if so, mark as a switch and break the loop:
							shouldSwitch = true;
							break;
						}
					}
				}
				if (shouldSwitch) {
		      /*If a switch has been marked, make the switch
		      and mark that a switch has been done:*/
					rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
					switching = true;
		      //Each time a switch is done, increase this count by 1:
					switchcount ++;      
				} else {
		      /*If no switching has been done AND the direction is "asc",
		      set the direction to "desc" and run the while loop again.*/
					if (switchcount == 0 && dir == "asc") {
						dir = "desc";
						switching = true;
					}
				}
			}
		}

		function searchFunction() {
			var input, filter, table, tr, td, i, txtValue;			
			var searchVal = $('#sel-search').val().split('|')[0];
			input  = document.getElementById("reportInput");
			filter = input.value.toUpperCase();
			table  = document.getElementById("tbl_report");
			tr     = table.getElementsByTagName("tr");
			for (i = 0; i < tr.length; i++) {
				if(searchVal=='1'){
					td = tr[i].getElementsByTagName("td")[2]; //id
				}else if(searchVal=='2'){
					td = tr[i].getElementsByTagName("td")[3]; //meld number
				}else if(searchVal=='3'){
					td = tr[i].getElementsByTagName("td")[4]; //address
				}else if(searchVal=='4'){
					td = tr[i].getElementsByTagName("td")[5]; //pending prev
				}else if(searchVal=='5'){
					td = tr[i].getElementsByTagName("td")[6]; //pending curr
				}

				if (td) {
					txtValue = td.textContent || td.innerText;
					if (txtValue.toUpperCase().indexOf(filter) > -1) {
						tr[i].style.display = "";
					} else {
						tr[i].style.display = "none";
					}
				}       
			}		
			clearAllCB(); 
			document.getElementById("mainCB").disabled = false;
			if(filter!=' ' && filter!=null && filter!=''){
				document.getElementById("mainCB").disabled = true;
				dispTotFooter();
			}			
		}

		function toggleSel() {
	    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
	    let total      = gtotal = profit = gprofit = 0;

	    table = document.getElementById("tbl_report");
			tr    = table.getElementsByTagName("tr"); 

	    for (var i = 0; i < checkboxes.length; i++) {
	        if (checkboxes[i] != checkboxes[0])
	            checkboxes[i].checked = checkboxes[0].checked;
          if(checkboxes[i].checked==true && checkboxes[i].getAttribute("data-total")!=null){
          	total   = checkboxes[i].getAttribute("data-total");
          	profit  = checkboxes[i].getAttribute("data-profit");
          	gtotal  = (+gtotal) + (+parseFloat(total).toFixed(2));
          	gprofit = (+gprofit) + (+parseFloat(profit).toFixed(2));
          }else{
          	gtotal = gprofit = 0;
          } 
	    }	    
			table.rows[tr.length-1].cells.namedItem("grandTotal").innerHTML = numberWithCommas(parseFloat(gtotal).toFixed(2));

			var t = "&nbsp; MATERIAL : " + numberWithCommas(parseFloat(gtotal-gprofit).toFixed(2)) +
	            "&nbsp; &nbsp; &nbsp; PROFIT : " + numberWithCommas(parseFloat(gprofit).toFixed(2)) +
	            "&nbsp; &nbsp; &nbsp; TOTAL : " + numberWithCommas(parseFloat(gtotal).toFixed(2));
	    document.getElementById('p-total').innerHTML = t;
		}

		function calcTotal() {    		
			var checkboxes  = document.querySelectorAll('input[type="checkbox"]');
			let total       = gtotal = profit = gprofit = 0;

			checkboxes[0].checked = false;
			for (var i = 1; i < checkboxes.length; i++) {
	        if (checkboxes[i].checked == true){
	        	profit  = checkboxes[i].getAttribute("data-profit");
          	total   = checkboxes[i].getAttribute("data-total");
          	gtotal  = (+gtotal) + (+parseFloat(total).toFixed(2));
          	gprofit = (+gprofit) + (+parseFloat(profit).toFixed(2));
	        }
	    }	    
			table  = document.getElementById("tbl_report");
			tr     = table.getElementsByTagName("tr");
			table.rows[tr.length-1].cells.namedItem("grandTotal").innerHTML = numberWithCommas(parseFloat(gtotal).toFixed(2));	

			var t = "&nbsp; MATERIAL : " + numberWithCommas(parseFloat(gtotal-gprofit).toFixed(2)) +
	            "&nbsp; &nbsp; &nbsp; PROFIT : " + numberWithCommas(parseFloat(gprofit).toFixed(2)) +
	            "&nbsp; &nbsp; &nbsp; TOTAL : " + numberWithCommas(parseFloat(gtotal).toFixed(2));
	    document.getElementById('p-total').innerHTML = t;
		} 

		function numberWithCommas(x) {
		    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		}

		function clearAllCB() {
		  var checkboxes = document.querySelectorAll('input[type="checkbox"]');
			for (var i = 0; i < checkboxes.length; i++) {
	        checkboxes[i].checked = false;
	    }
	    table  = document.getElementById("tbl_report");
			tr     = table.getElementsByTagName("tr");
			table.rows[tr.length-1].cells.namedItem("grandTotal").innerHTML = 0;
		}

		function selectSearch() {
			var jobsInputSearch = document.getElementById("reportInput");
			var textValue= $('#sel-search').val().split('|')[1];
			var index = $('#sel-search').val().split('|')[0];

			jobsInputSearch.placeholder = textValue;

			if(index == 4 || index == 5){
				document.getElementById("reportInput").disabled = true;
		  }else{
		  	document.getElementById("reportInput").disabled = false;
		  }
		}

	</script>

	<!-- LEFT SIDE BAR MENU 
	<div id="right_panel" >
		<p class="right_panel_text" style="margin-top:20px">  
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
	</div>
	-->
</body>
<footer id="footer">Maybelle Hernandez 2022 &copy; Copyright</footer>
</html>