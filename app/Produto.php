<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $table = 'produtos';

    protected $fillable = ['nome', 'descricao', 'custo', 'quantidade', 'fornecedor_id'];

    public function fornecedor(){
    	return $this->belongsTo(Fornecedor::class);
    }
}
