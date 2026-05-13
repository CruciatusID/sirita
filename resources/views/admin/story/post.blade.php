<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Story IG - {{ $post->title }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-kemenag.png') }}">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            background: #f4f6f8;
            color: #172018;
            font-family: Arial, Helvetica, sans-serif;
        }
        .page {
            display: grid;
            grid-template-columns: minmax(280px, 420px) minmax(320px, 540px);
            gap: 28px;
            align-items: start;
            max-width: 1040px;
            margin: 0 auto;
            padding: 28px 20px;
        }
        .panel {
            background: #fff;
            border: 1px solid #dde4dd;
            border-radius: 8px;
            padding: 18px;
            box-shadow: 0 18px 40px rgba(18, 32, 24, .08);
        }
        h1 {
            margin: 0 0 12px;
            font-size: 22px;
            line-height: 1.25;
        }
        .title {
            margin: 0 0 18px;
            color: #52615a;
            font-size: 14px;
            line-height: 1.5;
        }
        .actions {
            display: grid;
            gap: 10px;
        }
        button, a.button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            border: 0;
            border-radius: 7px;
            padding: 10px 14px;
            background: #166534;
            color: white;
            font: inherit;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }
        button.secondary, a.button.secondary {
            background: #eef2ee;
            color: #172018;
        }
        button:disabled {
            cursor: wait;
            opacity: .62;
        }
        .status {
            min-height: 22px;
            margin-top: 12px;
            color: #52615a;
            font-size: 13px;
        }
        .preview {
            overflow: hidden;
            border-radius: 8px;
            background: #d9e1dc;
            box-shadow: 0 18px 50px rgba(18, 32, 24, .18);
        }
        canvas {
            display: block;
            width: 100%;
            height: auto;
        }
        @media (max-width: 860px) {
            .page { grid-template-columns: 1fr; }
            .panel { order: 2; }
            .preview { order: 1; }
        }
    </style>
</head>
<body>
    <main class="page">
        <section class="panel">
            <h1>Story IG</h1>
            <p class="title">{{ $post->title }}</p>
            <div class="actions">
                <button type="button" data-download disabled>Download Story</button>
                <button type="button" class="secondary" data-copy>Salin Link Berita</button>
                <button type="button" class="secondary" data-share disabled>Bagikan dari Perangkat Ini</button>
                <a class="button secondary" href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer">Buka Instagram</a>
                <a class="button secondary" href="{{ route('posts.show', $post) }}" target="_blank" rel="noopener noreferrer">Lihat Berita</a>
            </div>
            <p class="status" data-status>Menyiapkan gambar story...</p>
        </section>

        <section class="preview">
            <canvas width="1080" height="1920" data-canvas></canvas>
        </section>
    </main>

    <script>
        const story = {
            title: @json($post->title),
            postUrl: @json($postUrl),
            templateUrl: @json($templateUrl),
            imageUrl: @json($imageUrl),
            fileName: @json('story-ig-' . $post->slug . '.png'),
        };

        const canvas = document.querySelector('[data-canvas]');
        const ctx = canvas.getContext('2d');
        const downloadButton = document.querySelector('[data-download]');
        const copyButton = document.querySelector('[data-copy]');
        const shareButton = document.querySelector('[data-share]');
        const statusText = document.querySelector('[data-status]');

        function loadImage(url) {
            return new Promise((resolve, reject) => {
                if (!url) {
                    resolve(null);
                    return;
                }

                const image = new Image();
                image.crossOrigin = 'anonymous';
                image.onload = () => resolve(image);
                image.onerror = reject;
                image.src = url;
            });
        }

        function coverImage(image, x, y, width, height) {
            const scale = Math.max(width / image.width, height / image.height);
            const sourceWidth = width / scale;
            const sourceHeight = height / scale;
            const sourceX = (image.width - sourceWidth) / 2;
            const sourceY = (image.height - sourceHeight) / 2;

            ctx.drawImage(image, sourceX, sourceY, sourceWidth, sourceHeight, x, y, width, height);
        }

        function wrapText(text, x, y, maxWidth, lineHeight, maxLines) {
            const words = text.split(/\s+/).filter(Boolean);
            const lines = [];
            let line = '';

            for (const word of words) {
                const testLine = line ? `${line} ${word}` : word;

                if (ctx.measureText(testLine).width <= maxWidth || !line) {
                    line = testLine;
                    continue;
                }

                lines.push(line);
                line = word;

                if (lines.length === maxLines) {
                    break;
                }
            }

            if (line && lines.length < maxLines) {
                lines.push(line);
            }

            if (words.length && lines.length === maxLines) {
                let last = lines[lines.length - 1];
                while (ctx.measureText(`${last}...`).width > maxWidth && last.includes(' ')) {
                    last = last.split(' ').slice(0, -1).join(' ');
                }
                lines[lines.length - 1] = `${last}...`;
            }

            const startY = y - ((lines.length - 1) * lineHeight / 2);
            lines.forEach((item, index) => ctx.fillText(item, x, startY + (index * lineHeight)));
        }

        async function copyLink() {
            await navigator.clipboard.writeText(story.postUrl);
            statusText.textContent = 'Link berita sudah disalin.';
        }

        function canvasToBlob() {
            return new Promise((resolve) => canvas.toBlob(resolve, 'image/png', 0.95));
        }

        async function drawStory() {
            const [template, newsImage] = await Promise.all([
                loadImage(story.templateUrl),
                loadImage(story.imageUrl),
            ]);

            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(template, 0, 0, canvas.width, canvas.height);

            if (newsImage) {
                coverImage(newsImage, 140, 502, 800, 533);
            } else {
                ctx.fillStyle = '#dce6df';
                ctx.fillRect(140, 502, 800, 533);
                ctx.fillStyle = '#53645b';
                ctx.font = '700 34px Arial, Helvetica, sans-serif';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText('Gambar utama belum tersedia', 540, 768);
            }

            ctx.fillStyle = '#050505';
            ctx.font = '800 54px Arial, Helvetica, sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            wrapText(story.title, 540, 1210, 780, 68, 4);

            downloadButton.disabled = false;
            shareButton.disabled = !navigator.canShare;
            statusText.textContent = 'Story siap. Link berita juga bisa langsung disalin.';
        }

        downloadButton.addEventListener('click', () => {
            const link = document.createElement('a');
            link.download = story.fileName;
            link.href = canvas.toDataURL('image/png');
            link.click();
        });

        copyButton.addEventListener('click', () => {
            copyLink().catch(() => {
                statusText.textContent = 'Browser menolak salin otomatis. Salin manual: ' + story.postUrl;
            });
        });

        shareButton.addEventListener('click', async () => {
            try {
                const blob = await canvasToBlob();
                const file = new File([blob], story.fileName, { type: 'image/png' });

                if (!navigator.canShare || !navigator.canShare({ files: [file] })) {
                    statusText.textContent = 'Browser ini belum mendukung share file. Pakai Download Story.';
                    return;
                }

                await navigator.share({
                    files: [file],
                    title: story.title,
                    text: story.postUrl,
                });
            } catch (error) {
                statusText.textContent = 'Share dibatalkan atau tidak didukung browser.';
            }
        });

        drawStory()
            .then(() => copyLink().catch(() => null))
            .catch(() => {
                statusText.textContent = 'Gagal membuat story. Pastikan template dan gambar utama bisa diakses.';
            });
    </script>
</body>
</html>
