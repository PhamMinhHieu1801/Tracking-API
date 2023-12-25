<?php 
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include_once("../database/connection.php");

    try{
        $email      = $_REQUEST['email'] ?? "";
        $password   = $_REQUEST['password'] ?? "";
        $name       = $_REQUEST['name'] ?? "";
        if(!isValidInput($email))
        {
            throw new Exception("Email sai định dạng !");
        }
        if(!isValidInput($password))
        {
            throw new Exception("Password sai định dạng !");
        }
        if(!isValidInput($name))
        {
            throw new Exception("Name sai định dạng !");
        }
        
        // Băm mật khẩu với salt và lưu vào cơ sở dữ liệu
        $hashedPassword = hashPassword($password);
        // kiem tra email da ton tai
        $query = "SELECT * FROM users WHERE email = '$email'";
        $stmt = $dbConn -> prepare($query);
        $stmt ->execute();
        $num = $stmt->rowCount();
        if ($num > 0)
        {
            throw new Exception("Email đã tồn tại");
        }
        else
        {
            //tao tai khooan
            $query = "INSERT INTO users SET email = '$email',
                password = '$password',name = '$name' ";
            $stmt = $dbConn->prepare($query);
            $stmt->execute();
            echo json_encode(
                array(
                    "status" => true,
                    "message" => "Đăng ký thành công"
                )
            );
        }
    }
    catch (Exception $e)
        {
            echo json_encode(
                array(
                    "status" => false,
                    "message" => $e-> getMessage()
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


?>