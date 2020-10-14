<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransactionmotiveToFinancialmovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('financialmovements', function (Blueprint $table) {
            $table->string('transactionmotive',300)->comment('transaction motive')->after('transactionamount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('financialmovements', function (Blueprint $table) {
            $table->dropColumn('transactionmotive');
        });
    }
}
