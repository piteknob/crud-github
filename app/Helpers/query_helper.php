<?php

$db = \Config\Database::connect();


if (!function_exists('generateDetailData')) {
    function generateDetailData($query)
    {
        $db = db_connect();
        $selectQuery = isset($query['select']) ? $query['select'] : '';
        $joinQuery = isset($query['join']) ? $query['join'] : '';
        $whereQuery = isset($query['where']) ? $query['where'] : '';
        $searchQuery = isset($query['search']) ? $query['search'] : '';
        $groupByQuery = isset($query['group_by']) ? $query['group_by'] : '';
        $limitQuery = isset($query['limit']) ? $query['limit'] : '';

        $data = (object) [];

        if (!empty($selectQuery)) {
            $sql = selectData($selectQuery);
        }

        if (!empty($joinQuery)) {
            $sql .= joinTable($joinQuery,);
        }

        if (!empty($whereQuery)) {
            $sql .= whereData($whereQuery);
        }

        if (!empty($searchQuery)) {
            $sql .= searchData($searchQuery);
        }

        if (!empty($groupByQuery)) {
            $sql .= groupBy($groupByQuery);
        }

        if (!empty($limitQuery)) {
            $sql .= limitData($limitQuery);
        }

        $sql = $db->query($sql)->getResultArray();
        $data->data = $sql;

        return $data;
    }
}


if (!function_exists('generateListData')) {
    function generateListData($query)
    {
        $db = db_connect();
        $selectQuery = isset($query['select']) ? $query['select'] : '';
        $searchQuery = isset($query['search']) ? $query['search'] : '';
        $joinQuery = isset($query['join']) ? $query['join'] : '';
        $whereQuery = isset($query['where']) ? $query['where'] : '';
        $groupByQuery = isset($query['group_by']) ? $query['group_by'] : '';
        $limitQuery = isset($query['limit']) ? $query['limit'] : '';

        $data = (object) [];


        if (!empty($selectQuery)) {
            $sql = selectData($selectQuery);
        }

        if (!empty($searchQuery)) {
            $sql .= searchData($query);
        }

        if (!empty($joinQuery)) {
            $sql .= joinTable($joinQuery,);
        }

        if (!empty($whereQuery)) {
            $sql .= whereData($whereQuery);
        }

        if (!empty($groupByQuery)) {
            $sql .= groupBy($groupByQuery);
        }

        if (!empty($limitQuery)) {
            $sql .= limitData($limitQuery);
        }

        $sql = $db->query($sql)->getResultArray();
        $data->data = $sql;

        return $data;
    }
}



// ------------------------------------------- PRINTILAN ------------------------------------------- //

// Generate result Array to JSON
if (!function_exists('setToJSON')) {
    function setToJSON($data)
    {
        $data = json_encode($data);
        return $data;
    }
}

// Generate result JSON to Array
if (!function_exists('setToArray')) {
    function setToArray($data)
    {
        $data = json_decode($data);
        return $data;
    }
}


// Generate query select data
if (!function_exists('selectData')) {
    function selectData($data)
    {
        $query = 'SELECT ';
        foreach ($data as $key => $row) {
            $query .= "{$key} AS {$row}, ";
        }
        $query = rtrim($query, ', ');
        return $query;
    }
}

// Generate query limit 
if (!function_exists('limitData')) {
    function limitData($data)
    {
        $query = " LIMIT {$data['limit']}";
        return $query;
    }
}
// Generate query group by
if (!function_exists('groupBy')) {
    function groupBy($data)
    {
        foreach ($data as $row) {
            $query = " GROUP BY {$row},";
            $query = rtrim($query, ', ');
            return $query;
        }
    }
}

// Generate query search
if (!function_exists('searchData')) {
    function searchData($data)
    {
        foreach ($data as $key => $row) {
            print_r($row);
            die;
            if (whereData([])) {
                $sql = " AND (product_stock_unit_name LIKE '%{$row['search']}%' OR
                product_stock_product_name LIKE '%{$row['search']}%' OR
                product_category_name LIKE '%{$row['search']}%') ";
                return $sql;
            } else {
                $sql = " AND (product_stock_unit_name LIKE '%{$row['search']}%' OR
                product_stock_product_name LIKE '%{$row['search']}%' OR
                product_category_name LIKE '%{$row['search']}%') ";
                return $sql;
            }
        }
    }
}


// Generate query inner join 
if (!function_exists('joinTable')) {
    function joinTable($data)
    {
        $join = " $data";
        return $join;
    }
}

// Generate query where clause
if (!function_exists('whereData')) {
    function whereData($data)
    {
        $where = " WHERE ";
        foreach ($data as $key => $row) {
            $where .= "{$key} = {$row} AND ";
        }
        $where = rtrim($where, ' AND ');
        return $where;
    }
}

// Generate query filter 
// // if (!function_exists('filterData')) {
// //     function filterData($data)
// //     {
// //         foreach ($data as $key => $row) {
// //             if ($row['range_harga_awal'] != null && $row['range_harga_akhir'] != null){
// //                 $sql .= " AND "
// //             }
// //         }
// //     }
// }
