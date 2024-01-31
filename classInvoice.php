<?php
	require('lib/fpdf.php');

	class PDF_Invoice extends FPDF
	{
		// private variables
		var $colonnes;
		var $format;
		var $angle=0;

		//Page Header
		function Header()
		{
		   $this->Image('img/logo.png',150,-1,40);
		   $this->SetFont('Arial','B',50);
		   // Move to the right
		   $this->Cell(5);
		   // Title
		   $this->Cell(70,30,'INVOICE',0,0,'C');
		   // Line break
		   $this->Ln(40);
		}

		//Second Header
		function addSecondHeader($con,$jobid)
		{
			//DARK BLUE  
		  $this->SetFillColor(15,89,210);
	    $this->Rect(10,35,190,8,'FD'); 
	    $this->Rect(10,63,190,8,'FD'); 
	    $this->Rect(10,95,190,8,'F');
	      
	    //LIGHT BLUE  
	    $this->SetFillColor(231,238,250);
	    $this->Rect(10,43,190,20,'FD');  
	    $this->Rect(10,71,190,24,'FD');	    
	    
	    $qry  = "SELECT * FROM job WHERE id = '$jobid' AND active = 'y';";
	    $res  = $con->query($qry);
	    while ($row  = $res->fetch_row()) {
        //TENANT INFO
        $this->addInfo( "INVOICE NUMBER (MELD #):  ",$row[1],
                       $row[2]."\n".
                       $row[3]."\n",15,47);

        $bill        = $row[4];
        $displaydate = $row[7];
	    }    

	    //$currentdate = date('F d, Y');
	    $this->addInfo( "INVOICE DATE: ".date('F d, Y',strtotime($displaydate)),"",
	                   " ",120,47);

	    //COMPANY INFO
	    $this->addInfo( "VENDOR: YOEL HERNANDEZ","",
	                   "5150 Boggy Creek Rd, J33\n" .
	                   "St. Cloud, FL 34771\n".
	                   "407-9533297",120,75);
	    //CLIENT INFO
	    $this->addInfo("BILL TO: BELMONT MANAGEMENT GROUP","",
	                  "1133 Luisiana Ave\n".
	                  "Winter Park Fl 32789\n".
	                  "407-7450696",15,75);

	    $cols=array( "#"                => 10,
	                 "JOB DESCRIPTION"  => 110,
	                 "QTY"              => 15,
	                 "PRICE"            => 25,
	                 "TOTAL"            => 30);

	    $this->addCols( $cols);

	    $cols=array( "#"                => "C",
	                 "JOB DESCRIPTION"  => "L",
	                 "QTY"              => "C",
	                 "PRICE"            => "R",
	                 "TOTAL"            => "R" );

	    $this->addLineFormat($cols);
	    $this->addLineFormat($cols);

	    $this->Line(10,103,200,103);  //line under table header
    	$this->addColLines(95,103);   //first row
		}

		// Add Blank Rows
		function addBlankRow($job,$i,$y,$currentY)
		{
		  $lastRow  = ($job==" ") ? 222:213;
		  $m = ($job==" ") ? 14:13;
	    $n = $lastRow-($currentY/8);
	    for($z=0; $z<$n; $z++){
	      if($this->GetY()<$lastRow && fmod($i,13)<$m){
	        $line  = array("#"                => $i,
	                       "JOB DESCRIPTION"  => " ",
	                       "QTY"              => " ",
	                       "PRICE"            => " ",
	                       "TOTAL"            => " ");
	        $y   += 2;  
	        $size = $this->addLine( $y, $line );
	        $y   += $size + 2;    
	        $this->Line(10,$y,200,$y); //separates row	        
    			$this->addColLines($y-9,$y);
	        $y   += 2; 
	        $i++;
	      }
	    } 
	    return array( $i, $y );
		} 

		// Add Discount Row
		function addDiscountRow($i,$y,$line)
		{		  
	      $y   += 2;  
	      $this->SetTextColor(0,0,255);
	      $size = $this->addLine( $y, $line );
	      $this->SetTextColor(0,0,0);
	      $y   += $size + 2;    
	      $this->Line(10,$y,200,$y); //separates row
	      $y   += 2; 
	      $i++;

	      return array( $i, $y );
		} 

		// ADD COLUMN LINES
		function addColLines($y1,$y2)
		{
		  //line separating columns
			$this->Line(10,$y1,10,$y2);  
	    $this->Line(20,$y1,20,$y2); 
	    $this->Line(130,$y1,130,$y2); 
	    $this->Line(145,$y1,145,$y2); 
	    $this->Line(170,$y1,170,$y2);
			$this->Line(200,$y1,200,$y2);
		}   

		// Page footer
		function Footer()
		{
		   // Position at 1.5 cm from bottom
		   $this->SetY(-15);
		   // Arial italic 8
		   $this->SetFont('Arial','I',8);
		   // Page number
		   $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
		}   

		function bottomCalculation($y,$sub,$bill,$tot)
		{			   			
		   $this->Rect(130,$y,70,24);

		   $this->SetXY(120,$y);
		   $this->Cell(45,8,"Invoice Subtotal",0,0,'R');
		   $this->SetXY(170,$y);
		   $this->Cell(28,8,$sub,0,0,'R');
		   $y+=8;
		   $this->Line(130,$y,200,$y); 

		   $this->SetXY(120,$y);	
		   $this->Cell(45,8,"Materials",0,0,'R');
		   $this->SetXY(170,$y);
		   $this->Cell(28,8,$bill,0,0,'R');
		   $y+=8;
		   $this->Line(130,$y,200,$y); 

		   $this->SetTextColor(255,0,0);
		   $this->SetFont('Arial','B',12);
		   $this->SetXY(120,$y);	
		   $this->Cell(45,8,"TOTAL",0,0,'R');
		   $this->SetXY(170,$y);
		   $this->Cell(28,8,$tot,0,0,'R');
		} 

		function bottomPage()
		{	
		   $y=256; 
		   $this->Rect(10,255,190,23); 
		   $this->SetY($y);		   
		   $this->SetTextColor(0,0,0);
		   $this->SetFont('Arial','I',10);
		   $this->Cell(0,10,"Please make all checks payable to YOEL HERNANDEZ.",0,0,'C');
		   $y+=5;
		   $this->SetY($y);
		   $this->Cell(0,10,"Total due in 15 days. Overdue accounts subject to a service charge of 2% per month.",0,0,'C');
		   $this->SetTextColor(255,0,0);
		   $this->SetFont('Arial','B',12);
		   $y+=5;
		   $this->SetY($y);
		   $this->Cell(0,10,"Thank you for your business!!!",0,0,'C');
		   $this->SetTextColor(0,0,0);
		} 

		// Add Info		
		function addInfo( $nom1,$nom2,$adress,$x1,$y1)
		{
			$this->SetXY( $x1, $y1 );
			$this->SetFont('Arial','B',12);
			$length = $this->GetStringWidth( $nom1 );			
			$this->Cell( $length, 2, $nom1);
			$this->SetTextColor( 255,0,0);
			$length = $this->GetStringWidth( $nom2 );
			$this->Cell( $length, 2, $nom2);
			$this->SetXY( $x1, $y1 + 4 );
			$this->SetFont('Arial','',11);
			$length = $this->GetStringWidth( $adress );
			$lignes = $this->sizeOfText( $adress, $length);
			$this->SetTextColor( 0,0,0);
			$this->MultiCell($length, 4, $adress);
		}

		function _endpage()
		{
			if($this->angle!=0)
			{
				$this->angle=0;
				$this->_out('Q');
			}
			parent::_endpage();
		}

		// public functions
		function sizeOfText( $texte, $largeur )
		{
			$index    = 0;
			$nb_lines = 0;
			$loop     = TRUE;
			while ( $loop )
			{
				$pos = strpos($texte, "\n");
				if (!$pos)
				{
					$loop  = FALSE;
					$ligne = $texte;
				}
				else
				{
					$ligne  = substr( $texte, $index, $pos);
					$texte  = substr( $texte, $pos+1 );
				}
				$length = floor( $this->GetStringWidth( $ligne ) );
				$res    = 1 + floor( $length / $largeur) ;
				$nb_lines += $res;
			}
			return $nb_lines;
		}

		function addCols( $tab )
		{
			global $colonnes;
			
			$r1  = 10;
			$r2  = $this->w - ($r1 * 2) ;
			$y1  = 95;
			$y2  = $this->h - 80 - $y1;
			$this->SetXY( $r1, $y1 );
			$colX     = $r1;
			$colonnes = $tab;
			foreach ( $tab as $lib => $pos )
			{				
				$this->SetXY( $colX, $y1+4 );
				$this->Cell( $pos, 1, $lib, 0, 0, "C");
				$colX += $pos;
			}
		}

		function addLineFormat( $tab )
		{
			global $format, $colonnes;
			
			foreach ( $colonnes as $lib => $pos )
			{
				if ( isset( $tab["$lib"] ) )
					$format[ $lib ] = $tab["$lib"];
			}
		}
		
		function addLine( $ligne, $tab )
		{
			global $colonnes, $format;

			$ordonnee     = 10;
			$maxSize      = $ligne;

			foreach ( $colonnes as $lib => $pos )
			{
				$longCell  = $pos -2;
				$texte     = $tab[ $lib ];
				$length    = $this->GetStringWidth( $texte );
				$tailleTexte = $this->sizeOfText( $texte, $length );
				$formText  = $format[ $lib ];
				$this->SetXY( $ordonnee, $ligne-1);
				$this->MultiCell( $longCell, 4 , $texte, 0, $formText);
				if ( $maxSize < ($this->GetY()  ) )
					$maxSize = $this->GetY() ;
				$ordonnee += $pos;
			}
			return ( $maxSize - $ligne );
		}
	}
?>
