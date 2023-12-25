<?php
    // http://localhost/api/accept-job.php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include_once("../database/connection.php");
    include_once("./jwt.php");
    try{
        // kiểm tra đăng nhập dữ liệu
        // $token   = get_bearer_token();
        // $ischeck = is_jwt_valid($token);
        // if(!$ischeck)
        // {
        //     throw new Exception("Permision refuse");
        // }
        $name_job = $_REQUEST['name_job'] ?? "";
        $status_job = 0;
        $id_type_job = (int)$_REQUEST['id_type_job'] ?? 1;
        $content_job = $_REQUEST['content_job'] ?? "";
        $description_job = $_REQUEST['description_job'] ?? "";
        $distance = (int)$_REQUEST['distance'] ?? "";
        $est_time = (int)$_REQUEST['est_time'] ?? "";
        $recipient_phone_number = $_REQUEST['recipient_phone_number'] ?? "";
        $name_receiver = $_REQUEST['name_receiver'] ?? "";
        $address_job = $_REQUEST['address_job'] ?? "";
        $lat_job = $_REQUEST['lat_job'] ?? "";
        $long_job = $_REQUEST['long_job'] ?? "";
        $id_ware_house = (int)$_REQUEST['id_ware_house'] ?? 1;
        if(!isValidInput($name_job))
        {
            throw new Exception("Tên đơn hàng không được để trống!");
        }        
        if(!isValidInput($id_type_job))
        {
            throw new Exception("Loại đơn hàng không được để trống!");
        }        
        if(!isValidInput($name_receiver))
        {
            throw new Exception("Tên người nhận kông được để trống!");
        }        
        if(!isValidInput($address_job))
        {
            throw new Exception("Địa chỉ không được để trống!");
        }        
        if(!isValidInput($id_ware_house))
        {
            throw new Exception("id kho không được để trống!");
        }        
        $query = "INSERT INTO job (id,name_job, status_job, id_type_job, content_job, description_job, distance,est_time,recipient_phone_number,name_receiver,address_job,lat_job,long_job,id_ware_house)
        VALUES (NULL,'$name_job', $status_job, $id_type_job, '$content_job', '$description_job', $distance,$est_time,'$recipient_phone_number','$name_receiver','$address_job','$lat_job','$long_job',$id_ware_house)";
          
        $result  = $dbConn->query($query);
        
        echo json_encode(
            array(
                "status" => true,
                "message" => "Đơn hàng được tạo thành công"
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
