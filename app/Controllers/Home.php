<?php

namespace App\Controllers;

use App\Controllers\Core\BaseController;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }
}
