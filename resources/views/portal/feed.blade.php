{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>SIRITA - Kemenag Tana Toraja</title>
        <link>{{ route('home') }}</link>
        <description>Portal berita resmi Kementerian Agama Kabupaten Tana Toraja</description>
        <language>id</language>
        <lastBuildDate>{{ now()->toRssString() }}</lastBuildDate>
        <atom:link href="{{ url()->current() }}" rel="self" type="application/rss+xml" />
        @foreach ($posts as $post)
            <item>
                <title>{{ $post->title }}</title>
                <link>{{ route('posts.show', $post) }}</link>
                <guid>{{ route('posts.show', $post) }}</guid>
                <pubDate>{{ $post->published_at?->toRssString() ?? $post->created_at->toRssString() }}</pubDate>
                <description><![CDATA[{!! $post->excerpt !!}]]></description>
            </item>
        @endforeach
    </channel>
</rss>
