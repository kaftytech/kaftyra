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
        Schema::create('document_template_settings', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // invoice, po, pr
            $table->string('name');
            $table->text('header')->nullable();
            $table->text('footer')->nullable();
            $table->text('note')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('paper_size')->default('A4'); // A4 / POS
            $table->string('theme')->default('default'); // light / dark etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_template_settings');
    }
};
