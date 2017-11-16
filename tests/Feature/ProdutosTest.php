<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

class ProdutosTest extends TestCase
{
	/**
	 * A basic test example.
	 *
	 * @return void
	 */
	public function testExample()
	{
		$this->assertTrue(true);
	}

	public function test_usuarios_logados_podem_inserir_produtos(){
		Artisan::call('migrate');
		$usuario = factory('App\User')->create();
		$fornecedor = factory('App\Fornecedor')->create();
		$produto = array(
            'nome' => 'Garrafa',
            'descricao' => 'Garrafa de água',
            'custo' => '20',
            'quantidade' => '100',
            'fornecedor' => ''.$fornecedor->id,
            '_token' => csrf_token()
        );

		//se o usuário não estiver logado
		$response = $this->withExceptionHandling()
						 ->call('POST', '/produto/inserir', $produto);

        $response->assertStatus(403);

        //se ele estiver
        $response = $this->withExceptionHandling()
						 ->actingAs($usuario)
						 ->call('POST', '/produto/inserir', $produto);

        $response->assertStatus(302);

        $this->assertDatabaseHas('produtos', 
        	[
            'nome' => 'Garrafa',
            'descricao' => 'Garrafa de água',
            'custo' => '20.0',
            'quantidade' => '100',
            'fornecedor_id' => ''.$fornecedor->id,
        ]);
	}

	public function test_usuarios_nao_logados_nao_inserem_produtos(){
	    Artisan::call('migrate');
		$fornecedor = factory('App\Fornecedor')->create();
		$response = $this->withExceptionHandling()->call('POST', '/produto/inserir', [
            'nome' => 'Garrafa',
            'descricao' => 'Garrafa de água',
            'custo' => '20',
            'quantidade' => '100',
            'fornecedor' => ''.$fornecedor->id,
            '_token' => csrf_token()
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('produtos', 
        	[
            'nome' => 'Garrafa',
            'descricao' => 'Garrafa de água',
            'custo' => '20.0',
            'quantidade' => '100',
            'fornecedor_id' => ''.$fornecedor->id,
        ]);
	}

	public function test_usuarios_logados_podem_acessar_pagina_de_produtos(){
		Artisan::call('migrate');
		$usuario = factory('App\User')->create();

		//se não estiver logado
   		$response = $this->withExceptionHandling()->call('GET', '/produto');
   		$response->assertStatus(403);

   		//se estiver
   		$response = $this->withExceptionHandling()->actingAs($usuario)->call('GET', '/produto');
   		$response->assertStatus(200);
   		
	}

	public function test_usuarios_logados_podem_dar_baixa_na_quantidade_produtos(){
		Artisan::call('migrate');
		$usuario = factory('App\User')->create();
		$fornecedor = factory('App\Fornecedor')->create();
		$produto = factory('App\Produto')->create();

		$quantidadeInicial = $produto->quantidade;

		//se não estiver logado, deve dar erro
		$response = $this->withExceptionHandling()->call('POST', '/produto/debitar', ['id' => ''.$produto->id, 'quantidade' => '3']);
		$response->assertStatus(403);

		//se estiver, vai dar tudo ok
		$response = $this->withExceptionHandling()->actingAs($usuario)->call('POST', '/produto/debitar', ['id' => ''.$produto->id, 'quantidade' => '3']);

		$produto = \App\Produto::all()->first();
		$response->assertStatus(302);
		$this->assertTrue(($quantidadeInicial - $produto->quantidade) == 3);

	}

	public function test_usuarios_logados_podem_listar_produtos(){
		Artisan::call('migrate');
		$usuario = factory('App\User')->create();
		$fornecedor = factory('App\Fornecedor')->create();
		for ($i = 0; $i < 5; $i++) { 
			$produtos[] = factory('App\Produto')->create();
		}

		$this->assertDatabaseHas('fornecedores', ['id' => $fornecedor->id]);

		//se não estiver logado
		$response = $this->withExceptionHandling()
					     ->call('GET', '/produto');

		$response->assertStatus(403);

		//se estiver
		$response = $this->withExceptionHandling()
					     ->actingAs($usuario)
					     ->call('GET', '/produto');

		$response->assertStatus(200);

		foreach ($produtos as $produto) {
            $response->assertSee($produto->nome);
            $response->assertSee(''.$produto->custo);
            $response->assertSee(''.$produto->quantidade);
    	}
	}

	public function test_usuarios_logados_podem_buscar_produtos(){
		Artisan::call('migrate');
		$usuario = factory('App\User')->create();
		$fornecedor = factory('App\Fornecedor')->create();
		for ($i = 0; $i < 5; $i++) { 
			$produtos[] = factory('App\Produto')->create();
		}

		$this->assertDatabaseHas('fornecedores', ['id' => $fornecedor->id]);

		//se não estiver logado
		$response = $this->withExceptionHandling()
					     ->call('GET', '/produto/buscar', ['nome' => $produtos[3]->nome]);

		$response->assertStatus(403);

		//se estiver
		$response = $this->withExceptionHandling()
					     ->actingAs($usuario)
					     ->call('GET', '/produto/buscar', ['nome' => $produtos[3]->nome]);

		$response->assertStatus(200);
        $response->assertSee($produtos[3]->nome);
        $response->assertSee(''.$produtos[3]->custo);
        $response->assertSee(''.$produtos[3]->quantidade);
	}

	public function test_usuarios_logados_podem_editar_produtos(){
		Artisan::call('migrate');
		$usuario = factory('App\User')->create();
		$fornecedor = factory('App\Fornecedor')->create();
		$produto = factory('App\Produto')->create();
		$produtoEditado = array(
            'id' => $produto->id,
            'nome' => 'Garrafa',
            'descricao' => $produto->descricao,
            'custo' => $produto->custo,
            'quantidade' => $produto->quantidade,
            'fornecedor' => $produto->fornecedor_id,
            '_token' => csrf_token()
        );

        $this->assertDatabaseHas('produtos', ['id' => ''.$produto->id]);
        $this->assertDatabaseMissing('produtos', ['nome' => 'Garrafa']);
        //se não estiver logado
        $response = $this->withExceptionHandling()->call('POST', '/produto/editar', $produtoEditado);
        $response->assertStatus(403);

        //se estiver
		$response = $this->withExceptionHandling()->actingAs($usuario)->call('POST', '/produto/editar', $produtoEditado);

        $response->assertStatus(302);
        $this->assertDatabaseHas('produtos', ['nome' => 'Garrafa']);
        $this->assertDatabaseHas('produtos', ['id' => ''.$produto->id]);
	}

	public function test_usuarios_logados_podem_exibir_produtos_de_um_fornecedor(){
		Artisan::call('migrate');
		$usuario = factory('App\User')->create();
		$fornecedor = factory('App\Fornecedor')->create();
		for ($i = 0; $i < 5; $i++) { 
			$produtos[] = factory('App\Produto')->create();
		}

		$this->assertDatabaseHas('fornecedores', ['id' => $fornecedor->id]);

		//se não estiver logado
		$response = $this->withExceptionHandling()
					     ->call('GET', '/produto/fornecedor/'.$fornecedor->id);

		$response->assertStatus(403);

		//se estiver
		$response = $this->withExceptionHandling()
					     ->actingAs($usuario)
					     ->call('GET', '/produto/fornecedor/'.$fornecedor->id);

		$response->assertStatus(200);

		foreach ($produtos as $produto) {
            $response->assertSee($produto->nome);
            $response->assertSee(''.$produto->custo);
            $response->assertSee(''.$produto->quantidade);
    	}

	}
}
