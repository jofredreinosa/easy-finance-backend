<?php 

namespace App\easyfinance\Dal;
use App\easyfinance\Model\FinancialMovement;
use \Illuminate\Database\QueryException;
use DB;

/**
 * Capa de Acceso a Datos para el Modelo FinancialMovement
 */
class FinancialMovementDAL
{
  /**
   * Array que almacena las relaciones con la tabla financialmovements
   * @var array
   */
    private $arrayRelaciones = ['transactiontypes'];

  /**
   * Devolver todos los tipos de Transacción
   * @return \Illuminate\Support\Collection
  */
    public function getAllMovements() {
        $movements = FinancialMovement::with($this->arrayRelaciones)
        ->orderBy('transactiondate', 'desc')
        ->get();
        return $movements;
    } 

    /**
     * Crear Movimiento
     * @param \Illuminate\Http\Request $data 
     * @return Object
     */
    public function createMovement($data) {
        $movement = new FinancialMovement;
        $movement->idtype            = $data->idtype;
        $movement->transactionnumber = $data->transactionnumber;
        $movement->transactiondate   = $data->transactiondate;
        $movement->transactionamount = $data->transactionamount;
        $movement->transactionmotive = $data->transactionmotive;
        $movement->save();
        return $movement;
    }

    /**
     * Actualizar Movimiento
     * @param int $id 
     * @param \Illuminate\Http\Request $data 
     * @return Object
     */
    public function updateMovement($id,$data) {
        $movement = FinancialMovement::where('id', $id)->first();
        if ( $movement ) {
          $movement->idtype            = $data->idtype;
          $movement->transactionnumber = $data->transactionnumber;
          $movement->transactiondate   = $data->transactiondate;
          $movement->transactionamount = $data->transactionamount;
          $movement->transactionmotive = $data->transactionmotive;
          $movement->updated_at        = date('Y-m-d H:i:s');
          $movement->save();
        }
        return $movement;
    }

    /**
      * Elimina un movimiento
      * @param int $id 
      * @return Object
      */ 
    public function deleteMovement($id) {
      try {
        $movement = FinancialMovement::where('id', $id)->delete();
        return $movement;
      }
      catch (QueryException $e) {
        return false;
      }
    }

    /**
      * Devolver resultado de los movimientos
      * @return \Illuminate\Support\Collection
    */
    public function getResult($desde, $hasta) {

        $result = DB::table('financialmovements')
        ->select('transactiontypes.operationtype', 'transactiontypes.desctype',
        DB::raw("count(financialmovements.id) as quantity"),
        DB::raw("sum(financialmovements.transactionamount) as total"),
        DB::raw("trim(to_char(sum(financialmovements.transactionamount),'999G999G999D99')) as formattedTotal"))
        ->join('transactiontypes', 'financialmovements.idtype', '=', 'transactiontypes.id')
        ->where('financialmovements.transactiondate', '>=', $desde)
        ->where('financialmovements.transactiondate', '<=', $hasta)
        ->groupBy('transactiontypes.operationtype', 'transactiontypes.desctype')
        ->get();

        return $result;
    } 
}
