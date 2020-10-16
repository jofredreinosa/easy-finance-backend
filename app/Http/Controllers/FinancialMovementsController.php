<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Validator;

use App\easyfinance\Model\FinancialMovement;
use App\easyfinance\Dal\FinancialMovementDAL;

class FinancialMovementsController extends Controller
{
    protected $FinancialMovement;
    protected $FinancialMovementDAL;

    protected $rules = [
        'idtype'            => 'required',
        'transactionnumber' => 'required|string|min:1:max:20',
        'transactiondate'   => 'required|date',
        'transactionamount' => 'required|numeric',
        'transactionmotive' => 'required|string|min:1:max:300',
    ];

    protected $validationMessages = [
      'idtype.required'            => 'Es necesario incluir tipo de transacción',
      'transactionnumber.required' => 'Es necesario incluir el número de transacción',
      'transactionnumber.max'      => 'El número de transacción no puede tener más de 20 dígitos',
      'transactiondate.required'   => 'Es necesario incluir la fecha de la transacción',
      'transactionamount.required' => 'Es necesario incluir el monto de la transacción',
      'transactionamount.numeric'  => 'El monto de la transacción debe ser numérico',
      'transactionmotive.required' => 'Es necesario incluir la descripción de la transacción',
      'transactionmotive.max'      => 'La descripción debe tener un máximo de 300 caracteres',
    ];

    public function  __construct(
      FinancialMovementDAL $FinancialMovementDAL,
      FinancialMovement $FinancialMovement
    ) {
        $this->FinancialMovementDAL = $FinancialMovementDAL;
        $this->FinancialMovement    = $FinancialMovement;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $movements = $this->FinancialMovementDAL->getAllMovements();
        
        return response()->json([
            'data' => $movements,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), $this->rules, $this->validationMessages);

        if ( $validation->fails() ) {
            return response()->json([
                'message' => 'Error al crear la transacción',
                'errors' => $validation->errors(),
            ], 422);
        }

        $movement = $this->FinancialMovementDAL->createMovement($request);

        if ( ! $movement ) {
          return response()->json([
            'message' => 'Error al crear la transacción',
            'errors' => []
          ], 422);
        }

        return response()->json([
            'message' => 'Transacción creada con éxito',
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), $this->rules, $this->validationMessages);
        if ( $validation->fails() ) {
          return response()->json([
            'message' => 'Error al actualizar el movimiento',
            'errors' => $validation->errors(),
          ], 422);
        }
        
        $movement = $this->FinancialMovementDAL->updateMovement($id , $request);

        if( ! $movement ) {
          return response()->json([
              'message' => 'Error al actualizar el movimiento',
              'errors' => []
          ], 422);
        }
        
        return response()->json([
            'message' => 'El movimiento fue Actualizado con éxito.',
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $movement = $this->FinancialMovementDAL->deleteMovement($id);

        if( ! $movement ) {
          return response()->json([
              'message' => 'Ocurrió un error al eliminar el movimiento',
              'errors' => []
          ], 404);
        }

        return response()->json([
            'message' => 'El movimiento fue eliminado con éxito.',
        ], 200);
      }

    /**
     * Retrieve movements results.
     *
     * @return \Illuminate\Http\Response
     */
    public function getResults($desde,$hasta)
    {
      $desde = is_null($desde) || empty($desde) || $desde == 'null' ? date('1970-01-01') : $desde;
      $hasta = is_null($hasta) || empty($hasta) || $hasta == 'null' ? date('2100-12-31') : $hasta;
      
      $result = $this->FinancialMovementDAL->getResult($desde,$hasta);

      return response()->json([
          'data' => $result,
      ], 200);
    }
}
