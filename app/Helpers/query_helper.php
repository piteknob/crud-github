<?php

if (!function_exists('generateDetailData')) {
    function generateDetailData($params, $query, $db)
    {
        $dataQuery = isset($query['data']) ? $query['data'] : '';
        $selectQuery = isset($query['select']) ? $query['select'] : '';
        $joinQuery = isset($query['join']) ? $query['join'] : '';
        // $whereQuery = isset($query['where']) ? $query['where'] : '';
        $idProduct = isset($params['id']) ? $params['id'] : '';
        $data = (object) [];

        $sql = '';

        if (!empty($selectQuery)) {
            $sql .= selectData($selectQuery);
        }

        if (!empty($dataQuery)){
            $sql .= dataFrom($dataQuery);
        }

        if (!empty($joinQuery)) {
            $sql .= joinTable($joinQuery,);
        }

        // if (!empty($idProduct)) {
        //     $sql .= whereData($idProduct);
        // }

        $sql .= " WHERE product_id = '{$idProduct}'";

        $sql = $db->query($sql)->getResultArray();
        $data->data = $sql;

        return $data;
    }
}

if (!function_exists('generateListData')) {
    function generateListData($params, $query, $db)
    {
        // Setup query data
        $dataQuery = isset($query['data']) ? $query['data'] : '';
        $selectQuery = isset($query['select']) ? $query['select'] : '';
        $searchQuery = isset($query['search']) ? $query['search'] : '';
        $joinQuery = isset($query['join']) ? $query['join'] : '';
        $whereQuery = isset($query['where']) ? $query['where'] : '';
        $groupByQuery = isset($query['group_by']) ? $query['group_by'] : '';
        $paginationResult = isset($query['pagination']) ? $query['pagination'] : '';
        $paginationPage = isset($params['page']) ? $params['page'] : 1;

        $data = (object) [];

        $sql = '';

        if (!empty($selectQuery)) {
            $sql .= selectData($selectQuery);
        }

        if (!empty($dataQuery)) {
            $sql .= dataFrom($dataQuery);
        }

        if (!empty($joinQuery)) {
            $sql .= joinTable($joinQuery);
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



        // Set Pagination from Params 

        $pagination = true;
        if (!empty($paginationResult)) {
            $pagination = paginationValue($paginationResult);
        }

        if (!empty($pagination)) {

            $countQuery = $sql;
            $countResult = $db->query($countQuery)->getResultArray();
            $countData = count($countResult);

            $limit = isset($query['limit']['limit']) ? $query['limit']['limit'] : 5;
            $offset = ($paginationPage - 1) * $limit;

            $sql .= " LIMIT {$offset}, {$limit}";

            $result = $db->query($sql)->getResultArray();
            $jumlahPage = ceil($countData / $limit);
            $pageSebelumnya = ($paginationPage - 1 > 0) ? ($paginationPage - 1) : null;
            $pageSelanjutnya = ($paginationPage + 1 <= $jumlahPage) ? ($paginationPage + 1) : null;

            // Data
            $data->data = $result;
            $data->pagination = [
                'jumlah_data' => $countData,
                'page' => $paginationPage,
                'jumlah_page' => $jumlahPage,
                'page_sebelumnya' => $pageSebelumnya,
                'page_selanjutnya' => $pageSelanjutnya
            ];
            return $data;
        }

        if (empty($pagination)) {
            return $db->query($sql)->getResultArray();
        }
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


if (!function_exists('dataFrom')) {
    function dataFrom($data)
    {
        $query = " FROM ";
        foreach ($data as $key => $value) {
            $query .= "{$value}, ";
        }
        $query = rtrim($query, ', ');
        return $query;
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
        $query = " LIMIT 0, {$data['limit']}";
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
        $sql = '';
        // if (whereData([])) {
            foreach ($data as $key => $row) {
                $sql .= " AND (product_stock_unit_name LIKE '%{$row}%' OR
                product_stock_product_name LIKE '%{$row}%' OR
                product_category_name LIKE '%{$row}%') ";
            }
        // } else
        //     foreach ($data as $key => $row) {
        //         $sql .= " AND (product_stock_unit_name LIKE '%{$row}%' OR
        //         product_stock_product_name LIKE '%{$row}%' OR
        //         product_category_name LIKE '%{$row}%') ";
        //     }
        return $sql;
    }
}


// Generate query inner join 
if (!function_exists('joinTable')) {
    function joinTable($data)
    {
        foreach ($data as $key => $row) {
            $join = " JOIN {$key} ON {$row}";
        }
        return $join;
    }
}


// Generate query left join
if (!function_exists('leftJoin')) {
    function leftJoin($data){
        foreach ($data as $key => $row) {
            $join = " LEFT JOIN {$key} ON {$row}";
        }
        return $join;
    }
}


// Generate query right join 
if (!function_exists('rightJoin')) {
    function rightJoin($data){
        foreach ($data as $key => $row) {
            $join = " RIGHT JOIN {$key} ON {$row}";
        }
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


if (!function_exists('paginationValue')) {
    function paginationValue($data)
    {
        foreach ($data as $key => $value) {
            $pagination = $value;
            return $pagination;
        }
    }
}
