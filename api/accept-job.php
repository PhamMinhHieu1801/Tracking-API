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
        $token   = get_bearer_token();
        $ischeck = is_jwt_valid($token);
        if(!$ischeck)
        {
            throw new Exception("Permision refuse");
        }
        $jobId = $_REQUEST['id'] ?? "";
        if(!isValidInput($jobId))
        {
            throw new Exception("ID công việc không được để trống!");
        }        
        $query = "SELECT * FROM job WHERE id = $jobId";
          
        $result  = $dbConn->query($query);
        $job = $result->fetchAll(PDO::FETCH_ASSOC);
        $infoUser = getInfoUser($token);
        $userId = (int)$infoUser['id'];
        if(count($job) > 0)
        {
            $query = "UPDATE job SET id_user = $userId WHERE id = $jobId";
            $result  = $dbConn->query($query);
            echo json_encode(
                array(
                    "status" => true,
                    "message" => "Success",
                    "data" => $job
                    )
                );
            return;
        }
        echo json_encode(
            array(
                "status" => false,
                "message" => "Công việc đã được giao trước đó"
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
