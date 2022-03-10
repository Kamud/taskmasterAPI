<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function createLog($resource,$action = 'modified')
    {
        $newLog = [
            'id' => mt_rand(10000000,99999999),
            'resource_id' => $resource['id'],
            'resource_category' => $resource['category'],
            'modified_by_user_id' => $resource['modified_by_user_id'],
            'action' => $action,
            'description'=> $resource['description']
        ];

        return Log::create($newLog);
    }
}
