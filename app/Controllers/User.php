<?php

namespace App\Controllers;

use App\Controllers\Core\AuthController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\Console\Input\StringInput;


class User extends AuthController
{
    use ResponseTrait;
    protected $db;
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }


    public function register()
    {
        $post = $this->request->getPost();
        $db = db_connect();

        $rules = [
            'email' => 'required|valid_email|is_unique[user.user_email]',
            'password' => 'required|min_length[6]',
            'confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }
        $email = htmlspecialchars($post['email']);
        $password = password_hash($post['password'], PASSWORD_BCRYPT);

        $in = "INSERT INTO user VALUES('', '$email', '$password')";

        $db->query($in);

        $response = [
            'message' => 'data berhasil di register'
        ];

        return $this->respond($response);
    }


    public function login()
    {
        $post = $this->request->getPost();
        $db = db_connect();

        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $tabel = $db->table('user');
        $user = $tabel->where('user_email', $email)->get()->getFirstRow('array');

        if (!$user) {
            return $this->failNotFound("Email not found");
        }

        if (!password_verify($password, $user['user_password'])) {
            return $this->fail('Wrong password');
        }

        $key = getenv('TOKEN_SECRET');
        $payload = [
            'iat' => 1356999524,
            'nbf' => 1357000000,
            "uid" => $user['user_id'],
            "email" => $user['user_email']
        ];

        $token = JWT::encode($payload, $key, 'HS256');

        $idUser = $payload["uid"];
        $email = $post['email'];
        $password = $post['password'];

        // cari expired token + 1 jam
        date_default_timezone_set("Asia/Jakarta");
        $date = date("Y-m-d H:i:s");
        $currentDate = strtotime($date);
        $futureDate = $currentDate + (60 * 60);
        $formatDate = date("Y-m-d H:i:s", $futureDate);


        $getID = "SELECT auth_user_user_id FROM auth_user WHERE auth_user_email = '{$email}'";
        $resultID = $db->query($getID);
        $id = $resultID->getResultArray();

        if (!$id) {
            $insertAuth = "INSERT INTO auth_user (auth_user_user_id, auth_user_email, auth_user_password, auth_user_token, auth_user_date_login, auth_user_date_expired) 
                    SELECT user_id, '{$email}', '{$password}', '{$token}', NOW(), '{$formatDate}'
                    FROM user
                    WHERE user_email = '{$email}'";
            $db->query($insertAuth);
            return $this->respond($token);
        }
        if ($id) {
            $updateAuth = "UPDATE auth_user SET
                    auth_user_date_login = NOW(),
                    auth_user_date_expired = '{$formatDate}' 
                    WHERE auth_user_user_id = '{$idUser}'";
            $db->query($updateAuth);
            return $this->respond($token);
        }
    }


    public function showToken()
    {
        $key = getenv('TOKEN_SECRET');
        $header = $this->request->getServer('HTTP_AUTHORIZATION');
        if (!$header) {
            return $this->failUnauthorized('Token required');
        }
        $token = explode(' ', $header)[1];

        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256')); // decode tokennya 
            $response = [
                'id' => $decoded->uid,
                'email' => $decoded->email
            ];
            return $this->respond($response);
        } catch (\Throwable $th) {
            return $this->fail('Invalid Token');
        }
    }
}
