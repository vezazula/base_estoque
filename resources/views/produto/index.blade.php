@extends('layouts.app')
@section('pageTitle', 'Gerenciar produtos')
@section('content')
<div class="container">

	<div class="row">
		<h3 style="float: left; margin-top: 8px">
			Gerenciar produtos:
		</h3>	
		<a href="#esgotados" style="float: right; margin-right: 6px; margin-bottom: 10px">
			<button class="btn btn-danger btn-lg" >
				Esgotados
			</button>
		</a>
		<a href="/produto" style="float: right; margin-right: 6px; margin-bottom: 10px">
			<button class="btn btn-primary btn-lg" >
				<i class="fa fa-refresh" aria-hidden="true"></i>
			</button>
		</a>
		<button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#novo_produto" href="" style="float: right; margin-right: 6px; margin-bottom: 6px">
			<i class="fa fa-plus" aria-hidden="true"></i>
		</button>
		
		</div>
    <div class="row">
    	<form action="produto/buscar" method="get">
		{{ csrf_field()}}
			<div class="input-group">
				<input type="text" name="nome" class="form-control" placeholder="Pesquisar produto por nome...">
				<span class="input-group-btn">
				<input type="submit" class="btn btn-info" type="button" value="Pesquisar">
				</span>
			</div>
		</form>
		<table class="table">
			<tr>
				<th>Nome</th>
				<th>Descrição</th>
				<th>Custo</th>
				<th>Quantidade</th>
				<th>Ações</th>
			</tr>
			@foreach($produtos as $produto)
			@if($produto->quantidade > 0)
			<tr>
				<td>{{$produto->nome}}</td>
				<td>{{$produto->descricao}}</td>
				<td>R${{$produto->custo}}</td>
				<td>{{$produto->quantidade}}</td>
				<td>
					<button type="button" class="btn btn-info" data-toggle="modal" data-target="#baixa_produto{{$produto->id}}">
						Dar baixa
					</button>
					<div class="modal fade" id="baixa_produto{{$produto->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
									<h3 class="modal-title" id="myModalLabel">Dar baixa no produto</h3>
								</div>
								<form action="produto/debitar" method="post">
								{{ csrf_field() }}
									<div class="modal-body">
										<div class="form-group">
											<input type="hidden" name="id" value="{{$produto->id}}">
											<label>Quantidade</label>
											<input type="number" name="quantidade" min="0" max="{{$produto->quantidade}}" class="form-control" value="0">
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
					<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editar_produto{{$produto->id}}">Editar</button>

					<!-- MODAL PARA A EDIÇÃO DE PRODUTOS -->
					<div class="modal fade" id="editar_produto{{$produto->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
									<h3 class="modal-title" id="myModalLabel">Editar produto</h3>
								</div>
								<form action="produto/editar" method="post">
								{{ csrf_field() }}
									<div class="modal-body">
										<div class="form-group">
											<input type="hidden" name="id" value="{{$produto->id}}">
											<label>Nome</label>
											<input type="text" name="nome" class="form-control" value="{{$produto->nome}}" placeholder="Nome" autofocus>
											<label>Descrição</label>
											<input type="text" name="descricao" class="form-control" value="{{$produto->descricao}}" placeholder="Descrição">
											<label>Custo</label>
											<input type="text" name="custo" class="form-control" value="{{$produto->custo}}" placeholder="Custo">
											<label>Quantidade</label>
											<input type="number" name="quantidade" min="0" class="form-control" value="{{$produto->quantidade}}">
											<label>Fornecedor</label>
											<select name="fornecedor" class="form-control">
												@foreach($fornecedores as $fornecedor)
												<option value="{{$fornecedor->id}}" {{$produto->fornecedor_id == $fornecedor->id ? "selected" : ""}}>
													{{$fornecedor->nome}} ({{$fornecedor->cnpj}})
												</option>
												@endforeach
												
											</select>
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
				</td>
			</tr>
			@endif

			@endforeach
		</table>
	</div>
	<div class="row">
		<div class="row">
			<h3 id="esgotados" style="float: left; margin-top: 8px;">
				&nbsp;&nbsp;&nbsp;Produtos esgotados:
			</h3>	
			</div>
	    <div class="row" style="margin-left: 0px">
		<table class="table">
						<tr>
							<th>Nome</th>
							<th>Descrição</th>
							<th>Custo</th>
							<th>Quantidade</th>
							<th colspan="3">Ações</th>
						</tr>
						@foreach($produtos as $produto)
						@if($produto->quantidade == 0)
						<tr>
							<td>{{$produto->nome}}</td>
							<td>{{$produto->descricao}}</td>
							<td>R${{$produto->custo}}</td>
							<td>{{$produto->quantidade}}</td>
							<td>
								<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editar_produto_esgotado{{$produto->id}}">
									Editar
								</button>

								<!-- MODAL PARA A EDIÇÃO DE PRODUTOS -->
								<div class="modal fade" id="editar_produto_esgotado{{$produto->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
												<h3 class="modal-title" id="myModalLabel">Editar produto</h3>
											</div>
											<form action="produto/editar" method="post">
											{{ csrf_field() }}
												<div class="modal-body">
													<div class="form-group">
														<input type="hidden" name="id" value="{{$produto->id}}">
														<label>Nome</label>
														<input type="text" name="nome" class="form-control" value="{{$produto->nome}}" placeholder="Nome" autofocus>
														<label>Descrição</label>
														<input type="text" name="descricao" class="form-control" value="{{$produto->descricao}}" placeholder="Descrição">
														<label>Custo</label>
														<input type="text" name="custo" class="form-control" value="{{$produto->custo}}" placeholder="Custo">
														<label>Quantidade</label>
														<input type="number" name="quantidade" min="0" class="form-control" value="{{$produto->quantidade}}">
														<label>Fornecedor</label>
														<select name="fornecedor" class="form-control">
															@foreach($fornecedores as $fornecedor)
															<option value="{{$fornecedor->id}}" {{$produto->fornecedor_id == $fornecedor->id ? "selected" : ""}}>
																{{$fornecedor->nome}} ({{$fornecedor->cnpj}})
															</option>
															@endforeach
															
														</select>
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
							</td>
						</tr>
						@endif

						@endforeach
					</table>
	</div>

	<div class="modal fade" id="esgotados" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title" id="myModalLabel">Produtos esgotados</h3>
				</div>

					
				
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<button type="button" class="btn btn-primary" data-dismiss="modal">Confirmar</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="novo_produto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title" id="myModalLabel">Cadastrar novo produto</h3>
				</div>
				<form action="produto/inserir" method="post">
				{{ csrf_field() }}
					<div class="modal-body">
						<div class="form-group">
							<label>Nome</label>
							<input type="text" name="nome" class="form-control" placeholder="Nome" autofocus>
							<label>Descrição</label>
							<input type="text" name="descricao" class="form-control" placeholder="Descrição">
							<label>Custo</label>
							<input type="text" name="custo" class="form-control" placeholder="Custo">
							<label>Quantidade</label>
							<input type="number" name="quantidade" min="0" class="form-control">
							<label>Fornecedor</label>
							<select name="fornecedor" class="form-control">
								<option value="" selected>Selecione</option>

								@foreach($fornecedores as $fornecedor)
								<option value="{{$fornecedor->id}}">
									{{$fornecedor->nome}} ({{$fornecedor->cnpj}})
								</option>
								@endforeach

							</select>
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
