<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

class FornecedoresTest extends TestCase
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

    public function test_usuarios_logados_podem_inserir_fornecedores(){
        Artisan::call('migrate');
        //criamos o usuário
        $usuario = factory('App\User')->create();
        //envia a requisição de inserção de fornecedor
        $response = $this->withExceptionHandling()->actingAs($usuario)->call('POST', '/fornecedor/inserir', [
            'nome' => 'Plásticos Federal Ltda',
            'cnpj' => '97377559000177',
            'endereco' => 'Av. Salgado Filho, 2457',
            '_token' => csrf_token()
        ]);
        //verifica se a página foi redirecionada
        $response->assertStatus(302);
        //verifica se o fornecedor foi inserido no banco de dados
        $this->assertDatabaseHas('fornecedores', ['nome' => 'Plásticos Federal Ltda', 'cnpj' => '97377559000177', 'endereco' => 'Av. Salgado Filho, 2457']);
    }

    public function test_usuarios_nao_logados_nao_inserem_fornecedores(){
        Artisan::call('migrate');
        
        //envia a requisição de inserção de fornecedor agora SEM O ACTING AS $USUARIO
        $response = $this->withExceptionHandling()->call('POST', '/fornecedor/inserir', [
            'nome' => 'Plásticos Federal Ltda',
            'cnpj' => '97377559000177',
            'endereco' => 'Av. Salgado Filho, 2457',
            '_token' => csrf_token()
        ]);

        //verifica se a requisição retorna o status '403 forbidden' 
        $response->assertStatus(403);
        //certifica que o fornecedor não foi inserido
        $this->assertDatabaseMissing('fornecedores', ['nome' => 'Plásticos Federal Ltda', 'cnpj' => '97377559000177', 'endereco' => 'Av. Salgado Filho, 2457']);

    }

    public function test_usuarios_logados_podem_acessar_pagina_de_fornecedores(){
        Artisan::call('migrate');
        $usuario = factory('App\User')->create();
        //se não estiver logado
        $response = $this->withExceptionHandling()->call('GET', '/fornecedor');
        $response->assertStatus(403);
        //se estiver
        $response = $this->withExceptionHandling()->actingAs($usuario)->call('GET', '/fornecedor');
        $response->assertStatus(200);
            
    }

    public function test_usuarios_logados_podem_listar_fornecedores(){
        Artisan::call('migrate');
        $usuario = factory('App\User')->create();
        for ($i = 0; $i < 5; $i++) { 
            $fornecedores[] = factory('App\Fornecedor')->create();
        }
        //se não estiver logado
        $response = $this->withExceptionHandling()
                         ->call('GET', '/fornecedor');
        $response->assertStatus(403);
        //se estiver
        $response = $this->withExceptionHandling()
                         ->actingAs($usuario)
                         ->call('GET', '/fornecedor');
        $response->assertStatus(200);
        foreach ($fornecedores as $fornecedor) {
            $response->assertSee($fornecedor->nome);
            $response->assertSee(''.$fornecedor->cnpj);
            $response->assertSee(''.$fornecedor->endereco);
        }
    }

    public function test_usuarios_logados_podem_buscar_fornecedor(){
        Artisan::call('migrate');
        $usuario = factory('App\User')->create();
        for ($i = 0; $i < 5; $i++) { 
            $fornecedores[] = factory('App\Fornecedor')->create();
        }
        //se não estiver logado
        $response = $this->withExceptionHandling()
                         ->call('GET', '/fornecedor/buscar', ['nome' => $fornecedores[3]->nome]);

        $response->assertStatus(403);

        //se estiver
        $response = $this->withExceptionHandling()
                         ->actingAs($usuario)
                         ->call('GET', '/fornecedor/buscar', ['nome' => $fornecedores[3]->nome]);

        $response->assertStatus(200);
        $response->assertSee($fornecedores[3]->nome);
        $response->assertSee(''.$fornecedores[3]->cnpj);
        $response->assertSee(''.$fornecedores[3]->endereco);
    }

    public function test_usuarios_logados_podem_editar_fornecedor(){
        Artisan::call('migrate');
        $usuario = factory('App\User')->create();
        $fornecedor = factory('App\Fornecedor')->create();
        $fornecedorEditado = array(
            'id' => $fornecedor->id,
            'nome' => 'Nome alterado',
            'cnpj' => $fornecedor->cnpj,
            'endereco' => $fornecedor->endereco,
            '_token' => csrf_token()
        );

        $this->assertDatabaseHas('fornecedores', ['id' => ''.$fornecedor->id]);
        $this->assertDatabaseMissing('fornecedores', ['nome' => 'Nome alterado']);
        //se não estiver logado
        $response = $this->withExceptionHandling()->call('POST', '/fornecedor/editar', $fornecedorEditado);
        $response->assertStatus(403);

        //se estiver
        $response = $this->withExceptionHandling()->actingAs($usuario)->call('POST', '/fornecedor/editar', $fornecedorEditado);

        $response->assertStatus(302);
        $this->assertDatabaseHas('fornecedores', ['nome' => 'Nome alterado']);
        $this->assertDatabaseHas('fornecedores', ['id' => ''.$fornecedor->id]);
    }

    public function test_usuarios_logados_podem_excluir_fornecedor(){
        Artisan::call('migrate');
        $usuario = factory('App\User')->create();
        $fornecedor = factory('App\Fornecedor')->create();
        $this->assertDatabaseHas('fornecedores', ['id' => $fornecedor->id]);

        //tenta acessar sem estar logado
        $response = $this->withExceptionHandling()
                         ->call('POST', '/fornecedor/deletar', ['id' => $fornecedor->id]);
        $response->assertStatus(403);

         //acessa estado logado
        $response = $this->withExceptionHandling()
                         ->actingAs($usuario) 
                         ->call('POST', '/fornecedor/deletar', ['id' => $fornecedor->id]);
        $response->assertStatus(302);

        $this->assertSoftDeleted('fornecedores', ['id' => $fornecedor->id]);
        $this->assertDatabaseMissing('produtos', ['fornecedor_id' => $fornecedor->id]);
    }
}
