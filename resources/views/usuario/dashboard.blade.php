@extends('layouts.app')
@section('pageTitle', 'Gerenciar produtos')
@section('content')
<div class="container">

	<div class="row">
		<h3 style="float: left; margin-top: 8px">
			Indicadores:
		</h3>
		
	</div>
    <div class="row">
		<table class="table">
			<tr>
				<th>Nome</th>
				<th>Descrição</th>
				<th>Custo</th>
				<th>Quantidade</th>
				<th>Total</th>
			</tr>
			@foreach($produtos as $produto)
			@if($produto->quantidade > 0)
			<tr>
				<td>{{$produto->nome}}</td>
				<td>{{$produto->descricao}}</td>
				<td>R${{$produto->custo}}</td>
				<td>{{$produto->quantidade}}</td>
				<td>
					<b>R${{($produto->custo * $produto->quantidade)}}</b>
				</td>
			</tr>
			@endif

			@endforeach
		</table>
		<h3>Valor total dos produtos: R${{$total}}</h3>
	</div>

</div>
@endsection
