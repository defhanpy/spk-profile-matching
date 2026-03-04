<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('criteria','ideal')) {
            Schema::table('criteria', function (Blueprint $table) {
                $table->float('ideal')->default(5.0)->after('weight');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('criteria','ideal')) {
            Schema::table('criteria', function (Blueprint $table) {
                $table->dropColumn('ideal');
            });
        }
    }
};
