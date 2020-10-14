<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableFinancialMovements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financialmovements', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();

            $table->bigInteger('idtype')->unsigned()->comment('foreign key to transaction-types');
            $table->string('transactionnumber',20)->comment('transaction number');
            $table->date('transactiondate')->comment('transaction date');
            $table->double('transactionamount',15,2)->comment('transaction amount');
            $table->timestamps();

            $table->index('idtype','i_financial_movements_idtype');

            $table->foreign('idtype', 'fk_financialmovements_to_transactiontypes')
            ->references('id')->on('transactiontypes')
            ->onUpdate('RESTRICT')
            ->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('financialmovements', function(Blueprint $table)
        {
            $table->dropForeign('fk_financialmovements_to_transactiontypes');
        });

        Schema::dropIfExists('financialmovements');
    }
}
