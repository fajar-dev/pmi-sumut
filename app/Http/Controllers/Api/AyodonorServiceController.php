<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Services\AyodonorService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;

class AyodonorServiceController extends Controller
{
    protected $ayodonorService;

    public function __construct(AyodonorService $ayodonorService)
    {
        $this->ayodonorService = $ayodonorService;
    }

    public function bloodStock($id)
    {
        try {
            $bloodDonorUnit = $this->ayodonorService->getContact();
            $bloodDonorUnitResponse = $this->ayodonorService->formatContact($bloodDonorUnit, $id);
            if (!$bloodDonorUnitResponse ){
                return Response::notFound('No blood donor unit data found');
            }
            $result = $this->ayodonorService->getBloodStock($id);
            $data = $this->ayodonorService->formatBloodStock($bloodDonorUnitResponse, $result);
            return Response::success($data, 'Blood stock data retrieved successfully');
        } catch (\Exception $e) {
            return Response::error(null, 'Internal server error', 500);
        }
    }

    public function mobileUnit($id, Request $request)
    {
        $date = Carbon::parse($request->input('date', Carbon::now()))->format('Y-m-d');
        try {
            $bloodDonorUnit = $this->ayodonorService->getContact();
            $bloodDonorUnitResponse = $this->ayodonorService->formatContact($bloodDonorUnit, $id);
            if (!$bloodDonorUnitResponse ){
                return Response::notFound('No blood donor unit data found');
            }
            $result = $this->ayodonorService->getMobileUnitSchedule($date, $id);
            // return $result;
            $data = $this->ayodonorService->formatMobileUnitSchedule($bloodDonorUnitResponse, $result);
            return Response::success($data, 'Mobile unit schedule retrieved successfully');
        } catch (\Exception $e) {
            return Response::error(null, 'Internal server error', 500);
        }
    }

    public function contact($id = null){
        try{
            $result = $this->ayodonorService->getContact();
            if (!$result || !isset($result['data'])) {
                return Response::notFound('No blood donor unit contact data found');
            }
            $data = $this->ayodonorService->formatContact($result, $id);
            if (!$data){
                return Response::notFound('No blood donor unit contact data found');
            }
            return Response::success($data, 'Blood donor unit contact retrieved successfully');
        } catch (\Exception $e) {
            return Response::error(null, 'Internal server error', 500);
        }
    }
}
