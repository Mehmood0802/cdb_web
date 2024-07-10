<?php

namespace  app\home\middleware;

use think\Response;

class Cors
{

	public function handle($request, \Closure $next)
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Max-Age: 1800');
		header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE');
		header('Access-Control-Allow-Headers: Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-CSRF-TOKEN, X-Requested-With, token, lng , lat , lang ');
		if (strtoupper($request->method()) == "OPTIONS") {
			return Response::create()->send();
		}

		return $next($request);
	}
}