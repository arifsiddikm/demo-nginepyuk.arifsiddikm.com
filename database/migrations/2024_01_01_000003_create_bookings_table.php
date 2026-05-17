<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            // Guest info (untuk non-login)
            $table->string('guest_name');
            $table->string('guest_email');
            $table->string('guest_phone')->nullable();
            // Booking detail
            $table->date('checkin_date');
            $table->date('checkout_date');
            $table->integer('nights');
            $table->integer('rooms')->default(1);
            $table->integer('guests')->default(1);
            $table->text('special_request')->nullable();
            // Pricing
            $table->decimal('price_per_night', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            // Payment
            $table->enum('payment_method', ['midtrans', 'bank_transfer'])->nullable();
            $table->enum('status', [
                'pending',
                'waiting_payment',
                'paid_unverified',
                'confirmed',
                'completed',
                'expired',
                'cancelled'
            ])->default('pending');
            $table->string('midtrans_order_id')->nullable();
            $table->string('midtrans_snap_token')->nullable();
            $table->string('midtrans_transaction_id')->nullable();
            $table->string('midtrans_payment_type')->nullable();
            $table->string('transfer_proof')->nullable(); // path file bukti TF
            $table->timestamp('transfer_uploaded_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->string('confirmed_by')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });

        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->integer('rating'); // 1-5
            $table->text('review');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('bank_accounts');
        Schema::dropIfExists('bookings');
    }
};
