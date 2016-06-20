<?php namespace Funblr\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use Funblr\User;

class ApiController extends Controller
{
    protected $user;
    
    public function __construct(Request $request)
    {
        if ($request->hasHeader('X-APIKEY'))
        {
            $apikey = $request->header('X-APIKEY');
            $user = User::whereApikey($apikey)->first();
            $this->user = $user;
        } else {
            $this->middleware('auth');
            $this->user = Auth::user();
        }
    }
}