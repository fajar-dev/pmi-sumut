<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Response;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Services\SiamoService;

class SiamoServiceController extends Controller
{
    protected $siamoService;

    public function __construct(SiamoService $siamoService)
    {
        $this->siamoService = $siamoService;
    }

    public function memberCount(Request $request)
    {
        $administativeArea = $request->input('administativeArea');

        try {
            $result = $this->siamoService->getMemberRecap($administativeArea);

            $main = $result['main'];
            $sub  = $result['sub'];

            if (isset($main['success']) && $main['success'] === false) {
                return Response::unauthorized();
            }

            $data = $this->siamoService->formatMemberData($main, $sub);
            return Response::success($data, 'Member count retrieved successfully');

        } catch (\Exception $e) {
            return Response::error('null', 'Internal Server Error', 500);
        }
    }

}
