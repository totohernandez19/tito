<?php
    //include connection file
    include_once("classMain.php");
    require('classInvoice.php');

    $jobid  = isset($_GET['jobid']) ? $_GET['jobid']:"";
    $status = isset($_GET['status']) ? $_GET['status']:"";

    $db    = new dbObj();
    $con   = $db->getConnstring();
    $pdf   = new PDF_Invoice( 'P', 'mm', 'A4' );

    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->addSecondHeader($con,$jobid);    

    $y = $startY = 105;    
    
    $i    = $numR = 1;   //SHOULD START WITH 1 FOR ROW #
    $line = $discline = array();
    $job  = $qty  = $price = $tot1 = " ";
    $sub  = $bill = $prod  = $tot  = 0;

    $qry1  = "SELECT meldnum, bill FROM job
             WHERE id = '$jobid' AND active = 'y';";
    $res1  = $con->query($qry1);
    while ($row1  = $res1->fetch_row()) {  
      $bill    = $row1[1];  
      $meldnum = $row1[0];    
    }

    $qry  = "SELECT id, jobid, job, qty, price FROM jobdet
             WHERE jobid = '$jobid' AND active = 'y';";
    $res  = $con->query($qry);
    $num  = mysqli_num_rows($res);
    while ($row  = $res->fetch_row()) { 
      if($row[2]=="**Discount**"){
        $job   = $row[2];
        $qty   = $row[3];
        $price = number_format($row[4], 2, '.', '');
        $tot1  = number_format($prod, 2, '.', '');
      }else{
        $prod  = $row[3]*$row[4];
        $sub  += $prod;
        $line  = array("#"                => $i,
                       "JOB DESCRIPTION"  => $row[2],
                       "QTY"              => $row[3],
                       "PRICE"            => number_format($row[4], 2, '.', ''),
                       "TOTAL"            => number_format($prod, 2, '.', ''));
        $y   += 2;  
        $size = $pdf->addLine( $y, $line );
        $y   += $size + 2;    
        $pdf->Line(10,$y,200,$y); //separates row
        $y   += 2; 
        $i++;     //number of rows shown in the first column
        $numR++;  //number of rows per page
      }

      $pdf->addColLines($y-11,$y-2); //column separator for every row

      //IF ROWS GREATER 14, ADD NEW PAGE
      if($num>14 && $i>14){
        $num  -= $i; //store number of total rows
        $numR -= $i; //store number of rows per page
        $pdf->bottomPage();
        $pdf->AddPage();
        $pdf->addSecondHeader($con,$jobid);
        $y=105;
      }
    }   

    // IF TOTAL NUMBER OF ROWS LESS THAN MAX ROWS, ADD BLANK ROWS
    $currentY   = $pdf->GetY();
    list($i,$y) = $pdf->addBlankRow($job,$i,$y,$currentY);

    // IF THERE'S NO DISCOUNT MAX ROW IS 14 ELSE 13
    $m = ($job==" ") ? 14:13;

    // ADD PAGE IF TOTAL ROWS GREATER THAN 13
    if($numR>$m){
      $pdf->bottomPage();
      $pdf->AddPage();
      $pdf->addSecondHeader($con,$meldnum);
      $y=105;
    }

    // ADD BLANK ROW
    if($numR<15){
      list($i,$y) = $pdf->addBlankRow($job,$i,$y,$currentY);
    }

    // IF WITH DISCOUNT, PUT AT THE END ROW
    if(($job!=" " && $y==222) || ($job!=" " && $num==13)){
      $sub  += ($qty*$price);
      $line  = array("#"                => $i,
                     "JOB DESCRIPTION"  => $job,
                     "QTY"              => $qty,
                     "PRICE"            => $price,
                     "TOTAL"            => ($qty*$price));
      list($i,$y) = $pdf->addDiscountRow($i,$y,$line);
    }    
    // --- END OF DISCOUNT ROW ---

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

    // -- MARK UP CALCULATION --
    if($bill>=0 && $bill<=80){
        $bill += $bill * (40/100); 
    }else if($bill>=80.01 && $bill<=100){
        $bill += $bill * (30/100); 
    }else if($bill>=100.01){
        $bill += $bill * (25/100); 
    }

    $tot = $sub + $bill;    
    $pdf->bottomCalculation($y-2,number_format($sub,2),number_format($bill,2),number_format($tot,2));    

    $pdf->bottomPage();
    if($status=='dl'){
      $fileName = "C:/Users/TOTO/Desktop/Importante trabajo/por enviar/".$meldnum.".pdf";
      $pdf->Output('F', $fileName);
      header('Location: http://localhost/tito/joblist.php');
    }else{
      $pdf->Output();
    }
    
?>
