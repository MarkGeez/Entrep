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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['customer', 'vendor', 'admin'])->default('customer')->after('password');
            $table->string('contact_number')->nullable()->after('role');
            $table->enum('status', ['active', 'suspended'])->default('active')->after('contact_number');
        });

        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('business_name');
            $table->text('description')->nullable();
            $table->text('address');
            $table->string('city');
            $table->string('business_permit')->nullable();
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->restrictOnDelete();
            $table->string('brand');
            $table->string('model');
            $table->unsignedSmallInteger('year');
            $table->string('plate_number')->unique();
            $table->string('vehicle_type');
            $table->string('transmission');
            $table->string('fuel_type');
            $table->unsignedTinyInteger('seats');
            $table->decimal('daily_price', 12, 2);
            $table->decimal('security_deposit', 12, 2);
            $table->text('description')->nullable();
            $table->enum('status', ['available', 'unavailable'])->default('available');
            $table->timestamps();
        });

        Schema::create('vehicle_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');
            $table->timestamps();
        });

        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('vehicle_id')->constrained()->restrictOnDelete();
            $table->date('pickup_date');
            $table->date('return_date');
            $table->unsignedInteger('rental_days');
            $table->decimal('daily_price', 12, 2);
            $table->decimal('total_amount', 12, 2);
            $table->decimal('commission_amount', 12, 2);
            $table->decimal('vendor_amount', 12, 2);
            $table->enum('status', ['pending', 'approved', 'ongoing', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->enum('payment_method', ['gcash', 'card', 'cash']);
            $table->string('transaction_reference')->unique();
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('rentals');
        Schema::dropIfExists('vehicle_images');
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('vendors');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'contact_number', 'status']);
        });
    }
};
