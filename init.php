<?php
    //LOAD DATABASE
    require(getcwd().'/dbcon.php');
    require(getcwd().'/src/lib/dbconfig.php');
    
    
    //AUTOMATICALLY LOAD ALL MODELS
    
    require(getcwd().'/src/models/product_model.php');
    require(getcwd().'/src/models/user_model.php');






?>