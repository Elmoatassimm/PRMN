<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration

{
    public function up()
    {
        Schema::create('invited_users', function (Blueprint $table) {
            $table->id();
            $table->string("token");
            $table->date("expires_at");
            $table->string('user_email')->nullable();
            $table->morphs('invitable'); // adds invitable_id and invitable_type for polymorphic relation
            $table->foreignId('invited_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['Pending', 'Accepted', 'Rejected'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invited_users');
    }
};
