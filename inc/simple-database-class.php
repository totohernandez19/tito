<?php
  class SimpleDBClass
  {
    public $isConn; 
    
    //To show query error messages set on or to hide then set to off
    //For trouble shooting only
    public $ShowQryErrors = 'on'; //on or off

    //--->Connect to database - Start
    public function __construct( $db_conn = array('host' => 'localhost', 'user' => 'root','pass' => '','database' => 'db_mysite', ) )  
    {
      $host = isset($db_conn['host']) ? $db_conn['host'] : 'localhost' ;
      $user = isset($db_conn['user']) ? $db_conn['user'] : 'root' ;
      $pass = isset($db_conn['pass']) ? $db_conn['pass'] : '' ;

      $database = isset($db_conn['database']) ? $db_conn['database'] : die('no database set') ;

      // Create connection
      $connection = mysqli_connect($host, $user, $pass,$database);

      // Check connection
      if (!$connection) 
      {
        //echo ("conasfasdfas");
        die("Connection failed: " . mysqli_connect_error());
        return false;
      }
      if($connection)
      {
        //echo ("conneted");
        $this->isConn = $connection;
      }
    }
    //--->Connect to database - End


    /*
      Different 5 types of queries
      - Select: all rows
      - Insert: add row(s)
      - Update: update field(s)
      - Delete: delete row(s)
      - Qry: general purpose query
    */

    //--->Select - Start
    function Select($SQLStatement)
    {
      // Create connection
      $con =  $this->isConn;

      // Check connection
      if (!$con) 
      {
        die("Connection failed in Select function - " . mysqli_connect_error());
      }

      // Connection is made
      if ($con) 
      {
        $q = $con->query($SQLStatement);

        //Fail to run query.
        if(!$q)
        {
          //show error message
          if($this->ShowQryErrors == 'on')
          {
            die( mysqli_error($con) );  
          }        
        }

        $row = $q->num_rows;

        //no rows found
        if($row <1)
        {
          $result = $row;  
        }
        //only one row of data
        else if($row == 1)
        {
          $result = array($q->fetch_assoc());
        }
        //multiple rows
        else if( $row >1)
        {
          $d1 = array( $q->fetch_assoc());
          
          $d2= array();
          while ($row = $q->fetch_assoc()) 
          {
            $d2[] = $row;
          }
          //merger array to get all rows
          $result = array_merge($d1 , $d2); 
        }
        //Will return a row data
        return $result;
        }
    }
    //--->Select - End


    //--->Insert - Start  
    function Insert($TableName, $row_arrays = array()  ) 
    { 
      foreach( array_keys($row_arrays) as $key ) 
      {
        $columns[] = "$key";
        $values[]  = "'" .  $row_arrays[$key] . "'";
      }
      //Get columns and values
      $columns = implode(",", $columns);
      $values  = implode(",", $values);

      $sql = "INSERT INTO $TableName ($columns) VALUES ($values)";      
      $con =  $this->isConn;

      // Check connection
      if (!$con) 
      {
        die("Connection failed in query function - " . mysqli_connect_error());
      }

      if($con)
      {
        $q = $con->query($sql);
        if(!$q)
        {  
          //show error message
          if($this->ShowQryErrors == 'on')
          {
            die( mysqli_error($con) );  
          }  
          $result =  0;
        }
        if($q)
        {
          //Will give the last inserted id
          $result =  $con->insert_id;      
        }
        
        //Will return a row data
        return $result; 
      }
    }
    //--->Insert - End

    //--->Update - Start
    function Update($strTableName, $array_fields, $array_where)
    { 
      //Get the update fields and value
      foreach($array_fields as $key=>$value) 
      {
        if($key) 
        {
          $field_update[] = " $key='$value'";
        }
      }
      $fields_update = implode( ',', $field_update );

      //Get where fields and value
      foreach($array_where as $key=>$value) 
      {
        if($key) 
        {
          $field_where[] = " $key='$value'";
        }
      }
      $fields_where = implode( ' and ', $field_where );
      $SQLStatement = "UPDATE $strTableName  SET $fields_update WHERE $fields_where ";
      $con          =  $this->isConn;

      // Check connection
      if (!$con) 
      {
        die("Connection failed in query function - " . mysqli_connect_error());
      }

      if($con)
      {
        $q = $con->query($SQLStatement);
        if(!$q)
        { 
          //show error message
          if($this->ShowQryErrors == 'on')
          {
            die( mysqli_error($con) );  
          } 

          $result =  0;
        }
        if($q)
        {  
          $result = 1;
        }
        
        //Will return a row data
        return $result; 
      }
    }
    //--->Update - End

    //--->Delete - Start
    function Delete($strTableName,$array_where,$array_where1,$paid)
    {
      //Get where fields and value
      foreach($array_where as $key=>$value) 
      {
        if($key) 
        {
          $field_where[] = " $key='$value' ";
        }
      }

      if($array_where1!=""){
        foreach($array_where1 as $key1=>$value1) 
        {
          if($key1) 
          {
            $field_where1[] = " $key1='$value1' ";
          }
        }
      }

      $updateField = ($paid=='y' && ($strTableName=='job' || $strTableName=='jobdet')) ? 
                     ", paid = 'y' ": "";
      
      //SET INACTIVE INSTEAD OF DELETE
      $fields_where = implode( ' and ', $field_where );
      $QDeleteRec   = "UPDATE $strTableName  SET active = 'n' $updateField WHERE $fields_where";
      $con          =  $this->isConn;      

      // Check connection
      if (!$con) 
      {
        die("Connection failed in query function - " . mysqli_connect_error());
      }

      if($con)
      {
        $q = $con->query($QDeleteRec);
        if(!$q)
        { 
          //show error message
          if($this->ShowQryErrors == 'on')
          {
            die( mysqli_error($con) );  
          } 

          $result =  0;
        }
        if($q)
        {  
          $result = 1;
          if($strTableName=='job'){
            $this->Delete("jobdet",$array_where1,"",$paid);
          }
        }
        
        //Will return a row data
        return $result; 
      }      
    }
    //--->Delete - End

    function Qry($SQLStatement)
    {
      /*
        This is for general purpose query. 
        If it ran successfully, it will return 1 else 0.

        Call it like this:  
        $db = new SimpleDBClass("host_name", "user_id", "password","database")
        $Qry = $db->Qry('select * from user where id=100');

      */
      // Create connection
      $con =  $this->isConn;
      
      // Check connection
      if (!$con) 
      {
        die("Connection failed in query function - " . mysqli_connect_error());
      }
      
      if($con)
      {
        $q = $con->query($SQLStatement);
        
        if(!$q)
        {
          //show error message
          if($this->ShowQryErrors == 'on')
          {
            die( mysqli_error($con) );  
          } 
          $result = 0;
        }
        if($q)
        {       
          //$result = true;
          $result = 1;
        }
        
        //Will return a row data
        return $result;
      }
    }


    function CleanDBData($Data)
    {
      /*
        This will help in preventing sql injections

        Call it like this:
        $db = new SimpleDBClass("host_name", "user_id", "password","database")
        $Qry = $db->CleanDBData($_POST["user_name"]); 
      */
      // Create connection
      $con =  $this->isConn;
      $str = mysqli_real_escape_string($con,$Data); 
      return $str;
    } 

    function CleanHTMLData($Data)
    {
      /*
        This will remove all HTML tags
        $db = new SimpleDBClass("host_name", "user_id", "password","database")
        $Qry = $db->CleanHTMLData($_POST["user_entry"]); 
      */
      
      // Create connection
      $con =  $this->isConn; 
      $str = mysqli_real_escape_string($con,$Data);
      
      $result = preg_replace('/(?:<|&lt;)\/?([a-zA-Z]+) *[^<\/]*?(?:>|&gt;)/', '', $str);
      
      return $result;
    } 
  }
?>
