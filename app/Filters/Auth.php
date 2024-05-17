<?php

namespace App\Filters;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use PhpParser\Node\Expr\ErrorSuppress;

class Auth implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    protected $db;

    public function __construct()
    {
        $db = $this->db = \Config\Database::connect();
    }
    use ResponseTrait;
    public function before(RequestInterface $request, $arguments = null)
    {
        $db = db_connect();


        // --------- AUTHORIZATION TOKEN --------- \\
        $header = getallheaders();
        $token = $header['Token-User'];
        $cocoklogi = "SELECT auth_user_token FROM auth_user WHERE auth_user_token = '{$token}'";
        $hasilCocoklogi = $db->query($cocoklogi);
        $hasilCocoklogi = $hasilCocoklogi->getResultArray(); // get result array


        // cek token ada atau tidak (valid/no)
        if (empty($token)) {
            return Services::response()
                ->setJSON([
                    'status' => ResponseInterface::HTTP_NETWORK_AUTHENTICATION_REQUIRED,
                    'message' => 'Token Required',
                    'error' => 'Token not inputed',
                    'inputed_token' => $token
                ]);
        }
        if (!$hasilCocoklogi) {
            return Services::response()
                ->setJSON([
                    'status' => ResponseInterface::HTTP_UNAUTHORIZED,
                    'message' => 'Invalid Token',
                    'error' => 'Token is not registered',
                    'inputed_token' => $token
                    
                ]);
        } else {
            $tabel = $db->table('auth_user');
            $user = $tabel->where('auth_user_token', $token)->get()->getFirstRow('array');

            $payload = [
                "expired" => $user['auth_user_date_expired']
            ];

            $expired = $payload["expired"];
            $tanggal = date('Y-m-d H:i:s');

            // cek token expired
            if ($expired < $tanggal) {
                return Services::response()
                    ->setJSON([
                        'status' => ResponseInterface::HTTP_REQUEST_TIMEOUT,
                        'message' => 'Token Expired',
                        'error' => 'Token date expired',
                        'inputed_token' => $token
                    ]);
            }
            // ------------------------ TAMBAH JAM EXPIRED ----------------------- //
            $tanggal = date('Y-m-d H:i:s');
            $strtotime = strtotime($tanggal);
            $tambahBiling = $strtotime + (60 * 60);
            $biling = date('Y-m-d H:i:s', $tambahBiling);

            $updateExpired = "UPDATE auth_user 
            SET 
            auth_user_date_login = NOW(),
            auth_user_date_expired = '{$biling}'
            WHERE auth_user_token = '{$token}'";
            $db->query($updateExpired);
        }
    }


    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
