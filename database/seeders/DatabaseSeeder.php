<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeds the database with the original data from benevole_db.sql,
 * translated to the new schema structure.
 *
 * No user accounts are seeded: create the first admin with
 * `php artisan society:create-admin` before running this seeder, since
 * categories need an owner.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategoriesSeeder::class,
            SettingsSeeder::class,
        ]);
    }
}


// ─── You can split these into separate files; kept together here for clarity ──

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $ownerId = DB::table('users')->where('role', 'admin')->orderBy('id')->value('id');

        if ($ownerId === null) {
            $this->command?->warn('No admin account yet: categories skipped. Run `php artisan society:create-admin`, then `php artisan db:seed` again.');

            return;
        }

        // Original data had PHP-serialized multilingual names.
        // Deserialized and split into name_en / name_ar columns.
        $categories = [
            [
                'id'         => 1,
                'name_en'    => 'Health Projects',
                'name_ar'    => 'مشاريع صحية',
                'slug'       => 'health-projects',
                'user_id'    => $ownerId,
                'created_at' => '2019-08-06 04:33:19',
                'updated_at' => '2019-08-06 04:33:19',
            ],
            [
                'id'         => 2,
                'name_en'    => 'Projects for Elders',
                'name_ar'    => 'مشاريع لكبار السن',
                'slug'       => 'projects-for-elders',
                'user_id'    => $ownerId,
                'created_at' => '2019-08-06 04:35:18',
                'updated_at' => '2019-08-07 08:41:34',
            ],
            [
                'id'         => 3,
                'name_en'    => 'Projects for People with Disabilities',
                'name_ar'    => 'مشاريع لذوي الإعاقات',
                'slug'       => 'projects-for-people-with-disabilities',
                'user_id'    => $ownerId,
                'created_at' => '2019-08-06 05:12:35',
                'updated_at' => '2019-08-07 08:40:36',
            ],
        ];

        // insertOrIgnore: re-seeding adds only missing rows, never overwrites.
        DB::table('categories')->insertOrIgnore($categories);
    }
}


class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        // Original had PHP-serialized multilingual values.
        // Deserialized; richtext types split into value_en / value_ar.
        $settings = [
            [
                'key'        => 'email',
                'label_en'   => 'Contact Email',
                'label_ar'   => 'البريد الإلكتروني',
                'group'      => 'contact',
                'type'       => 'email',
                'value'      => 'info@benevolentsociety.ps',
                'value_en'   => null,
                'value_ar'   => null,
                'is_public'  => true,
                'updated_by' => null,
                'created_at' => null,
                'updated_at' => '2019-08-07 08:43:26',
            ],
            [
                'key'        => 'phone',
                'label_en'   => 'Contact Phone',
                'label_ar'   => 'رقم الهاتف',
                'group'      => 'contact',
                'type'       => 'phone',
                'value'      => '+972082844810',
                'value_en'   => null,
                'value_ar'   => null,
                'is_public'  => true,
                'updated_by' => null,
                'created_at' => null,
                'updated_at' => '2019-08-07 08:43:26',
            ],
            [
                'key'       => 'address',
                'label_en'  => 'Office Address',
                'label_ar'  => 'عنوان المكتب',
                'group'     => 'contact',
                'type'      => 'text',
                'value'     => null,
                'value_en'  => 'Gaza Strip, Gaza City, Al-Shifa\'a Street, south to Al-Shifa Tour, Abo Al-Qumsan Building, 1st Floor.',
                'value_ar'  => 'قطاع غزة/ مدينة غزة- شارع الشفاء ، جنوب برج الشفاء، عمارة أبو القمصان الطابق الأول',
                'is_public' => true,
                'updated_by' => null,
                'created_at' => null,
                'updated_at' => '2019-08-07 08:43:26',
            ],
            [
                'key'       => 'aims',
                'label_en'  => 'Society Aims',
                'label_ar'  => 'أهداف الجمعية',
                'group'     => 'about',
                'type'      => 'richtext',
                'value'     => null,
                'value_en'  => null,
                'value_ar'  => null,
                'is_public' => true,
                'updated_by' => null,
                'created_at' => null,
                'updated_at' => '2019-08-07 05:03:00',
            ],
            [
                'key'       => 'vision',
                'label_en'  => 'Society Vision',
                'label_ar'  => 'رؤية الجمعية',
                'group'     => 'about',
                'type'      => 'richtext',
                'value'     => null,
                'value_en'  => null,
                'value_ar'  => null,
                'is_public' => true,
                'updated_by' => null,
                'created_at' => null,
                'updated_at' => '2019-08-07 05:03:25',
            ],
            [
                'key'      => 'about_message',
                'label_en' => 'About / Vision & Aims Combined',
                'label_ar' => 'عن الجمعية / الرؤية والأهداف',
                'group'    => 'about',
                'type'     => 'richtext',
                'value'    => null,
                // Excerpt of the original rich-text content (full HTML preserved)
                'value_en' => '<p><strong>Society Vision</strong></p><ul><li>Social justice, in all life aspects for people with disabilities.</li></ul><p><strong>Society Aims</strong></p><ol><li>Implementing different programs and projects aimed at merging people with disabilities into society.</li><li>Providing social rehabilitation and care services for people with disabilities.</li><li>Working on merging people with disabilities into local community.</li><li>Advocating the rights and interests of people with disabilities.</li><li>Cooperating with responsible associations and stakeholders.</li><li>Providing assistances and services for people with disabilities, patients and needy people.</li><li>Contributing in supporting health programs and services to the local community.</li></ol>',
                'value_ar' => '<p dir="rtl"><strong>رؤية الجمعية:</strong></p><ul dir="rtl"><li>العدالة الاجتماعية في مختلف مناحي الحياة للأشخاص ذوي الإعاقة والمرضى ودمجهم في المجتمع.</li></ul><p dir="rtl"><strong>أهداف الجمعية:</strong></p><ol><li>خلق برامج للوصول بالطفل والبالغ ممن يعانون من قصور وظيفي وعقلي ليتكيف ويندمج في المجتمع.</li><li>العمل على توفير خدمات الرعاية الاجتماعية لذوي الإعاقة والمرضى.</li><li>العمل على دمج ذوي الإعاقة والمرضى عبر برامج الدمج المختلفة.</li><li>تفعيل دور ذوي الإعاقة في المجتمع عبر البرامج التنموية.</li><li>التنسيق والتعاون مع الهيئات والمؤسسات الرسمية والأهلية ذات العلاقة.</li><li>تقديم المساعدات والخدمات لذوي الإعاقة والمرضى والمحتاجين.</li><li>المساهمة في دعم البرامج الصحية والخدمات الطبية للمجتمع المحلي.</li></ol>',
                'is_public' => true,
                'updated_by' => null,
                'created_at' => null,
                'updated_at' => '2019-08-07 10:35:50',
            ],
        ];
        // insertOrIgnore: re-seeding adds only missing rows, never overwrites.
        DB::table('settings')->insertOrIgnore($settings);
    }
}
