<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Müşteri Bilgileri
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            
            // Teslimat Adresi
            $table->text('address');
            $table->string('city');
            $table->string('district');
            $table->string('postal_code');
            
            // Sipariş Bilgileri
            $table->json('items'); // Sepet içeriği
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->decimal('total', 10, 2);
            
            // Ödeme
            $table->string('payment_method'); // cash_on_delivery, credit_card
            $table->string('payment_status')->default('pending'); // pending, paid, failed
            $table->string('order_status')->default('pending'); // pending, processing, shipped, delivered, cancelled
            
            // Notlar
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
