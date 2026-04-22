<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_details', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel sales id
            $table->foreignId('sale_id')
                  ->constrained('sales')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            
            // Kolom detail barang
            $table->string('product_serial', 10);
            $table->bigInteger('selling_price');
            $table->integer('qty');
            $table->bigInteger('subtotal');
            $table->timestamps();

            // Opsional: Hubungkan ke tabel products jika ada
            $table->foreign('product_serial')
                  ->references('serial_number')
                  ->on('products')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_details');
    }
};