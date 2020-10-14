<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Validator;

use App\easyfinance\Model\TransactionType;
use App\easyfinance\Dal\TransactionTypeDal;

class TransactionTypeController extends Controller
{
    protected $TransactionTypeDAL;
    protected $TransactionType;

    protected $rules = [
        'desctype'      => 'required|string|min:1:max:100',
        'codetype'      => 'required|string|size:3',
        'statustype'    => 'required|string|size:1|in:A,I',
        'operationtype' => 'required|string|size:1|in:I,E',
    ];

    protected $validationMessages = [
      'desctype.required'      => 'Es necesario incluir una descripción',
      'codetype.required'      => 'Es necesario incluir el código',
      'statustype.required'    => 'Es necesario incluir el estado',
      'operationtype.required' => 'Es necesario incluir el tipo de operación',
      'codetype.size'          => 'El código debe contener 3 caracteres',
      'statustype.size'        => 'El estado debe contener 1 caracter',
      'statustype.in'          => 'El estado debe ser A o I',
      'operationtype.size'     => 'El tipo de operación debe contener 1 caracter',
      'operationtype.in'       => 'El tipo de operación debe ser I o E',
    ];

    public function  __construct(TransactionTypeDAL $TransactionTypeDAL, TransactionType $TransactionType) {
        $this->TransactionTypeDAL = $TransactionTypeDAL;
        $this->TransactionType    = $TransactionType;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactionTypes = $this->TransactionTypeDAL->getAllTransactionType();
        
        return response()->json([
            'data'    => $transactionTypes,
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
        if ( ! $validation->fails() ) {

            $transactionType = $this->TransactionTypeDAL->createTransactionType($request);

            if ( $transactionType ) {
                return response()->json([
                    'message' => 'Tipo de transacción creado con éxito',
                ], 201);
            }
            else {
                return response()->json([
                    'message' => 'Error al Crear el tipo de transacción',
                    'errors' => []
                ], 422);
            }
        }
        else {
            return response()->json([
                'message' => 'Error al crear el tipo de transacción',
                'errors' => $validation->errors(),
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transactionType = $this->TransactionTypeDAL->getOneTransactionType($id);
        if ( $transactionType ) {
            return response()->json([
                'success' => true,
                'data'    => $transactionType,
            ], 200);
        }

        return response()->json([
            'message' => 'No se encontró el tipo de transacción',
            'errors' => [],
        ], 404);
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
        $transactionType = $this->TransactionTypeDAL->getOneTransactionType($id);
        
        if ( ! $transactionType ) {
            return response()->json([
                'message' => 'Error al actualizar el tipo de transacción ' . 'El identificador [' . $id . '] NO existe.',
                'errors' => []
            ], 404);
        }
        else {
            $validation = Validator::make($request->all(), $this->rules, $this->validationMessages);
            if ( ! $validation->fails() ) {
                $transactionType = $this->TransactionTypeDAL->updateTransactionType($transactionType->id , $request);

                if( $transactionType ) {
                    return response()->json([
                        'message' => 'El tipo de transacción fue Actualizado con éxito.',
                    ], 200);
                }
                else {
                    return response()->json([
                        'message' => 'Error al actualizar el tipo de transacción 1',
                        'errors' => []
                    ], 422);
                }
            }
            else {
                return response()->json([
                    'message' => 'Error al actualizar el tipo de transacción 2',
                    'errors' => $validation->errors(),
                ], 422);
            }
        } 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transactionType = $this->TransactionTypeDAL->getOneTransactionType($id);

        if ( ! $transactionType ) {
            return response()->json([
                'message' => 'Error al inactivar el tipo de transacción ' . 'El identificador [' . $id . '] NO existe.',
                'errors' => []
            ], 404);
        }
        else {
            $transactionType = $this->TransactionTypeDAL->inactivateTransactionType($id);

            if( $transactionType ) {
                return response()->json([
                    'message' => 'El tipo de transacción fue inactivado con éxito.',
                ], 200);
            }
            else {
                return response()->json([
                    'message' => 'Error al inactivar el tipo de transacción',
                    'errors' => []
                ], 422);
            }
        } 
    }
}
