<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('description')->nullable();
            $table->json('status')->default(json_encode([
                'en' => 'Not Started',  // English
                'fr' => 'Non Commencé', // French
                'ar' => 'لم يبدأ بعد'   // Arabic
            ]));
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
};
