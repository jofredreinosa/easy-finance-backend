<?php

namespace App\easyfinance\Model;

use Illuminate\Database\Eloquent\Model;
use App\easyfinance\Model\TransactionType;

class FinancialMovement extends Model
{
  protected $table = 'financialmovements';
  
  public function transactiontypes()
  {
      return $this->belongsTo(TransactionType::class,'idtype','id');
  }
}