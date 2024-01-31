<?php
    //include connection file
    include_once("classMain.php");
    require('classInvoice.php');

    $jobid  = isset($_GET['jobid']) ? $_GET['jobid']:"";
    $status = isset($_GET['status']) ? $_GET['status']:"";

    $db    = new dbObj();
    $con   = $db->getConnstring();
    $pdf   = new PDF_Invoice( 'P', 'mm', 'A4' );

    $pdf->AddPage();

    //DARK BLUE  
    $pdf->SetFillColor(15,89,210);
    $pdf->Rect(10,40,190,8,'FD'); 
    $pdf->Rect(10,75,190,8,'FD'); 
    $pdf->Rect(10,110,190,8,'F');
      
    //LIGHT BLUE  
    $pdf->SetFillColor(231,238,250);
    $pdf->Rect(10,48,190,27,'FD');  
    $pdf->Rect(10,83,190,27,'FD');     

    $y = $startY = 120;    
    
    $i    = $numR = 1;   //SHOULD START WITH 1 FOR ROW #
    $line = array();

    $qry  = "SELECT * FROM markuplist WHERE active = 'y';";
    $res  = $con->query($qry);
    $num  = mysqli_num_rows($res);
    while ($row  = $res->fetch_row()) {  
      $pdf->addColLines($y-11,$y-2);
      if($num>=15 && $i>=15){
        $num  -= $i; //store number of total rows
        $numR -= $i; //store number of rows per page
        $pdf->bottomPage();
        $y=120;
      }
    }

    $endY = $pdf->GetY();
    //table outside vertical line
    $pdf->Line(10,$startY-2,10,$endY+2); 
    $pdf->Line(200,$startY-2,200,$endY+2); 
    //line separating columns
    $pdf->Line(20,$startY-10,20,$endY+2); 
    $pdf->Line(130,$startY-10,130,$endY+2); 
    $pdf->Line(145,$startY-10,145,$endY+2); 
    $pdf->Line(170,$startY-10,170,$endY+2);
    //vertical line at total
    $pdf->Line(170,$endY+2,170,$endY+26);

    $pdf->bottomPage();
    $pdf->Output();
    
?>
