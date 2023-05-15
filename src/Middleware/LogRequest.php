<?php
namespace Flynn314\RequestLogger\Middleware;

use Flynn314\RequestLogger\Model\Record;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LogRequest
{
    public function handle(Request $request, \Closure $next)
    {
        $record = new Record();
        $record->method = $request->getMethod();
        $record->url = $request->url();
        $record->request_headers = (array) $request->headers->all();
        $record->request_content = $request->getContent();
        $record->query = (array) $request->query();
        $record->ip = $request->ip();
        if ($request->user() && $request->user()->user_token_id) {
            $record->user_token_id = $request->user()->user_token_id;
        }
        $record->save();

        $request->headers->set('X-'.config('app.name').'-RequestId', $record->getKey());

        /** @var Response $response */
        $response = $next($request);
        $response->headers->set('X-'.config('app.name').'-RequestId', $record->getKey());

        return $response;
    }
}
