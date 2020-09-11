<?php
//LOAD DATABASE
require(getcwd().'/dbcon.php');
require(getcwd().'/lib/dbconfig.php');
require(getcwd().'/lib/jsonxml.php');


//AUTOMATICALLY LOAD ALL MODELS

    //require(getcwd().'/models/product_model.php');
    //require(getcwd().'/models/user_model.php');
    //MANIPULATING SOURCE HERE
    //LOAD MODELS
    foreach( array_diff(scandir(getcwd().'/models'), array('..', '.')) as $model)
    {
        if( strtolower( substr($model,-9) ) == 'model.php')
        {
            require(getcwd().'/models/'.$model);
        }
    }
    
    //var_dump($scanned_directory);

    /*
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
        $link = "https"; 
    else
        $link = "http"; 
      
    // Here append the common URL characters. 
    $link .= "://"; 
      
    // Append the host(domain name, ip) to the URL. 
    $link .= $_SERVER['HTTP_HOST']; 
      
    // Append the requested resource location to the URL 
    $link .= $_SERVER['REQUEST_URI']; 
    */
    // Print the link 
    
    ///EXECUTING METHOD
   // echo json_encode($_SERVER);
   //API VERSION
   if(substr( strtolower( $_GET['method']),0,3) == 'v1/')
    include('v1.php');
   else
    include('methods/'. $_GET['method']);    


?>