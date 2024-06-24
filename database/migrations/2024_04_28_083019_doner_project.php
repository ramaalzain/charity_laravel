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
        Schema::create('doner_project', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('project_id')->constrained('projects','id');
            $table->foreignId('doner_id')->constrained('doners','id');
            // $table->primary(['project_id','doner_id']);
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
        Schema::dropIfExists('doner_project');
    }
};