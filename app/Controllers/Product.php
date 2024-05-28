<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use App\Controllers\Core\AuthController;


class Product extends AuthController
{
    public function DetailData()
    {
        $query['data'] = ['product'];

        $query['select'] = [
            'product_id' => 'id_product',
            'product_stock_id' => 'id_stock',
            'product_name' => 'name',
            'product_category_name' => 'category_name',
            'product_stock_unit_name' => 'unit_name',
            'product_stock_value' => 'value',
            'product_stock_price_buy' => 'price_buy',
            'product_stock_price_sell' => 'price_sell',
            'product_stock_in' => 'stock_in',
            'product_stock_out' => 'stock_out',
            'product_created_at' => 'created',
            'product_updated_at' => 'updated',
        ];

        $query['join'] = [
            'product_stock' => 'product_stock.product_stock_product_id = product.product_id'
        ];

        $query = generateDetailData($this->request->getVar(), $query, $this->db);

        return $this->responseSuccess(ResponseInterface::HTTP_OK, 'detail data', $query);
    }

    public function listData()
    {
        $query['data'] = ['product'];

        $query['select'] = [
            'product_name' => 'name',
            'product_category_name' => 'category_name',
            'product_stock_unit_name' => 'unit_name',
            'product_stock_price_sell' => 'price',
        ];

        // can change to left_join, right_join or just join(inner join)
        $query['join'] = [
            'product_stock' => 'product_stock.product_stock_product_id = product.product_id'
        ];

        $query['where_detail'] = [];

        // ---------- PAGINATION TRUE/FALSE (DELETE THIS AND PAGINATION AUTO CREATED) ---------- //
        $query['pagination'] = [
            'pagination' => true
        ];

        // ----------- FIELD SEARCH DATA ----------- // 
        $query['search_data'] = [
            'product_stock_unit_name',
            'product_stock_product_name',
            'product_category_name',
        ];

        // ----------- LIMIT SHOW DATA ----------- //
        $query['limit'] = [
            'limit' => 5,
        ];

        $query['filter'] = [
            "product_category_name",
            "product_stock_unit_name",
        ];

        $query['filter_between'] = [
            "product_stock_price_sell"
        ];

        // $query['date'] = [
        //     "product_created_at"
        // ];

        $query['group_by'] = [
            'product.product_name'
        ];

        $query['order_by'] = [
            'product_stock_price_sell'
        ];

        $query = generateListData($this->request->getVar(), $query, $this->db);


        return $this->responseSuccess(ResponseInterface::HTTP_OK, 'list data', $query);
    }


    // ------------------------------------------ INDEX ------------------------------------------ //
    public function index()
    {
    }



    // --------------------------------------- SHOW DATA (SEARCH FILTER) --------------------------------------- //
    public function search()
    {
        $get = $this->request->getVar();
        $db = db_connect();


        $search = isset($get["search"]) ? $get["search"] : ''; // jika data tidak ada, maka akan bernilai string kosong 
        $unit = isset($get["unit"]) ? $get["unit"] : '';
        $kategori = isset($get["kategori"]) ? $get["kategori"] : '';
        $range_harga_awal = isset($get["range_harga_awal"]) ? $get["range_harga_awal"] : null; // jika data tidak ada, maka akan bernilai null
        $range_harga_akhir = isset($get["range_harga_akhir"]) ? $get["range_harga_akhir"] : null;
        $page = isset($get["page"]) ? $get["page"] : 1; // jika data tidak ada, maka akan bernilai 1

        $sql = "SELECT 
                    product.product_id AS id,
                    product.product_name AS produk,
                    product.product_category_name AS kategori,
                    product_stock_unit_name AS unit,
                    product_stock_price_sell AS harga
            FROM
                product_stock
            LEFT JOIN product ON product_stock.product_stock_product_id = product.product_id
            WHERE 1=1";

        if ($range_harga_awal != null && $range_harga_akhir != null) {
            $sql .= " AND product_stock_price_sell BETWEEN '{$range_harga_awal}' AND '{$range_harga_akhir}'";
        }
        if ($range_harga_awal != null) {
            $sql .= " AND product_stock_price_sell >= '{$range_harga_awal}'";
        }
        if ($range_harga_akhir != null) {
            $sql .= " AND product_stock_price_sell <= '{$range_harga_akhir}'";
        }
        if (!empty($unit)) {
            $sql .= " AND product_stock_unit_name = '{$unit}'";
        }
        if (!empty($kategori)) {
            $sql .= " AND product.product_category_name = '{$kategori}'";
        }
        if (!empty($search)) {
            $sql .= " AND (
                product.product_name LIKE '%{$search}%' OR
                product_stock_unit_name LIKE '%{$search}%' OR
                product.product_id LIKE '%{$search}%' OR
                product.product_category_name LIKE '%{$search}%' OR
                product_stock.product_stock_unit_name LIKE '%{$search}%')";
        }

