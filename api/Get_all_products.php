<?php
    // http://127.0.0.1:1111/api/get_all_products.php?keyword=4&sort=1&limit=3&page=26+9
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include_once("../database/connection.php");

    try{
        // dọc keyword từ url
        $keyword = isset($_Get['keyword']) ? $_GET['keyword'] :"";

        // sắp xếp
        $sort = isset($_GET['sord']) ? $GET['sort'] :0;
        // neu sort la chuw chuyen thanh so
        if (!is_numeric($sort)) {
            $sort = 0;
        }
        if ($sort == 0) {
            $sort = 'p.id asc';
        } else if ($sort == 1){
            $sort = 'p.id desc';
        } else if ($sort == 2){
            $sort = 'p.price asc';
        } else if ($sort == 3){
            $sort = 'p.price desc';
        }

        // limit : số lượng bản ghi trên 1 trang
        $limit = isset($_GET['limit']) ? $_GET['limit'] : 3;
        //page : trang hiện tại
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        if (!is_numeric($limit)) {
            $limit = 3;
        }
        if (!is_numeric($page)) {
            $page = 1;
        }
        // tinh so phan tu bo qua offset
        $offset = ($page - 1) * $limit;


        // lay du lieu tu database
        $query = "SELECT p.id, p.name, p.price, p.quantity,
		            p.image,p.description, p.categoryId 
                    FROM products p
                    INNER JOIN categories c
                    on p.`categoryId` = c.id
                    WHERE p.name like '%$keyword%' -- name sản phẩm có chứa số 4
                    ORDER BY $sort
                    LIMIT $offset,$limit -- phân trang";

        $result = $dbConn->query($query);
        $products=$result->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(
            array(
                "status" => true,
                "message" => "Lấy được dữ liều ròi nè mày",
                "data" => $products
                )
            );
    }
    catch(Exception $e)
    {
        echo json_encode(
            array(
                "status" => false,
                "message" => $e->getMessage()
            )
            );
    }
