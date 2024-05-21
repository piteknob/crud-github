<?php

$db = \Config\Database::connect();


// Generate list data with pagination 

function dataPagination($data, $pagination)
{
    
}





// ------------------------------------------- PRINTILAN ------------------------------------------- //


// Generate Pagination
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

    $pagination = [
        'jumlah_data' => $jumlahData,
        'page' => $page,
        'jumlah_page' => $jumlahPage,
        'page_sebelumnya' => $pageSebelumnya,
        'page_selanjutnya' => $pageSelanjutnya
        
    ];

    return $pagination;
}


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
        $query = 'SELECT';
        foreach ($data as $key => $row) {
            $query .= " {$row}, ";
        }
        $query = rtrim($query, ', ');
        return $query;
    }
}


// Generate query group by
if (!function_exists('groupBy')) {
    function groupBy($column, $table)
    {
        $query = "SELECT {$column}, COUNT(*)
        FROM {$table}
        GROUP BY {$column}";
        return $query;
    }
}

// Generate query search
if (!function_exists('searchData')) {
    function searchData($data, $search)
    {
        $query = '';
        foreach ($data as $key => $row) {
            $query .= "{$row} LIKE '{$search}' OR ";
        }
        $query = rtrim($query, ' OR ');
        return $query;
    }
}


// Generate query inner join 
if (!function_exists('joinTable')) {
    function joinTable($table, $key)
    {
        $join = " FROM {$table['table1']} 
        JOIN {$table['table2']} ON {$table['table2']}.{$key['key2']}={$table['table1']}.{$key['key1']}";
        return $join;
    }
}

// Generate query left join 
if (!function_exists('leftJoinTable')) {
    function leftJoinTable($data)
    {
        $join = "FROM {$data['table1']} 
        LEFT JOIN {$data['table2']} ON {$data['table2']}.{$data['key2']}={$data['table1']}.{$data['key1']}";
        return $join;
    }
}

// Generate 
