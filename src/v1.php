<?php
    //TEST THE METHOD HERE
    //echo $_SERVER['REQUEST_METHOD'];
    $dbconfig = new DBConfig('PDOMSSQL');
    $parser = new JSONXMLContentParser();
    
    //$par = $parser->ReadInput();
    
    $split_arr = explode('/', $_GET['method']);
    $procedure = $split_arr[1];
    
    $result = '';
    
    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        //$_GET['method'];
       
        //0 - Procedure
        //1 - PageNo
        //2 - ItemCount
        //3 - Query
        $id = 0;
        
        if(count($split_arr) >= 4)
        {
            $PageNo = $split_arr[2];
            $ItemPerPage = $split_arr[3];
            $Query = $split_arr[4];
        }
        else if(count($split_arr) == 3)
        {
            $id = $split_arr[2];
            $PageNo = 1;
            $ItemPerPage = 20;
            $Query = ' ';
        }
        
        $params = 
            array(
                    ":ID" => $id,
                    ":PageNo" => $PageNo,
                    ":ItemPerPage" => $ItemPerPage,
                    ":Search" => $Query
                );
        
      $result =  $dbconfig->SelectResult(
            null,
             'sp'.$procedure.'Select',
            $params
            );
    }
    else if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $par = $parser->ReadInput();
        //die( var_dump($par) );
        
        $json = json_encode($par);
        $array = json_decode($json,TRUE);
        
        $result = $dbconfig->CommandResult(null,
        'sp'.$procedure.'Insert',
        $array
        );
    }
    
    else if($_SERVER['REQUEST_METHOD'] == 'PUT')
    {
        $par = $parser->ReadInput();
        //die( var_dump($par) );
        
        $json = json_encode($par);
        $array = json_decode($json,TRUE);
        
        $result = $dbconfig->CommandResult(null,
        'sp'.$procedure.'Update',
        $array
        );
    }
    else if($_SERVER['REQUEST_METHOD'] == 'DELETE')
    {
        //$_GET['method'];
        //echo 'DELETE';
        
        $par = $parser->ReadInput();
        //die( var_dump($par) );
        
        $json = json_encode($par);
        $array = json_decode($json,TRUE);
        
        $result = $dbconfig->CommandResult(null,
        'sp'.$procedure.'Delete',
        $array
        );
    }
    
    $parser->DisplayResult( $result , 200);
?>