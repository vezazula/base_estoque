<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fornecedor extends Model
{
	use SoftDeletes;

    protected $table = 'fornecedores';
	protected $dates = ['deleted_at'];
   	protected $fillable = ['nome', 'cnpj', 'endereco'];

    public function produtos(){
    	return $this->hasMany(Produto::class);
    }
}
