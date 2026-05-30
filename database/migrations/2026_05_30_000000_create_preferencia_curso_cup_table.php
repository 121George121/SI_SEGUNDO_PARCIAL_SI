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
        Schema::create('preferencia_curso_cup', function (Blueprint $table) {
            $table->increments('id_preferencia');
            $table->string('modalidad');
            $table->string('turno');
            $table->string('periodo_academico');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->string('estado')->default('Activo');
            $table->text('descripcion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preferencia_curso_cup');
    }
};
