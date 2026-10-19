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
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        $damascus = City::where('code', 'DAM')->first();
        $rifDimashq = City::where('code', 'RDM')->first();
        $aleppo = City::where('code', 'ALE')->first();
        $homs = City::where('code', 'HMS')->first();
        $latakia = City::where('code', 'LAT')->first();
        $alqunaitra = City::where('code', 'AQN')->first();
        $daraa = City::where('code', 'DRA')->first();
        $idlib = City::where('code', 'IDL')->first();
        $tartous = City::where('code', 'TRS')->first();
        $swaida = City::where('code', 'SWD')->first();
        $qunaitra = City::where('code', 'QUN')->first();
        $hama = City::where('code', 'HMA')->first();
        $hasakah = City::where('code', 'HAS')->first();
        $raqa = City::where('code', 'RAQ')->first();
        $dierEzZor = City::where('code', 'DEZ')->first();

        Area::insert([
            // دمشق
            ['name' => 'القصاع', 'code' => 'DAM_QSS', 'latitude' => '33.5200', 'longitude' => '36.3180', 'city_id' => $damascus->id],
            ['name' => 'باب توما', 'code' => 'DAM_BTM', 'latitude' => '33.5134', 'longitude' => '36.3131', 'city_id' => $damascus->id],
            ['name' => 'مزة شرقية', 'code' => 'DAM_MZS', 'latitude' => '33.5042', 'longitude' => '36.2486', 'city_id' => $damascus->id],
            ['name' => 'مزة غربية', 'code' => 'DAM_MZG', 'latitude' => '33.5011', 'longitude' => '36.2355', 'city_id' => $damascus->id],
            ['name' => 'أبو رمانة', 'code' => 'DAM_ARM', 'latitude' => '33.5194', 'longitude' => '36.2811', 'city_id' => $damascus->id],
            ['name' => 'المالكي', 'code' => 'DAM_MAL', 'latitude' => '33.5215', 'longitude' => '36.2760', 'city_id' => $damascus->id],
            ['name' => 'كفرسوسة', 'code' => 'DAM_KFS', 'latitude' => '33.4988', 'longitude' => '36.2694', 'city_id' => $damascus->id],
            ['name' => 'الميدان', 'code' => 'DAM_MDL', 'latitude' => '33.4960', 'longitude' => '36.2990', 'city_id' => $damascus->id],
            ['name' => 'المهاجرين', 'code' => 'DAM_MHR', 'latitude' => '33.5283', 'longitude' => '36.2711', 'city_id' => $damascus->id],
            ['name' => 'ركن الدين', 'code' => 'DAM_RKD', 'latitude' => '33.5350', 'longitude' => '36.3020', 'city_id' => $damascus->id],
            ['name' => 'البرامكة', 'code' => 'DAM_BRM', 'latitude' => '33.5090', 'longitude' => '36.2880', 'city_id' => $damascus->id],
            ['name' => 'مشروع دمر', 'code' => 'DAM_MDR', 'latitude' => '33.5356', 'longitude' => '36.2255', 'city_id' => $damascus->id],
            ['name' => 'برزة', 'code' => 'DAM_BRZ', 'latitude' => '33.5510', 'longitude' => '36.3210', 'city_id' => $damascus->id],
            ['name' => 'جرمانا', 'code' => 'DAM_GRM', 'latitude' => '33.4883', 'longitude' => '36.3542', 'city_id' => $damascus->id],
        
            // ريف دمشق
            ['name' => 'صحنايا', 'code' => 'RDM_SHN', 'latitude' => '33.4180', 'longitude' => '36.2510', 'city_id' => $rifDimashq->id],
            ['name' => 'قدسيا', 'code' => 'RDM_QDS', 'latitude' => '33.5370', 'longitude' => '36.2180', 'city_id' => $rifDimashq->id],
            ['name' => 'دوما', 'code' => 'RDM_DOM', 'latitude' => '33.5714', 'longitude' => '36.4022', 'city_id' => $rifDimashq->id],
            ['name' => 'صيدنايا', 'code' => 'RDM_SDN', 'latitude' => '33.6910', 'longitude' => '36.3710', 'city_id' => $rifDimashq->id],
            ['name' => 'الزبداني', 'code' => 'RDM_ZBD', 'latitude' => '33.7258', 'longitude' => '36.0906', 'city_id' => $rifDimashq->id],
            ['name' => 'السيدة زينب', 'code' => 'RDM_SZB', 'latitude' => '33.4444', 'longitude' => '36.3400', 'city_id' => $rifDimashq->id],
        
            // حلب
            ['name' => 'الشهباء', 'code' => 'ALE_SHB', 'latitude' => '36.2215', 'longitude' => '37.1290', 'city_id' => $aleppo->id],
            ['name' => 'الموكامبو', 'code' => 'ALE_MKB', 'latitude' => '36.2230', 'longitude' => '37.1350', 'city_id' => $aleppo->id],
            ['name' => 'الفرقان', 'code' => 'ALE_FRK', 'latitude' => '36.2010', 'longitude' => '37.1180', 'city_id' => $aleppo->id],
            ['name' => 'حلب الجديدة', 'code' => 'ALE_HJ', 'latitude' => '36.2085', 'longitude' => '37.0980', 'city_id' => $aleppo->id],
            ['name' => 'العزيزية', 'code' => 'ALE_AZY', 'latitude' => '36.2090', 'longitude' => '37.1510', 'city_id' => $aleppo->id],
            ['name' => 'السليمانية', 'code' => 'ALE_SLM', 'latitude' => '36.2150', 'longitude' => '37.1550', 'city_id' => $aleppo->id],
            ['name' => 'حي السبيل', 'code' => 'ALE_HSB', 'latitude' => '36.2200', 'longitude' => '37.1450', 'city_id' => $aleppo->id],
            ['name' => 'حي النيل', 'code' => 'ALE_HN', 'latitude' => '36.2250', 'longitude' => '37.1400', 'city_id' => $aleppo->id],
        
            // حمص
            ['name' => 'الإنشاءات', 'code' => 'HMS_ENS', 'latitude' => '34.7180', 'longitude' => '36.6980', 'city_id' => $homs->id],
            ['name' => 'الوعر', 'code' => 'HMS_WR', 'latitude' => '34.7350', 'longitude' => '36.6650', 'city_id' => $homs->id],
            ['name' => 'الحمراء', 'code' => 'HMS_HR', 'latitude' => '34.7210', 'longitude' => '36.7080', 'city_id' => $homs->id],
            ['name' => 'عكرمة', 'code' => 'HMS_AKM', 'latitude' => '34.7080', 'longitude' => '36.7120', 'city_id' => $homs->id],
            ['name' => 'المحطة', 'code' => 'HMS_MH', 'latitude' => '34.7300', 'longitude' => '36.7050', 'city_id' => $homs->id],
            ['name' => 'الغوطة', 'code' => 'HMS_GT', 'latitude' => '34.7360', 'longitude' => '36.7020', 'city_id' => $homs->id],
        
            // اللاذقية
            ['name' => 'الزراعة', 'code' => 'LAT_ZR', 'latitude' => '35.5220', 'longitude' => '35.8110', 'city_id' => $latakia->id],
            ['name' => 'المشروع السابع', 'code' => 'LAT_MS', 'latitude' => '35.5450', 'longitude' => '35.7910', 'city_id' => $latakia->id],
            ['name' => 'الأمريكان', 'code' => 'LAT_AMR', 'latitude' => '35.5260', 'longitude' => '35.7860', 'city_id' => $latakia->id],
            ['name' => 'الكورنيش الغربي', 'code' => 'LAT_KGN', 'latitude' => '35.5310', 'longitude' => '35.7720', 'city_id' => $latakia->id],
            ['name' => 'مشروع الأوقاف', 'code' => 'LAT_MOQ', 'latitude' => '35.5380', 'longitude' => '35.8020', 'city_id' => $latakia->id],
            ['name' => 'الرمل الشمالي', 'code' => 'LAT_RM', 'latitude' => '35.5420', 'longitude' => '35.7820', 'city_id' => $latakia->id],
        
            // حماة
            ['name' => 'الحاضر', 'code' => 'HMA_HAD', 'latitude' => '35.1390', 'longitude' => '36.7590', 'city_id' => $hama->id],
            ['name' => 'حي الشريعة', 'code' => 'HMA_SHR', 'latitude' => '35.1310', 'longitude' => '36.7410', 'city_id' => $hama->id],
            ['name' => 'طريق حلب', 'code' => 'HMA_TB', 'latitude' => '35.1510', 'longitude' => '36.7480', 'city_id' => $hama->id],
            ['name' => 'حي الصابونية', 'code' => 'HMA_SBN', 'latitude' => '35.1220', 'longitude' => '36.7650', 'city_id' => $hama->id],
            ['name' => 'حي الأندلس', 'code' => 'HMA_ANDL', 'latitude' => '35.1220', 'longitude' => '36.7710', 'city_id' => $hama->id],
        
            // طرطوس
            ['name' => 'الغمقة شرقية', 'code' => 'TRS_GQ', 'latitude' => '34.8780', 'longitude' => '35.8920', 'city_id' => $tartous->id],
            ['name' => 'المشروع السادس', 'code' => 'TRS_MS', 'latitude' => '34.9050', 'longitude' => '35.8890', 'city_id' => $tartous->id],
            ['name' => 'شارع الميناء', 'code' => 'TRS_SM', 'latitude' => '34.8910', 'longitude' => '35.8750', 'city_id' => $tartous->id],
            ['name' => 'حي الإنشاءات', 'code' => 'TRS_ENS', 'latitude' => '34.8850', 'longitude' => '35.8950', 'city_id' => $tartous->id],
            ['name' => 'الجمارك', 'code' => 'TRS_JMC', 'latitude' => '34.8950', 'longitude' => '35.8820', 'city_id' => $tartous->id],
        
            // السويداء
            ['name' => 'مركز السويداء', 'code' => 'SWD_SZD', 'latitude' => '32.7081', 'longitude' => '36.5682', 'city_id' => $swaida->id],
            ['name' => 'حي النهضة', 'code' => 'SWD_NHZ', 'latitude' => '32.7120', 'longitude' => '36.5710', 'city_id' => $swaida->id],
            ['name' => 'حي القلعة', 'code' => 'SWD_QLZ', 'latitude' => '32.7050', 'longitude' => '36.5650', 'city_id' => $swaida->id],
        
            // درعا
            ['name' => 'درعا المحطة', 'code' => 'DRA_DZM', 'latitude' => '32.6250', 'longitude' => '36.1080', 'city_id' => $daraa->id],
            ['name' => 'درعا البلد', 'code' => 'DRA_DBL', 'latitude' => '32.6100', 'longitude' => '36.1010', 'city_id' => $daraa->id],
            ['name' => 'حي السبيل', 'code' => 'DRA_SBL', 'latitude' => '32.6280', 'longitude' => '36.1150', 'city_id' => $daraa->id],
        
            // إدلب
            ['name' => 'إدلب المدينة', 'code' => 'IDL_IDB', 'latitude' => '35.9306', 'longitude' => '36.6339', 'city_id' => $idlib->id],
            ['name' => 'حي الثورة', 'code' => 'IDL_TR', 'latitude' => '35.9350', 'longitude' => '36.6410', 'city_id' => $idlib->id],
            ['name' => 'حي الضبيط', 'code' => 'IDL_ZB', 'latitude' => '35.9320', 'longitude' => '36.6380', 'city_id' => $idlib->id],
        
            // الحسكة والقامشلي
            ['name' => 'الحسكة المركز', 'code' => 'HAS_HSM', 'latitude' => '36.5024', 'longitude' => '40.7475', 'city_id' => $hasakah->id],
            ['name' => 'القامشلي (الوسطى)', 'code' => 'HAS_QMS', 'latitude' => '37.0506', 'longitude' => '41.2222', 'city_id' => $hasakah->id],
            ['name' => 'حي غويران', 'code' => 'HAS_GYN', 'latitude' => '36.4850', 'longitude' => '40.7550', 'city_id' => $hasakah->id],
            ['name' => 'حي السياحي', 'code' => 'HAS_SY', 'latitude' => '37.0450', 'longitude' => '41.2150', 'city_id' => $hasakah->id],
        
            // دير الزور
            ['name' => 'حي الجورة', 'code' => 'DEZ_JR', 'latitude' => '35.3440', 'longitude' => '40.1180', 'city_id' => $dierEzZor->id],
            ['name' => 'حي القصور', 'code' => 'DEZ_QS', 'latitude' => '35.3480', 'longitude' => '40.1250', 'city_id' => $dierEzZor->id],
            ['name' => 'حي الحميدية', 'code' => 'DEZ_HM', 'latitude' => '35.3350', 'longitude' => '40.1450', 'city_id' => $dierEzZor->id],
        
            // الرقة
            ['name' => 'الرقة المدينة', 'code' => 'RAQ_RQB', 'latitude' => '35.9519', 'longitude' => '39.0114', 'city_id' => $raqa->id],
            ['name' => 'حي الثكنة', 'code' => 'RAQ_TK', 'latitude' => '35.9480', 'longitude' => '39.0080', 'city_id' => $raqa->id],
            ['name' => 'حي المشلب', 'code' => 'RAQ_MLB', 'latitude' => '35.9420', 'longitude' => '39.0450', 'city_id' => $raqa->id],
        
            // القنيطرة
            ['name' => 'مدينة البعث', 'code' => 'QUN_MDB', 'latitude' => '33.1250', 'longitude' => '35.8550', 'city_id' => $qunaitra->id],
            ['name' => 'خان أرنبة', 'code' => 'QUN_KAR', 'latitude' => '33.1610', 'longitude' => '35.8650', 'city_id' => $qunaitra->id],
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
