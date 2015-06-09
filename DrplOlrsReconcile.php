<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        require_once '../contxt/madonapps.inc';                // sets environment Variables
        require_once '../common/phpmailer/class.phpmailer.php';
        require_once '../common/phpmailer/class.smtp.php';     // set up mail extensions
        
        require_once '../contxt/drpl_dbconnect.inc';           // set dbconnect strings
        #require_once '../common/drpl/drpl_functions.inc';      // define useful functions
        
        #require_once '../common/mrbs/mrbs_periodnames.inc';    // sets period names
        #require_once '../common/mrbs/mrbs_functions.inc';      // define useful functions
        #require_once '../contxt/mrbs_dbconnect.inc';           // set dbconnect strings

        #require_once './DrplOlrsReconcile.ini';          // default params & constants

        $RoleIds=GetRoleIds();
        $MakeWhrStr($RoleIds);
 /*
  * =====================================================================
  * function definitions start here
  * =====================================================================
  */       
        function GetRoleIds() {
            Global $DBName, $DBServer, $DBUser, $DBPass;
            $conn = new mysqli($DBServer, $DBUser, $DBPass, $DBName);

            // check connection
            if ($conn->connect_error) {
                trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
            }
    
            // Get record set
            $sql="SELECT * FROM role ORDER by rid";
            $rs=$conn->query($sql);

            if($rs === false) :
                trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $conn->error, E_USER_ERROR);
            else:
                $rows_returned = $rs->num_rows;
            endif;
            
            // iterate over record set
            $rs->data_seek(0);
            while($row = $rs->fetch_assoc()){
                if ($row['name']==="Committee Member"):
                    $drplRoleIDs[$row['rid']] = ($row['name']);
                endif;
                if ($row['name']==="Rider"):
                    $drplRoleIDs[$row['rid']] = ($row['name']);
                endif;
                if ($row['name']==="Driver"):
                    $drplRoleIDs[$row['rid']] = ($row['name']);
                endif;
                if ($row['name']==="Controller"):
                    $drplRoleIDs[$row['rid']] = ($row['name']);
                endif;
            }
            return $drplRoleIDs;
        }       
        
        function MakeWhrStr($frids){
        $myWhrStr = "";
        foreach($frids as $rn){
            $StLen = strlen($myWhrStr);
            if ($StLen == 0):
                $myWhrStr = "WHERE (rid = '$rn')";
            else:
                $myWhrStr = $myWhrStr . " " . " OR (rid = '$rn')";
            endif;
        }
            
        }   
        ?>
    </body>
</html>
