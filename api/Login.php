<?php
// http://127.0.0.1:1111/api/Login.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once("../database/connection.php");
include_once("./jwt.php");

try {
    // doc du lieu tu body request
    $email = $data->email ?? $_REQUEST['email'];
    $password = $data->password ?? $_REQUEST['password'];
    // kiểm tra dữ liệu
    if (empty($email) || empty($password)) {
        throw new Exception("Dữ liệu ko đc để trống kìa mầy");
    }
    $query = "SELECT * FROM users WHERE email = '$email' ";
    $result = $dbConn->query($query);
    $user = $result->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        throw new Exception("Email chưa được đăng ký");
    } else {
        $pswd = $user['password'];
        if ($password != $pswd) {
            throw new Exception("Mật khẩu ko chính xác");
        }
        else{
            // tạo token
            $token = array(
                "id" => $user['id'],
                "email"=> $user['email'],
                "name"=> $user['name'],
                "exp" => (time() + 60)
            );
            $headers = array('alg' => 'HS256','tyoe' =>'JWT');
            $jwt = generate_jwt($headers,$token);

            $idUser = (int)$user['id'];
            $deviceId = "" ;
            if(isset($_REQUEST['device_id']))
            {
                $deviceId = $_REQUEST['device_id'] ;
                $query = "UPDATE users SET device_id = '$deviceId' WHERE id = $idUser";
          
                $result  = $dbConn->query($query);
            }

            echo json_encode(
                array(
                    "status" => true,
                    "message" => "Đăng nhập thành công",
                    "token" => $jwt
                )
            );
        }
    }
} catch (Exception $e) {
    echo json_encode(
        array(
            "status" => false,
            "message" => $e->getMessage()
        )
    );
}

// Hàm để tạo mật khẩu băm
function hashPassword($password) {
    // Sử dụng PASSWORD_DEFAULT để chọn thuật toán băm mặc định
    return password_hash($password, PASSWORD_DEFAULT);
}

// Hàm để kiểm tra mật khẩu
function verifyPassword($userInputPassword, $storedHashedPassword) {
    // Sử dụng password_verify để so sánh mật khẩu người dùng và mật khẩu đã lưu
    return password_verify($userInputPassword, $storedHashedPassword);
}