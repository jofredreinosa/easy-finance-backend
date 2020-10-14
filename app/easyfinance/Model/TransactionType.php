<?php

namespace App\easyfinance\Model;

use Illuminate\Database\Eloquent\Model;

use App\easyfinance\Model\FinancialMovement;

class TransactionType extends Model
{
	protected $table = 'transactiontypes';
	
	public function movements()
  {
      return $this->hasMany(FinancialMovement::class,'idtype','id');
  }
}