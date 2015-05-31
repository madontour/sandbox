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
     
        function isBankHoliday($fdate) {
        // takes in a string formatted dd-mm-yyyy
        // and returns true if thatdate is a bank holiday
            $isBH = FALSE;
            
        //----------------------------------------------------------
        //  Now do actual checks     
        //-----------------------------------------------------------
            if (isNewYearsDay($fdate)===TRUE) {$isBH = TRUE;}
            if (isGoodFriday($fdate)===TRUE) {$isBH = TRUE;}
            if (isEasterMonday($fdate)===TRUE) {$isBH = TRUE;}
            if (isEarlyMonday($fdate)===TRUE) {$isBH = TRUE;}
            if (isLateMonday($fdate,"05")===TRUE) {$isBH = TRUE;}
            if (isLateMonday($fdate,"08")===TRUE) {$isBH = TRUE;}
            if (isChristmasDay($fdate)===TRUE) {$isBH = TRUE;}
            if (isBoxingDay($fdate)===TRUE) {$isBH = TRUE;}
            return $isBH;
       }
       
       function isBoxingDay($fdate) {
        // takes in a string formatted dd-mm-yyyy
        // and returns true if that date is boxing day bank holiday
            $isBH = FALSE;
            $bits = explode('-',$fdate);    // day - month - year
            $fyear = $bits[2];
        //  Now do actual checks            
        //  Boxing Day  - 26th Dec unless that falls on a weekend
            $dayfalls = date("w",strtotime("26-12-$fyear"));
            if ($dayfalls ==0):
                $tbh = "28-01-$fyear";
            elseif ($dayfalls == 6):
                $tbh = "28-12-$fyear";
            else:
                $tbh = "26-12-$fyear";
            endif; 
            if (strtotime($fdate) == strtotime($tbh)) {$isBH = TRUE;}
            return $isBH;
       }
       
       function isNewYearsDay($fdate) {
        // takes in a string formatted dd-mm-yyyy
        // and returns true if that date is new years day bank holiday
            $isBH = FALSE;
            $bits = explode('-',$fdate);    // day - month - year
            $fyear = $bits[2];
            $dayfalls = date("w",strtotime("01-01-$fyear"));
            if ($dayfalls ==0):
                $tbh = "02-01-$fyear";
            elseif ($dayfalls == 6):
                $tbh = "03-12-$fyear";
            else:
                $tbh = "01-01-$fyear";
            endif; 
            if (strtotime($fdate) == strtotime($tbh)) {$isBH = TRUE;}
            return $isBH;
       }
       
       function isChristmasDay($fdate) {
        // takes in a string formatted dd-mm-yyyy
        // and returns true if that date is xmas day bank holiday
            $isBH = FALSE;
            $bits = explode('-',$fdate);    // day - month - year
            $fyear = $bits[2];
            $dayfalls = date("w",strtotime("25-12-$fyear"));
            if ($dayfalls ==0):
                $tbh = "27-12-$fyear";
            elseif ($dayfalls == 6):
                $tbh = "27-12-$fyear";
            else:
                $tbh = "25-12-$fyear";
            endif; 
            if (strtotime($fdate) == strtotime($tbh)) {$isBH = TRUE;}
            return $isBH;
       }
       
       function isGoodFriday($fdate){
        // takes in a string formatted dd-mm-yyyy
        // and returns true if that date is good friday bank holiday
            $isBH = FALSE;
            $dayfalls = date("w",strtotime($fdate));
            if ($dayfalls == 5):                // is the date passed a friday
                $bits = explode('-',$fdate);    // day - month - year
                $fyear = $bits[2];                     
                $StartSecs=mktime(0, 0, 0, $bits[1], $bits[0], $bits[2]);      // for date passed          

                $yr=date("Y",easter_date($fyear)); 
                $mo=date("n",easter_date($fyear));
                $da=date("j",easter_date($fyear));
                $BHSecs=mktime(0, 0, 0, $mo, $da-2, $yr);      // for good friday
                if ($StartSecs === $BHSecs) {$isBH = TRUE;}
            endif;
            return $isBH;            
       }
       
       function isEasterMonday($fdate){
        // takes in a string formatted dd-mm-yyyy
        // and returns true if that date is easter monday bank holiday
            $isBH = FALSE;
            $dayfalls = date("w",strtotime($fdate));
            if ($dayfalls == 1):                // is the date passed a friday
                $bits = explode('-',$fdate);    // day - month - year
                $fyear = $bits[2];                     
                $StartSecs=mktime(0, 0, 0, $bits[1], $bits[0], $bits[2]);      // for date passed          

                $yr=date("Y",easter_date($fyear)); 
                $mo=date("n",easter_date($fyear));
                $da=date("j",easter_date($fyear));
                $BHSecs=mktime(0, 0, 0, $mo, $da+1, $yr);      // for good friday
                if ($StartSecs === $BHSecs) {$isBH = TRUE;}
            endif;
            return $isBH;
       }
       
       function isEarlyMonday($fdate){
        // takes in a string formatted dd-mm-yyyy 
        // and returns true if that date is early may bank holiday
            $isBH = FALSE;
            $bits = explode('-',$fdate);    // day - month - year
            $fyear = $bits[2];                     
            $StartSecs=mktime(0, 0, 0, $bits[1], $bits[0], $bits[2]);
            $dayfalls = date("w",strtotime("01-05-$fyear"));
            if ($dayfalls > 1):                
                $dbhm = 9-$dayfalls;
            elseif ($dayfalls == 0):
                $dbhm = 2;
            else:
                $dbhm = 1;
            endif;
            $BHSecs=mktime(0, 0, 0, 05, $dbhm, $fyear);      // for bh monday
            if ($StartSecs === $BHSecs) {$isBH = TRUE;}
            return $isBH;
       }
       
       function isLateMonday($fdate,$fmo){
        // takes in a string formatted dd-mm-yyyy and month 05 or 08
        // and returns true if that date is late monday bank holiday
            $isBH = FALSE;
            $bits = explode('-',$fdate);    // day - month - year
            $fyear = $bits[2];                     
            $StartSecs=mktime(0, 0, 0, $bits[1], $bits[0], $bits[2]);
            $dayfalls = date("w",strtotime("31-$fmo-$fyear"));
            if ($dayfalls == 1):                // is the date passed a friday
                $dbhm = 31;
            elseif ($dayfalls == 0):
                $dbhm = 31 - 6;
            else:
                $dbhm = 31 - $dayfalls + 1;
            endif;
            $BHSecs=mktime(0, 0, 0, $fmo, $dbhm, $fyear);      // for bh monday
            if ($StartSecs === $BHSecs) {$isBH = TRUE;}
            return $isBH;
       }
       ?>
    </body>
</html>
