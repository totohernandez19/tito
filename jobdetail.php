<?php
	// Start the session
	session_start();
	include_once dirname(__FILE__).'/inc/config.php';

	$jobid = $_GET['jobid'];
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
		.ui-class { top: 50%; left: 50%; }
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
		<h1 class="topcenter_h1">  JOB DETAIL LIST </h1>
	</p>

	<!-- MAIN CENTER PANEL -->	
	<div id="center_panel">
		<div>
			<input type="text" style="margin: 10px 15px;" id="jobdetInput" onkeyup="myFunction()" placeholder="Search for Job Detail.." title="Type in Job Detail">
			<div id="show_dropdown" class="dropdown" style="display: none">
				<button class="dropbtn">SHOW</button>
				<div class="dropdown-content">
					<a href="#">Active</a>		
				</div>
			</div>
		</div>
		<div id="table-wrapper">
			<div id="table-scroll">	
				<table id="tbl_jobdet" style="align-self: center;">
					<tr>	
						<th style='display:none;'></th>
						<th style='display:none;'></th>		
						<th onclick="sortTable(2)">JOB DETAIL</th>
						<th onclick="sortTable(3)">PRICE</th>
						<th onclick="sortTable(4)">QTY</th>
						<th onclick="sortTable(5)">TOTAL</th>	
						<th>EDIT</th>
						<th>DEL</th>					
					</tr>
					<?php
					include_once("classMain.php");

					$db      = new dbObj();
					$con     = $db->getConnstring();		  

					$qry = "SELECT id, jobid, job, price, qty FROM jobdet
									WHERE jobid = '$jobid' AND active = 'y' ORDER BY job ASC; ";
					$res = $con->query($qry);
					$n   = $prod = $tot = 0;
					while ($row = $res->fetch_row()) {
						echo "<tr>";
						$prod = $row[3]*$row[4];
						echo "<td class='id' style='display:none;'>$row[0]</td>";	
						echo "<td class='jobid' style='display:none;'>$row[1]</td>";				    		    	  
						echo "<td class='job' style='text-align:left'>$row[2]</td>";
						echo "<td class='price' style='text-align:right'>$row[3]</td>";	
						echo "<td class='qty' style='text-align:center'>$row[4]</td>";
						echo "<td class='total' style='text-align:right'>".number_format($prod, 2)."</td>";
						echo "<td><a href='#' onclick='toggleModal(this,$n)'>
						<img src='img/editIcon.png' style='width:40px;height:40px;'></a></td>";
						echo "<td><a href='#' onclick='removeRow(this)'>
						<img src='img/deleteIcon.png' style='width:40px;height:40px;'></a></td>";     	    
						echo "</tr>";
						$n++;
						$tot += $prod;
					}
					
					echo "<tr>
						<td colspan=1 style='color:red'>TOTAL</td>
						<td colspan=3 style='color:red; text-align:right'>".number_format($tot, 2)."</td>
						<td colspan=2></td>
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
			</div>
		</div>		
	</div>

	<!-- Creating a popup modal -->
	<div class="modal" tabindex="-1" role="dialog" id="exampleModal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">EDIT JOB DETAIL</h5>
					<button type="button" onclick="closeModal()" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group" style='display:none;'>
						<label for="id">ID</label>
						<input type="text" id="id" class="form-control">
					</div>

					<div class="form-group" style='display:none;'>
						<label for="jobid">JOB ID</label>
						<input type="text" id="jobid" class="form-control">
					</div>

					<div class="form-group" style="width:100%;">
						<label for="sel">SELECT JOB</label>
						<br>
						<select  id="sel" class="form-select" style="color: black;">
							<?php 
								include_once("classMain.php");

								$db   = new dbObj();
								$con  = $db->getConnstring();

								$qry = "SELECT id,job,price FROM pricelist 
								        WHERE active = 'y' ORDER BY job ASC";
								$res = $con->query($qry);
								$n = 0;
								echo "<option value='0' data-id='0' data-price='0'> -- SELECT -- </option>";
								while ($row = $res->fetch_row()) {
							    echo "<option value='".$row[1]."' data-id='".$row[0]."' data-price='".$row[2]."'>".$row[1]."</option>";							    
							  }
						 	?> 
						 	<script>
						    $(function() { 
								  $('#sel').change(function() { 
								    $('#job').val($(this).val());
								    $('#price').val($(this).find(':selected').data('price'));
										document.getElementById("priceCB").checked = false;
								  });
								});
							</script>
						</select>
					</div>

					<div class="form-group" style="width:100%;">
						<label for="job">JOB</label>
						<input type="text" id="job" class="form-control">
					</div>

					<div>
						<div class="form-group" style="width:10%; float: left;">
							<label for="priceCB"> 70 </label>
							<input id='priceCB' type="checkbox" onClick="toggleSel()" 
							style="width:30px; height: 30px; margin-top: 18px;">
						</div>
						<div class="form-group" style="width:50%; float: left;">
							<label for="price">PRICE</label>
							<input type="text" id="price" class="form-control" style="text-align: right;">
						</div>	
						<div class="form-group" style="width:15%; float: left; margin-left: 30px;">
							<label for="qty">QTY</label>
							<input type="text" id="qty" class="form-control" style="text-align: right;">
						</div>
					</div>					

					<div class="form-group" style='display:none;'>
						<label for="total">TOTAL</label>
						<input type="text" id="total" class="form-control">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" id="btn-popup-save" onclick="saveInfo()">Save changes</button>
					<button type="button" class="btn btn-secondary" id="btn-popup-close" onclick="closeModal()">Close</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-backdrop fade show" id="backdrop" style="display: none;"></div>

	<script>
		let tableRowElement;
		var ajax_url  = "<?php echo APPURL;?>/ajax.php" ;
		var action    = "";
		var jobidpass = <?php echo $jobid; ?> 	

		function toggleModal(element) {

			tableRowElement = element.parentElement.parentElement;
			const id        = tableRowElement.getElementsByClassName('id')[0].innerHTML;
			const jobid     = tableRowElement.getElementsByClassName('jobid')[0].innerHTML;
			const job       = tableRowElement.getElementsByClassName('job')[0].innerHTML;
			const price     = tableRowElement.getElementsByClassName('price')[0].innerHTML;
			const qty       = tableRowElement.getElementsByClassName('qty')[0].innerHTML;
			const total     = tableRowElement.getElementsByClassName('total')[0].innerHTML;

			document.getElementById('id').value         = id;
			document.getElementById('jobid').value      = jobid;
			document.getElementById('job').value        = job;
			document.getElementById('price').value      = price;
			document.getElementById('qty').value        = qty;
			document.getElementById('total').value      = total;
			document.getElementById('sel').value        = 0;

			action = "edit";
			openModal();
		}

		function addRow() {
			document.getElementById('id').value         = "";
			document.getElementById('jobid').value      = "";
			document.getElementById('job').value        = "";
			document.getElementById('price').value      = "";
			document.getElementById('qty').value        = 1;
			document.getElementById('total').value      = "";
			document.getElementById('sel').value        = 0;

			action = "add";
			openModal();
		}

		function saveInfo() {
			const id        = (action=='edit') ? document.getElementById('id').value : '';
			const jobid     = (action=='edit') ? document.getElementById('jobid').value : jobidpass;
			const job       = document.getElementById('job').value;
			const price     = document.getElementById('price').value;
			const qty       = document.getElementById('qty').value;
			var prod        = price * qty;

			document.getElementById('id').value        = id;
			document.getElementById('jobid').value     = jobid;
			document.getElementById('job').value       = job;
			document.getElementById('price').value     = price;	
			document.getElementById('qty').value       = qty;

			var call_type = (action=='edit') ? "row_entry" : "new_row_entry";
			var data_obj  = { call_type:call_type,
												table:'jobdet',
												id:id,
												jobid:jobid,
												job:job,
												price:price,
												qty:qty, };

			$.post(ajax_url, data_obj, function(data) 
			{ 
				var d1 = JSON.parse(data);

				if(d1.status == "success")
				{
					if(action=="add"){
						location.reload(true); 
					}else{
						tableRowElement.getElementsByClassName('id')[0].innerHTML        = id;
						tableRowElement.getElementsByClassName('jobid')[0].innerHTML     = jobid;
						tableRowElement.getElementsByClassName('job')[0].innerHTML       = job;
						tableRowElement.getElementsByClassName('price')[0].innerHTML     = parseFloat(price).toFixed(2);
						tableRowElement.getElementsByClassName('qty')[0].innerHTML       = qty;
						tableRowElement.getElementsByClassName('total')[0].innerHTML     = parseFloat(prod).toFixed(2);
					}					 
				}
			});	
			closeModal();        
		}

		function openModal() {
			document.getElementById("priceCB").checked            = false;
			document.getElementById("backdrop").style.display     = "block";
			document.getElementById("exampleModal").style.display = "block";
			document.getElementById("exampleModal").classList.add("show");
		}

		function closeModal() {    		
			document.getElementById("priceCB").checked            = false;
			document.getElementById("backdrop").style.display     = "none";
			document.getElementById("exampleModal").style.display = "none";
			document.getElementById("exampleModal").classList.remove("show");
		}    

		function removeRow(current) {
			let conf = "Are you sure you want to remove this row?";
			if (confirm(conf) == true) {

				tableRowElement = current.parentElement.parentElement;
				const id        = tableRowElement.getElementsByClassName('id')[0].innerHTML;
				var data_obj    = { call_type:'delete_row_entry',
														table:'jobdet',
														id:id, };

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
		}

		function sortTable(n) {
			var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
			table = document.getElementById("tbl_jobdet");
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
				for (i = 1; i < (rows.length - 1); i++) {
		      //start by saying there should be no switching:
					shouldSwitch = false;
		      /*Get the two elements you want to compare,
		      one from current row and one from the next:*/

					x = rows[i].getElementsByTagName("TD")[n];
					y = rows[i + 1].getElementsByTagName("TD")[n];

					var xContent = (isNaN(x.innerHTML)) 
					? (x.innerHTML.toLowerCase() === '-')
					? 0 : x.innerHTML.toLowerCase()
					: parseFloat(x.innerHTML);
					var yContent = (isNaN(y.innerHTML)) 
					? (y.innerHTML.toLowerCase() === '-')
					? 0 : y.innerHTML.toLowerCase()
					: parseFloat(y.innerHTML);

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

		function myFunction() {
			var input, filter, table, tr, td, i, txtValue;
			input  = document.getElementById("jobdetInput");
			filter = input.value.toUpperCase();
			table  = document.getElementById("tbl_jobdet");
			tr     = table.getElementsByTagName("tr");
			
			for (i = 0; i < tr.length; i++) {
				td = tr[i].getElementsByTagName("td")[3];
				if (td) {
					txtValue = td.textContent || td.innerText;
					if (txtValue.toUpperCase().indexOf(filter) > -1) {
						tr[i].style.display = "";
					} else {
						tr[i].style.display = "none";
					}
				}       
			}
		}	

		function toggleSel() {
	    if(document.getElementById("priceCB").checked == true){
	    	document.getElementById("price").value = "70.00";
	    }else{
	    	document.getElementById("price").value = "0.00";
	    }	
	  }
	</script>

	<!-- LEFT SIDE BAR MENU -->
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

</body>
<footer id="footer">Maybelle Hernandez 2022 &copy; Copyright</footer>
</html>