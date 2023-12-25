<?php
// http://127.0.0.1:1111/api/Logout.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once("../database/connection.php");
include_once("./jwt.php");

try {
    // kiểm tra đăng nhập dữ liệu
    $token   = get_bearer_token();
    $ischeck = is_jwt_valid($token);
    if(!$ischeck)
    {
        throw new Exception("Permision refuse");
    }
    setcookie("jwt_token", "", time() - 1, "/");
        echo json_encode(
            array(
                "status" => true,
                "message" => "Đăng xuất thành công"
            )
        );
    
} catch (Exception $e) {
    echo json_encode(
        array(
            "status" => false,
            "message" => $e->getMessage()
        )
    );
}
