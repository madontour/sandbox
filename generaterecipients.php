<!DOCTYPE html>
<!--
This script generates a list of email recipients 


-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
         <?php
         
         $myrecipients = GenerateEmailRecipients("D");
         foreach($myrecipients as $ema){
             echo $ema . "<br>";
         }
                 
        function GenerateEmailRecipients($fcat){
            // fcat can be D or R or C
        
            require_once '../contxt/madonapps.inc';                // sets environment Variables
            require_once '../contxt/mrbs_dbconnect.inc';           // set dbconnect strings
    
            unset($adreses);

            $conn = new mysqli($DBServer, $DBUser, $DBPass, $DBName);

            // check connection
            if ($conn->connect_error) {
                trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
            }
    
            // Get record set
            $sql="SELECT name, email, registers FROM mrbs_users "
                    . "WHERE (registers LIKE '%$fcat%') ";
            $rs=$conn->query($sql);

            if($rs === false) {
                trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $conn->error, E_USER_ERROR);
            } else {
                $rows_returned = $rs->num_rows;
            }
            // iterate over record set
            $rs->data_seek(0);
            while($row = $rs->fetch_assoc()){
                $adreses[]=$row['email'];
            }
            return $adreses;
        }
        ?>
    </body>
</html>

