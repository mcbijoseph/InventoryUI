<?php

//MySQL
//mysql:host=localhost;dbname=inventory;Port=3306
//User: root
//Password: <none>

//MSSQL
//"sqlsrv:server=mcbi-dev2,1433; Database=HRMS1;", "sa", "password.."


//LOAD DB CONNECTIONS

class DBConfig
{
    /////SAMPLES////
    //PDO SAMPLE=>DBTYPES would be PDOMSSQL / PDOMySQL
    //$this->QueryJson('select * from users where email=:email ', array(':email'=>'a')
    //MySQL & MSSQL
    //QueryJson('select * from users where inactive = ? ', array('0') );
    
    private $DBType = 'MySQL';
    public $ServerName= DB_ServerName;
    public $DBName= DB_DBName;
    public $User = DB_User;
    public $Password= DB_Password;
    public $Port= DB_Port;
    
    
    public function __construct($dbType='MySQL'){
        $this->DBType = $dbType;  
    }  
    
    
    public function GetProcedureDescription($storeprocedureName)
    {
        //MYSQL
        /*
            SELECT PARAMETER_NAME as ParameterName, DATA_TYPE as 'Type', CHARACTER_MAXIMUM_LENGTH as 'Length', NUMERIC_PRECISION as Prec,  NUMERIC_SCALE as Scale, ORDINAL_POSITION as ParamOrder, COLLATION_NAME as Collation FROM information_schema.parameters WHERE SPECIFIC_NAME = 'spsample' AND ROUTINE_TYPE='PROCEDURE';
        */
        if($this->DBType == 'MySQL')
        {
          return  $this->QueryJson(
                                "SELECT PARAMETER_NAME as ParameterName, DATA_TYPE as 'Type', CHARACTER_MAXIMUM_LENGTH as 'Length', NUMERIC_PRECISION as Prec,  NUMERIC_SCALE as Scale, ORDINAL_POSITION as ParamOrder, COLLATION_NAME as Collation FROM information_schema.parameters WHERE SPECIFIC_NAME = ? AND ROUTINE_TYPE='PROCEDURE' order by ParamOrder",
                                array($storeprocedureName)
                            );
        }
        else if($this->DBType == 'PDOMySQL')
        {
            return $this->QueryJson(
                                "SELECT PARAMETER_NAME as ParameterName, DATA_TYPE as 'Type', CHARACTER_MAXIMUM_LENGTH as 'Length', NUMERIC_PRECISION as Prec,  NUMERIC_SCALE as Scale, ORDINAL_POSITION as ParamOrder, COLLATION_NAME as Collation FROM information_schema.parameters WHERE SPECIFIC_NAME = :storedprocedure AND ROUTINE_TYPE='PROCEDURE' order by ParamOrder",
                                array( ":storedprocedure"=>$storeprocedureName)
                        );
        }
        /*
        //MSSQL 
          SELECT   'ParameterName' = name,   'Type'   = type_name(user_type_id),   'Length'   = max_length,   'Prec'   = case when type_name(system_type_id) = 'uniqueidentifier' then precision else OdbcPrec(system_type_id, max_length, precision) end, 'Scale'   = OdbcScale(system_type_id, scale), 'ParamOrder'  = parameter_id, 'Collation'   = convert(sysname, case when system_type_id in (35, 99, 167, 175, 231, 239) then ServerProperty('collation') end) from sys.parameters where object_id = object_id('spProcedureCommand')
        */
        else if($this->DBType == 'MSSQL')
        {
            return $this->QueryJson(
                                "SELECT   'ParameterName' = name,   'Type'   = type_name(user_type_id),   'Length'   = max_length,   'Prec'   = case when type_name(system_type_id) = 'uniqueidentifier' then precision else OdbcPrec(system_type_id, max_length, precision) end, 'Scale'   = OdbcScale(system_type_id, scale), 'ParamOrder'  = parameter_id, 'Collation'   = convert(sysname, case when system_type_id in (35, 99, 167, 175, 231, 239) then ServerProperty('collation') end) from sys.parameters where object_id = object_id(?) order by ParamOrder",
                                array($storeprocedureName)
                        );
        }
        else if($this->DBType == 'PDOMSSQL')
        {
            return $this->QueryJson(
                                "SELECT   'ParameterName' = name,   'Type'   = type_name(user_type_id),   'Length'   = max_length,   'Prec'   = case when type_name(system_type_id) = 'uniqueidentifier' then precision else OdbcPrec(system_type_id, max_length, precision) end, 'Scale'   = OdbcScale(system_type_id, scale), 'ParamOrder'  = parameter_id, 'Collation'   = convert(sysname, case when system_type_id in (35, 99, 167, 175, 231, 239) then ServerProperty('collation') end) from sys.parameters where object_id = object_id(:storedprocedure)  order by ParamOrder",
                                array(":storedprocedure"=>$storeprocedureName)
                        );
        }
        return null;
    }
    
    
    function MySqlConnect()
    {
        $mysqli =  new mysqli
                (
                    $this->ServerName,
                    $this->User,
                    $this->Password,
                    $this->DBName,
                    $this->Port
                    );
                    
        
        if ($mysqli -> connect_errno) {
          echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
          exit();
        }
        return $mysqli;
    }
    
