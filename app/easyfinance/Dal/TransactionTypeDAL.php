<?php 

namespace App\easyfinance\Dal;
use DB;
use App\easyfinance\Model\TransactionType;
use \Illuminate\Database\QueryException;
/**
 * Capa de Acceso a Datos para el Modelo TransactionType
 */
class TransactionTypeDAL
{

    /**
     * Devolver todos los tipos de Transacción
     * @return \Illuminate\Support\Collection
    */
    public function getAllTransactionType() {
        $transactionTypes = TransactionType::select('id', 'codetype', 'desctype','operationtype','statustype')
        ->where('statustype', '=', 'A')
        ->get();
        return $transactionTypes;
    } 

    /**
     * Devolver un tipo de Transacción
     * @param int $id 
     * @return \Illuminate\Support\Collection
    */
    public function getOneTransactionType($id) {
        $transactionType = TransactionType::where('id', $id)->first();
        return $transactionType;
    }
    
    /**
     * Crear un Nuevo Tipo de Transacción
     * @param \Illuminate\Http\Request $data 
     * @return Object
     */
    public function createTransactionType($data) {
        $transactionType = new TransactionType;
        $transactionType->codetype      = $data->codetype;
        $transactionType->desctype      = $data->desctype;
        $transactionType->operationtype = $data->operationtype;
        $transactionType->statustype    = $data->statustype;
        $transactionType->save();
        return $transactionType;
    }

    /**
      * Actualiza un Tipo de transacción
      * @param int $id 
      * @param \Illuminate\Http\Request $data 
      * @return Object
      */ 
    public function updateTransactionType($id,$data) {
        $transactionType = TransactionType::where('id', $id)->first();
        if ( $transactionType ) {
            $transactionType->codetype      = $data->codetype;
            $transactionType->desctype      = $data->desctype;
            $transactionType->operationtype = $data->operationtype;
            $transactionType->statustype    = $data->statustype;
            $transactionType->updated_at    = date('Y-m-d H:i:s');
            $transactionType->save();
        }
        return $transactionType;
    }

    /**
      * Elimina un Tipo de transacción
      * @param int $id 
      * @return Object
      */ 
    public function deleteTransactionType($id) {
      try {
        $transactionType = TransactionType::where('id', $id)->delete();
        return $transactionType;
      }
      catch (QueryException $e) {
        return false;
      }
    }
}
