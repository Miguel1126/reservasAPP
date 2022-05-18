<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo',15)->nullable();
            $table->string('nombre',100);
            $table->text('descripcion');
            $table->string('estado',1)->default('D');
            $table->foreignId('categoria_id')
            ->nullable()
            ->constrained('categorias')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->foreignId('marca_id')
            ->nullable()
            ->constrained('marcas')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipos');
    }
};
