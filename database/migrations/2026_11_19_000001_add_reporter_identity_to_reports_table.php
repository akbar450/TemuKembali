<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('reporter_role', 20)->nullable()->after('reporter_contact');
            $table->string('reporter_id_number', 50)->nullable()->after('reporter_role');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['reporter_role', 'reporter_id_number']);
        });
    }
};
