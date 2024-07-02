<?php

namespace App\Http\Middleware;

use App\Models\OperationLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class LogOperations
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     */
    public function terminate(Request $request, Response $response): void
    {
        $attributes = [
            'path' => $request->path(),
            'method' => $request->method(),
            'ip' => $request->getClientIp(),
            'payload' => $request->all(),
            'user_id' => Auth::id(),
        ];

        if ($this->shouldLogOperations($request)) {
            try {
                OperationLog::query()->create($attributes);
            } catch (\Exception $exception) {
                Log::error($exception->getMessage());
            }
        }
    }

    /**
     * Determine if the request should log.
     *
     * @param Request $request
     * @return bool
     */
    protected function shouldLogOperations(Request $request): bool
    {
        return Auth::check() &&
            ! $this->inExceptedPaths($request) &&
            $this->inAllowedMethods($request->method());
    }

    /**
     * Determine if the request method is in allowed methods.
     *
     * @param string $method
     * @return bool
     */
    protected function inAllowedMethods(string $method): bool
    {
        $allowedMethods = [
            'GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH'
        ];

        $filteredAllowedMethods = collect($allowedMethods)->filter();

        if ($filteredAllowedMethods->isEmpty()) {
            return true;
        }

        return $filteredAllowedMethods->map(function ($item) {
            return strtoupper($item);
        })->contains(strtoupper($method));
    }

    /**
     * Determine if the request path is in excepted paths.
     *
     * @param Request $request
     * @return bool
     */
    protected function inExceptedPaths(Request $request): bool
    {
        $exceptedPaths = [
            '/api/admin/operation-logs*',
        ];

        foreach ($exceptedPaths as $item) {
            if ($item !== '/') {
                $item = trim($item, '/');
            }

            $methods = [];

            if (Str::contains($item, ':')) {
                list($methods, $item) = explode(':', $item);
                $methods = explode(',', $methods);
            }

            $methods = array_map('strtoupper', $methods);

            if ($request->is($item) &&
                (empty($methods) || in_array($request->method(), $methods))) {
                return true;
            }
        }

        return false;
    }
}
