<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\SuperUser;

class SuperUserController extends ApiController
{
    public function index()
    {
        $superUsers = SuperUser::all();

        return $this->response($superUsers);
    }
}
