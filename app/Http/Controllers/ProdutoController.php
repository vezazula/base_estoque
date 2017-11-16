<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Produto;
use App\Fornecedor;
use Illuminate\Support\Facades\DB;

class ProdutoController extends Controller
{
    public function index(){
        parent::validaLogin();
        $user = Auth()->user();
        $admin = $user->admin;
        $produtos = Produto::all();
        $fornecedores = Fornecedor::orderBy('nome', 'DESC')->get();
        return view('produto.index', compact('produtos', 'fornecedores', 'admin'));
    }
    
    public function buscar(Request $request){
        parent::validaLogin();
        $fornecedores = Fornecedor::all();
        $produtos = DB::select('select * from produtos where nome like ?', ['%'.$request->nome.'%']);
        return view('produto.pesquisa', compact('produtos', 'fornecedores'));
    }

    public function debitar(Request $request){
        parent::validaLogin();
        $produto = Produto::find($request->id);
        $produto->quantidade -= $request->quantidade;
        $produto->save();
        return redirect()->back();
    }

    public function store(Request $request){
        parent::validaLogin();
        $produto = new Produto;
        $produto->nome = $request->nome;
        $produto->descricao = $request->descricao;
        $produto->custo = $request->custo;
        $produto->quantidade = $request->quantidade;
        $produto->fornecedor_id = $request->fornecedor;

        if (!empty($request->fornecedor)) {
            $produto->save();
        }

        return redirect()->back();
    }

    public function editar(Request $request){
        parent::validaLogin();
        $produto = Produto::find($request->id);
        $produto->nome = $request->nome;
        $produto->descricao = $request->descricao;
        $produto->custo = $request->custo;
        $produto->quantidade = $request->quantidade;
        $produto->fornecedor_id = $request->fornecedor;
        
        if (!empty($request->fornecedor)) {
            $produto->save();
        }
        return redirect()->back();
    }

    public function listarPorFornecedor($id){
        parent::validaLogin();
        $deFornecedor = Fornecedor::where('id', $id)->first();
        $fornecedores = Fornecedor::all();
        $produtos = DB::select('select * from produtos where fornecedor_id = ?', [$id]);
        return view('fornecedor.produtos', compact('produtos', 'fornecedores', 'deFornecedor'));
    }
}
