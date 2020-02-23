<?php   
    $TowerID = $_REQUEST['TowerID'];
    $target_time = $_REQUEST['target_time'];
    $type = $_REQUEST['type'];

    // $str = '2019121008-2019121209';
    $t = explode("-",$target_time);
    
    $year = floor($t[0]/1000000);
    $t[0] = $t[0]%1000000;
    $month = floor($t[0]/10000);
    $t[0] = $t[0]%10000;
    $day = floor($t[0]/100);
    $hour = $t[0]%100;
    $t[0] = strtotime("$year-$month-$day $hour:00:00");
    $target_time_start = date('Y-m-d H:00:00',$t[0]);
    // print($target_time_start);
    if(count($t) == 2){
        $year = floor($t[1]/1000000);
        $t[1] = $t[1]%1000000;
        $month = floor($t[1]/10000);
        $t[1] = $t[1]%10000;
        $day = floor($t[1]/100);
        $hour = $t[1]%100;
        $t[1] = strtotime("$year-$month-$day $hour:00:00");
        $target_time_end = date('Y-m-d H:00:00',$t[1]);
        // print($target_time_end);

    }
   
    // echo $target_time;
    // echo date('Y-m-d H:00:00',$target_time);
    // $TowerID = 34;
    // $target_time ='2019-11-10 20:00:00';
    $servername = "";
    $username = "";
    $password = "";
    header('content-type:application/json;charset=utf8');
    
    // set time clock
    date_default_timezone_set('Asia/Taipei');
    // get time_now
    // $date_now ="".date("Y-m-d H:00:00");

    $web_dbname = "TowerBase_WEB";

    // connect  DB
    $cur_db = mysqli_connect($servername,$username,$password)
        or die("sql connect failed!<br>");
    if(!$cur_db)
       die("error<br>");
    mysqli_query($cur_db, 'SET NAMES utf8');
    
    mysqli_select_db($cur_db,$web_dbname) or
        die("select failed<br>");

    // $sql = "SELECT chart_Rainfall_avghour.TowerID,chart_Rainfall_avgday.RouteID,chart_Rainfall_avgday.time FROM chart_Rainfall_avgday WHERE chart_Rainfall_avgday.TowerID=".$TowerID."";
    if($type == null || strcmp($type,"month")==0){
         if(count($t) == 1){
            $sql_month = "SELECT chart_Rainfall_avgmonth.TowerID,chart_Rainfall_avgmonth.rainfall,chart_Rainfall_avgmonth.time FROM chart_Rainfall_avgmonth WHERE chart_Rainfall_avgmonth.TowerID=".$TowerID." and chart_Rainfall_avgmonth.time <= '$target_time_start' order by chart_Rainfall_avgmonth.time DESC LIMIT 1";
        }else{
            $sql_month = "SELECT chart_Rainfall_avgmonth.TowerID,chart_Rainfall_avgmonth.rainfall,chart_Rainfall_avgmonth.time FROM chart_Rainfall_avgmonth WHERE chart_Rainfall_avgmonth.TowerID=".$TowerID." and chart_Rainfall_avgmonth.time >= '$target_time_start' AND chart_Rainfall_avgmonth.time <= '$target_time_end' order by chart_Rainfall_avgmonth.time ASC ";
        }
        $result_month = mysqli_query($cur_db,$sql_month);
        $results_month = array();
        while ($row = mysqli_fetch_assoc($result_month)) {  
            $row['data_type'] = "month";
            $results_month[] = $row;     
        }
        if(!empty($results_month)){
            $all['month'] = $results_month;
            //$results_month = json_encode($results_month,JSON_UNESCAPED_UNICODE);
            //echo $results_month;
        }
        mysqli_free_result($result_month);
    }
    
    if($type == null || strcmp($type,"day")==0){
         if(count($t) == 1){
            $sql_day = "SELECT chart_Rainfall_avgday.TowerID,chart_Rainfall_avgday.rainfall,chart_Rainfall_avgday.time FROM chart_Rainfall_avgday WHERE chart_Rainfall_avgday.TowerID=".$TowerID." and chart_Rainfall_avgday.time <= '$target_time_start' order by chart_Rainfall_avgday.time DESC LIMIT 1";
        }else{
            $sql_day = "SELECT chart_Rainfall_avgday.TowerID,chart_Rainfall_avgday.rainfall,chart_Rainfall_avgday.time FROM chart_Rainfall_avgday WHERE chart_Rainfall_avgday.TowerID=".$TowerID." and chart_Rainfall_avgday.time BETWEEN '$target_time_start' AND '$target_time_end' order by chart_Rainfall_avgday.time ASC ";
        }
        $result_day = mysqli_query($cur_db,$sql_day);
        $results_day = array();
        while ($row = mysqli_fetch_assoc($result_day)) {
            $row['data_type'] = "day";
            $results_day[] = $row;
        }
        if(!empty($results_day)){
            $all['day'] = $results_day;
            //$results_day = json_encode($results_day,JSON_UNESCAPED_UNICODE);
            //echo $results_day;
        }
        mysqli_free_result($result_day);
    }
    if($type == null || strcmp($type,"hour")==0 || strcmp($type,"three_hour")==0){
         if(count($t) == 1){
            $sql_hour = "SELECT chart_Rainfall_avghour.TowerID,chart_Rainfall_avghour.rainfall,chart_Rainfall_avghour.time FROM chart_Rainfall_avghour WHERE chart_Rainfall_avghour.TowerID=".$TowerID." and chart_Rainfall_avghour.time <= '$target_time_start' order by chart_Rainfall_avghour.time DESC LIMIT 1";
        }else{
            $sql_hour = "SELECT chart_Rainfall_avghour.TowerID,chart_Rainfall_avghour.rainfall,chart_Rainfall_avghour.time FROM chart_Rainfall_avghour WHERE chart_Rainfall_avghour.TowerID=".$TowerID." and chart_Rainfall_avghour.time BETWEEN '$target_time_start' AND '$target_time_end' order by chart_Rainfall_avghour.time ASC ";
        }
        $result_hour = mysqli_query($cur_db,$sql_hour);
        $results_hour = array();
        while ($row = mysqli_fetch_assoc($result_hour)) {
            $row['data_type'] = "hour";
            $results_hour[] = $row;
        }
        if(!empty($results_hour) && (strcmp($type,"hour")==0 || $type == null)){
            $all['hour'] = $results_hour;
            //$results_hour_show = json_encode($results_hour,JSON_UNESCAPED_UNICODE);
            //echo $results_hour_show;
        }
        mysqli_free_result($result_hour);
    }
    if($type == null || strcmp($type,"three_hour")==0){
        for ($i = 0; $i <= count($results_hour)-3; $i = $i+3) {
            $three_rainfall = 0;
            for ($j = 0; $j <= 2; $j++) {
                $three_rainfall += $results_hour[$i+$j]['rainfall'];
            }
            $results_three_hour[] = array("TowerID"=>$results_hour[$i]['TowerID'], "rainfall"=>$three_rainfall, "time"=>$results_hour[$i]['time'], "data_type"=>"three_hour");
        }

        if(!empty($results_three_hour)){
            $all['three_hour'] = $results_three_hour;
            //$results_three_hour = json_encode($results_three_hour,JSON_UNESCAPED_UNICODE);
            //echo $results_three_hour;
        }
    }
    $all = json_encode($all,JSON_UNESCAPED_UNICODE);
    echo $all;

    mysqli_close($cur_db);

?>