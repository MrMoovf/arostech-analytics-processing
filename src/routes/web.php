<?php

use Illuminate\Support\Facades\Route;
use Arostech\Api\ApiController;
 

// ------------------ ANALYTICS
Route::post('/analytics', function (){
    return response('Middleware has run',200);
});