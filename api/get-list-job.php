<?php
    // http://localhost/api/get-list-job.php
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
        $query = "SELECT job.*, type_job.name_type ,ware_house.name_ware_house,ware_house.address_ware_house,ware_house.lat_ware_house,ware_house.long_ware_house FROM job JOIN type_job ON job.id_type_job = type_job.id JOIN ware_house ON job.id_ware_house = ware_house.id WHERE job.status_job = 0 AND job.id_user IS NULL";
          
        $result  = $dbConn->query($query);
        $job = $result->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(
            array(
                "status" => true,
                "message" => "Success",
                "data" => $job
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
