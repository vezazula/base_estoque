<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/home');
});

Auth::routes();
//Route::post('login', 'Auth\AuthController@authenticate')->name('login');

Route::get('/dashboard', 'AdministrativoController@index');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/usuario', 'UsuarioController@index');
Route::post('/usuario/registrar', 'UsuarioController@store');
Route::post('/usuario/atualizar', 'UsuarioController@update');
Route::post('/usuario/desativar', 'UsuarioController@desativar');
Route::post('/usuario/reativar', 'UsuarioController@reativar');

Route::get('/produto', 'ProdutoController@index');
Route::get('/produto/fornecedor/{id}', 'ProdutoController@listarPorFornecedor');
Route::get('/produto/buscar', 'ProdutoController@buscar');
Route::post('/produto/inserir', 'ProdutoController@store');
Route::post('/produto/debitar', 'ProdutoController@debitar');
Route::post('/produto/editar', 'ProdutoController@editar');

Route::get('/fornecedor', 'FornecedorController@index');
Route::get('/fornecedor/buscar', 'FornecedorController@buscar');
Route::post('/fornecedor/deletar', 'FornecedorController@deletar');
Route::post('/fornecedor/editar', 'FornecedorController@editar');
Route::post('/fornecedor/inserir', 'FornecedorController@store');