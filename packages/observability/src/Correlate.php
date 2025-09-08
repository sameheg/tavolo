<?php
namespace CafeSaaS\Observability;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class Correlate
{
    public function handle(Request $request, Closure $next)
    {
        $cid = $request->headers->get('x-correlation-id') ?: Str::uuid()->toString();
        Log::withContext(['cid' => $cid]);
        $response = $next($request);
        $response->headers->set('x-correlation-id', $cid);
        return $response;
    }
}
