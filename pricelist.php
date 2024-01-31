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
  		<li> MENU </li>
  		<li> ------------- </li>
			<li><a href="joblist.php"> MELD JOBS </a></li>
			<li><a href="report.php"> REPORT </a></li>
	  	<br><br>	
  		<li> ------------- </li>
			<li><a href="pricelist.php"> PRICE LIST </a></li>
	  	<li><a href="markuplist.php"> MARKUP LIST </a></li> 
  	</ul>
  </nav>

	<!-- TOP CENTER -->
	<p id="topcenter_panel">
		<h1 class="topcenter_h1"> PRICE LIST </h1>
	</p>

	<!-- MAIN CENTER PANEL -->	
	<div id="center_panel">
		<div>
			<input type="text" style="margin: 10px 15px;" id="priceInput" onkeyup="myFunction()" placeholder="Search for Job Description..." title="Type in Job Description">
			<div id="show_dropdown" class="dropdown" style="display: none">
				<button class="dropbtn">SHOW</button>
				<div class="dropdown-content">
					<a href="#">Active</a>		
				</div>
			</div>
		</div>
		<div id="table-wrapper">
			<div id="table-scroll">
				<table id='tbl_price' style="padding: 5px 150px; margin-right: 10px;">
					<tr>
						<th style='display:none;'></th>
						<th style='text-align:center' onclick="sortTable(1)">JOB DESCRIPTION</th>
						<th style='text-align:center' onclick="sortTable(2)">PRICE</th>
						<th style="text-align:center">EDIT</th>
						<th style="text-align:center">DEL</th>
					</tr>
					<?php
					include_once("classMain.php");

					$db   = new dbObj();
					$con  = $db->getConnstring();

					$qry = "SELECT id,job,price FROM pricelist WHERE active = 'y' ORDER BY job ASC;";
					$res = $con->query($qry);
					$n = 0;
					while ($row = $res->fetch_row()) {
						echo "<tr>";
						echo "<td class='id' style='display:none;'>$row[0]</td>";				    		    	  
						echo "<td class='job'>$row[1]</td>";
						echo "<td class='price'>".number_format($row[2], 2)."</td>";
						echo "<td><a href='#' onclick='toggleModal(this,$n)'><img src='img/editIcon.png' style='width:40px;height:40px;'></a></td>";
						echo "<td><a href='#' onclick='removeRow(this)'><img src='img/deleteIcon.png' style='width:40px;height:40px;'></a></td>";     
						echo "</tr>";
						$n++;
					}
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
		</div>		
	</div>

	<!-- Creating a popup modal -->
	<div class="modal" tabindex="-1" role="dialog" id="exampleModal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">PRICE LIST</h5>
					<button type="button" onclick="closeModal()" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group" style='display:none;'>
						<label for="id">ID</label>
						<input type="text" id="id" class="form-control">
					</div>

					<div class="form-group">
						<label for="job">JOB</label>
						<input type="text" id="job" class="form-control">
					</div>

					<div class="form-group">
						<label for="price">PRICE</label>
						<input type="text" id="price" class="form-control">
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

		function toggleModal(element) {

			tableRowElement = element.parentElement.parentElement;
			const id        = tableRowElement.getElementsByClassName('id')[0].innerHTML;
			const job       = tableRowElement.getElementsByClassName('job')[0].innerHTML;
			const price     = tableRowElement.getElementsByClassName('price')[0].innerHTML;

			document.getElementById('id').value    = id;
			document.getElementById('job').value   = job;
			document.getElementById('price').value = price;

			action = "edit";
			openModal();
		}

		function addRow() {
			document.getElementById('id').value    = "";
			document.getElementById('job').value   = "";
			document.getElementById('price').value = "";

			action = "add";
			openModal();
		}

		function saveInfo() {
			const id    = (action=='edit') ? document.getElementById('id').value : '';
			const job   = document.getElementById('job').value;
			const price = document.getElementById('price').value;

			document.getElementById('id').value    = id;
			document.getElementById('job').value   = job;
			document.getElementById('price').value = price;	

			var call_type = (action=='edit') ? "row_entry" : "new_row_entry";
			var data_obj  = { call_type:call_type,
												table:'pricelist',
												id:id,
												job:job,
												price:price, };

			$.post(ajax_url, data_obj, function(data) 
			{ 
				var d1 = JSON.parse(data);

				if(d1.status == "success")
				{
					if(action=="add"){
						location.reload(true); 
					}else{
						tableRowElement.getElementsByClassName('id')[0].innerHTML    = id;
						tableRowElement.getElementsByClassName('job')[0].innerHTML   = job;
						tableRowElement.getElementsByClassName('price')[0].innerHTML = parseFloat(price).toFixed(2);	
					}				
				}
			});	
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
			//let conf = "Are you sure you want to remove this row?";
			//if (confirm(conf) == true) {

				tableRowElement = current.parentElement.parentElement;
				const id        = tableRowElement.getElementsByClassName('id')[0].innerHTML;
				var data_obj    = { call_type:'delete_row_entry',
				table:'pricelist',
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
						alert("Successfully deleted!");		
					}
				});
			//} 
			closeModal();   
		}

		function sortTable(n) {
			var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
			table = document.getElementById("tbl_price");
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
			input  = document.getElementById("priceInput");
			filter = input.value.toUpperCase();
			table  = document.getElementById("tbl_price");
			tr     = table.getElementsByTagName("tr");
			for (i = 0; i < tr.length; i++) {
				td = tr[i].getElementsByTagName("td")[1];
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