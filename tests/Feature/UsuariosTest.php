<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\User;

class UsuariosTest extends TestCase
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

    public function test_um_usuario_podera_fazer_login_no_sistema(){
        Artisan::call('migrate');

        $password = bcrypt('password');

        //cadastro do usuário de teste no banco de dados
        User::create([
            'name' => 'jose',
            'email' => 'jose@email.com',
            'admin' => 0,
            'password' => $password
        ]);

        //verificamos se o usuário foi efetivamente cadastrado
        $this->assertDatabaseHas('users', ['email' => 'jose@email.com']);

        //enviamos uma requisição de login
        Session::start();
        $response = $this->withExceptionHandling()->call('POST', '/login', [
            'email' => 'jose@email.com',
            'password' => 'password',
            '_token' => csrf_token()
        ]);

        //verificamos se o sistema redirecionou para a página home
        //se der erro no login, o sistema redireciona para '/'
        $this->assertEquals(302, $response->getStatusCode());
        $response->assertRedirect('/home');
    }

    public function test_usuario_nao_cadastrado_nao_consegue_logar(){
        Artisan::call('migrate');
        Session::start();

        //enviamos uma requisição de login
        $response = $this->withExceptionHandling()->call('POST', '/login', [
            'email' => 'bad@email.com',
            'password' => 'badpassword',
            '_token' => csrf_token()
        ]);

        //verifica se existe esse usuário no banco de dados
        $this->assertDatabaseMissing('users', ['email' => 'bad@email.com']);

        //verificamos o redirecionamento. Login inválido redirecina para /
        $this->assertEquals(302, $response->getStatusCode());
        $response->assertRedirect('/');
    }

    public function test_usuarios_nao_cadastrados_nao_podem_cadastrar_usuarios(){
        Artisan::call('migrate');

        //envia-se uma requisição de cadastro de novo usuário
        $response = $this->withExceptionHandling()->call('POST', '/usuario/registrar', [
            'name' => 'name',
            'email' => 'usuario@email.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'admin' => 0,
            '_token' => csrf_token()
        ]);

        //verifica a ausência do usuário no banco de dados
        $this->assertDatabaseMissing('users', ['email' => 'usuario@email.com']);      
    }

    public function test_usuarios_comuns_nao_podem_cadastrar_usuarios(){
        Artisan::call('migrate');

        //cadastro do usuário de teste no banco de dados
        $password = bcrypt('password');
        $usuario = User::create([
            'name' => 'jose',
            'email' => 'jose@email.com',
            'admin' => 0,
            'password' => $password
        ]);

        //verificamos se foi cadastrado no banco de dados 
        $this->assertDatabaseHas('users', ['email' => 'jose@email.com']);

        //envia-se uma requisição de cadastro de novo usuário
        $response = $this->withExceptionHandling()->actingAs($usuario)->call('POST', '/usuario/registrar', [
            'name' => 'name',
            'email' => 'usuario@email.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'admin' => 0,
            '_token' => csrf_token()
        ]);

        //verifica a ausência do usuário no banco de dados
        $this->assertDatabaseMissing('users', ['email' => 'usuario@email.com']);
    }

    public function test_usuario_administrador_pode_cadastrar_usuarios(){
        Artisan::call('migrate');

        //cadastro do usuário de teste no banco de dados
        $password = bcrypt('password');
        $usuario = User::create([
            'name' => 'jose',
            'email' => 'jose@email.com',
            'admin' => 1,
            'password' => $password
        ]);

        //verificamos se foi cadastrado no banco de dados 
        $this->assertDatabaseHas('users', ['email' => 'jose@email.com']);

        //envia-se uma requisição de cadastro de novo usuário
        $response = $this->withExceptionHandling()->actingAs($usuario)->call('POST', '/usuario/registrar', [
            'name' => 'name',
            'email' => 'usuario@email.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'admin' => 0,
            '_token' => csrf_token()
        ]);

        //verifica se o usuário foi cadastrado
        $this->assertDatabaseHas('users', ['email' => 'usuario@email.com']);
    }

    public function test_usuario_administrador_acessa_pagina_de_gerenciar_usuarios(){
        Artisan::call('migrate');
        //cadastro do usuário de teste no banco de dados
        $password = bcrypt('password');
        $usuarioA = User::create([
            'name' => 'jose',
            'email' => 'jose@email.com',
            'admin' => 1,
            'password' => $password
        ]);

        $usuarioB = User::create([
            'name' => 'maria',
            'email' => 'maria@email.com',
            'admin' => 0,
            'password' => $password
        ]);

        //verificamos se foi cadastrado no banco de dados 
        $this->assertDatabaseHas('users', ['email' => 'jose@email.com']);
        $this->assertDatabaseHas('users', ['email' => 'maria@email.com']);

        //se o usuário não estiver logado
        $response = $this->withExceptionHandling()
                         ->call('GET', '/usuario');

        $response->assertStatus(403);

        //se o usuário não for administrador
        $response = $this->withExceptionHandling()
                         ->actingAs($usuarioB)
                         ->call('GET', '/usuario');

        $response->assertStatus(403);

        //se estiver
        $response = $this//->withExceptionHandling()
                         ->actingAs($usuarioA)
                         ->call('GET', '/usuario');

        $response->assertStatus(200);
        
    }

    public function test_usuarios_logados_adm_podem_listar_usuarios(){
        Artisan::call('migrate');
        //cadastro do usuário de teste no banco de dados
        $password = bcrypt('password');
        $usuarioA = User::create([
            'name' => 'jose',
            'email' => 'jose@email.com',
            'admin' => 1,
            'password' => $password
        ]);

        $usuarioB = User::create([
            'name' => 'maria',
            'email' => 'maria@email.com',
            'admin' => 0,
            'password' => $password
        ]);

        for ($i = 0; $i < 5; $i++) { 
            $usuarios[] = factory('App\User')->create();
        }

        //se não estiver logado
        $response = $this->withExceptionHandling()
                         ->call('GET', '/usuario');

        $response->assertStatus(403);

        //se não for adm
        $response = $this->withExceptionHandling()
                         ->actingAs($usuarioB)
                         ->call('GET', '/usuario');

        $response->assertStatus(403);

        //se estiver logado
        $response = $this->withExceptionHandling()
                         ->actingAs($usuarioA)
                         ->call('GET', '/usuario');

        $response->assertStatus(200);

        foreach ($usuarios as $usuario) {
            $response->assertSee($usuario->name);
            $response->assertSee($usuario->email);
        }
    }

    public function test_administradores_editam_usuarios(){
        Artisan::call('migrate');

        //cadastro do usuário de teste no banco de dados
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
            'password' => $password
        ]);

        //verificamos se foi cadastrado no banco de dados 
        $this->assertDatabaseHas('users', ['email' => 'jose@email.com']);
        $this->assertDatabaseHas('users', ['email' => 'manuel@email.com']);
        $this->assertDatabaseMissing('users', ['name' => 'maria@email.com']);

        //USUÁRIO NÃO LOGADO
        $response = $this->withExceptionHandling()->call('POST', '/usuario/atualizar', [
            'id' => $usuarioA->id,
            'name' => 'maria',
            'email' => 'jose@email.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'admin' => 1,
            '_token' => csrf_token()
        ]);

        $response->assertStatus(403);

        //USUÁRIO NÃO É ADMINISTRADOR
        $response = $this->withExceptionHandling()->actingAs($usuarioB)->call('POST', '/usuario/atualizar', [
            'id' => $usuarioA->id,
            'name' => 'maria',
            'email' => 'jose@email.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'admin' => 1,
            '_token' => csrf_token()
        ]);

        $response->assertStatus(403);

        //USUÁRIO LOGADO E ADMINISTRADOR
        $response = $this->withExceptionHandling()->actingAs($usuarioA)->call('POST', '/usuario/atualizar', [
            'id' => $usuarioA->id,
            'name' => 'maria',
            'email' => 'jose@email.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'admin' => 1,
            '_token' => csrf_token()
        ]);

        $response->assertStatus(302);

        //verifica se o usuário foi cadastrado
        $this->assertDatabaseHas('users', ['name' => 'maria']);
    }

    public function test_usuario_administrador_pode_desativar_acesso_de_usuarios(){
       Artisan::call('migrate');

        //cadastro do usuário de teste no banco de dados
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
            'password' => $password
        ]);

        //verificamos se foi cadastrado no banco de dados 
        $this->assertDatabaseHas('users', ['email' => 'jose@email.com']);
        $this->assertDatabaseHas('users', ['email' => 'manuel@email.com']);

        //USUÁRIO NÃO LOGADO
        $response = $this->withExceptionHandling()->call('POST', '/usuario/desativar', [
            'id' => $usuarioA->id,
            '_token' => csrf_token()
        ]);

        $response->assertStatus(403);

        //USUÁRIO NÃO É ADM
        $response = $this->withExceptionHandling()->actingAs($usuarioB)->call('POST', '/usuario/desativar', [
            'id' => $usuarioA->id,
            '_token' => csrf_token()
        ]);

        $response->assertStatus(403);

        //USUÁRIO LOGADO E ADMINISTRADOR
        $response = $this->withExceptionHandling()->actingAs($usuarioA)->call('POST', '/usuario/desativar', [
            'id' => $usuarioA->id,
            '_token' => csrf_token()
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('users', ['email' => 'jose@email.com', 'active' => '0']);
    }

    public function test_usuario_desativado_nao_loga(){
        Artisan::call('migrate');

        //cadastro do usuário de teste no banco de dados
        $password = bcrypt('password');
        $usuarioA = User::create([
            'name' => 'jose',
            'email' => 'jose@email.com',
            'admin' => 1,
            'password' => $password
        ]);

        $usuarioA->active = 0;
        $usuarioA->save();

        //enviamos uma requisição de login
        $response = $this->withExceptionHandling()->call('POST', '/login', [
            'email' => 'jose@email.com',
            'password' => 'password',
            '_token' => csrf_token()
        ]);

        //verifica se existe esse usuário no banco de dados
        $this->assertDatabaseHas('users', ['email' => 'jose@email.com', 'active' => '0']);

        //verificamos o redirecionamento. Login inválido redirecina para /
        $response->assertStatus(302);
        $response->assertRedirect('/');

    }

    public function test_usuario_administrador_pode_reativar_acesso_de_usuarios(){
       Artisan::call('migrate');

        //cadastro do usuário de teste no banco de dados
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

        //verificamos se foi cadastrado no banco de dados 
        $this->assertDatabaseHas('users', ['email' => 'jose@email.com']);
        $this->assertDatabaseHas('users', ['email' => 'manuel@email.com']);

        //USUÁRIO NÃO LOGADO
        $response = $this->withExceptionHandling()->call('POST', '/usuario/reativar', [
            'id' => $usuarioB->id,
            '_token' => csrf_token()
        ]);

        $response->assertStatus(403);

        //USUÁRIO NÃO É ADM
        $response = $this->withExceptionHandling()->actingAs($usuarioB)->call('POST', '/usuario/reativar', [
            'id' => $usuarioB->id,
            '_token' => csrf_token()
        ]);

        $response->assertStatus(403);

        //USUÁRIO LOGADO E ADMINISTRADOR
        $response = $this->withExceptionHandling()->actingAs($usuarioA)->call('POST', '/usuario/reativar', [
            'id' => $usuarioB->id,
            '_token' => csrf_token()
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('users', ['email' => 'manuel@email.com', 'active' => '1']);
    }
}
