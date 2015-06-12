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
        require_once '../contxt/mrbs_dbconnect.inc';           // set dbconnect strings

        #require_once './DrplOlrsReconcile.ini';          // default params & constants

        $RoleIds=GetRoleIds();                      //Get role numbers from drupal
        $WhrStr = MakeWhrStr($RoleIds);             //Make a WHERE string
        $DrplMembers=GetDrplMembers($WhrStr);       //Get Array of Drupal IDs with Role Ids
        $OLRSMembers=GetOLRSMembers();              //Get Array of OLRS IDs with Role Ids
        $Mismatches=GetMisMatches($DrplMembers,$OLRSMembers);
 /*
  * =====================================================================
  * function definitions start here
  * =====================================================================
  */       
        function GetRoleIds() {
            Global $DBNameD, $DBServer, $DBUser, $DBPass;
            $conn = new mysqli($DBServer, $DBUser, $DBPass, $DBNameD);

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
        foreach(array_Keys($frids) as $rn){
            $StLen = strlen($myWhrStr);
            if ($StLen == 0):
                $myWhrStr = "WHERE (rid = '$rn')";
            else:
                $myWhrStr .= " " . " OR (rid = '$rn')";
            endif;
        }
        return $myWhrStr;    
        }   
        
        function GetDrplMembers($fWhrStr){
            Global $DBNameD, $DBServer, $DBUser, $DBPass;
            $conn = new mysqli($DBServer, $DBUser, $DBPass, $DBNameD);

            // check connection
            if ($conn->connect_error):
                trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
            endif;
            
            $sql="SELECT users.uid, name, rid FROM users " .
                     "RIGHT JOIN users_roles ON users.uid=users_roles.uid " .
                     $fWhrStr .
                     " ORDER by uid, rid";
            #echo $sql."<br>";
            $rs=$conn->query($sql);

            if($rs === false) :
                trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $conn->error, E_USER_ERROR);
            else:
                $rows_returned = $rs->num_rows;
            endif;
            
            // iterate over record set
            unset($DrplMembers);
            $rs->data_seek(0);
            while($row = $rs->fetch_assoc()){
                $myDrplName = strtolower($row['name']);
                if(isset($DrplMembers[$row['uid']])):
                    $myRoleStr = $DrplMembers[$row['uid']];
                    $myRoleStr .= "," . $row['rid'];
                else:
                    $myRoleStr = $row['rid'];
                endif;
                $DrplMembers[$row['uid']]=$myRoleStr;
                #echo $row['name'] . " " . $row['rid']. " - $myRoleStr <br>";
            }
            return $DrplMembers;
        }
        
        function GetOLRSMembers(){
            Global $DBName, $DBServer, $DBUser, $DBPass;
            $conn = new mysqli($DBServer, $DBUser, $DBPass, $DBName);

            // check connection
            if ($conn->connect_error):
                trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
            endif;
            
            $sql="SELECT uid, registers FROM mrbs_users " .
                     "WHERE name NOT Like '~*'  " .
                     " ORDER by uid";
            echo $sql."<br>";
            $rs=$conn->query($sql);

            if($rs === false) :
                trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $conn->error, E_USER_ERROR);
            else:
                $rows_returned = $rs->num_rows;
            endif;
            
            // iterate over record set
            unset($DrplMembers);
            $rs->data_seek(0);
            while($row = $rs->fetch_assoc()){
                if (strlen(trim($row['registers']))>0):
                    $myRoleStr=Regs2Roles(trim($row['registers']));
                    $OLRSMembers[$row['uid']]=$myRoleStr;    
                endif;
                
                #echo $row['name'] . " " . $row['rid']. " - $myRoleStr <br>";
            }
            return $OLRSMembers;
        }
        
        function Regs2Roles($myregs){
/*
 * Send a string containing RDC#
 * Convert to s string containg role numbers 11,15,12,5
 * Sort the role numbers and return the string
 */
            $mystrlen=strlen($myregs);
            for($i = 0; $i <= $mystrlen-1; $i++){
                if (substr($myregs, $i , 1 ) === "#"):
                    if (isset($myroles)):
                        $myroles .=(",5");
                    else:
                        $myroles = "5";
                    endif;
                elseif (substr($myregs, $i , 1 ) === "C"):
                    if (isset($myroles)):
                        $myroles .=(",12");
                    else:
                        $myroles = "12";
                    endif;
                elseif (substr($myregs, $i , 1 ) === "D"):
                    if (isset($myroles)):
                        $myroles .=(",15");
                    else:
                        $myroles = "15";
                    endif;    
                elseif (substr($myregs, $i , 1 ) === "R"):
                    if (isset($myroles)):
                        $myroles .=(",11");
                    else:
                        $myroles = "11";
                    endif;
                endif;
            }
                $bits =explode(",", $myroles);
                asort($bits);
                $myroles="";
                foreach($bits as $r){
                    $myroles.=",$r";
                }
                $myroles=  substr($myroles,1);  
  
            return $myroles;    
        }
        
        function GetMismatches($fd,$fo){
            foreach (array_keys($fd) as $Duid){
                $UserInOLRS = False;
                foreach(array_keys($fo) as $Ouid){
                    if ($Duid === $Ouid):
                        $UserInOLRS = True;
                        if ($fd[$Duid] !== $fo[$Ouid]) :
                            $Mismatch[] = "User id $Duid has these " .
                                "Drupal roles $fd[$Duid] but these " .
                                "OLRS roles $fo[$Ouid]";
                        endif;
                    endif;
                }
                if ($UserInOLRS == False):
                    $Mismatch[] =  "User id $Duid has these Drupal roles" .
                                " $fd[$Duid] but is not found in OLRS";
                endif;
            }
        return $Mismatch;
        }
        ?>
    </body>
</html>
