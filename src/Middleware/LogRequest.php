<?php
namespace Flynn314\RequestLogger\Middleware;

use Flynn314\RequestLogger\Model\Record;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

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
        try {
            $record->save();
        } catch (\Throwable $e) {
            $record->request_content = null;
            $record->save();

            Log::error('Unable to log request', [
                'message' => $e->getMessage(),
                'request_content' => $request->getContent(),
                'attributes' => $record->getAttributes(),
            ]);
        }

        $request->headers->set('X-'.config('app.name').'-RequestId', $record->getKey());

        /** @var Response $response */
        $response = $next($request);
        $response->headers->set('X-'.config('app.name').'-RequestId', $record->getKey());

        return $response;
    }
}
