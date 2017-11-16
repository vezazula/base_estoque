@extends('layouts.app')

@section('content')
<div class="container">

	<div class="row">
		<h3 style="float: left; margin-top: 8px">
			Gerenciar fornecedores:
		</h3>	
		<a href="/fornecedor" style="float: right; margin-right: 6px; margin-bottom: 10px">
			<button class="btn btn-primary btn-lg" >
				<i class="fa fa-refresh" aria-hidden="true"></i>
			</button>
		</a>
		<button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#novo_fornecedor" href="" style="float: right; margin-right: 6px; margin-bottom: 6px">
			<i class="fa fa-plus" aria-hidden="true"></i>
		</button>
		
		</div>
    <div class="row">
    	<form action="fornecedor/buscar" method="get">
		{{ csrf_field()}}
			<div class="input-group">
				<input type="text" name="nome" class="form-control" placeholder="Pesquisar fornecedor por nome...">
				<span class="input-group-btn">
				<input type="submit" class="btn btn-info" type="button" value="Pesquisar">
				</span>
			</div>
		</form>
		<table class="table">
			<tr>
				<th>Nome</th>
				<th>CNPJ</th>
				<th>Endereço</th>
				<th>Ações</th>
			</tr>
			@foreach($fornecedores as $fornecedor)
			<tr>
				<td>{{$fornecedor->nome}}</td>
				<td>{{$fornecedor->cnpj}}</td>
				<td>{{$fornecedor->endereco}}</td>
				<td>
					<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editar_fornecedor{{$fornecedor->id}}">Editar</button>

					<!-- MODAL PARA A EDIÇÃO DE fornecedores -->
					<div class="modal fade" id="editar_fornecedor{{$fornecedor->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
									<h3 class="modal-title" id="myModalLabel">Editar fornecedor</h3>
								</div>
								<form action="fornecedor/editar" method="post">
								{{ csrf_field() }}
									<div class="modal-body">
										<div class="form-group">
											<input type="hidden" name="id" value="{{$fornecedor->id}}">
											<label>Nome</label>
											<input type="text" name="nome" class="form-control" value="{{$fornecedor->nome}}" placeholder="Nome" autofocus>
											<label>CNPJ</label>
											<input type="text" name="cnpj" class="form-control" value="{{$fornecedor->cnpj}}" placeholder="Descrição">
											<label>Endereço</label>
											<input type="text" name="endereco" class="form-control" value="{{$fornecedor->endereco}}" placeholder="Custo">
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
										<input type="submit" class="btn btn-primary" value="Confirmar">
									</div>
								</form>
							</div>
						</div>
					</div>

					<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deletar_fornecedor{{$fornecedor->id}}">Deletar</button>

					<div class="modal fade" id="deletar_fornecedor{{$fornecedor->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
									<h3 class="modal-title" id="myModalLabel">Editar fornecedor</h3>
								</div>
								<form action="fornecedor/deletar" method="post">
								{{ csrf_field() }}
									<div class="modal-body">
										<p>Tem certeza que quer deletar este fornecedor? (Ele só será deletado se não houver nenhum produto vinculado a ele)</p>
									</div>
									<div class="modal-footer">
										<input type="hidden" name="id" value="{{$fornecedor->id}}">
										<button type="button" class="btn btn-default" data-dismiss="modal">Não</button>
										<input type="submit" class="btn btn-primary" value="Sim">
									</div>
								</form>
							</div>
						</div>
					</div>
					<a href="produto/fornecedor/{{$fornecedor->id}}">
						<button type="button" class="btn btn-success">
							Produtos
						</button>
					</a>
					
				</td>
			</tr>

			@endforeach
		</table>
	</div>
	<div class="modal fade" id="novo_fornecedor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title" id="myModalLabel">Cadastrar novo fornecedor</h3>
				</div>
				<form action="fornecedor/inserir" method="post">
				{{ csrf_field() }}
					<div class="modal-body">
						<div class="form-group">
							<label>Nome</label>
							<input type="text" name="nome" class="form-control" placeholder="Nome" autofocus>
							<label>CNPJ</label>
							<input type="text" name="cnpj" class="form-control" placeholder="CNPJ">
							<label>Endereço</label>
							<input type="text" name="endereco" class="form-control" placeholder="Endereço">
						
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
						<input type="submit" class="btn btn-primary" value="Confirmar">
					</div>
				</form>
			</div>
		</div>
	</div>	
</div>
@endsection
