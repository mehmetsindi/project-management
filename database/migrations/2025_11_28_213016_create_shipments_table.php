<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->string('carrier'); // e.g., 'DHL', 'UPS', 'FedEx'
            $table->string('tracking_number')->unique();
            $table->string('origin');
            $table->string('destination');
            $table->date('estimated_delivery');
            $table->date('actual_delivery')->nullable();
            $table->string('status')->default('pending'); // pending, picked_up, in_transit, out_for_delivery, delivered, exception
            $table->decimal('shipping_cost', 10, 2)->nullable();
            $table->decimal('weight', 8, 2)->default(1.0); // in kg
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
