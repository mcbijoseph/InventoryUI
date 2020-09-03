<?php
    function Login($username, $password)
    {
        $DB = new DBConfig();
        
        $result = $DB->SerializeObject('User', "select * from users ");
        
        return $result;
    }
        
   $parser = new JSONXMLContentParser();
   $par = $parser->ReadInput();
   $data = Login($par->Email,$par->Password);
   $parser->DisplayResult($data, 200);


?>