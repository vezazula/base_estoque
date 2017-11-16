<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function validaLogin(){
    	if(auth()->user() == null) abort(403); 
    }

    public function validaAdm(){
    	$user = Auth()->user();
        if ($user->admin == 0) {
            abort(403);
        }
    }
}
