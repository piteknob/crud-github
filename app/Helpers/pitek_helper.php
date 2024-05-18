<?php

$db = \Config\Database::connect();

function pitek()
{
    $db = db_connect();
    $data = "SELECT product.*, category.category_name 
        FROM product
        LEFT JOIN category on product.product_category_id = category.category_id";

    $data = $db->query($data)->getResultArray();
    $pitek = json_encode($data);
    return $pitek;
}
