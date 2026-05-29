<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\LoggerHelper;
use Illuminate\Support\Facades\Auth;

class AuditMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only log if user is authenticated and the request is making modifications or logging
        if (Auth::check() && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            // Ignore login and logout routes since they are handled separately or would duplicate logs
            if (!$request->is('login') && !$request->is('logout')) {
                $tipo = 'MODIFICACION';
                $descripcion = "Acción {$request->method()} en la ruta: {$request->path()}";
                
                // Construct a detailed summary of request inputs for audit purposes
                $inputs = $request->except(['password', 'contraseña', '_token', '_method']);
                $accion = json_encode($inputs, JSON_UNESCAPED_UNICODE);

                if (strlen($accion) > 1000) {
                    $accion = substr($accion, 0, 1000) . '...';
                }

                LoggerHelper::log($tipo, $descripcion, $accion);
            }
        }

        return $response;
    }
}
