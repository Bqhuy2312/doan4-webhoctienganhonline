<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('options', function (Blueprint $table) {
            $table->integer('blank_index')->nullable()->after('is_correct');
        });
    }

    public function down()
    {
        Schema::table('options', function (Blueprint $table) {
            $table->dropColumn('blank_index');
        });
    }
};
