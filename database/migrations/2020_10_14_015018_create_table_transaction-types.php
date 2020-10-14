<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTransactionTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction-types', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();

            $table->string('codetype',3)->unique()->comment('Código del tipo de transacción');
            $table->string('desctype',100)->comment('Descripcion del tipo de transacción');
            $table->string('operationtype',1)->comment('Indica si el tipo es ingreso o egreso (I / E)');
            $table->string('statustype',1)->comment('Estado de la transacción (A / I)');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction-types');
    }
}
