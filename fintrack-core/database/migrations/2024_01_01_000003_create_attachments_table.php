<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organization_id')->nullable()->index();

            $table->uuidMorphs('attachable');

            $table->string('name');
            $table->string('original_name');
            $table->string('path');
            $table->string('url')->nullable();
            $table->string('mime_type');
            $table->unsignedBigInteger('size');
            $table->string('disk')->default('local');
            $table->json('metadata')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index('mime_type');
            $table->index('disk');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
