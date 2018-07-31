<?php

namespace Gurudin\Admin\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Ajax response
     *
     * @param bool $status
     * @param array $data = []
     * @param string $msg = ''
     * @param int $header_code = 200
     *
     * @return Json
     */
    public function response(bool $status, array $data = [], string $msg = '', int $header_code = 200)
    {
        return response()->json([
            'status' => $status,
            'msg'    => $msg,
            'data'   => $data
        ], $header_code);
    }
}
