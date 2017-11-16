<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UsuarioController extends Controller
{
    public function index(){
    	parent::validaLogin();
    	parent::validaAdm();
        $user = Auth()->user();
        $admin = $user->admin;
        
    	$usuarios = User::orderBy('name', 'ASC')->get();

    	return view('usuario.index', compact('usuarios', 'user', 'admin'));
    }

    public function store(Request $request){
    	parent::validaLogin();
    	parent::validaAdm();
    	$usuario = new User;

//        $this->validate($request, [
//            'name' => 'required|min:20',
//            'email' => 'unique:users|email|min:5',
//        ]);

    	$usuario->name = $request->name;
    	$usuario->email = $request->email;
    	$usuario->password = bcrypt($request->password);
    	$usuario->admin = $request->admin;

    	$usuario->save();

    	return redirect('/usuario');
    }

    public function update(Request $request){
    	parent::validaLogin();
    	parent::validaAdm();

    	$usuario = User::where('id', $request->id)->first();

    	$usuario->name = $request->name;
    	$usuario->email = $request->email;
    	$usuario->admin = $request->admin;

    	$usuario->save();

    	return redirect('/usuario');
    }

    public function desativar(Request $request){
    	parent::validaLogin();
    	parent::validaAdm();

    	$usuario = User::where('id', $request->id)->first();
    	$usuario->active = 0;

    	$usuario->save();

    	return redirect('/usuario');
    }

    public function reativar(Request $request){
        parent::validaLogin();
        parent::validaAdm();

        $usuario = User::where('id', $request->id)->first();
        $usuario->active = 1;

        $usuario->save();

        return redirect('/usuario');
    }
}
