<?php

namespace App\Http\Controllers;

use App\Services\ResponseService;
use Illuminate\Support\Facades\App;
use App\Models\Project;

class BaseController extends Controller
{
    protected $responseService;

    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
        $locale = request()->get('lang', 'en');
        App::setLocale($locale);
    }

    

    protected function success($message, $data = [], $status = 200)
    {
        return $this->responseService->success($message, $data, $status);
    }

    protected function error($message, $data = [], $status = 400)
    {
        return $this->responseService->error($message, $data, $status);
    }

    protected function notFound($message = null)
    {
        return $this->responseService->notFound($message ?? trans('messages.not_found'));
    }
}