<?php
    // http://localhost/api/refuse-job.php
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

        $query = "SELECT * FROM job WHERE id = $jobId AND id_user is null";
          
        $result  = $dbConn->query($query);
        $job = $result->fetchAll(PDO::FETCH_ASSOC);
        $infoUser = getInfoUser($token);
        $userId = (int)$infoUser['id'];
        
        if(count($job) > 0)
        {
            $query2  = "SELECT * FROM users WHERE id = $userId";
            $user   = $dbConn->query($query2);
            $user   = $user->fetchAll(PDO::FETCH_ASSOC);
            if(empty($user['refuse']))
            {
                $user['refuse'] = "";
            }
            $refuse = json_decode($user['refuse'],true);

            $refuse[]   = $jobId;
            array_unique($refuse);
            $dataJson = json_encode($refuse);
            $query = "UPDATE users SET refuse = '$dataJson' WHERE id = $jobId";
            $result  = $dbConn->query($query);
            echo json_encode(
                array(
                    "status" => true,
                    "message" => "Success",
                    "data" => $job
                    )
                );
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
