<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id();
            $table->string('note_number_purchase', 15)->nullable();
            $table->string('serial_number_product', 20)->nullable();
            $table->bigInteger('purchase_price')->default(0);
            $table->bigInteger('selling_price')->default(0);
            $table->smallInteger('selling_margin')->default(0);
            $table->integer('purchase_amount')->default(0);
            $table->bigInteger('subtotal')->default(0);
            $table->date('expired_date')->nullable();

            $table->foreign('note_number_purchase')
                  ->references('note_number')
                  ->on('purchases')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('serial_number_product')
                  ->references('serial_number')
                  ->on('products')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_details');
    }
};