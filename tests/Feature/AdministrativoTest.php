<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use App\User;

class AdministrativoTest extends TestCase
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

    public function test_usuario_administrativo_pode_visualizar_valor_total_estoque(){
    	
        Artisan::call('migrate');
        $password = bcrypt('password');
        $usuarioA = User::create([
            'name' => 'jose',
            'email' => 'jose@email.com',
            'admin' => 1,
            'password' => $password
        ]);

        $usuarioB = User::create([
            'name' => 'manuel',
            'email' => 'manuel@email.com',
            'admin' => 0,
            'active' => 0,
            'password' => $password
        ]);

        $fornecedor = factory('App\Fornecedor')->create();
        for ($i = 0; $i < 5; $i++) { 
            $produtos[] = factory('App\Produto')->create();
        }

        $this->assertDatabaseHas('fornecedores', ['id' => $fornecedor->id]);

        //se não estiver logado
        $response = $this->withExceptionHandling()
                         ->call('GET', '/dashboard');

        $response->assertStatus(403);

        //se estiver
        $response = $this->withExceptionHandling()
                         ->actingAs($usuarioA)
                         ->call('GET', '/dashboard');

        $response->assertStatus(200);

        $total = 0;
        foreach ($produtos as $produto) {
            $response->assertSee($produto->nome);
            $response->assertSee(''.($produto->custo * $produto->quantidade));
            $response->assertSee(''.$produto->quantidade);
            $total += ($produto->custo * $produto->quantidade);
        }
        $response->assertSee(''.$total);
    }
}
