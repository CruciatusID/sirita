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
        Unit::firstOrCreate(
            ['slug' => 'kantor-kemenag-tana-toraja'],
            [
                'name' => 'Kantor Kemenag Tana Toraja',
                'slug' => 'kantor-kemenag-tana-toraja',
                'type' => 'Kantor',
                'address' => 'Kabupaten Tana Toraja',
                'is_active' => true,
            ],
        );

        foreach ([
            'Kemenag Tana Toraja',
            'Kepala Kantor',
            'Sekretariat',
            'Bimas Kristen',
            'Bimas Islam',
            'Pendidikan Madrasah',
            'KUA',
            'Madrasah',
            'Penyuluh',
            'Kerukunan Umat',
            'Hari Besar Keagamaan',
        ] as $name) {
            Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'is_active' => true],
            );
        }

        foreach (['Hardiknas', 'Moderasi Beragama', 'ZI', 'Haji', 'ASN', 'Digitalisasi'] as $name) {
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
