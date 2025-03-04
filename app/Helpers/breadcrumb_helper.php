<?php

if (!function_exists('generate_breadcrumb')) {
    function generate_breadcrumb()
    {
        $uri = service('uri');
        $segments = $uri->getSegments();

        // Mulai dengan Home
        $breadcrumb = [
            [
                'title'  => 'Home',
                'link'   => base_url(),
                'active' => empty($segments)
            ]
        ];

        if (empty($segments)) {
            return $breadcrumb;
        }

        // Simpan segment pertama untuk membangun link, meski tidak ditampilkan
        $link = '/' . $segments[0];

        // Hanya ambil maksimal 3 segment untuk tampilan (abaikan segment pertama)
        $filteredSegments = array_slice($segments, 1, 2);

        foreach ($filteredSegments as $index => $segment) {
            // Tambahkan segment ke link untuk membentuk URL yang lengkap
            $link .= '/' . $segment;

            // Ubah dash/underscore menjadi spasi dan kapitalisasi judul
            $title = ucwords(str_replace(['-', '_'], ' ', $segment));

            $breadcrumb[] = [
                'title'  => $title,
                'link'   => base_url($link),
                'active' => ($index === count($filteredSegments) - 1)
            ];
        }

        return $breadcrumb;
    }
}
