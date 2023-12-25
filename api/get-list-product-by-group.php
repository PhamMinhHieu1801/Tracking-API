<?php
    // http://localhost/api/get-list-product-by-group.php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include_once("../database/connection.php");
    include_once("./jwt.php");
    try{
        // kiểm tra đăng nhập
        $token   = get_bearer_token();
        $ischeck = is_jwt_valid($token);
        if(!$ischeck)
        {
            throw new Exception("Permision refuse");
        }
        // query lấy ra danh sách loại sản phẩm
        $query2 = "SELECT * FROM type_product";
          
        $data   = $dbConn->query($query2);
        $type_products  = $data->fetchAll(PDO::FETCH_ASSOC);

        $valueData = [];
        if(empty($type_products))
        {
            echo json_encode(
                array(
                    "status" => true,
                    "message" => "Danh sách sản phẩm đang trống",
                    "data" => $valueData
                    )
                );
        }
        // lặp qua mảng loại sản phẩm và lấy theo id map tương ứng
        foreach ($type_products as $type_product) {

            $typeId     = (int)$type_product['id'];
            $typeNameEn = $type_product['name_en'] ?? "";

            $query      = "SELECT sanpham.*, type_product.name FROM sanpham JOIN type_product ON sanpham.type = type_product.id WHERE sanpham.type = $typeId order by is_read desc";
            $result     = $dbConn->query($query);
            $sanphams   = $result->fetchAll(PDO::FETCH_ASSOC);

            $valueData[$typeNameEn] = $sanphams;
        }
        echo json_encode(
            array(
                "status" => true,
                "message" => "Success",
                "data" => $valueData
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
