<?php

use Arostech\Models\Request as ModelsRequest;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// ------------------ ANALYTICS
Route::post('/analytics', function (Request $request){
    ModelsRequest::create([
        'log_type' => 'pageview',
        'route' => $request->path(),
        'useragent' => $request->userAgent(),
        'visitor_id' => crypt($request->ip(),'123'),
        'referer' => request()->header('referer') ?? 'wasNull'
    ]);
    return $response;
    return response('Middleware has run',200);
});
