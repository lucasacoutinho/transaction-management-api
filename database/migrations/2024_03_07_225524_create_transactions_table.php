<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('accounts');
            $table->foreignId('receiver_id')->constrained('accounts');
            $table->unsignedBigInteger('amount');
            $table->enum('status', ['COMPLETED', 'REJECTED', 'SCHEDULED'])->index();
            $table->date('process_at')->index()->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
