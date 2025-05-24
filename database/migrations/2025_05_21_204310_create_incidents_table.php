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
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('file');
            $table->date('date');
            $table->tinyInteger('priority')->comment('0 = High, 1 = Medium, 2 = Low');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by') ->nullable()->constrained('users')->onDelete('set null');
            $table->tinyInteger('status')->default(0)->comment('0 = Open, 1 = In Progress, 2 = Resolved');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
