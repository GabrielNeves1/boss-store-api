<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table): void {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('model');
            $table->string('color');
            $table->unsignedInteger('storage_gb')->nullable();
            $table->unsignedTinyInteger('battery_health')->nullable();
            $table->string('condition');
            $table->unsignedSmallInteger('warranty_months')->nullable();
            $table->json('accessories');
            $table->unsignedInteger('price_pix_cents');
            $table->unsignedInteger('price_card_cents');
            $table->unsignedTinyInteger('max_installments');
            $table->string('status');
            $table->boolean('featured')->default(false);
            $table->timestamps();

            $table->index(['category_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
