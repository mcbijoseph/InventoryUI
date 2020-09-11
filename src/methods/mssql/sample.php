
<?php
    /*
    $serverName = "mcbi-dev2"; //serverName\instanceName, portNumber (default is 1433)
    $connectionInfo = array( "Database"=>"HRMS1", "UID"=>"sa", "PWD"=>"password..");
    $conn = sqlsrv_connect( $serverName, $connectionInfo);
    
    if( $conn ) {
        
         //echo "Connection established.<br />";
    }else{
         //echo "Connection could not be established.<br />";
         die( print_r( sqlsrv_errors(), true));
    }
    
    $sql = "SELECT * FROM [ERP_HRMS2].[b_refedata].[GenderList]";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $stmt = sqlsrv_query( $conn, $sql , $params, $options );

    //$row_count = sqlsrv_num_rows( $stmt );
    $count = 0;
    $resultdata = '';
    $resultdata .= '[';   
    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC ) ) {
         if($count > 0)
            $resultdata .= ',';
         $count++;
         $resultdata .= json_encode($row);
    }
    $resultdata .= ']';
    print $resultdata;

    sqlsrv_close($conn);
    */
    $dbconfig = new DBConfig('PDOMSSQL');
    //tblAddress
    //users
    echo $dbconfig->SelectResult(null,'spsample',array(":id"=>3,":NAME"=>'Hello')  );
    //echo $dbconfig->QueryJson('spsample', array('0','Hello')  );
    //echo $dbconfig->GetProcedureDescription('spAddProfilePhoto');
    /*
      try  
{ 
    $conn = new PDO( "sqlsrv:server=mcbi-dev2; Database=HRMS1", "sa", "password..");  
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );  
    }  
catch(Exception $e)  
{   
die( print_r( $e->getMessage() ) );   
}  
    $stmt = $conn->prepare("SELECT * from tblAddress where id=:id");
    
    $stmt->bindParam(":id", $value);
    $value = '1';
    $resultdata = '';
    if($stmt->execute())
    {
        try
        {
            while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
                    print(json_encode($row ));
                  if($resultdata != '')
                    $resultdata .= ',';
                  $resultdata .= json_encode($row );
             
                }
            }
        catch(Exception $e)
        {}
        }
    //echo $resultdata;
    */
?>
