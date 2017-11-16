@extends('layouts.app')

@section('content')
<div class="container">

	<div class="row">
		<h3 style="float: left; margin-top: 8px">
			Gerenciar usuários:
		</h3>	
		<a href="/usuario" style="float: right; margin-right: 6px; margin-bottom: 10px">
			<button class="btn btn-primary btn-lg" >
				<i class="fa fa-refresh" aria-hidden="true"></i>
			</button>
		</a>
		<button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#novo_usuario" style="float: right; margin-right: 6px; margin-bottom: 6px">
			<i class="fa fa-plus" aria-hidden="true"></i>
		</button>
		
		</div>
    <div class="row">
		<table class="table">
			<tr>
				<th>Nome</th>
				<th>Email</th>
				<th colspan="2">Ações</th>
			</tr>
			@foreach($usuarios as $usuario)
			@if($usuario->id == $user->id)
			<tr>
				<td>{{$usuario->name}}
					@if($usuario->admin == true)
						<span class="label label-danger">Admin</span>
						<span class="label label-success">Logado</span>
					@endif
				</td>	
				<td>{{$usuario->email}}</td>
				<td>
					<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editar_usuario{{$usuario->id}}">
						Editar
					</button>
					<div class="modal fade" id="editar_usuario{{$usuario->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h3 class="modal-title" id="myModalLabel">Editar usuário{{$usuario->id}}</h3>
								</div>
								<form action="/usuario/atualizar" method="post">
								{{ csrf_field() }}
									<div class="modal-body">
										<div class="form-group">
											<input type="hidden" name="id" value="{{$usuario->id}}">
											<label>Nome</label>
											<input type="text" name="name" class="form-control" value="{{$usuario->name}}" placeholder="Nome" autofocus>
											<label>Email</label>
											<input type="email" name="email" class="form-control" value="{{$usuario->email}}" placeholder="Email">
											<br>
											
											@if($usuario->id != $user->id)
											@if($usuario->admin == 1)
											<label>Administrador</label>
											<div class="radio">
											  	<label><input type="radio" name="admin" value="1" checked>Sim</label>&nbsp;
											  	<label><input type="radio" name="admin" value="0" >Não</label>
											</div>
											@else
											<div class="radio">
											  	<label><input type="radio" name="admin" value="1">Sim</label>&nbsp;
											  	<label><input type="radio" name="admin" value="0" checked>Não</label>
											</div>
											@endif
											@endif
											
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
				<td>
					
				</td>
			</tr>
			@endif
			@endforeach
			@foreach($usuarios as $usuario)
			@if($usuario->id != $user->id)
			<tr>
				<td>{{$usuario->name}}
					@if($usuario->admin == true)
						<span class="label label-danger">Admin</span>
					@endif
					@if($usuario->active == false)
						<span class="label label-default">Desabilitado</span>
					@endif
				</td>
				<td>{{$usuario->email}}</td>
				<td>
					<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editar_usuario{{$usuario->id}}">
						Editar
					</button>
					<div class="modal fade" id="editar_usuario{{$usuario->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h3 class="modal-title" id="myModalLabel">Editar usuário{{$usuario->id}}</h3>
								</div>
								<form action="/usuario/atualizar" method="post">
								{{ csrf_field() }}
									<div class="modal-body">
										<div class="form-group">
											<input type="hidden" name="id" value="{{$usuario->id}}">
											<label>Nome</label>
											<input type="text" name="name" class="form-control" value="{{$usuario->name}}" placeholder="Nome" autofocus>
											<label>Email</label>
											<input type="email" name="email" class="form-control" value="{{$usuario->email}}" placeholder="Email">
											<br>
											
											@if($usuario->id != $user->id)
											@if($usuario->admin == 1)
											<label>Administrador</label>
											<div class="radio">
											  	<label><input type="radio" name="admin" value="1" checked>Sim</label>&nbsp;
											  	<label><input type="radio" name="admin" value="0" >Não</label>
											</div>
											@else
											<div class="radio">
											  	<label><input type="radio" name="admin" value="1">Sim</label>&nbsp;
											  	<label><input type="radio" name="admin" value="0" checked>Não</label>
											</div>
											@endif
											@endif
											
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
				<td>
					@if($usuario->active == 1)
					<form action="usuario/desativar" method="post">
						{{ csrf_field() }}
						<input type="hidden" value="{{ $usuario->id }}" name="id">
						<input type="submit" value="Desativar" class="btn btn-danger">
					</form>
					@else
					<form action="usuario/reativar" method="post">
						{{ csrf_field() }}
						<input type="hidden" value="{{ $usuario->id }}" name="id">
						<input type="submit" value="Ativar" class="btn btn-primary">
					</form>
					@endif
				</td>
			</tr>
			@endif
			@endforeach
		</table>
	</div>
	<div class="modal fade" id="novo_usuario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title" id="myModalLabel">Cadastrar novo usuario</h3>
				</div>
				<form action="/usuario/registrar" method="post">
				{{ csrf_field() }}
					<div class="modal-body">
						<div class="form-group">
							<label>Nome</label>
							<input type="text" name="name" class="form-control" placeholder="Nome" autofocus>
							<label>Email</label>
							<input type="email" name="email" class="form-control" placeholder="Email">
							<label>Senha</label>
							<input type="password" name="password" class="form-control" placeholder="Senha">
							<label>Confirme a Senha</label>
							<input type="password" name="password_confirmation" class="form-control" placeholder="Senha">
							<br>
							
							<label>Administrador</label>
							<div class="radio">
							  	<label><input type="radio" name="admin" value="1">Sim</label>&nbsp;
							  	<label><input type="radio" name="admin" value="0" checked>Não</label>
							</div>
							
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
