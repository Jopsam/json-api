<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ValidateJsonApiHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @throw \Symfony\Component\HttpKernel\Exception\HttpException
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('accept') !== 'application/vnd.api+json') {
            throw new HttpException(Response::HTTP_NOT_ACCEPTABLE, __('NOT ACCEPTABLE'));
        }

        if (($request->isMethod('POST') || $request->isMethod('PATCH'))
            && $request->header('content-type') !== 'application/vnd.api+json'
        ) {
            throw new HttpException(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, __('UNSUPPORTED MEDIA TYPE'));
        }

        return $next($request)->withHeaders([
            'content-type' => 'application/vnd.api+json',
        ]);
    }
}
