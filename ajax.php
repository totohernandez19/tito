<?php

include_once dirname(__FILE__).'/inc/config.php';


//--->get all job > start
if(isset($_GET['call_type']) && $_GET['call_type'] =="get")
{
	$q1 = app_db()->select("SELECT * FROM job WHERE active = 'y';");
	echo json_encode($q1);
}
//--->get all job > end

//--->update a whole row  > start
if(isset($_POST['call_type']) && $_POST['call_type'] =="row_entry")
{		
	$table 	    = app_db()->CleanDBData($_POST['table']);
	if($table=="job"){
		$id 	      = app_db()->CleanDBData($_POST['id']);
		$meldnum      = app_db()->CleanDBData($_POST['meldnum']); 
		$addr1  	  = app_db()->CleanDBData($_POST['addr1']); 
		$addr2  	  = app_db()->CleanDBData($_POST['addr2']); 	
		$bill   	  = app_db()->CleanDBData($_POST['bill']); 
		$datedisplay  = app_db()->CleanDBData($_POST['datedisplay']); 	
	}else if($table=="pricelist"){
		$id 	    = app_db()->CleanDBData($_POST['id']);
		$job  	    = app_db()->CleanDBData($_POST['job']); 
		$price      = app_db()->CleanDBData($_POST['price']); 
	}else if($table=="jobdet"){
		$id 	    = app_db()->CleanDBData($_POST['id']);
		$jobid 	    = app_db()->CleanDBData($_POST['jobid']);
		$job  	    = app_db()->CleanDBData($_POST['job']); 
		$price      = app_db()->CleanDBData($_POST['price']); 
		$qty        = app_db()->CleanDBData($_POST['qty']); 
	}	
	
	$qry = "SELECT * FROM $table WHERE id='$id' AND active = 'y';";	
	$q1  = app_db()->select($qry);
	
	if($q1 < 1) 
	{
		//no record found in the database
		echo json_encode(array(
			'status' => 'error', 
			'msg'    => $qry.'-'.'no entries were found', 
		));
		die();
	}
	else if($q1 > 0) 
	{
		//found record in the database		 
		$strTableName = $table;
		if($table=="job"){
			$array_fields = array( 'meldnum' => strip_tags($meldnum),
													   'addr1'       => strip_tags($addr1),
													   'addr2'       => strip_tags($addr2),
													   'bill'        => strip_tags($bill),
													   'datedisplay' => strip_tags($datedisplay), );
		}else if($table=="pricelist"){
			$array_fields = array( 'job'     => strip_tags($job),
								   					 'price'   => strip_tags($price), );
		}else if($table=="jobdet"){
			$array_fields = array( 'jobid'   => strip_tags($jobid),
					                   'job'     => strip_tags($job),
													   'price'   => strip_tags($price),
													   'qty'     => strip_tags($qty), );
		}
		$array_where = array( 'id' => $id, );

		//Call it like this:  
		app_db()->Update($strTableName, $array_fields, $array_where);
		echo json_encode(array(
			'status' => 'success', 
			'msg'    => 'updated row entry', 
		));
		die();
	}
}
//--->update a whole row > end

//--->new row entry  > start
if(isset($_POST['call_type']) && $_POST['call_type'] =="new_row_entry")
{	
	$table 	    = app_db()->CleanDBData($_POST['table']);
	if($table=="job"){
		$id 	     = app_db()->CleanDBData($_POST['id']);
		$meldnum     = app_db()->CleanDBData($_POST['meldnum']); 
		$addr1  	 = app_db()->CleanDBData($_POST['addr1']); 
		$addr2  	 = app_db()->CleanDBData($_POST['addr2']); 
		$bill   	 = app_db()->CleanDBData($_POST['bill']); 	
		$datedisplay = app_db()->CleanDBData($_POST['datedisplay']); 	
	}else if($table=="pricelist"){
		$id 	    = app_db()->CleanDBData($_POST['id']);
		$job  	    = app_db()->CleanDBData($_POST['job']); 
		$price      = app_db()->CleanDBData($_POST['price']); 
	}else if($table=="jobdet"){
		$id 	    = app_db()->CleanDBData($_POST['id']);
		$jobid 	    = app_db()->CleanDBData($_POST['jobid']);
		$job  	    = app_db()->CleanDBData($_POST['job']); 
		$price      = app_db()->CleanDBData($_POST['price']); 
		$qty        = app_db()->CleanDBData($_POST['qty']); 
	}	
	
	$q1 = app_db()->select("SELECT * FROM $table WHERE id='$id' AND active = 'y';");
	if($q1 < 1) 
	{
		//add new row
		$strTableName = $table;
		if($table=="job"){
			$insert_arrays = array( 'meldnum' => strip_tags($meldnum),
															'addr1'        => strip_tags($addr1),
															'addr2'        => strip_tags($addr2),
															'bill'         => strip_tags($bill),
															'datedisplay'  => strip_tags($datedisplay), );
		}else if($table=="pricelist"){
			$insert_arrays = array( 'job'     => strip_tags($job),
									'price'   => strip_tags($price), );
		}else if($table=="jobdet"){
			$insert_arrays = array( 'job'     => strip_tags($job),
					                'jobid'   => strip_tags($jobid),
									'price'   => strip_tags($price), 
									'qty'     => strip_tags($qty), );
		}

		//Call it like this:
		$new_id = app_db()->Insert($strTableName, $insert_arrays);
		echo json_encode(array(
			'status' => 'success', 
			'msg'    => $new_id, 
		));
		die();
	}	 
}
//--->new row entry  > end

// --- START DELETE ROW ---
if(isset($_POST['call_type']) && $_POST['call_type'] =="delete_row_entry")
{	

	$table  = app_db()->CleanDBData($_POST['table']);
	$id     = app_db()->CleanDBData($_POST['id']); 
	$paid   = app_db()->CleanDBData($_POST['paid']); 
	$qry    = "SELECT * FROM $table WHERE id='$id' AND active = 'y';";
	$q1     = app_db()->select($qry);

	if($q1 > 0) 
	{
		//found a row to be deleted
		$strTableName = $table;
		$array_where  = array('id'   => $id, );
		$array_where1 = "";

		if($strTableName=="job"){
			$array_where1  = array('jobid' => $id);
		}		

		//Call it like this:
		app_db()->Delete($strTableName,$array_where,$array_where1,$paid);

		echo json_encode(array(
			'status' => 'success', 
			'msg'    => 'deleted entry', 
		));		
	}else{
		echo json_encode(array(
			'status' => 'error', 
			'msg'    => $qry, 
		));
	}	
	die(); 
}
// --- END DELETE ROW ---

?>