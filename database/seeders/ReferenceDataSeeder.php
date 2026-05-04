<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Page;
use App\Models\Tag;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ReferenceDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['Kantor Kemenag Tana Toraja', 'Kantor'],
            ['Seksi Bimbingan Masyarakat Kristen', 'Seksi'],
            ['Seksi Bimbingan Masyarakat Islam', 'Seksi'],
            ['Seksi Pendidikan Islam', 'Seksi'],
            ['Penyelenggara Katolik', 'Penyelenggara'],
            ['Penyelenggara Zakat dan Wakaf', 'Penyelenggara'],
            ['KUA Bittuang', 'KUA'],
            ['KUA Bonggakaradeng', 'KUA'],
            ['KUA Gandangbatu Sillanan', 'KUA'],
            ['KUA Kurra', 'KUA'],
            ['KUA Makale', 'KUA'],
            ['KUA Makale Selatan', 'KUA'],
            ['KUA Makale Utara', 'KUA'],
            ['KUA Malimbong Balepe', 'KUA'],
            ['KUA Mappak', 'KUA'],
            ['KUA Masanda', 'KUA'],
            ['KUA Mengkendek', 'KUA'],
            ['KUA Rano', 'KUA'],
            ['KUA Rantetayo', 'KUA'],
            ['KUA Rembon', 'KUA'],
            ['KUA Saluputti', 'KUA'],
            ['KUA Sangalla', 'KUA'],
            ['KUA Sangalla Selatan', 'KUA'],
            ['KUA Sangalla Utara', 'KUA'],
            ['KUA Simbuang', 'KUA'],
            ['MIN 1 Tana Toraja', 'Madrasah'],
            ['MIN 2 Tana Toraja', 'Madrasah'],
            ['MIN 3 Tana Toraja', 'Madrasah'],
            ['MIN 4 Tana Toraja', 'Madrasah'],
            ['MTsN 1 Tana Toraja', 'Madrasah'],
            ['MTsN 2 Tana Toraja', 'Madrasah'],
            ['MAN Tana Toraja', 'Madrasah'],
        ];

        $unitSlugs = [];

        foreach ($units as [$name, $type]) {
            $unit = Unit::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'type' => $type,
                    'address' => 'Kabupaten Tana Toraja',
                    'is_active' => true,
                ],
            );

            $unitSlugs[] = $unit->slug;
        }

        Unit::query()
            ->whereNotIn('slug', $unitSlugs)
            ->update(['is_active' => false]);

        $categories = [
            'Kemenag Tana Toraja' => [],
            'Seksi Bimbingan Masyarakat Kristen' => [],
            'Seksi Bimbingan Masyarakat Islam' => [],
            'Seksi Pendidikan Islam' => [],
            'Penyelenggara Katolik' => [],
            'Penyelenggara Zakat dan Wakaf' => [],
            'KUA' => [
                'Bittuang',
                'Bonggakaradeng',
                'Gandangbatu Sillanan',
                'Kurra',
                'Makale',
                'Makale Selatan',
                'Makale Utara',
                'Malimbong Balepe',
                'Mappak',
                'Masanda',
                'Mengkendek',
                'Rano',
                'Rantetayo',
                'Rembon',
                'Saluputti',
                'Sangalla',
                'Sangalla Selatan',
                'Sangalla Utara',
                'Simbuang',
            ],
            'Madrasah' => [
                'MIN 1 Tana Toraja',
                'MIN 2 Tana Toraja',
                'MIN 3 Tana Toraja',
                'MIN 4 Tana Toraja',
                'MTsN 1 Tana Toraja',
                'MTsN 2 Tana Toraja',
                'MAN Tana Toraja',
            ],
        ];

        $categorySlugs = [];

        foreach ($categories as $name => $children) {
            $category = Category::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'parent_id' => null, 'is_active' => true],
            );

            $categorySlugs[] = $category->slug;

            foreach ($children as $childName) {
                $child = Category::updateOrCreate(
                    ['slug' => Str::slug($childName)],
                    ['name' => $childName, 'parent_id' => $category->id, 'is_active' => true],
                );

                $categorySlugs[] = $child->slug;
            }
        }

        Category::query()
            ->whereNotIn('slug', $categorySlugs)
            ->update(['is_active' => false]);

        foreach ([
            'ASN',
            'Layanan Publik',
            'Moderasi Beragama',
            'Kerukunan Umat',
            'Haji',
            'Umrah',
            'Zakat',
            'Wakaf',
            'Madrasah',
            'KUA',
            'Bimas Islam',
            'Bimas Kristen',
            'Katolik',
            'Pendidikan Islam',
            'PPID',
            'Pengumuman',
            'Kegiatan',
            'Rapat Koordinasi',
            'Pembinaan',
            'Sosialisasi',
            'Digitalisasi',
            'Zona Integritas',
        ] as $name) {
            Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name],
            );
        }

        foreach ([
            'Profil Kantor',
            'Visi Misi',
            'Struktur Organisasi',
            'Kontak',
            'PPID',
        ] as $title) {
            Page::firstOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'content' => '<p>Konten halaman ini dapat diperbarui melalui panel admin.</p>',
                    'status' => 'published',
                ],
            );
        }
    }
}
