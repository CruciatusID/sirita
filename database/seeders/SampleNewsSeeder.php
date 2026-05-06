<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Media;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SampleNewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $editors = [
            [
                'name' => 'Mira Damayanti',
                'email' => 'mira.damayanti@sirita.local',
                'unit_slug' => 'seksi-bimbingan-masyarakat-islam',
            ],
            [
                'name' => 'Rudi Hartono',
                'email' => 'rudi.hartono@sirita.local',
                'unit_slug' => 'seksi-bimbingan-masyarakat-kristen',
            ],
            [
                'name' => 'Sinta Wulandari',
                'email' => 'sinta.wulandari@sirita.local',
                'unit_slug' => 'seksi-pendidikan-islam',
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@sirita.local',
                'unit_slug' => 'seksi-pendidikan-islam',
            ],
        ];

        $contributors = [
            [
                'name' => 'Ahmad Rizal Pratama',
                'email' => 'ahmad.rizal@sirita.local',
                'unit_slug' => 'seksi-bimbingan-masyarakat-islam',
                'title_prefix' => 'Bimas Islam',
            ],
            [
                'name' => 'Fransiskus Toding',
                'email' => 'fransiskus.toding@sirita.local',
                'unit_slug' => 'seksi-bimbingan-masyarakat-kristen',
                'title_prefix' => 'Bimas Kristen',
            ],
            [
                'name' => 'Riska Amelia Sari',
                'email' => 'riska.amelia@sirita.local',
                'unit_slug' => 'seksi-pendidikan-islam',
                'title_prefix' => 'Pendidikan Islam',
            ],
        ];

        $contributorsByUnit = [];
        $editorsByEmail = [];

        foreach ($editors as $editor) {
            $unit = Unit::where('slug', $editor['unit_slug'])->firstOrFail();

            $user = User::updateOrCreate(
                ['email' => $editor['email']],
                [
                    'name' => $editor['name'],
                    'password' => Hash::make('password'),
                    'unit_id' => $unit->id,
                    'status' => 'active',
                ],
            );

            $user->syncRoles(['Editor']);

            $editorsByEmail[$editor['email']] = $user;
        }

        foreach ($contributors as $contributor) {
            $unit = Unit::where('slug', $contributor['unit_slug'])->firstOrFail();

            $user = User::updateOrCreate(
                ['email' => $contributor['email']],
                [
                    'name' => $contributor['name'],
                    'password' => Hash::make('password'),
                    'unit_id' => $unit->id,
                    'status' => 'active',
                ],
            );

            $user->syncRoles(['Kontributor']);

            $contributorsByUnit[$contributor['unit_slug']] = [
                'user' => $user,
                'unit' => $unit,
                'title_prefix' => $contributor['title_prefix'],
            ];
        }

        foreach ([
            'Pelayanan Publik',
            'Pembinaan ASN',
            'Kerukunan Umat',
            'Moderasi Beragama',
            'Digitalisasi',
            'Game Online',
            'Etika Digital',
            'Remaja',
            'Keluarga',
            'Keseimbangan Ibadah',
            'Layanan KUA',
            'Madrasah',
            'Kegiatan',
            'Sosialisasi',
            'Rapat Koordinasi',
            'Pembinaan',
        ] as $name) {
            Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name],
            );
        }

        // Gambar dummy berita diambil dari folder `contohgambar` lalu disalin
        // ke `storage/app/public/media` dengan nama tetap `dummy-news-01.jpg`
        // sampai `dummy-news-09.jpg`.
        // Pemetaan gambar disusun agar lebih relevan dengan isi berita:
        // 01 rapat koordinasi, 02 pembinaan ASN, 03 dialog lintas iman,
        // 04 literasi digital madrasah, 05 layanan nikah, 06 pembinaan siswa,
        // 07 remaja dan game, 08 keluarga dan game, 09 panduan bijak game.
        $this->copyDummyNewsImages();
        $this->syncMediaFolder();

        $posts = [
            [
                'title' => 'Rapat Koordinasi Awal Tahun di Kantor Kemenag Tana Toraja',
                'excerpt' => 'Kantor Kemenag Tana Toraja menggelar rapat koordinasi awal tahun untuk menyamakan langkah pelayanan dan target kerja.',
                'content' => [
                    '<p>Kantor Kemenag Tana Toraja mengawali tahun dengan rapat koordinasi lintas seksi untuk menyamakan target pelayanan, disiplin kerja, dan tindak lanjut program prioritas.</p>',
                    '<p>Pada forum tersebut dibahas alur layanan publik, penguatan administrasi, serta agenda monitoring kegiatan unit kerja selama satu triwulan ke depan.</p>',
                ],
                'category_slug' => 'kemenag-tana-toraja',
                'unit_slug' => 'seksi-bimbingan-masyarakat-islam',
                'author_email' => 'ahmad.rizal@sirita.local',
                'editor_email' => 'mira.damayanti@sirita.local',
                'tags' => ['rapat-koordinasi', 'pembinaan-asn', 'pelayanan-publik', 'zona-integritas'],
                'featured_image' => 'media/dummy-news-01.jpg',
                'og_image' => 'media/dummy-news-01.jpg',
                'published_at' => now()->subDays(8),
                'views' => 184,
                'likes_count' => 27,
                'shares_count' => 9,
            ],
            [
                'title' => 'Bimas Islam Gelar Pembinaan ASN dan Penyuluh Agama',
                'excerpt' => 'Seksi Bimas Islam memperkuat kapasitas ASN dan penyuluh agama melalui pembinaan rutin dan diskusi pelayanan.',
                'content' => [
                    '<p>Seksi Bimas Islam mengadakan pembinaan ASN dan penyuluh agama untuk memperkuat kualitas pelayanan, memperjelas tugas lapangan, dan menjaga konsistensi program kerja.</p>',
                    '<p>Kegiatan ini juga menjadi ruang evaluasi untuk menyesuaikan kebutuhan masyarakat dengan layanan yang lebih cepat dan tertata.</p>',
                ],
                'category_slug' => 'seksi-bimbingan-masyarakat-islam',
                'unit_slug' => 'seksi-bimbingan-masyarakat-islam',
                'author_email' => 'ahmad.rizal@sirita.local',
                'editor_email' => 'rudi.hartono@sirita.local',
                'tags' => ['pembinaan-asn', 'pembinaan', 'bimas-islam', 'layanan-publik'],
                'featured_image' => 'media/dummy-news-02.jpg',
                'og_image' => 'media/dummy-news-02.jpg',
                'published_at' => now()->subDays(6),
                'views' => 156,
                'likes_count' => 18,
                'shares_count' => 7,
            ],
            [
                'title' => 'Dialog Lintas Iman Dorong Kerukunan di Wilayah Pelayanan Kristen',
                'excerpt' => 'Bimas Kristen mendorong ruang dialog lintas iman untuk menjaga kerukunan dan memperkuat moderasi beragama.',
                'content' => [
                    '<p>Bimas Kristen menghadirkan dialog lintas iman bersama tokoh masyarakat untuk memperkuat semangat kerukunan di wilayah pelayanan.</p>',
                    '<p>Forum ini membahas komunikasi yang sehat, kerja sama sosial, dan cara menjaga suasana damai dalam kehidupan antarumat beragama.</p>',
                ],
                'category_slug' => 'seksi-bimbingan-masyarakat-kristen',
                'unit_slug' => 'seksi-bimbingan-masyarakat-kristen',
                'author_email' => 'fransiskus.toding@sirita.local',
                'editor_email' => 'sinta.wulandari@sirita.local',
                'tags' => ['kerukunan-umat', 'moderasi-beragama', 'sosialisasi'],
                'featured_image' => 'media/dummy-news-03.jpg',
                'og_image' => 'media/dummy-news-03.jpg',
                'published_at' => now()->subDays(5),
                'views' => 131,
                'likes_count' => 22,
                'shares_count' => 5,
            ],
            [
                'title' => 'Madrasah Tingkatkan Literasi Digital untuk Siswa dan Guru',
                'excerpt' => 'Pendidikan Islam mendorong madrasah lebih siap memanfaatkan ruang digital untuk pembelajaran dan administrasi.',
                'content' => [
                    '<p>Seksi Pendidikan Islam mendorong madrasah untuk meningkatkan literasi digital siswa dan guru melalui pendampingan sederhana dan praktik langsung.</p>',
                    '<p>Program ini diarahkan agar madrasah lebih siap memanfaatkan teknologi untuk pembelajaran, dokumentasi kegiatan, dan layanan informasi.</p>',
                ],
                'category_slug' => 'seksi-pendidikan-islam',
                'unit_slug' => 'seksi-pendidikan-islam',
                'author_email' => 'riska.amelia@sirita.local',
                'editor_email' => 'dewi.lestari@sirita.local',
                'tags' => ['madrasah', 'digitalisasi', 'pendidikan-islam', 'kegiatan'],
                'featured_image' => 'media/dummy-news-04.jpg',
                'og_image' => 'media/dummy-news-04.jpg',
                'published_at' => now()->subDays(4),
                'views' => 143,
                'likes_count' => 16,
                'shares_count' => 6,
            ],
            [
                'title' => 'KUA Makale Siapkan Layanan Nikah dan Konseling Pra-Nikah',
                'excerpt' => 'KUA Makale memperkuat layanan nikah dengan pendampingan pra-nikah dan penataan jadwal pelayanan.',
                'content' => [
                    '<p>KUA Makale menyiapkan layanan nikah dan konseling pra-nikah agar masyarakat mendapatkan layanan yang lebih tertib, ramah, dan mudah diakses.</p>',
                    '<p>Pendampingan pra-nikah menjadi bagian dari penguatan layanan keluarga sakinah dan kesiapan administrasi calon pengantin.</p>',
                ],
                'category_slug' => 'makale',
                'unit_slug' => 'seksi-bimbingan-masyarakat-islam',
                'author_email' => 'ahmad.rizal@sirita.local',
                'editor_email' => 'mira.damayanti@sirita.local',
                'tags' => ['kua', 'layanan-publik', 'kegiatan', 'pelayanan-publik'],
                'featured_image' => 'media/dummy-news-05.jpg',
                'og_image' => 'media/dummy-news-05.jpg',
                'published_at' => now()->subDays(3),
                'views' => 204,
                'likes_count' => 31,
                'shares_count' => 11,
            ],
            [
                'title' => 'MTsN 1 Tana Toraja Perkuat Pembinaan dan Disiplin Belajar',
                'excerpt' => 'MTsN 1 Tana Toraja menjalankan pembinaan rutin untuk menjaga disiplin belajar dan semangat peserta didik.',
                'content' => [
                    '<p>MTsN 1 Tana Toraja memperkuat pembinaan dan disiplin belajar melalui pengarahan singkat, evaluasi kedisiplinan, dan penegasan budaya sekolah.</p>',
                    '<p>Kegiatan ini diharapkan menjaga ritme belajar siswa sekaligus mendukung iklim madrasah yang lebih tertib dan produktif.</p>',
                ],
                'category_slug' => 'mtsn-1-tana-toraja',
                'unit_slug' => 'seksi-pendidikan-islam',
                'author_email' => 'riska.amelia@sirita.local',
                'editor_email' => 'dewi.lestari@sirita.local',
                'tags' => ['madrasah', 'pembinaan', 'kegiatan'],
                'featured_image' => 'media/dummy-news-06.jpg',
                'og_image' => 'media/dummy-news-06.jpg',
                'published_at' => now()->subDays(2),
                'views' => 117,
                'likes_count' => 14,
                'shares_count' => 4,
            ],
            [
                'title' => 'Remaja dan Game Online: Menjaga Waktu Ibadah dan Belajar',
                'excerpt' => 'Artikel ini mengajak remaja mengatur waktu bermain game online agar tetap seimbang dengan ibadah, belajar, dan tanggung jawab keluarga.',
                'content' => [
                    '<p>Dari sudut pandang agama, game online bukan sekadar hiburan, tetapi juga ruang yang perlu dibatasi agar tidak mengganggu ibadah, belajar, dan hubungan dengan keluarga.</p>',
                    '<p>Remaja didorong untuk membuat jadwal harian yang jelas, menetapkan durasi bermain, serta menjaga adab saat berinteraksi di dunia digital.</p>',
                ],
                'category_slug' => 'seksi-pendidikan-islam',
                'unit_slug' => 'seksi-pendidikan-islam',
                'author_email' => 'sinta.wulandari@sirita.local',
                'editor_email' => null,
                'tags' => ['game-online', 'remaja', 'etika-digital', 'keseimbangan-ibadah'],
                'featured_image' => 'media/dummy-news-07.jpg',
                'og_image' => 'media/dummy-news-07.jpg',
                'published_at' => now()->subDay(),
                'views' => 98,
                'likes_count' => 12,
                'shares_count' => 3,
            ],
            [
                'title' => 'Bimas Kristen Bahas Bijak Bermedia dan Batasan Game Online di Keluarga',
                'excerpt' => 'Bimas Kristen mengingatkan keluarga untuk mendampingi anak dan remaja agar penggunaan game online tetap sehat dan bertanggung jawab.',
                'content' => [
                    '<p>Dalam pembinaan keluarga, penggunaan game online perlu diarahkan dengan nilai tanggung jawab, kejujuran, dan pengendalian diri.</p>',
                    '<p>Orang tua didorong mendampingi anak, membatasi waktu layar, dan menegaskan bahwa hiburan tidak boleh menggeser prioritas ibadah dan pendidikan.</p>',
                ],
                'category_slug' => 'seksi-bimbingan-masyarakat-kristen',
                'unit_slug' => 'seksi-bimbingan-masyarakat-kristen',
                'author_email' => 'rudi.hartono@sirita.local',
                'editor_email' => null,
                'tags' => ['game-online', 'keluarga', 'etika-digital', 'moderasi-beragama'],
                'featured_image' => 'media/dummy-news-08.jpg',
                'og_image' => 'media/dummy-news-08.jpg',
                'published_at' => now()->subHours(18),
                'views' => 86,
                'likes_count' => 10,
                'shares_count' => 2,
            ],
            [
                'title' => 'Panduan Bijak Menyikapi Game Online dalam Kehidupan Umat',
                'excerpt' => 'Umat diajak melihat game online sebagai hiburan yang boleh, selama tidak mendorong lalai, emosi berlebihan, atau perilaku tidak pantas.',
                'content' => [
                    '<p>Game online bisa menjadi hiburan yang wajar, tetapi perlu dikawal dengan prinsip agama agar tidak mendorong lalai, boros waktu, atau konflik dengan orang sekitar.</p>',
                    '<p>Nilai seperti disiplin, pengendalian diri, dan saling menghormati tetap harus hadir saat bermain maupun ketika berinteraksi di komunitas game.</p>',
                ],
                'category_slug' => 'kemenag-tana-toraja',
                'unit_slug' => 'seksi-bimbingan-masyarakat-islam',
                'author_email' => 'mira.damayanti@sirita.local',
                'editor_email' => null,
                'tags' => ['game-online', 'etika-digital', 'layanan-publik', 'remaja'],
                'featured_image' => 'media/dummy-news-09.jpg',
                'og_image' => 'media/dummy-news-09.jpg',
                'published_at' => now()->subHours(10),
                'views' => 77,
                'likes_count' => 8,
                'shares_count' => 1,
            ],
        ];

        foreach ($posts as $postData) {
            $category = Category::where('slug', $postData['category_slug'])->firstOrFail();
            $author = User::where('email', $postData['author_email'])->firstOrFail();
            $editor = filled($postData['editor_email'] ?? null)
                ? ($editorsByEmail[$postData['editor_email']] ?? null)
                : null;
            $unit = Unit::where('slug', $postData['unit_slug'])->firstOrFail();
            $tagIds = Tag::whereIn('slug', $postData['tags'])->pluck('id');

            $post = Post::updateOrCreate(
                ['slug' => Str::slug($postData['title'])],
                [
                    'title' => $postData['title'],
                    'excerpt' => $postData['excerpt'],
                    'content' => implode("\n", $postData['content']),
                    'featured_image' => $postData['featured_image'],
                    'featured_image_caption' => $postData['title'],
                    'category_id' => $category->id,
                    'user_id' => $author->id,
                    'editor_user_id' => $editor?->id,
                    'unit_id' => $unit->id,
                    'status' => 'published',
                    'published_at' => $postData['published_at'],
                    'seo_title' => $postData['title'],
                    'seo_description' => $postData['excerpt'],
                    'og_image' => $postData['og_image'],
                    'views' => $postData['views'],
                    'likes_count' => $postData['likes_count'],
                    'shares_count' => $postData['shares_count'],
                ],
            );

            $post->tags()->sync($tagIds);
        }
    }

    private function copyDummyNewsImages(): void
    {
        $sourceImages = collect(['jpg', 'jpeg', 'png', 'webp'])
            ->flatMap(fn (string $extension): array => glob(base_path("contohgambar/*.{$extension}")) ?: [])
            ->sort()
            ->values();

        if ($sourceImages->count() < 9) {
            $this->command?->warn('Folder contohgambar tidak berisi 9 gambar dummy berita.');

            return;
        }

        Storage::disk('public')->makeDirectory('media');

        $sourceImages->take(9)->each(function (string $sourcePath, int $index): void {
            $fileNumber = str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
            $contents = file_get_contents($sourcePath);

            if ($contents === false) {
                $this->command?->warn("Gagal membaca gambar dummy: {$sourcePath}");

                return;
            }

            Storage::disk('public')->put("media/dummy-news-{$fileNumber}.jpg", $contents);
        });
    }

    private function syncMediaFolder(): void
    {
        collect(Storage::disk('public')->files('media'))
            ->filter(fn (string $path): bool => preg_match('/\.(jpe?g|png|webp|gif)$/i', $path) === 1)
            ->each(function (string $path): void {
                Media::updateOrCreate(
                    ['path' => $path],
                    [
                        'filename' => basename($path),
                        'mime_type' => Storage::disk('public')->mimeType($path),
                        'size' => Storage::disk('public')->size($path),
                    ],
                );
            });
    }
}
