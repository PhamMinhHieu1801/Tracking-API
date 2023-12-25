<?php
    // http://localhost/api/push-notify.php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include_once("../database/connection.php");
    include_once("./jwt.php");
    try{
        // kiểm tra đăng nhập dữ liệu
        $token   = get_bearer_token();
        $ischeck = is_jwt_valid($token);
        if(!$ischeck)
        {
            throw new Exception("Permision refuse");
        }
        $distance = (int)$_REQUEST['distance'] ?? 1;
        if(!isValidInput($distance))
        {
            throw new Exception("khoảng cách không được để trống không được để trống!");
        }   
        // kiểm tra đăng nhập dữ liệu
        $query = "SELECT job.*, type_job.name_type ,ware_house.name_ware_house,ware_house.address_ware_house,ware_house.lat_ware_house,ware_house.long_ware_house FROM job JOIN type_job ON job.id_type_job = type_job.id JOIN ware_house ON job.id_ware_house = ware_house.id WHERE job.status_job = 0 AND distance >= $distance AND job.id_user IS NULL";
          
        $result  = $dbConn->query($query);
        $job = $result->fetchAll(PDO::FETCH_ASSOC);

        foreach($job as $key => $item)
        {
            $title = $item['name_job'] ?? "";
            $body = $item['content_job'] ?? "";
            $response = sendFCMNotification($token, $title, $body);
        }
        echo json_encode(
            array(
                "status" => true,
                "message" => "Success",
                )
            );
    }catch(Exception $e)
    {
        echo json_encode(
            array(
                "status" => false,
                "message" => $e->getMessage()
            )
            );
    }


    function sendFCMNotification($token, $title, $body) {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $serverKey = 'AAAAy9l2Mr0:APA91bFYyyGzP4KpTePCsxB0H7wB2-H3D_w1QBFnuQ6xvL_qdNfmlepIy1MgzVWLCuO8XC3-7g1ChFg6PO9dWoWWvPbka1tq35u5bgcW9qyrG_MwosZDImyzljUlHG8i-kVPm4puiWJ8';
    
        $notification = [
            'title' => $title,
            'body' => $body,
            'sound' => 'default'
        ];
    
        $data = [
            'title' => $title,
            'body' => $body
        ];
    
        $fields = [
            'to' => $token,
            'notification' => $notification,
            'data' => $data
        ];
    
        $headers = [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json'
        ];
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    
        $result = curl_exec($ch);
    
        if ($result === false) {
            die('Curl failed: ' . curl_error($ch));
        }
    
        curl_close($ch);
        return $result;
    }