<?php 

include 'dbconnection.php';

$url=array("https://docs.google.com/spreadsheets/d/e/2PACX-1vTw6snNkCCs_HmfzE0XCFZ-gZRrDH6XZSpzzwWk9mBVo10-x9pcDgEkh_LOv4WUFNUpGDrkLe3WsJr6/pub?gid=1198581277&single=true&output=csv", 
 "https://docs.google.com/spreadsheets/d/e/2PACX-1vQpxx5_ph4ZTHXYtV9sljo3SK7B0DMLYD6TVR3fkNnRj6OVGBxsbTmpUH0N3x1E10BLIqTlRofdnvK7/pub?gid=475877382&single=true&output=csv");
   

$sql = "SELECT * FROM cron";
$result =$con->query($sql);

if($result->num_rows == 0){
    insertSheetData($url, $con, $isToday=0);
}else{
    insertSheetData($url, $con,$isToday=1, date("n/j/Y"));
}

function insertSheetData($url, $con, $isToday, $date=''){
    foreach($url as $url){
        $count=0;
        
        $spreadsheet_url = $url;
        if (($handle = fopen($spreadsheet_url, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                
                if ($count==0){
                    $count++;
                    continue;
                }

                if($date != '' && $date != null){
                    if(stripos($data[0], $date) !== false){
                        $sql = "INSERT INTO `resources`(`name`, `mobile`, `email`, `location`,`address`, `work experience`, `skills`,`resume`,  `current role`, `current company`, `notice period`,`joining date`, `linkedin`, `recruiter`,`hourlyRate`,`device available`,`graduation-course`,`graduation-type`,`current ctc`,`freelance ready`,`type of job`) 
                        values('$data[1]','$data[2]','$data[3]','$data[4]','$data[4]','$data[9]','$data[8]','$data[21]','$data[10]','$data[11]','$data[18]','$data[18]','$data[20]','$data[23]','$data[25]','$data[19]','$data[5]','$data[6]','$data[12]','$data[14]','$data[15]')";
                        $result =$con->query($sql);
                    }
                }elseif($isToday == 0){
                    $sql = "INSERT INTO `resources`(`name`, `mobile`, `email`, `location`,`address`, `work experience`, `skills`,`resume`,  `current role`, `current company`, `notice period`,`joining date`, `linkedin`, `recruiter`,`hourlyRate`,`device available`,`graduation-course`,`graduation-type`,`current ctc`,`freelance ready`,`type of job`) 
                    values('$data[1]','$data[2]','$data[3]','$data[4]','$data[4]','$data[9]','$data[8]','$data[21]','$data[10]','$data[11]','$data[18]','$data[18]','$data[20]','$data[23]','$data[25]','$data[19]','$data[5]','$data[6]','$data[12]','$data[14]','$data[15]')";
                    $result =$con->query($sql);
                }
                $spreadsheet_data[] = $data;
            }
            fclose($handle);
        }else{
            die("Problem reading csv");
        }


    }  
    if($date == ''){
        $today = date("d/m/y");
        $sql = "INSERT INTO `cron` (`ID`, `TIMESTAMP`) VALUES (NULL,'$today')";
        $result =$con->query($sql);
    }
}

    
