<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\{Area, City};

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->foreignId('city_id')->constrained('cities');
            $table->timestamps();
        });

        $damascus = City::where('code', 'DAM')->first();
        $rifDimashq = City::where('code', 'RDM')->first();
        $aleppo = City::where('code', 'ALE')->first();
        $homs = City::where('code', 'HMS')->first();
        $latakia = City::where('code', 'LAK')->first();
        $alqunaitra = City::where('code', 'AQN')->first();
        $daraa = City::where('code', 'DRA')->first();
        $idlib = City::where('code', 'IDL')->first();
        $tartous = City::where('code', 'TRS')->first();
        $swaida = City::where('code', 'SWD')->first();
        $qunaitra = City::where('code', 'QUN')->first();
        $hama = City::where('code', 'HMA')->first();
        $deirEzzor = City::where('code', 'DEZ')->first();
        $hasakah = City::where('code', 'HAS')->first();
        $raqa = City::where('code', 'RAQ')->first();
        $dierEzZor = City::where('code', 'DEZ')->first();

        Area::insert([
            // دمشق
            ['name' => 'القصاع', 'latitude' => '33.5200', 'longitude' => '36.3180', 'city_id' => $damascus->id],
            ['name' => 'باب توما', 'latitude' => '33.5134', 'longitude' => '36.3131', 'city_id' => $damascus->id],
            ['name' => 'مزة شرقية', 'latitude' => '33.5042', 'longitude' => '36.2486', 'city_id' => $damascus->id],
            ['name' => 'مزة غربية', 'latitude' => '33.5011', 'longitude' => '36.2355', 'city_id' => $damascus->id],
            ['name' => 'أبو رمانة', 'latitude' => '33.5194', 'longitude' => '36.2811', 'city_id' => $damascus->id],
            ['name' => 'المالكي', 'latitude' => '33.5215', 'longitude' => '36.2760', 'city_id' => $damascus->id],
            ['name' => 'كفرسوسة', 'latitude' => '33.4988', 'longitude' => '36.2694', 'city_id' => $damascus->id],
            ['name' => 'الميدان', 'latitude' => '33.4960', 'longitude' => '36.2990', 'city_id' => $damascus->id],
            ['name' => 'المهاجرين', 'latitude' => '33.5283', 'longitude' => '36.2711', 'city_id' => $damascus->id],
            ['name' => 'ركن الدين', 'latitude' => '33.5350', 'longitude' => '36.3020', 'city_id' => $damascus->id],
            ['name' => 'البرامكة', 'latitude' => '33.5090', 'longitude' => '36.2880', 'city_id' => $damascus->id],
            ['name' => 'مشروع دمر', 'latitude' => '33.5356', 'longitude' => '36.2255', 'city_id' => $damascus->id],
            ['name' => 'برزة', 'latitude' => '33.5510', 'longitude' => '36.3210', 'city_id' => $damascus->id],
            ['name' => 'جرمانا', 'latitude' => '33.4883', 'longitude' => '36.3542', 'city_id' => $damascus->id],
        
            // ريف دمشق
            ['name' => 'صحنايا', 'latitude' => '33.4180', 'longitude' => '36.2510', 'city_id' => $rifDimashq->id],
            ['name' => 'قدسيا', 'latitude' => '33.5370', 'longitude' => '36.2180', 'city_id' => $rifDimashq->id],
            ['name' => 'دوما', 'latitude' => '33.5714', 'longitude' => '36.4022', 'city_id' => $rifDimashq->id],
            ['name' => 'صيدنايا', 'latitude' => '33.6910', 'longitude' => '36.3710', 'city_id' => $rifDimashq->id],
            ['name' => 'الزبداني', 'latitude' => '33.7258', 'longitude' => '36.0906', 'city_id' => $rifDimashq->id],
            ['name' => 'السيدة زينب', 'latitude' => '33.4444', 'longitude' => '36.3400', 'city_id' => $rifDimashq->id],
        
            // حلب
            ['name' => 'الشهباء', 'latitude' => '36.2215', 'longitude' => '37.1290', 'city_id' => $aleppo->id],
            ['name' => 'الموكامبو', 'latitude' => '36.2230', 'longitude' => '37.1350', 'city_id' => $aleppo->id],
            ['name' => 'الفرقان', 'latitude' => '36.2010', 'longitude' => '37.1180', 'city_id' => $aleppo->id],
            ['name' => 'حلب الجديدة', 'latitude' => '36.2085', 'longitude' => '37.0980', 'city_id' => $aleppo->id],
            ['name' => 'العزيزية', 'latitude' => '36.2090', 'longitude' => '37.1510', 'city_id' => $aleppo->id],
            ['name' => 'السليمانية', 'latitude' => '36.2150', 'longitude' => '37.1550', 'city_id' => $aleppo->id],
            ['name' => 'حي السبيل', 'latitude' => '36.2200', 'longitude' => '37.1450', 'city_id' => $aleppo->id],
            ['name' => 'حي النيل', 'latitude' => '36.2250', 'longitude' => '37.1400', 'city_id' => $aleppo->id],
        
            // حمص
            ['name' => 'الإنشاءات', 'latitud' => '34.7180', 'longitude' => '36.6980', 'city_id' => $homs->id],
            ['name' => 'الوعر', 'latitud' => '34.7350', 'longitude' => '36.6650', 'city_id' => $homs->id],
            ['name' => 'الحمراء', 'latitud' => '34.7210', 'longitude' => '36.7080', 'city_id' => $homs->id],
            ['name' => 'عكرمة', 'latitude' => '34.7080', 'longitude' => '36.7120', 'city_id' => $homs->id],
            ['name' => 'المحطة', 'latitude' => '34.7300', 'longitude' => '36.7050', 'city_id' => $homs->id],
            ['name' => 'الغوطة', 'latitude' => '34.7360', 'longitude' => '36.7020', 'city_id' => $homs->id],
        
            // اللاذقية
            ['name' => 'الزراعة', 'latitude' => '35.5220', 'longitude' => '35.8110', 'city_id' => $latakia->id],
            ['name' => 'المشروع السابع', 'latitude' => '35.5450', 'longitude' => '35.7910', 'city_id' => $latakia->id],
            ['name' => 'الأمريكان', 'latitude' => '35.5260', 'longitude' => '35.7860', 'city_id' => $latakia->id],
            ['name' => 'الكورنيش الغربي', 'latitude' => '35.5310', 'longitude' => '35.7720', 'city_id' => $latakia->id],
            ['name' => 'مشروع الأوقاف', 'latitude' => '35.5380', 'longitude' => '35.8020', 'city_id' => $latakia->id],
            ['name' => 'الرمل الشمالي', 'latitude' => '35.5420', 'longitude' => '35.7820', 'city_id' => $latakia->id],
        
            // حماة
            ['name' => 'الحاضر', 'latitude' => '35.1390', 'longitude' => '36.7590', 'city_id' => $hama->id],
            ['name' => 'حي الشريعة', 'latitude' => '35.1310', 'longitude' => '36.7410', 'city_id' => $hama->id],
            ['name' => 'طريق حلب', 'latitude' => '35.1510', 'longitude' => '36.7480', 'city_id' => $hama->id],
            ['name' => 'حي الصابونية', 'latitude' => '35.1220', 'longitude' => '36.7650', 'city_id' => $hama->id],
            ['name' => 'حي الأندلس', 'latitude' => '35.1220', 'longitude' => '36.7710', 'city_id' => $hama->id],
        
            // طرطوس
            ['name' => 'الغمقة شرقية', 'latitude' => '34.8780', 'longitude' => '35.8920', 'city_id' => $tartous->id],
            ['name' => 'المشروع السادس', 'latitude' => '34.9050', 'longitude' => '35.8890', 'city_id' => $tartous->id],
            ['name' => 'شارع الميناء', 'latitude' => '34.8910', 'longitude' => '35.8750', 'city_id' => $tartous->id],
            ['name' => 'حي الإنشاءات', 'latitude' => '34.8850', 'longitude' => '35.8950', 'city_id' => $tartous->id],
            ['name' => 'الجمارك', 'latitude' => '34.8950', 'longitude' => '35.8820', 'city_id' => $tartous->id],
        
            // السويداء
            ['name' => 'مركز السويداء', 'latitude' => '32.7081', 'longitude' => '36.5682', 'city_id' => $swaida->id],
            ['name' => 'حي النهضة', 'latitude' => '32.7120', 'longitude' => '36.5710', 'city_id' => $swaida->id],
            ['name' => 'حي القلعة', 'latitude' => '32.7050', 'longitude' => '36.5650', 'city_id' => $swaida->id],
        
            // درعا
            ['name' => 'درعا المحطة', 'latitude' => '32.6250', 'longitude' => '36.1080', 'city_id' => $daraa->id],
            ['name' => 'درعا البلد', 'latitude' => '32.6100', 'longitude' => '36.1010', 'city_id' => $daraa->id],
            ['name' => 'حي السبيل', 'latitude' => '32.6280', 'longitude' => '36.1150', 'city_id' => $daraa->id],
        
            // إدلب
            ['name' => 'إدلب المدينة', 'latitude' => '35.9306', 'longitude' => '36.6339', 'city_id' => $idlib->id],
            ['name' => 'حي الثورة', 'latitude' => '35.9350', 'longitude' => '36.6410', 'city_id' => $idlib->id],
            ['name' => 'حي الضبيط', 'latitude' => '35.9320', 'longitude' => '36.6380', 'city_id' => $idlib->id],
        
            // الحسكة والقامشلي
            ['name' => 'الحسكة المركز', 'latitude' => '36.5024', 'longitude' => '40.7475', 'city_id' => $hasakah->id],
            ['name' => 'القامشلي (الوسطى)', 'latitude' => '37.0506', 'longitude' => '41.2222', 'city_id' => $hasakah->id],
            ['name' => 'حي غويران', 'latitude' => '36.4850', 'longitude' => '40.7550', 'city_id' => $hasakah->id],
            ['name' => 'حي السياحي', 'latitude' => '37.0450', 'longitude' => '41.2150', 'city_id' => $hasakah->id],
        
            // دير الزور
            ['name' => 'حي الجورة', 'latitude' => '35.3440', 'longitude' => '40.1180', 'city_id' => $dierEzZor->id],
            ['name' => 'حي القصور', 'latitude' => '35.3480', 'longitude' => '40.1250', 'city_id' => $dierEzZor->id],
            ['name' => 'حي الحميدية', 'latitude' => '35.3350', 'longitude' => '40.1450', 'city_id' => $dierEzZor->id],
        
            // الرقة
            ['name' => 'الرقة المدينة', 'latitude' => '35.9519', 'longitude' => '39.0114', 'city_id' => $raqa->id],
            ['name' => 'حي الثكنة', 'latitude' => '35.9480', 'longitude' => '39.0080', 'city_id' => $raqa->id],
            ['name' => 'حي المشلب', 'latitude' => '35.9420', 'longitude' => '39.0450', 'city_id' => $raqa->id],
        
            // القنيطرة
            ['name' => 'مدينة البعث', 'latitude' => '33.1250', 'longitude' => '35.8550', 'city_id' => $qunaitra->id],
            ['name' => 'خان أرنبة', 'latitude' => '33.1610', 'longitude' => '35.8650', 'city_id' => $qunaitra->id],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};
