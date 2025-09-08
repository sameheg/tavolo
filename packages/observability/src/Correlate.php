<?php
namespace CafeSaaS\Observability; use Closure; use Illuminate\Http\Request; use Illuminate\Support\Str; use Illuminate\Support\Facades\Log;
class Correlate{public function handle(Request $r,Closure $n){$cid=$r->headers->get('x-correlation-id')?:Str::uuid()->toString();Log::withContext(['cid'=>$cid]);$res=$n($r);$res->headers->set('x-correlation-id',$cid);return $res;}}
