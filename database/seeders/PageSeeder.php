<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Page::firstOrCreate([
            'name' => 'home',
            'title' => 'ابحث عن عقارك المثالي',
            'subtitle' => 'يمكنك استعراض العقارات وفلترتها حسب ما ترغب'
        ]);
        Page::firstOrCreate([
            'name' => 'contact',
            'title' => 'تواصل معنا',
            'subtitle' => 'يمكنك الاستفسار والتواصل معنا من خلال تعبئة النموذج بالأسفل'
        ]);
        Page::firstOrCreate([
            'name' => 'profile',
            'title' => 'تعديل الملف الشخصي',
            'subtitle' => 'قم بتعديل بياناتك حتى يتمكن الآخرون من رؤيتك والتواصل معك'
        ]);
        Page::firstOrCreate([
            'name' => 'properties.index',
            'title' => 'جميع العقارات',
            'subtitle' => 'قم بتصفح جميع العقارات لاختيار العقار الأنسب',
        ]);
        Page::firstOrCreate([
            'name' => 'properties.create',
            'title' => 'إضافة عقار',
            'subtitle' => 'قم بإضافة عقار جديد مع كافة التفاصيل',
        ]);
        Page::firstOrCreate([
            'name' => 'favorites',
            'title' => 'العقارات المفضلة',
            'subtitle' => 'جميع العقارات التي قمت بإضافتها إلى المفضلة',
        ]); // North Governorate
    }
}
