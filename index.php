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
        for ($myr =2014; $myr <2017; $myr++){
            for ($mmo = 1; $mmo <=12; $mmo++){
                for ($mda = 1; $mda  <= 31; $mda++) {
                    $mydate ="$mda-$mmo-$myr";
                $mybh = isBankHoliday($mydate);
                if ($mybh == 1):
                    echo $mydate ." is Bank Holiday " . "<br>";
                endif;
                }
            } 
        }
 /*
  * =====================================================================
  * function definitions start here
  * =====================================================================
  */       
    
       ?>
    </body>
</html>
