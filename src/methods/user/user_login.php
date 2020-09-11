<?php
    function Login($username, $password)
    {
        $DB = new DBConfig("PDOMySQL");
        
        $result = $DB->SerializeObject('User', "select * from users where email=:email and password=:password", array(":email"=>$username,":password"=>$password));
        
        return $result;
    }
        
   $parser = new JSONXMLContentParser();
   
   $par = $parser->ReadInput();
   $data = Login($par->Email,$par->Password);
   
   $parser->DisplayResult($data, 200);


?>