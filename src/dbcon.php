<?php
/*


//MySQL
//mysql:host=localhost;dbname=inventory;Port=3306
//User: root
//Password: <none>

//MSSQL
//"sqlsrv:server=mcbi-dev2,1433; Database=HRMS1;", "sa", "password.."


    private $DBType = 'MySQL';
    public $ServerName= 'localhost';
    public $DBName= 'inventory';
    public $User = 'root';
    public $Password='';
    public $Port=3306;
    

*/
function Connections($load)
{
    //MySQL
    if($load == 1)
    {
        define('DB_ServerName', 'localhost');
        define('DB_DBName','inventory');
        define('DB_User','root');
        define('DB_Password', '');
        define('DB_Port', 3306);
    }
    //MSSQL
    else if($load == 2)
    {
        define('DB_ServerName', 'mcbi-dev2');
        define('DB_DBName', 'HRMS1');
        define('DB_User','sa');
        define('DB_Password', 'password..');
        define('DB_Port', 1433);
    }
}
Connections(2);

?>