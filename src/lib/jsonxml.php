<?php

    class JSONXMLContentParser
    {
        public $contentType = "";
        
        function toJson($arr)
        {
            if(is_string($arr))
                return $arr;
            return json_encode($arr);
        }
        
        function generateValidXmlFromObj($obj, $node_block='nodes', $node_name='node') {
            
            if(is_string($obj))
            {
                $obj = json_decode($obj);
            }
            $arr = get_object_vars($obj);
            return $this->generateValidXmlFromArray($arr, $node_block, $node_name);
        }

        function generateValidXmlFromArray($array, $node_block='nodes', $node_name='node') {
            $xml = '<?xml version="1.0" encoding="UTF-8" ?>';
            $xml = ' ';
            $xml .= '<' . $node_block . '>';
            $xml .= $this->generateXmlFromArray($array, $node_name);
            $xml .= '</' . $node_block . '>';
    
            return $xml;
        }
        
        
        function generateXmlFromArray($array, $node_name) {
            $xml = '';
    
            if (is_array($array) || is_object($array)) {
                foreach ($array as $key=>$value) {
                    if (is_numeric($key)) {
                        $key = $node_name;
                    }
    
                    $xml .= '<' . $key . '>' . self::generateXmlFromArray($value, $node_name) . '</' . $key . '>';
                }
            } else {
                $xml = htmlspecialchars($array, ENT_QUOTES);
            }
    
            return $xml;
        }
        
        function toXML($arr, $node_block='nodes', $node_name='node')
        {
            if(is_array($arr))
                return $this->generateValidXmlFromArray($arr, $node_block, $node_name);
            else 
                return $this->generateValidXmlFromObj($arr, $node_block, $node_name);
        } 
        
        
        function __construct(){ 
            
                foreach (getallheaders() as $name => $value) {
                    if($name == "Content-Type")
                    {
                        $this->contentType = strtolower($value);
                        break;
                    }
                }
            
        }  
        
        function ConvertResult($Result)
        {
            if($this->contentType == 'application/json')
              return  $this->toJson($Result);
            else if($this->contentType == 'application/xml'|| $this->contentType == 'text/xml')
              return  $this->toXML($Result);
            return null;
        }
        
        function DisplayResult($Result, $statuscode = 200)
        {
            http_response_code ( $statuscode );
            header('Content-Type: '.$this->contentType);
            echo $this->ConvertResult($Result);
        }
        
        function ReadInput($input = null)
        {
            if($input == null)
            {
                $input = file_get_contents("php://input");
            }
            
            if($this->contentType == 'application/json')
            {
                return json_decode($input);
            }
            else if($this->contentType == 'application/xml' || $this->contentType == 'text/xml')
            {
                return simplexml_load_string($input);
            }
            return null;
           //return file_get_contents("php://input");
        }
        
        
    }
    
    
    
    
    /*
    $test_array = array (
      'bla' => 'blub',
      'foo' => 'bar',
      'another_array' => array (
        'stack' => 'overflow',
      ),
    );
    $xml = new SimpleXMLElement('<root/>');
    array_walk_recursive($test_array, array ($xml, 'addChild'));
    print $xml->asXML();
     */      
?>