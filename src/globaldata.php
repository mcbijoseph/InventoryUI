<?php
    class GlobalData
    {
        function OnlineUsers($add = null)
        {
            static $OnlineUser = array();
            
            if($add != null)
                array_push($OnlineUser, $add);
            
            return $OnlineUser;
        }
    }
?>