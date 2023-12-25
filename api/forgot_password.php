<?php 
// http://127.0.0.1:1111/api/forgot_password.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once("../database/connection.php");


use PHPMailer\PHPMailer\PHPMailer;

include_once $_SERVER['DOCUMENT_ROOT'] . '/utilities/PHPMailer-master/src/PHPMailer.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/utilities/PHPMailer-master/src/SMTP.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/utilities/PHPMailer-master/src/Exception.php';

try{
    // doc email tu body
    $data = json_decode(file_get_contents("php://input"));
    $email = $data->email;
    // kiem tra email co hay ko
    if (!isset($email)) {
        throw new Exception("Du lieu ko duoc de trong kiaaa");
    }
    // kiem tra email co trong db hay khong
    $query = " SELECT id FROM users WHERE email = '$email' ";
    $result = $dbConn->query($query);
    if (!$result) {
        echo json_encode(
            array(
                "status" => false,
                "message" => "Email khong ton tai"
            )
        );
    }


    // tao ra token bang md5
    $token = md5($email . time());
    // luu token vao trong db
    $dbConn->query(" insert into reset_password
                    (email , token) values ('$email' , '$token') ");

    // gui email
    $link = "<a href='http://127.0.0.1:1111/reset_password.php?email="
    . $email . "&token=" . $token . "'>Click to reset password</a>";
    $mail = new PHPMailer();
    $mail->CharSet = "utf-8";
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Username = "namdmps23583@fpt.edu.vn";
    $mail->Password = "oyqxlhrvmrfofmpm";
    $mail->SMTPSecure = "ssl";
    $mail->Host = "ssl://smtp.gmail.com";
    $mail->Port = "465";
    $mail->From = "duongminhnam@fpt.edu.vn";
    $mail->FromName = "Duong Minh Nam";
    $mail->addAddress($email, 'Hello');
    $mail->Subject = "Reset Password";
    $mail->isHTML(true);
    $mail->Body = "Click on this link to reset password " . $link . " ";
    $res = $mail->Send();
    if ($res) {
        echo json_encode(array(
            "message" => "Email sent",
            "status" => true
        ));
    } else {
        echo json_encode(array(
            "message" => " Email sent failed",
            "status" => false
        ));
    }



} catch (Exception $e) {
    echo json_encode(
        array(
            "status" => false,
            "message" => $e->getMessage()
        )
    );
}

?>