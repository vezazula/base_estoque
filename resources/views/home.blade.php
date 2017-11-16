@extends('layouts.app')

@section('content')
<div class="container">
    <div class="jumbotron">
        <h1>Sistema de controle de estoque</h1>
        <h3>Bem vindo</h3>
		<hr>
		<br>

		@if($admin == 1)
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Dashboard</h3>
			</div>
			<div class="panel-body">
			<p>Os usuários do tipo administrador poderão visualizar o valor total dos produtos em estoque</p>
			</div>
			<p>&nbsp;&nbsp;<a href="/dashboard" class="btn btn-default btn-lg">Acessar Dashboard</a></p>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Gerenciar usuários</h3>
			</div>
			<div class="panel-body">
			<p>Permite ao administrador cadastrar, pesquisar e gerenciar usuários cadastrados</p>
			</div>
			<p>&nbsp;&nbsp;<a href="/usuario" class="btn btn-default btn-lg">Gerenciar usuários</a></p>
		</div>
		@endif

		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Gerenciar fornecedores</h3>
			</div>
			<div class="panel-body">
			<p>Permite inserir, excluir e atualizar fornecedores</p>
			</div>
			<p>&nbsp;&nbsp;<a href="/fornecedor" class="btn btn-default btn-lg">Gerenciar fornecedores</a></p>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Gerenciar produtos</h3>
			</div>
			<div class="panel-body">
			<p>Permite cadastrar, remover, atualizar e pesquisar produtos no estoque</p>
			</div>
			<p>&nbsp;&nbsp;<a href="/produto" class="btn btn-default btn-lg">Gerenciar produtos</a></p>
		</div>
    </div>
</div>
@endsection
