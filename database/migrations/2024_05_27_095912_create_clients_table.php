<?php

use App\Models\{City, Client};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('gender');
            $table->string('rg', 20)->unique()->nullable();
            $table->string('taxpayer_id')->unique();
            $table->string('taxpayer_type');
            $table->string('marital_status')->nullable();
            $table->string('phone_one', 20)->nullable();
            $table->string('phone_two', 20)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('father')->nullable();
            $table->string('father_phone', 20)->nullable();
            $table->string('mother')->nullable();
            $table->string('mother_phone', 20)->nullable();
            $table->string('affiliated_one')->nullable();
            $table->string('affiliated_one_phone')->nullable();
            $table->string('affiliated_two')->nullable();
            $table->string('affiliated_two_phone')->nullable();
            $table->string('description')->nullable();

            $table->timestamps();
        });

        Schema::create('client_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Client::class)->constrained()->cascadeOnDelete();
            $table->string('zip_code');
            $table->string('street');
            $table->string('number');
            $table->string('neighborhood');
            $table->foreignIdFor(City::class)->constrained()->cascadeOnDelete();
            $table->string('state');
            $table->string('complement')->nullable();
            $table->timestamps();
        });

        Schema::create('client_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Client::class)->constrained()->cascadeOnDelete();
            $table->string('path', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_photos');
        Schema::dropIfExists('client_addresses');
        Schema::dropIfExists('clients');
    }
};
