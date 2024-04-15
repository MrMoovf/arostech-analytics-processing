<?php

namespace Arostech\Middleware;

use Arostech\Models\Request as ModelsRequest;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        try {
            ModelsRequest::create([
                'log_type' => 'pageview',
                'route' => $request->path(),
                'useragent' => $request->userAgent(),
                'visitor_id' => crypt($request->ip(),'123'),
                'referer' => request()->header('referer') ?? 'wasNull'
            ]);
            return $response;
        } catch (\Throwable $th) {
            report($th);
            return $response;
        }
    }
}
