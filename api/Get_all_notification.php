<?php
    // http://127.0.0.1:2222/api/get_all_notification.php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include_once("../database/connection.php");

    try{
        $query = "SELECT id, name, price1, price2,day
                    FROM notifications";

                    
        $result = $dbConn->query($query);
        $notifications=$result->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(
            array(
                "status" => true,
                "message" => "Lấy được dữ liều ròi nè mày",
                "data" => $notifications
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
