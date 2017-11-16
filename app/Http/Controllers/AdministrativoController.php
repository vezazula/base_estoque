<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Produto;

class AdministrativoController extends Controller
{
    public function index(){
    	parent::validaLogin();
    	parent::validaAdm();
        $user = Auth()->user();
        $admin = $user->admin;

    	$usuarios = User::all();
    	$produtos = Produto::all();

    	$total = 0;
    	foreach ($produtos as $produto) {
    		$total += ($produto->custo * $produto->quantidade);
    	}

    	return view('usuario.dashboard', compact('usuarios', 'produtos', 'total', 'admin'));
    }
}
