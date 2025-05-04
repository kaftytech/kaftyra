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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->morphs('auditlogable');
            $table->unsignedBigInteger('user_id')->constrained('users')->nullable();
            $table->string('event')->nullable();
            $table->longText('old_values')->nullable();
            $table->longText('new_values')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
