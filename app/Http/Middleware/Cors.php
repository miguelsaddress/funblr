<?php namespace Funblr\Http\Middleware;

use Closure;

class Cors {
    private $openRoutes = ['export/zip'];

    public function handle($request, Closure $next)
    {
        
        foreach($this->openRoutes as $route) {
            if ($request->is($route)) {
                return $next($request);
            }
        }


        $headers = [
            'Access-Control-Allow-Origin'      => $_SERVER['PHP_SELF'],
            // CORS doesn't accept Access-Control-Allow-Origin = * for security reasons
            'Access-Control-Allow-Methods'     => 'POST, OPTIONS',
            //'Access-Control-Allow-Methods'   => 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Max-Age'           => '86400',
            'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With, Accept, Authorization, X-Requested-With, Application',
            //'Access-Control-Allow-Headers'   => 'X-Custom-Header, X-Requested-With, Content-Type, Origin, Authorization, Accept, Client-security-token',
            
        ];
 
 
        //Using this you don't need an method for 'OPTIONS' on controller
        if ($request->isMethod('OPTIONS'))
            return Response::json('{"method":"OPTIONS"}', 200, $headers);
 
        // For all other cases
        $response = $next($request);
        foreach ($headers as $key => $value)
            $response->header($key, $value);
 
        return $response;
    }
}
