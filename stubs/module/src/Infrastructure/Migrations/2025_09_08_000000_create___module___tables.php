<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void { Schema::create('{{module}}_samples', function (Blueprint $t){ $t->id(); $t->string('name'); $t->timestamps(); }); } public function down(): void { Schema::dropIfExists('{{module}}_samples'); } };
