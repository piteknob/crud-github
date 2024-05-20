<?php

$db = \Config\Database::connect();

function joinProductCategory()
{
    $data = "SELECT product.*, category.category_name 
        FROM product
        LEFT JOIN category on product.product_category_id = category.category_id";
    return $data;
}

function joinProductProduct_stock()
{
    $db = db_connect();
    $data = "SELECT product.product_category_id, product.product_category_name, product_stock.*
    FROM product
    LEFT JOIN product_stock on product.product_id = product_stock.product_stock_product_id";

    return $data;
}

function pagination($page, $sql, $jumlahData)
{
    $db = db_connect();

    $limit = 3;
    $offset = ($page - 1) * $limit;
    $sql .= " LIMIT {$offset}, {$limit}";
    $query = $db->query($sql);
    $jumlahPage = ceil($jumlahData / $limit);
    $pageSebelumnya = ($page - 1 == 0) ? null : ($page - 1);
    $pageSelanjutnya = ($page + 1 == $jumlahPage + 1) ? null : ($page + 1);

    $object = (object) [];
     $object->filter = $query->getResultArray();
     $object->pagination = [
         'jumlah_data' => $jumlahData,
         'page' => $page,
         'jumlah_page' => $jumlahPage,
         'page_sebelumnya' => $pageSebelumnya,
         'page_selanjutnya' => $pageSelanjutnya
    ];

    return $object;
}
