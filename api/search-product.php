<?php
    // http://localhost/api/search-product.php
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
        $type = '';
        if(isset($_REQUEST['type']))
        {
            $type = $_REQUEST['type'];
        }
        if (empty($type)) {
            throw new Exception("type không được để trống");
        }
        $query = "SELECT sanpham.*, type_product.name FROM sanpham JOIN type_product ON sanpham.type = type_product.id Where type_product.name LIKE '%$type%' order by created_at desc";
          
        $result  = $dbConn->query($query);
        $sanpham = $result->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(
            array(
                "status" => true,
                "message" => "Success",
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
