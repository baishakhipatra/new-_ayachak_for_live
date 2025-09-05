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
        Schema::create('c_s_r_project_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('csr_project_id')
            ->constrained('c_s_r_projects')
            ->onDelete('cascade');

            $table->foreignId('tag_id')
            ->constrained('tags')
            ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('c_s_r_project_tag');
    }
};
