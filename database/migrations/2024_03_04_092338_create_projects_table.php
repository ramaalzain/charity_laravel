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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_type_id')->constrained('project_types','id')->default(null);
            // link department
            $table->foreignId('department_id')->constrained('departments','id')->default(null);
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('prograss')->default(0);
            $table->string('image')->default(null);
            $table->string('description')->default(null);
            $table->integer('fundrise')->unsigned()->default(0);
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
        Schema::dropIfExists('projects');
    }
};