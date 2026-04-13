<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\City;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->timestamps();
        });

        City::insert([
            [
                'name' => 'دمشق',
                'code' => 'DAM',
                'latitude' => '33.5102',
                'longitude' => '36.2913',
            ],
            [
                'name' => 'ريف دمشق',
                'code' => 'RDM',
                'latitude' => '33.5717',
                'longitude' => '36.4017',
            ],
            [
                'name' => 'درعا',
                'code' => 'DRA',
                'latitude' => '32.6189',
                'longitude' => '36.1021',
            ],
            [
                'name' => 'السويداء',
                'code' => 'SWD',
                'latitude' => '32.7089',
                'longitude' => '36.5695',
            ],
            [
                'name' => 'القنيطرة',
                'code' => 'QUN',
                'latitude' => '33.1258',
                'longitude' => '35.8245',
            ],
            [
                'name' => 'حمص',
                'code' => 'HMS',
                'latitude' => '34.7324',
                'longitude' => '36.7137',
            ],
            [
                'name' => 'حماة',
                'code' => 'HMA',
                'latitude' => '35.1318',
                'longitude' => '36.7578',
            ],
            [
                'name' => 'إدلب',
                'code' => 'IDL',
                'latitude' => '35.9312',
                'longitude' => '36.6313',
            ],
            [
                'name' => 'اللاذقية',
                'code' => 'LAT',
                'latitude' => '35.5177',
                'longitude' => '35.7762',
            ],
            [
                'name' => 'طرطوس',
                'code' => 'TRS',
                'latitude' => '34.8867',
                'longitude' => '35.8866',
            ],
            [
                'name' => 'حلب',
                'code' => 'ALE',
                'latitude' => '36.2021',
                'longitude' => '37.1343',
            ],
            [
                'name' => 'الرقة',
                'code' => 'RAQ',
                'latitude' => '35.9596',
                'longitude' => '39.0094',
            ],
            [
                'name' => 'دير الزور',
                'code' => 'DEZ',
                'latitude' => '35.3359',
                'longitude' => '40.1408',
            ],
            [
                'name' => 'الحسكة',
                'code' => 'HAS',
                'latitude' => '36.5024',
                'longitude' => '40.7477',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
