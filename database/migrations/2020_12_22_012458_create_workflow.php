<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkflow extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflow', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->enum('status', ['inserted', 'consumed'])->default('inserted');
            $table->jsonb('data');
            $table->json('steps');
        });
        DB::statement('CREATE EXTENSION IF NOT EXISTS "uuid-ossp";');
        DB::statement("ALTER TABLE workflow ALTER COLUMN uuid SET DEFAULT uuid_generate_v4();");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workflow');
    }
}
