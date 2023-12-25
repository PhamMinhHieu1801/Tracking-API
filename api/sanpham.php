<?php
    // http://192.168.1.163:1111/api/sanpham.php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include_once("../database/connection.php");

    try{
        $query = "SELECT id, name, content, author
                    FROM sanpham";

                    
        $result = $dbConn->query($query);
        $sanpham=$result->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(
            array(
                "status" => true,
                "message" => "Lấy được dữ liều ròi nè mày",
                "data" => $sanpham
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
