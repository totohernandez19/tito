<?php
  $hostname     = "localhost";
  $username     = "root";
  $password     = "";
  $databasename = "db_mysite";

  // Create connection
  $conn = mysqli_connect($hostname, $username, $password,$databasename);
  // Check connection
  if (!$conn) {
      die("Unable to Connect database: " . mysqli_connect_error());
  }

  $db=$conn;

  // fetch query
  function fetch_data(){
    global $db;
    $query = "SELECT id,meldnum,addr1,addr2,bill,
              (SELECT SUM(price*qty) FROM jobdet WHERE jobid = job.id AND paid = 'y') AS total,
              DATE(datecreated) AS datec  
              FROM job WHERE paid = 'y' ORDER BY timestamp DESC;";
    $exec  = mysqli_query($db, $query);
    
    if(mysqli_num_rows($exec)>0){
      $row= mysqli_fetch_all($exec, MYSQLI_ASSOC);
      return $row;            
    }else{
      return $row=[];
    }
  }

  $fetchData = fetch_data();
  show_data($fetchData);

  function show_data($fetchData){
    echo '<table border="1">
          <tr>
              <th>S.N</th>
              <th>Full Name</th>
              <th>Email Address</th>
              <th>City</th>
              <th>Country</th>
              <th>Edit</th>
              <th>Delete</th>
          </tr>';
    if(count($fetchData)>0){
      $sn=1;
      foreach($fetchData as $data){ 
        echo "<tr>
                <td>".$sn."</td>
                <td>".$data['fullName']."</td>
                <td>".$data['emailAddress']."</td>
                <td>".$data['city']."</td>
                <td>".$data['country']."</td>
              </tr>";
       
        $sn++; 
      }
    }else{         
      echo "<tr>
                <td colspan='7'>No Data Found</td>
            </tr>"; 
    }
    echo "</table>";
  }
?>