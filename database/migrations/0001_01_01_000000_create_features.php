<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {

        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('shortcode')->unique();
            $table->string('feature_class')->unique();
            $table->string('description')->nullable();
            $table->string('reset_period')->nullable();
            $table->string('reset_interval')->nullable();
            $table->string('limit')->nullable();
            $table->string('unit')->nullable();
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('feature_items', function (Blueprint $table) {
            $table->id();
            $table->string('feature_id');
            $table->uuidMorphs('featureable');
            $table->string('reset_period')->nullable();
            $table->string('reset_interval')->nullable();
            $table->string('limit')->nullable();
            $table->timestamps();
        });

        Schema::create('feature_usages', function (Blueprint $table) {
            $table->id();
            $table->string('feature_id');
            $table->nullableUuidMorphs('user');
            $table->nullableUuidMorphs('owner');
            $table->json('meta')->nullable();
            $table->string('value')->nullable();
            $table->integer('count')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('features');
        Schema::dropIfExists('plan_features');
        Schema::dropIfExists('feature_usages');
    }
};