    function MSSQLConnect()
    {
        $serverName = $this->ServerName; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array( "Database"=>$this->DBName, "UID"=>$this->User, "PWD"=>$this->Password,"PORT"=>$this->Port);
        $conn = sqlsrv_connect( $serverName, $connectionInfo);
        if( $conn ) {
            return $conn;
        }else{
            // echo "Connection could not be established.<br />";
             //die( print_r( sqlsrv_errors(), true));
              echo "Failed to connect to MySQL: " . print_r( sqlsrv_errors(),true);
              exit();
        }
    }
    
    function PDOMySQLConnect()
    {
        // new PDO('sqlsrv:Server=localhost\\SQLEXPRESS;Database=MyDatabase', 'MyUsername', 'MyPassword');
        $conn = new PDO("mysql:host=$this->ServerName;dbname=$this->DBName;Port=$this->Port", $this->User, $this->Password);
        //set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // get the default db connection
        //$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);    
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $conn;
    }
    function PDOMSSQLConnect()
    {
        $conn = new PDO( "sqlsrv:server=$this->ServerName,$this->Port; Database=$this->DBName;", $this->User, $this->Password);  
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );  
        // get the default db connection
        //$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);    
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $conn;
    }
    
    function PDOConnect($db)
    {
        if($db == 'MySQL')
        {
            return $this->PDOMySQLConnect();
        }
        else if($db == 'MSSQL')
        {       
            return $this->PDOMSSQLConnect();
        }
    }
    
    function Connection()
    {
        if($this->DBType == 'MySQL')
            return $this->MySqlConnect();
        else if($this->DBType == 'MSSQL')
            return $this->MSSQLConnect();
        else if($this->DBType == 'PDOMySQL')
            return $this->PDOMySQLConnect();
        else if($this->DBType == 'PDOMSSQL')
            return $this->PDOMSSQLConnect();
    }
    
    
    
    public function SelectResult($model, $query, $params=array(), $options=array( "Scrollable" => SQLSRV_CURSOR_KEYSET ))
    {
        
        //die(var_dump($params));
        $data = null;
        if(!$model)
        {
            $data = $this->QueryJson($query,$params, $options);
        }
        else   
        {
            $data = $this->SerializeObject($model, $query, $params, $options);
        }
        //SomeProcess
        
        
        //Finalize
        $selectresult = array(
            "Data" => json_decode( $data ),
            "Message" => "Sample Message",
            "ReturnValue" => 1,
            "Page" => 1,
            "ItemPerPage" => 20,
            "PageCount"=>1,
            "ItemsFound"=> 17
        );
        
        return json_encode( $selectresult );
           
    }
    
    public function CommandResult($model, $query, $params=array(), $options=array( "Scrollable" => SQLSRV_CURSOR_KEYSET ))
    {
        $commandresult = $this->QueryJson($query, $params, $options);
        
        $command = json_decode( $commandresult);
        
        return json_encode( $command[0] );
        //die(json_decode($commandresult));
        //return json_encode($commandresult);
    }
    
    
    public function SerializeObject($model, $query, $params=array(), $options=array( "Scrollable" => SQLSRV_CURSOR_KEYSET ))
    {
        //GET JSON QUERY TO BE PARSE HERE
        $json = $this->QueryJson($query,$params, $options);
        //$json = '[]';
        $jsonObject = json_decode($json, true);
        
        if(!$jsonObject)
        {
           //exit('Error: '.$query);
           return array();
        }else
            $jsonlength =  count($jsonObject);
        
        //IF NOTHING IS EXIST RETURNS 0 as possible.
        if($jsonlength <= 0)
            return array();
            
        
        //PROCESS MODELS HERE
        for($i =0 ; $i< $jsonlength; $i++)
        {
            $ref = new ReflectionClass($model);
            //$ref->newInstanceArgs(array( $json  ));
            unset($ref->name);
            $currentJson = $jsonObject[$i];
            
            foreach( array_keys( $currentJson) as $k)
            {
                //SET PROPERTIES HERE
                if(property_exists($model, $k)) // where myClass is your class name.
                {
                  $ref->{$k} = $currentJson[$k];
                }
            }
            
            //RESET THE $jsonObject
            $jsonObject[$i] = $ref;
        }
        
        
        return $jsonObject;
    }
    
    public function QueryJson($query, $params=array(), $options=array( "Scrollable" => SQLSRV_CURSOR_KEYSET ))
    {
        $exp = explode(" ",$query);
        //echo  $query ." = ".var_dump($exp);
        $count = count( $exp );
        if($count > 1)
        {
        
            if($this->DBType == 'MySQL')
            {
                return $this->MySQLJson($query, $params);
            }
            else if($this->DBType == 'MSSQL')
            {
                return $this->MSSQLJson($query, $params, $options);
            }
            else if($this->DBType == 'PDOMySQL' || $this->DBType == 'PDOMSSQL')
            {
                return $this->PDOJson($query, $params);
            }
        }
        else if($count == 1)
        {
            if($this->DBType == 'MSSQL' || $this->DBType == 'MySQL')
            {
                $pa = ''; 
                for($i = 0; $i < count($params); $i++)
                {
                    if($pa != '')
                        $pa .=',';
                    $pa .= '? ';
                }
                if($this->DBType == 'MSSQL')
                    return $this->QueryJson('EXEC '.$query. ' '.$pa, $params);
                else if($this->DBType == 'MySQL')
                    return $this->QueryJson('CALL '.$query. '( '.$pa.')', $params);
            }
            else
            {
                //getProceduresInfo
                $proceduresParameters = json_decode($this->GetProcedureDescription($query) );
                $proc_count = count($proceduresParameters);
                $pa = '';
                for($i = 0; $i < $proc_count; $i++ )
                {
                    $proceduresParameters[$i]->ParameterName = strtolower( $proceduresParameters[$i]->ParameterName );
                    if($pa != '')
                        $pa .=',';
                    //if($this->DBType == 'PDOMySQL')
                        //$pa .=":".$proceduresParameters[$i]->ParameterName;
                    //else if($this->DBType == 'PDOMSSQL')
                    //Removng _ in MySQL or @ in MSSQL
                    $pa .=":".substr($proceduresParameters[$i]->ParameterName, 1);
                }
                if($this->DBType == 'PDOMSSQL')
                    return $this->QueryJson( 'EXEC '.$query.' '. $pa, $params);
                else if($this->DBType == 'PDOMySQL')
                    return $this->QueryJson( 'CALL '.$query.'( '.$pa.')', $params);
            }
            //$getProcedure = $query;
        }
        
        
        
    }
    
    private function MySQLJson($query, $params=array())
    {
        
        $mysqli = $this->MySqlConnect();
        $returndata = '[]';
        $stmt = $mysqli->prepare($query);
        $types = '';
        $params_count = count($params);
        for($i = $params_count; $i > 0; $i-- )
        {
            $types .='s';
        }
        if($params_count >0)
        $stmt->bind_param($types,...$params);
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $count = 0;
        $resultdata = '';
        $resultdata .= '[';   
        while ($row = $result->fetch_assoc()) {
            if($count > 0)
                $resultdata .= ',';
             $count++;
           $resultdata .= json_encode($row);
        }
        $resultdata .= ']';
        return $resultdata;
        
    }
    
    private function PDOJson( $query, $params=array())
    {
        
        $conn = $this->Connection();
        $stmt = $conn->prepare($query);
        foreach ($params as $key => $value)
        {
            $stmt->bindParam( strtolower("$key"), $params[$key]);
            //${$key} = $value;
            //echo $key;
        }
        $resultdata = '';
        if ($stmt->execute()) {
            
            try{
                while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
                    
                    $resultdata .= json_encode($row );
             
                }
            }catch(Exception $e){
                //die( print_r( $e->getMessage() ) );
            }
        }
        if($resultdata)
        {
            
        }
        
        return  $resultdata;
        
    }
    
    
    private function MSSQLJson($query, $params=array(), $options=array( "Scrollable" => SQLSRV_CURSOR_KEYSET ))
    {
        
        $conn  = $this->MSSQLConnect();
        
        $stmt = sqlsrv_query( $conn, $query , $params, $options );
    
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
        sqlsrv_close($conn);
        return $resultdata;
    }
    
    
    
}
?>