<?php

namespace App\Controllers\Core;


use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Controllers\Core\DataController;


class AuthController extends DataController
{
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
    }


    public function checkUserExist($data)
    {
        $query = "SELECT auth_user_token 
        FROM auth_user
        WHERE auth_user_email = '{$data}'";
        $result = $this->db->query($query)->getResultArray();
        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }


    public function getDataUser($data)
    {
        $query = "SELECT 
        user_id AS id,
        user_email AS email,
        auth_user.auth_user_password AS 'password',
        user_password AS 'password_encrypt',
        auth_user.auth_user_token AS token,
        auth_user.auth_user_date_login AS login_at,
        auth_user.auth_user_date_expired AS expired_at
        FROM user 
        LEFT JOIN auth_user ON auth_user.auth_user_user_id=user.user_id
        WHERE user_email = '{$data}'";
        $result = $this->db->query($query)->getRowArray();
        return $result;
    }
}
