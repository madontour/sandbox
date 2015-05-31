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
        $mydate ="26-12-2014";
        $mybh = isBankHoliday($mydate);
        echo $mydate ." BH " . $mybh;
        
        
        function isBankHoliday($fdate) {
        // takes in a string formatted dd-mm-yyyy
        // and returns true if thatdate is a bank holiday
            $isBH = FALSE;
            $bits = explode('-',$fdate);    // day - month - year
            $fyear = $bits[2];
        //  Now do actual checks
        //  New Years Day  - 1st of Jan unless that falls on a weekend
            $dayfalls = date("w",strtotime("01-01-$fyear"));
            if ($dayfalls ==0):
                $tbh = "02-01-$fyear";
            elseif ($dayfalls == 6):
                $tbh = "03-01-$fyear";
            else:
                $tbh = "01-01-$fyear";
            endif; 
            if (strtotime($fdate) == strtotime($tbh)) {$isBH = TRUE;}
            
        //  Christmas Day  - 25th Dec unless that falls on a weekend
            $dayfalls = date("w",strtotime("25-12-$fyear"));
            if ($dayfalls ==0):
                $tbh = "27-01-$fyear";
            elseif ($dayfalls == 6):
                $tbh = "27-12-$fyear";
            else:
                $tbh = "25-12-$fyear";
            endif; 
            if (strtotime($fdate) == strtotime($tbh)) {$isBH = TRUE;}
            
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
       
       
                //$base = new DateTime("$year-03-21");
            //$days = easter_days($year);  
       
       
       
       
        ?>
    </body>
</html>
