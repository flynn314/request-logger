<?php
namespace Flynn314\RequestLogger\Middleware;

use Flynn314\RequestLogger\Model\Record;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogResponse
{
    public function handle(Request $request, \Closure $next)
    {
        /** @var JsonResponse $response */
        $response = $next($request);

        if ($request->hasHeader('X-'.config('app.name').'-RequestId') && $record = Record::find($request->header('X-'.config('app.name').'-RequestId'))) {
            $record->response_headers = (array) $response->headers->all();
            $record->response_content = $response->getContent();
            $record->save();
        } else {
            Log::warning('Unable to log response', [
                'user' => $request->user() ? $request->user()->getKey() : null,
                'url' => $request->url(),
                'headers' => $response->headers->all(),
                'content' => $response->getContent(),
            ]);
        }

        return $response;
    }
}
