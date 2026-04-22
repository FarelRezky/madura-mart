<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pastikan nama tabelnya 'sales'
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('sale_number', 15)->unique(); // Contoh: SL-20240101-001
            $table->date('sale_date');
            $table->bigInteger('total_price')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};