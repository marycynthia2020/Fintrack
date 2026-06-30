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
        Schema::create('ledger', function (Blueprint $table) {
            $table->id()->primary();
            $table->uuid('organization_id')->index();
            $table->decimal('amount', 15, 2);
            $table->uuidMorphs('ledgerable');
            $table->string('type');
            $table->text('description')->nullable();
            $table->string('event_type');
            $table->uuid('created_by')->index();
            $table->timestamp('processed_at')->nullable();
            $table->json('metadata')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledger');
    }
};
