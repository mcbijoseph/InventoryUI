<?php
//LOAD DATABASE
require(getcwd().'/lib/dbconfig.php');
require(getcwd().'/lib/jsonxml.php');


//AUTOMATICALLY LOAD ALL MODELS

require(getcwd().'/models/product_model.php');
require(getcwd().'/models/user_model.php');
    //MANIPULATING SOURCE HERE


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
    include('methods/'. $_GET['method']);    


?>