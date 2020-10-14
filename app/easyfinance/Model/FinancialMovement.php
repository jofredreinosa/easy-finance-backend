<?php

namespace App\easyfinance\Model;

use Illuminate\Database\Eloquent\Model;


class FinancialMovement extends Model
{
	protected $table = 'transaction-types';
	
	/* public function movements()
  {
      return $this->hasMany(Fondofijo::class,'codoficina','codoficina');
  } */
}