<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_media', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->unsignedSmallInteger('position')->default(0);
            $table->string('alt');
            $table->unsignedInteger('width');
            $table->unsignedInteger('height');
            $table->json('variants');
            $table->json('sources')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_media');
    }
};