        print_r($sql);
        die;
        $query = $db->query($sql);
        $result = $query->getResultArray();
        $jumlahData = count($result);

        // pagination
        $data = [
            'page' => $page,
            'sql' => $sql,
            'count' => $jumlahData,
            'limit' => 3
        ];
        // $pitek = pagination($data);

        // return $this->responseSuccess(ResponseInterface::HTTP_OK, 'OK', $pitek, '');
    }




    // ------------------------------------- INSERT DATA USING OBJECT JSON ------------------------------------- //
    public function insertObject()
    {
        $posts = $this->request->getJSON();
        $db = db_connect();

        $response = [];
        $error = [];

        foreach ($posts->product_array as $key) {
            $validator = \Config\Services::validation();
            $validator->setRules([
                'product_name' => 'required',
                'product_category_id' => 'required',
                'product_unit_id' => 'required'
            ]);
            $getArray = json_encode($key);
            $getArray = json_decode($getArray);



            if (!$validator->run((array)$key)) {
                $error = $validator->getErrors();
                if ($error) {
                    return $this->responseErrorValidation(ResponseInterface::HTTP_PRECONDITION_FAILED, 'error validation', $error);
                }
            }
        }

        foreach ($posts->product_array as $post) {
            $produk = htmlspecialchars($post->product_name);
            $stok = $post->product_stock_value;
            $beli = $post->product_buy;
            $jual = $post->product_sell;
            $kategori = $post->product_category_id;
            $unit = $post->product_unit_id;

            $queryProduct = "INSERT INTO product (product_name, product_category_id, product_category_name)
        SELECT '{$produk}', category_id, category_name
        FROM category
        WHERE category_id = '{$kategori}'";

            $db->query($queryProduct);

            // get latest id
            $idProduct = $db->insertID();

            $queryStock = "INSERT INTO product_stock (product_stock_product_id, product_stock_product_name, product_stock_unit_id, product_stock_unit_name, product_stock_value, product_stock_price_buy, product_stock_price_sell)
        SELECT '{$idProduct}', '{$produk}', '{$unit}', unit_name, '{$stok}', '{$beli}', '{$jual}'
        FROM unit
        WHERE unit_id = '{$unit}'";

            $db->query($queryStock);

            $detail = "SELECT product_stock_product_name as `name`, product_stock_unit_name as unit, product_category_name as category, product_stock_value as `value`, product_stock_price_sell as sell, product_stock_price_buy as buy 
                   FROM product
                   LEFT JOIN product_stock ON product.product_id = product_stock.product_stock_product_id
                   WHERE product_stock_product_id = '{$idProduct}'";

            $dataLengkap = $db->query($detail);
            $dataLengkap = $dataLengkap->getResultArray();

            $response[] = $dataLengkap;
        }
        return $this->responseSuccess(ResponseInterface::HTTP_OK, 'data successfully added', $response, '');
    }




    // --------------------------------------- INSERT/CREATE DATA --------------------------------------- //
    public function insert()
    {
        $post = $this->request->getPost();
        $db = db_connect();
        $response = [];
        $error = [];

        $validator = \Config\Services::validation();
        $validator->setRules([
            'product_name' => 'required',
            'product_category_id' => 'required',
            'product_unit_id' => 'required'
        ]);


        if (!$validator->run((array)$post)) {
            $error[] = $validator->getErrors();
        }

        if (count($error) > 0) {

            return $this->responseErrorValidation(ResponseInterface::HTTP_PRECONDITION_FAILED, 'Produk harus di isi dengan lengkap', $error);
        }

        $produk = htmlspecialchars($post["product_name"]);
        $stok = htmlspecialchars($post["product_stock_value"]);
        $beli = htmlspecialchars($post["product_buy"]);
        $jual = htmlspecialchars($post["product_sell"]);
        $kategori = $post["product_category_id"];
        $unit = $post["product_unit_id"];

        //  gas transaction
        $db->transStart();

        $queryProduct = "INSERT INTO product (product_name, product_category_id, product_category_name)
            SELECT ?, category_id, category_name
            FROM category
            WHERE category_id = ?";

        $db->query($queryProduct, [$produk, $kategori]);

        // get latest id
        $idProduct = $db->insertID();

        $queryStock = "INSERT INTO product_stock (product_stock_product_id, product_stock_product_name, product_stock_unit_id, product_stock_unit_name, product_stock_value, product_stock_price_buy, product_stock_price_sell)
            SELECT ?, ?, ?, unit_name, ?, ?, ?
            FROM unit
            WHERE unit_id = ?";

        $db->query($queryStock, [$idProduct, $produk, $unit, $stok, $beli, $jual, $unit]);

        // commit transaction
        $db->transComplete();

        $getProduct = "SELECT  product.product_name, product.product_category_id, product_stock.product_stock_unit_id, product_stock.product_stock_value, product_stock.product_stock_price_buy, product_stock.product_stock_price_sell
        FROM product
        LEFT JOIN product_stock
        ON product_stock.product_stock_product_id = product.product_id
        WHERE product.product_id = '{$idProduct}'";
        $data = $db->query($getProduct);
        $data = $data->getResultArray();

        $response = $data;

        return $this->responseSuccess(ResponseInterface::HTTP_OK, 'data successfully added', $response);
    }



    // --------------------------------------- UPDATE DATA --------------------------------------- //
    public function update($id = null)
    {
        $post = $this->request->getPost();
        $db = db_connect();


        $produk = $post['name'];
        $stok = $post['value'];
        $beli = $post['buy'];
        $jual = $post['sell'];
        $kategori = $post['category'];
        $unit = $post['unit'];


        $queryProduct = "UPDATE product
            SET product_name = '{$produk}',
                product_category_id = '{$kategori}',
                product_category_name = (SELECT category_name FROM category WHERE category_id = '{$kategori}')
            WHERE product_id = '{$id}'";


        $db->query($queryProduct);

        $queryProductStock = "UPDATE product_stock
            SET product_stock_product_name = '{$produk}',
                product_stock_unit_id = '{$unit}',
                product_stock_unit_name = (SELECT unit_name FROM unit WHERE unit_id = '{$unit}'),
                product_stock_value = '{$stok}',
                product_stock_price_buy = '{$beli}',
                product_stock_price_sell = '{$jual}'
            WHERE product_stock_product_id = '{$id}'";

        $db->query($queryProductStock);

        $data = [
            'name' => $produk,
            'category' => $kategori,
            'unit' => $unit,
            'value' => $stok,
            'buy' => $beli,
            'sell' => $jual
        ];

        return $this->responseSuccess(ResponseInterface::HTTP_OK, 'data successfully updated', $data);
    }



    // --------------------------------------- DELETE DATA --------------------------------------- //
    public function delete($id = null)
    {
        $db = db_connect();

        // mulai transaction
        $db->transStart();

        $builderProduct = $this->db->table('product');
        $builderProductStock = $this->db->table('product_stock');

        $builderProduct->where('product_id', $id);
        $builderProductStock->where('product_stock_product_id', $id);

        $getData = "SELECT product_stock_product_name as `name`, product_stock_unit_name as unit, product_category_name as category, product_stock_value as `value`, product_stock_price_sell as sell, product_stock_price_buy as buy 
        FROM product
        LEFT JOIN product_stock ON product.product_id = product_stock.product_stock_product_id
        WHERE product_stock_product_id = '{$id}'";

        $data = $db->query($getData);
        $data = $data->getResultArray();

        $builderProductStock->delete();
        $builderProduct->delete();

        // commit transaction
        $db->transComplete();

        return $this->responseSuccess(ResponseInterface::HTTP_OK, 'data successfully deleted', $data);
    }




    // --------------------------------------- REDUCE STOCK --------------------------------------- //
    public function reduce()
    {
        $post = $this->request->getPost();
        $db = db_connect();

        // print_r($post);
        // die;

        $id = $post['product'];
        $stock = $post['stock'];
        $kategori = $post['category'];
        $unit = $post['unit'];

        $queryLog = "INSERT INTO log_stock (log_stock_product_id, log_stock_product_name, log_stock_status,log_stock_value, log_stock_category_id, log_stock_category_name, log_stock_unit_id, log_stock_unit_name,log_stock_date)
            SELECT product_id, product_name, 'reduce', '{$stock}', category_id, category_name, unit_id, unit_name, NOW()
            FROM product, category, unit
            WHERE product_id = '{$id}' AND category_id = '{$kategori}' AND unit_id = '{$unit}'";

        $db->query($queryLog);

        $queryStock = "UPDATE product_stock 
            SET product_stock_out = product_stock_out + '{$stock}', 
                product_stock_value = product_stock_value - '{$stock}' 
            WHERE product_stock_product_id = '{$id}' AND product_stock_unit_id = '{$unit}'";


        $db->query($queryStock);

        $data = "SELECT product_stock_product_name as `name`, product_stock_unit_name as unit, product_category_name as category, product_stock_value as `value`, product_stock_out as stock_out, product_stock_in as stock_in 
        FROM product
        LEFT JOIN product_stock ON product.product_id = product_stock.product_stock_product_id
        WHERE product_stock_product_id = '{$id}'";
        $data = $db->query($data);
        $data = $data->getResultArray();

        return $this->responseSuccess(ResponseInterface::HTTP_OK, 'data successfully reduced', $data);
    }


    // --------------------------------------- ADD STOCK --------------------------------------- //

    public function add()
    {
        $post = $this->request->getPost();
        $db = db_connect();

        $id = $post['product'];
        $stock = $post['stock'];
        $kategori = $post['category'];
        $unit = $post['unit'];

        $queryLog = "INSERT INTO log_stock (log_stock_product_id, log_stock_product_name, log_stock_status, log_stock_value, log_stock_category_id, log_stock_category_name, log_stock_unit_id, log_stock_unit_name, log_stock_date)
            SELECT product_id, product_name, 'add', '{$stock}', category_id, category_name, unit_id, unit_name, NOW()
            FROM product, category, unit
            WHERE product_id = '{$id}' AND category_id = '{$kategori}' AND unit_id = '{$unit}'";

        $db->query($queryLog);

        $queryStock = "UPDATE product_stock 
            SET product_stock_in = product_stock_in + '{$stock}', 
                product_stock_value = product_stock_value + '{$stock}' 
            WHERE product_stock_product_id = '{$id}' AND product_stock_unit_id = '{$unit}'";


        $db->query($queryStock);


        $data = "SELECT product_stock_product_name as `name`, product_stock_unit_name as unit, product_category_name as category, product_stock_value as `value`, product_stock_out as stock_out, product_stock_in as stock_in 
        FROM product
        LEFT JOIN product_stock ON product.product_id = product_stock.product_stock_product_id
        WHERE product_stock_product_id = '{$id}'";
        $data = $db->query($data);
        $data = $data->getResultArray();

        return $this->responseSuccess(ResponseInterface::HTTP_OK, 'data successfully added', $data);
    }


    // --------------------------------------- MOVE STOCK --------------------------------------- //
    public function move()
    {
        $post = $this->request->getPost();
        $db = db_connect();

        $data = [];

        $id = $post["product"];
        $stockO = $post["stock_origin"];
        $stockD = $post["stock_destination"];
        $kategori = $post['category'];
        $unit = $post["unit"];
        $idD = $post["product_destination"];
        $unitD = $post["unit_id_destination"];

        $queryLogO = "INSERT INTO log_stock (log_stock_product_id, log_stock_product_name, log_stock_status, log_stock_value, log_stock_category_id, log_stock_category_name, log_stock_unit_id, log_stock_unit_name, log_stock_date)
            SELECT product_id, product_name, 'reduce', $stockO, category_id, category_name, unit_id, unit_name, NOW()
            FROM product, category, unit
            WHERE product_id = '$id' AND category_id = '$kategori' AND unit_id = '$unit'";

        $db->query($queryLogO);

        $idOrigin = $db->insertID();

        $queryLogD = "INSERT INTO log_stock (log_stock_product_id, log_stock_product_name, log_stock_status, log_stock_value, log_stock_category_id, log_stock_category_name, log_stock_unit_id, log_stock_unit_name, log_stock_date)
            SELECT product_id, product_name, 'add', $stockD, category_id, category_name, unit_id, unit_name, NOW()
            FROM product, category, unit
            WHERE product_id = '$idD' AND category_id = '$kategori' AND unit_id = '$unitD'";

        $db->query($queryLogD);

        $idDestination = $db->insertID();

        $queryStock = "UPDATE product_stock 
            SET product_stock_out = product_stock_out + $stockO, 
                product_stock_value = product_stock_value - $stockO 
            WHERE product_stock_product_id = $id AND product_stock_unit_id = $unit";

        $db->query($queryStock);


        $queryMove = "UPDATE product_stock
            SET product_stock_value = product_stock_value + $stockD,
                product_stock_in = product_stock_in + $stockD
            WHERE product_stock_product_id = $idD AND product_stock_unit_id = $unitD";

        $db->query($queryMove);

        $dataOrigin = "SELECT * FROM log_stock WHERE log_stock_id = '{$idOrigin}'";
        $dataOrigin = $db->query($dataOrigin);
        $dataOrigin = $dataOrigin->getResultArray();
        $dataDestination = "SELECT * FROM log_stock WHERE log_stock_id = '{$idDestination}'";
        $dataDestination = $db->query($dataDestination);
        $dataDestination = $dataDestination->getResultArray();

        $data = [
            'origin' => $dataOrigin,
            'destination' => $dataDestination
        ];
        return $this->responseSuccess(ResponseInterface::HTTP_OK, 'data successfully moved', $data);
    }


    // --------------------------------------- SOFT DELETE CATEGORY --------------------------------------- //

    public function softDeleteCategory()
    {
        $post = $this->request->getPost();
        $db = db_connect();
        $deleted = [];
        $delete = htmlspecialchars($post["delete"]);

        $data = "SELECT category_id, category_name FROM category WHERE category_id = '{$delete}'";
        $data = $db->query($data);
        $data = $data->getResultArray();

        $allDataBefore = "SELECT * FROM category";
        $allDataBefore = $db->query($allDataBefore);
        $allDataBefore = $allDataBefore->getResultArray();

        $softDelete = "UPDATE category SET category_deleted_at = NOW() WHERE category_id = '{$delete}'";

        $db->query($softDelete);

        $allDataNow = "SELECT * FROM category";
        $allDataNow = $db->query($allDataNow);
        $allDataNow = $allDataNow->getResultArray();


        $deleted = [
            'deleted' => $data,
            'data_category_before' => $allDataBefore,
            'data_category_now' => $allDataNow
        ];

        return $this->responseSuccess(ResponseInterface::HTTP_OK, 'data successfully soft deleted', $deleted);
    }



    // --------------------------------------- RESTORE CATEGORY --------------------------------------- //
    public function restoreCategory()
    {
        $post = $this->request->getPost();
        $db = db_connect();

        $restore = htmlspecialchars($post["restore"]);

        $data = "SELECT category_id, category_name FROM category WHERE category_id = '{$restore}'";
        $data = $db->query($data);
        $data = $data->getResultArray();

        $allDataBefore = "SELECT * FROM category";
        $allDataBefore = $db->query($allDataBefore);
        $allDataBefore = $allDataBefore->getResultArray();

        $restoreCategory = "UPDATE category SET category_deleted_at = NULL WHERE category_id = '{$restore}'";

        $db->query($restoreCategory);

        $allDataNow = "SELECT * FROM category";
        $allDataNow = $db->query($allDataNow);
        $allDataNow = $allDataNow->getResultArray();




        $response = [
            'restored' => $data,
            'data_category_before' => $allDataBefore,
            'data_category_now' => $allDataNow
        ];

        return $this->responseSuccess(ResponseInterface::HTTP_OK, 'data successfully restored', $response);
    }



    // --------------------------------------- SOFT DELETE UNIT --------------------------------------- //
    public function softDeleteUnit()
    {
        $post = $this->request->getPost();
        $db = db_connect();

        $delete = htmlspecialchars($post["delete"]);

        $data = "SELECT unit_id, unit_name FROM unit WHERe unit_id = '{$delete}'";
        $data = $db->query($data);
        $data = $data->getResultArray();

        $allDataBefore = "SELECT * FROM unit";
        $allDataBefore = $db->query($allDataBefore);
        $allDataBefore = $allDataBefore->getResultArray();

        $softDelete = "UPDATE unit SET unit_deleted_at = NOW() WHERE unit_id = '{$delete}'";

        $db->query($softDelete);

        $allDataNow = "SELECT * FROM unit";
        $allDataNow = $db->query($allDataNow);
        $allDataNow = $allDataNow->getResultArray();

        $response = [
            'deleted' => $data,
            'data_unit_before' => $allDataBefore,
            'data_unit_now' => $allDataNow
        ];

        return
            $this->responseSuccess(ResponseInterface::HTTP_OK, 'data successfully soft deleted', $response);
    }



    // --------------------------------------- RESTORE UNIT --------------------------------------- //
    public function restoreUnit()
    {
        $post = $this->request->getPost();
        $db = db_connect();

        $restore = htmlspecialchars($post["restore"]);

        $data = "SELECT unit_id, unit_name FROM unit WHERE unit_id = '{$restore}'";
        $data = $db->query($data);
        $data = $data->getResultArray();

        $allDataBefore = "SELECT * FROM unit";
        $allDataBefore = $db->query($allDataBefore);
        $allDataBefore = $allDataBefore->getResultArray();

        $restoreUnit = "UPDATE unit SET unit_deleted_at = NULL WHERE unit_id = '{$restore}'";

        $db->query($restoreUnit);

        $allDataNow = "SELECT * FROM unit";
        $allDataNow = $db->query($allDataNow);
        $allDataNow = $allDataNow->getResultArray();

        $response = [
            'data' => $data,
            'data_unit_before' => $allDataBefore,
            'data_unit_now' => $allDataNow
        ];

        return $this->responseSuccess(ResponseInterface::HTTP_OK, 'data successfully restored', $response);
    }
}
