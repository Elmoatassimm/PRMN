<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sub_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->boolean('is_completed')->default(false);
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sub_tasks');
    }
}
;