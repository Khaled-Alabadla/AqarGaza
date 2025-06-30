<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Zone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Get existing cities by their Arabic names
        $gaza = City::where('name', 'غزة')->first();
        $khanYunis = City::where('name', 'خانيونس')->first();
        $rafah = City::where('name', 'رفح')->first();
        $central = City::where('name', 'الوسطى')->first(); // Central Governorate
        $north = City::where('name', 'الشمال')->first(); // North Governorate

        // Zones for Gaza (Gaza City and surrounding areas)
        if ($gaza) {
            Zone::firstOrCreate(['city_id' => $gaza->id, 'name' => 'الرمال']);
            Zone::firstOrCreate(['city_id' => $gaza->id, 'name' => 'تل الهوا']);
            Zone::firstOrCreate(['city_id' => $gaza->id, 'name' => 'الشيخ رضوان']);
            Zone::firstOrCreate(['city_id' => $gaza->id, 'name' => 'الزيتون']);
            Zone::firstOrCreate(['city_id' => $gaza->id, 'name' => 'الصبرة']);
            Zone::firstOrCreate(['city_id' => $gaza->id, 'name' => 'الشجاعية']);
            Zone::firstOrCreate(['city_id' => $gaza->id, 'name' => 'التفاح']);
            Zone::firstOrCreate(['city_id' => $gaza->id, 'name' => 'الدرج']);
            Zone::firstOrCreate(['city_id' => $gaza->id, 'name' => 'البلدة القديمة']);
            // You might add more specific neighborhoods within Gaza City if needed
        }

        // Zones for Khan Yunis (Khan Yunis City and surrounding areas)
        if ($khanYunis) {
            Zone::firstOrCreate(['city_id' => $khanYunis->id, 'name' => 'مدينة حمد']);
            Zone::firstOrCreate(['city_id' => $khanYunis->id, 'name' => 'بني سهيلا']);
            Zone::firstOrCreate(['city_id' => $khanYunis->id, 'name' => 'عبسان الكبيرة']);
            Zone::firstOrCreate(['city_id' => $khanYunis->id, 'name' => 'عبسان الصغيرة']);
            Zone::firstOrCreate(['city_id' => $khanYunis->id, 'name' => 'المواصي']);
            Zone::firstOrCreate(['city_id' => $khanYunis->id, 'name' => 'خزاعة']);
            Zone::firstOrCreate(['city_id' => $khanYunis->id, 'name' => 'عبسان الصغيرة']);
            Zone::firstOrCreate(['city_id' => $khanYunis->id, 'name' => 'القرارة']);
            Zone::firstOrCreate(['city_id' => $khanYunis->id, 'name' => 'البلد']);
            Zone::firstOrCreate(['city_id' => $khanYunis->id, 'name' => 'المعسكر']);
            Zone::firstOrCreate(['city_id' => $khanYunis->id, 'name' => 'حي الأمل']);
            Zone::firstOrCreate(['city_id' => $khanYunis->id, 'name' => 'السطر الشرقي']);
            Zone::firstOrCreate(['city_id' => $khanYunis->id, 'name' => 'السطر الغربي']);
            Zone::firstOrCreate(['city_id' => $khanYunis->id, 'name' => 'خزاعة']);
            Zone::firstOrCreate(['city_id' => $khanYunis->id, 'name' => 'الشيخ ناصر']);
            Zone::firstOrCreate(['city_id' => $khanYunis->id, 'name' => 'قيزان النجار']);
            Zone::firstOrCreate(['city_id' => $khanYunis->id, 'name' => 'معن']);
            Zone::firstOrCreate(['city_id' => $khanYunis->id, 'name' => 'قيزان أبو رشوان']);
            Zone::firstOrCreate(['city_id' => $khanYunis->id, 'name' => 'الفخاري']);
            Zone::firstOrCreate(['city_id' => $khanYunis->id, 'name' => 'جورت اللوت']);
            Zone::firstOrCreate(['city_id' => $khanYunis->id, 'name' => 'المنارة']);
            Zone::firstOrCreate(['city_id' => $khanYunis->id, 'name' => 'القرين']);
            Zone::firstOrCreate(['city_id' => $khanYunis->id, 'name' => 'البطن السمين']);
        }

        if ($rafah) {
            Zone::firstOrCreate(['city_id' => $rafah->id, 'name' => 'الشوكة']);
            Zone::firstOrCreate(['city_id' => $rafah->id, 'name' => 'الحي السعودي']);
            Zone::firstOrCreate(['cit y_id' => $rafah->id, 'name' => 'مواصي رفح']);
            Zone::firstOrCreate(['city_id' => $rafah->id, 'name' => 'الجنينة']);
            Zone::firstOrCreate(['city_id' => $rafah->id, 'name' => 'الشابورة']);
            Zone::firstOrCreate(['city_id' => $rafah->id, 'name' => 'السلام']);
            Zone::firstOrCreate(['city_id' => $rafah->id, 'name' => 'حي البيوك']);
            Zone::firstOrCreate(['city_id' => $rafah->id, 'name' => 'تبة زراع']);
            Zone::firstOrCreate(['city_id' => $rafah->id, 'name' => 'مخيم يبنا']);
            Zone::firstOrCreate(['city_id' => $rafah->id, 'name' => 'تل السلطان']);
            Zone::firstOrCreate(['city_id' => $rafah->id, 'name' => 'خربة العدس']);
            Zone::firstOrCreate(['city_id' => $rafah->id, 'name' => 'مصبح']);
            Zone::firstOrCreate(['city_id' => $rafah->id, 'name' => 'الحشاشين']);
            Zone::firstOrCreate(['city_id' => $rafah->id, 'name' => 'رفح الغربية']);
            Zone::firstOrCreate(['city_id' => $rafah->id, 'name' => 'الزهور']);
        }

        if ($central) {
            Zone::firstOrCreate(['city_id' => $central->id, 'name' => 'دير البلح']);
            Zone::firstOrCreate(['city_id' => $central->id, 'name' => 'المصدر']);
            Zone::firstOrCreate(['city_id' => $central->id, 'name' => 'الزوايدة']);
            Zone::firstOrCreate(['city_id' => $central->id, 'name' => 'المغازي']);
            Zone::firstOrCreate(['city_id' => $central->id, 'name' => 'النصيرات']);
            Zone::firstOrCreate(['city_id' => $central->id, 'name' => 'البريج']); //
        }

        // Zones for North Governorate (e.g., Jabalia, Beit Lahia, Beit Hanoun, Al-Qalayah)
        if ($north) {
            Zone::firstOrCreate(['city_id' => $north->id, 'name' => 'جباليا']); // Jabalia Town
            Zone::firstOrCreate(['city_id' => $north->id, 'name' => 'بيت لاهيا']); // Beit Lahia Town
            Zone::firstOrCreate(['city_id' => $north->id, 'name' => 'بيت حانون']); //
        }
    }
}
