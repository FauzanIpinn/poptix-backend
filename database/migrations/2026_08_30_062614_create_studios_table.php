<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void 
    {
        Schema::table('seats', function (Blueprint $table) {
            $table->foreignId('studio_id')->after('cinema_id')->constrained()->cascadeOnDelete();
            $table->dropUnique(['cinema_id', 'row', 'number']);
            $table->unique(['studio_id', 'row', 'number']);
        });
    }
 
    public function down(): void {
        Schema::table('seats', function (Blueprint $table) {
            $table->dropUnique(['studio_id', 'row', 'number']);
            $table->unique(['cinema_id', 'row', 'number']);
            $table->dropConstrainedForeignId('studio_id');
        });
    }
};
