<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
  public function up(): void {
    Schema::create('outbox_messages', function (Blueprint $t) {
      $t->uuid('id')->primary();
      $t->string('aggregate_type');
      $t->string('aggregate_id');
      $t->string('event_type');
      $t->json('payload');
      $t->timestamp('occurred_at');
      $t->timestamp('published_at')->nullable();
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('outbox_messages'); }
};
