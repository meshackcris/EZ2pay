<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('apt_pay_transactions', function (Blueprint $table) {
        $table->uuid('Id')->primary();
        $table->uuid('UserId');
        $table->integer('ReferenceNumber');
        $table->text('AptPayReferenceId');
        $table->decimal('Amount', 15, 2);
        $table->string('Currency');
        $table->integer('PaymentDirection');
        $table->integer('TransactionType');
        $table->integer('Status');
        $table->text('ErrorMessage')->nullable();
        $table->text('IdentityId')->nullable();
        $table->text('Memo')->nullable();
        $table->boolean('WaiveFee');
        $table->timestampTz('CreatedAt');
        $table->timestampTz('UpdatedAt')->nullable();
        $table->string('Discriminator', 50);
        $table->uuid('AptPayTransactionId')->nullable();
        $table->integer('DaysToExpire')->nullable();
        $table->integer('InteracType')->nullable();
        $table->integer('Authorization')->nullable();
        $table->integer('AuthorizationExpiry')->nullable();
        $table->text('Custom1')->nullable();
        $table->text('Custom2')->nullable();
        $table->text('Custom3')->nullable();
        $table->text('Custom4')->nullable();
        $table->text('Custom5')->nullable();
        $table->text('PaymentMethod')->nullable();
        $table->text('BankAccountNumber')->nullable();
        $table->text('BankInstitutionNumber')->nullable();
        $table->text('BankTransitNumber')->nullable();
        $table->uuid('AptPayCollectionTransaction_AptPayTransactionId')->nullable();
        $table->uuid('BankDetailsId')->nullable();
        $table->text('CryptoCurrency');
    });
}

};
