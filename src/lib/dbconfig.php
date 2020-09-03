<?php

class DBConfig
{
    public $ServerName= 'localhost';
    public $DBName= 'inventory';
    public $User = 'root';
    public $Password='';
    public $Port=3306;
    
    
    public function __construct(){ 
        
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
    
    
    public function SerializeObject($model, $query)
    {
        //GET JSON QUERY TO BE PARSE HERE
        $json = $this->QueryJson($query);
        
        $jsonObject = json_decode($json, true);
        
        if(!$jsonObject)
           exit('Error: '.$query);
        else
            $jsonlength =  count($jsonObject);
        
        //IF NOTHING IS EXIST RETURNS 0 as possible.
        if($jsonlength <= 0)
            return array();
            
        
        //PROCESS MODELS HERE
        for($i =0 ; $i< $jsonlength; $i++)
        {
            $ref = new ReflectionClass($model);
            //$ref->newInstanceArgs(array( $json  ));
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
    
    public function QueryJson($query)
    {
        $mysqli = $this->MySqlConnect();
        $returndata = null;
        
        if(  $result =  $mysqli->Query($query))
        {
        
            $returndata = json_encode(mysqli_fetch_all($result, MYSQLI_ASSOC)); 
            $result -> free_result();
        
        }
        
        $mysqli -> close();
        return $returndata;
        
    }
    
    
    
}
?>