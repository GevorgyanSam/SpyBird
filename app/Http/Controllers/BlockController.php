<?php

namespace App\Http\Controllers;

use App\Services\Block\BlockUserService;
use App\Services\Block\UnblockUserService;
use Illuminate\Http\Request;

class BlockController extends Controller
{

    // ---- ------ -- -------- -- ------- ----
    // This Method Is Designed To Unblock User
    // ---- ------ -- -------- -- ------- ----

    public function unblockUser(int $id, UnblockUserService $service)
    {
        return $service->handle($id);
    }

    // ---- ------ -- -------- -- ----- ----
    // This Method Is Designed To Block User
    // ---- ------ -- -------- -- ----- ----

    public function blockUser(int $id, BlockUserService $service)
    {
        return $service->handle($id);
    }

}