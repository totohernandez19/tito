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
		<h1 class="topcenter_h1" >  JOB LIST </h1>
	</p>

	<!-- MAIN CENTER PANEL -->	
	<div id="center_panel">  
		<div class="select-wrapper"> 
			<div class="select">
			  <select id="sel-search" onchange="selectSearch()">
			    <option value="0|Search for Address"> Search By : </option>
			    <option value="1|Search for Date Created"> Date Created </option>
			    <option value="2|Search for Meld Number"> Meld Number </option>
			    <option value="3|Search for Address"> Address </option>
			  </select>
			</div> 
			<input type="text" style="margin: 10px 10px; float: left;" id="jobsInput" onkeyup="searchFunction()" placeholder="Search for Address" title="Type in Address">
		</div>
		<div id="table-wrapper">
			<div id="table-scroll">
				<table style="margin-right: 10px;" id='tbl_job'>
					<tr style="top: 0px; position: sticky;">
						<th style='text-align:center'>
							  <input id='mainCB' type="checkbox" onClick="toggleSel()"><br/>
						</th>							
						<th style='display:none;'>ID</th>
						<th onclick="sortTable(2)">DATE CREATED</th>	
						<th style='text-align:center' onclick="sortTable(3)">MELD #</th>
						<th style='text-align:center' onclick="sortTable(4)">ADDRESS</th>
						<th style='display:none;'>ADDRESS 2</th>	
						<th onclick="sortTable(6)">MTRL</th>				
						<th onclick="sortTable(7)">TOTAL</th>				
						<th onclick="sortTable(8)">DISPLAY DATE</th>	
						<th>ADD</th>		
						<th>VIEW</th>		
						<th>DL</th>							
						<th>DEL</th>						
						<th>PAID</th>				
					</tr>
					<?php	    
						include_once("classMain.php");

						$db          = new dbObj();
						$con         = $db->getConnstring();

						$qry  = " SELECT id,meldnum,addr1,addr2,bill,
										(SELECT SUM(price*qty) FROM jobdet WHERE jobid = job.id AND active = 'y') AS total,
										DATE(datecreated) AS datec,
										DATE(datedisplay) AS datedisplay	
										FROM job WHERE active = 'y' ORDER BY timestamp DESC; ";
						$res  = $con->query($qry);
						$n    = $tot = $gtot =$bill = $totBill = $totRow = $profit = $gprofit = $markup = 0;
						$dateArr = array();
						$dateStr = "";

						$dateDispArr = array();
						$dateDispStr = "";
						while ($row = $res->fetch_row()) {	

							$bill     = $row[4];
							$totBill += $row[4];

							// -- MARK UP CALCULATION --
						    if($bill>=0 && $bill<=80){
						        $bill  += $bill * (40/100); 
						    }else if($bill>=80.01 && $bill<=100){
						        $bill  += $bill * (30/100);
						    }else if($bill>=100.01){
						        $bill  += $bill * (25/100);
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
							echo "<td class='date' style='text-align:center;'>$dateStr</td>";					    		    	  
							echo "<td class='meldnum'>$row[1]</td>";
							echo "<td class='addr1'>$row[2]</td>";
							echo "<td class='addr2' style='display:none;'>$row[3]</td>";
							echo "<td class='bill'style='text-align:right'>$row[4]</td>";
							echo "<td class='total' style='text-align:right'>".(($tot==NULL||$tot==0) ? '0.00':
								    number_format($tot, 2))."</td>";								
							echo "<td class='datedisplay' style='text-align:center;'>$dateDispStr</td>";
							echo "<td><a href='jobdetail.php?jobid=$row[0]'><img src='img/addIcon.png' style='width:40px;height:40px;'></a></td>";
							echo "<td><a href='generateInvoice.php?jobid=".$row[0]."&amp;status=show' target='_blank'>
							      <img src='img/pdfIcon.png' style='width:40px;height:40px;'></a></td>";
							echo "<td><a href='generateInvoice.php?jobid=".$row[0]."&amp;status=dl'>
							      <img src='img/downloadIcon.png' style='width:40px;height:40px;'></a></td>";
							echo "<td><a href='#' onclick='removeRow(this)'>
							      <img src='img/deleteIcon.png' style='width:40px;height:40px;'></a></td>";  
							echo "<td><a href='#' onclick='paid(this)'>
							      <img src='img/paidIcon.png' style='width:40px;height:40px;'></a></td>";  
							echo "</tr>";
							$n++;
							$totRow++;
						}
						echo "<tr>
							<td style='color:red' colspan=4>TOTAL COLLECTIBLE (".$totRow.")</td>
							<td class='grandTotal' id='grandTotal' style='color:red; text-align:right' colspan=2 style='text-align:right'>".number_format($gtot, 2)."</td>
							<td colspan=6></td>
							</tr>";
						$con->close();
					?>
				</table>
			</div>
		</div>
		<div id="div-tot-addback">			
			<div class="btn-group">			
				<button id="btn-back" onclick = "history.back();">BACK</button>
				<button id="btn-add"  onclick = "addRow();">ADD</button>
				<button id="btn-print"  onclick = "print();">PRINT</button>
			</div>
			<div id="div-total">
				<p id="p-total"></p>
			</div>
		</div>		
	</div>

	<!-- Creating a popup modal -->
	<div class="modal" tabindex="-1" role="dialog" id="exampleModal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">EDIT JOB LIST</h5>
					<button type="button" onclick="closeModal()" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group" style="display:none;">
							<label for="id">ID</label>
							<input type="text" id="id" class="form-control">
					</div>

					<div>			
						<div class="form-group"  style="width:33%; float: left;">
							<label for="meldnum">MELD NUMBER</label>
							<input type="text" id="meldnum" class="form-control">
						</div>
						<div class="form-group" style="width:33%; float: left;">
							<label for="bill">MATERIALS</label>
							<input type="text" id="bill" class="form-control">
						</div>
						<div class="form-group" style="width:33%; float: right;">
							<label for="datedisplay">DISPLAY DATE</label>
							<input type="date" style="margin-top: 17px;" id="datedisplay" class="form-control">
						</div>
					</div>						

					<div class="form-group">
						<label for="addr1">ADDRESS 1</label>
						<input type="text" id="addr1" class="form-control">
					</div>

					<div class="form-group">
						<label for="addr2">ADDRESS 2</label>
						<input type="text" id="addr2" class="form-control">						
					</div>	
					
					<div class="modal-footer" style="float:center">
						<button type="button" class="btn btn-primary" id="btn-popup-save" onclick="saveInfo()">Save changes</button>
						<button type="button" class="btn btn-secondary" id="btn-popup-close" onclick="closeModal()">Close</button>
					</div>
				</div>
			</div>
		</div>		
	</div> 

	<div class="modal-backdrop fade show" id="backdrop" style="display: none;"></div>

	<script>
		window.onload = function(){
		    dispTotFooter();
		    clearAllCB();
		};
		
		let tableRowElement;
		var ajax_url  = "<?php echo APPURL;?>/ajax.php" ;
		var action    = "";

		function dispTotFooter(){
			var t = "&nbsp; MATERIAL : 0.00" + 
	            "&nbsp; &nbsp; &nbsp; PROFIT : 0.00" +
	            "&nbsp; &nbsp; &nbsp; TOTAL : 0.00";
	    document.getElementById('p-total').innerHTML = t;
		}

		function doubleClickEdit(x) {
			tableRowElement    = x.parentElement.parentElement;
			var n              = x.rowIndex-1;
			const id           = tableRowElement.getElementsByClassName('id')[n].innerHTML;
			const meldnum      = tableRowElement.getElementsByClassName('meldnum')[n].innerHTML;
			const addr1        = tableRowElement.getElementsByClassName('addr1')[n].innerHTML;
			const addr2        = tableRowElement.getElementsByClassName('addr2')[n].innerHTML;
			const bill         = tableRowElement.getElementsByClassName('bill')[n].innerHTML;
			const datedisplay  = tableRowElement.getElementsByClassName('datedisplay')[n].innerHTML;

			document.getElementById('id').value          = id;
			document.getElementById('meldnum').value     = meldnum;
			document.getElementById('addr1').value       = addr1;
			document.getElementById('addr2').value       = addr2;
			document.getElementById('bill').value        = bill;			
			document.getElementById('datedisplay').value = datedisplay;

			action = "edit";
			openModal();
		}

		function addRow() {
			var date = new Date();
			var day = date.getDate();
			var month = date.getMonth() + 1;
			var year = date.getFullYear();

			if (month < 10) month = "0" + month;
			if (day < 10) day = "0" + day;

			var today = year + "-" + month + "-" + day;

			document.getElementById('id').value           = "";
			document.getElementById('meldnum').value      = "";
			document.getElementById('addr1').value        = "";
			document.getElementById('addr2').value        = "";
			document.getElementById('bill').value         = "";			
			document.getElementById('datedisplay').value  = today;

			action = "add";
			openModal();
		}

		function saveInfo() {
			const id            = (action=='edit') ? document.getElementById('id').value : '';
			const meldnum       = document.getElementById('meldnum').value;
			const addr1         = document.getElementById('addr1').value;
			const addr2         = document.getElementById('addr2').value;
			const bill          = document.getElementById('bill').value;
			const datedisplay   = document.getElementById('datedisplay').value;

			document.getElementById('id').value          = id;
			document.getElementById('meldnum').value     = meldnum;
			document.getElementById('addr1').value       = addr1;
			document.getElementById('addr2').value       = addr2;
			document.getElementById('bill').value        = bill;
			document.getElementById('datedisplay').value = datedisplay;

			var call_type = (action=='edit') ? "row_entry" : "new_row_entry";
			var data_obj  = { call_type:call_type,
												table:'job',
												id:id,
												meldnum:meldnum,
												addr1:addr1,
												addr2:addr2,
												bill:bill,
												datedisplay:datedisplay, };

			$.post(ajax_url, data_obj, function(data) 
			{ 
				var d1 = JSON.parse(data);
				if(d1.status == "success")
				{
					if(action=="add"){
						window.location.href="http://localhost/tito/jobdetail.php?jobid="+d1.msg;
					}else{
						tableRowElement.getElementsByClassName('id')[0].innerHTML          = id;
						tableRowElement.getElementsByClassName('meldnum')[0].innerHTML     = meldnum;
						tableRowElement.getElementsByClassName('addr1')[0].innerHTML       = addr1;	
						tableRowElement.getElementsByClassName('addr2')[0].innerHTML       = addr2;
						tableRowElement.getElementsByClassName('bill')[0].innerHTML        = parseFloat(bill).toFixed(2);	
						tableRowElement.getElementsByClassName('datedisplay')[0].innerHTML = datedisplay;	
						location.reload(true);	
					}			
				}
			});	
			clearAllCB(); 
			closeModal();        
		}

		function openModal() {
			document.getElementById("backdrop").style.display     = "block";
			document.getElementById("exampleModal").style.display = "block";
			document.getElementById("exampleModal").classList.add("show");
		}

		function closeModal() {    		
			document.getElementById("backdrop").style.display     = "none";
			document.getElementById("exampleModal").style.display = "none";
			document.getElementById("exampleModal").classList.remove("show");
		}    

		function removeRow(current) {
			let text = "Are you sure you want to remove this row?";
			if (confirm(text) == true) {
				tableRowElement = current.parentElement.parentElement;
				const id        = tableRowElement.getElementsByClassName('id')[0].innerHTML;
				var data_obj    = { call_type:'delete_row_entry',
														table:'job',
														id:id,
														paid:'n' };

				$.post(ajax_url, data_obj, function(data) 
				{ 
					var d1 = JSON.parse(data); 
					if(d1.status == "error")
					{
						alert("Failed to delete!");	
					}
					else if(d1.status == "success")
					{
						current.parentElement.parentElement.remove();
					}
				});
			}   
			clearAllCB(); 
			closeModal();      
		}

		function paid(current) {
			let text = "Paid jobs will be removed from display table. Do you still want to proceed?";
			if (confirm(text) == true) {
				tableRowElement = current.parentElement.parentElement;
				const id        = tableRowElement.getElementsByClassName('id')[0].innerHTML;
				var data_obj    = { call_type:'delete_row_entry',
														table:'job',
														id:id,
														paid:'y' };

				$.post(ajax_url, data_obj, function(data) 
				{ 
					var d1 = JSON.parse(data); 
					if(d1.status == "error")
					{
						alert("Failed to delete!");	
					}
					else if(d1.status == "success")
					{
						current.parentElement.parentElement.remove();
					}
				});
			}   
			clearAllCB(); 
			closeModal();      
		}

		function sortTable(n) {
			var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
			table = document.getElementById("tbl_job");
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
			input  = document.getElementById("jobsInput");
			filter = input.value.toUpperCase();
			table  = document.getElementById("tbl_job");
			tr     = table.getElementsByTagName("tr");
			for (i = 0; i < tr.length; i++) {
				if(searchVal=='1'){
					td = tr[i].getElementsByTagName("td")[2]; //id
				}else if(searchVal=='2'){
					td = tr[i].getElementsByTagName("td")[3]; //meld number
				}else{
					td = tr[i].getElementsByTagName("td")[4]; //address
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
			}			
		}

		function toggleSel() {
	    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
	    let total      = gtotal = profit = gprofit = 0;

	    table = document.getElementById("tbl_job");
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
			table  = document.getElementById("tbl_job");
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
	    table  = document.getElementById("tbl_job");
			tr     = table.getElementsByTagName("tr");
			table.rows[tr.length-1].cells.namedItem("grandTotal").innerHTML = 0;
		}

		function selectSearch() {
			var jobsInputSearch = document.getElementById("jobsInput");
			var textValue= $('#sel-search').val().split('|')[1];

			jobsInputSearch.placeholder = textValue;
		}

		function print() {

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