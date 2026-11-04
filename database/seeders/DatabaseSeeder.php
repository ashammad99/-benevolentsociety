<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeds an empty install with the settings rows the admin Settings screen
 * expects. No users or categories are seeded: create the first admin with
 * `php artisan society:create-admin`, then add categories in the admin.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SettingsSeeder::class,
        ]);
    }
}


// ─── You can split these into separate files; kept together here for clarity ──

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        // Rows with bilingual labels and empty values; fill them in under
        // Admin → Settings. Until then the public site shows the fallbacks
        // from config/society.php.
        $rows = [
            ['email', 'Contact Email', 'البريد الإلكتروني', 'contact', 'email'],
            ['phone', 'Contact Phone', 'رقم الهاتف', 'contact', 'phone'],
            ['address', 'Office Address', 'عنوان المكتب', 'contact', 'text'],
            ['aims', 'Society Aims', 'أهداف الجمعية', 'about', 'richtext'],
            ['vision', 'Society Vision', 'رؤية الجمعية', 'about', 'richtext'],
        ];

        $now = now();
        $settings = array_map(fn ($row) => [
            'key' => $row[0],
            'label_en' => $row[1],
            'label_ar' => $row[2],
            'group' => $row[3],
            'type' => $row[4],
            'value' => null,
            'value_en' => null,
            'value_ar' => null,
            'is_public' => true,
            'updated_by' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ], $rows);

        // insertOrIgnore: re-seeding adds only missing rows, never overwrites.
        DB::table('settings')->insertOrIgnore($settings);
    }
}
