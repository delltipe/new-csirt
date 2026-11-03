<?php

namespace App\Http\Controllers;

use App\Models\CybersecurityGuide;
use App\Models\Infographic;
use Illuminate\Http\Request;

class SecawController extends Controller
{
    /**
     * Display the Security Awareness (SecAw) landing hub.
     */
    public function index()
    {
        return view('secaw.index');
    }

    /**
     * Display the SecAw Materi (educational resources & guides) module.
     */
    public function materi(Request $request)
    {
        $query = CybersecurityGuide::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        $guides = $query->latest('id')->paginate(9)->withQueryString();
        $search = $request->query('search', '');

        return view('secaw.materi', compact('guides', 'search'));
    }

    /**
     * Display the SecAw Infografis gallery module.
     */
    public function infografis(Request $request)
    {
        $query = Infographic::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('title', 'like', "%{$search}%");
        }

        $infographics = $query->latest('id')->paginate(12)->withQueryString();
        $search = $request->query('search', '');

        return view('secaw.infografis', compact('infographics', 'search'));
    }

    /**
     * Display the SecAw Video catalogue module.
     */
    public function video(Request $request)
    {
        // Verified official Security Awareness webinar recordings
        $allVideos = [
            [
                'id' => 'Ud9fc2EXxJI',
                'title' => 'Keluarga Aman Digital - Melindungi Ibu dan Anak dari Ancaman Siber',
                'series' => 'Security Awareness Edisi 30',
                'date' => '20 Agustus 2026',
                'speaker' => 'Muhammad Ismu Hadi (BSSN)',
                'description' => 'Pembahasan strategi perlindungan keluarga, perempuan, dan anak dari kejahatan siber, perundungan digital, dan eksploitasi data pribadi di era digital.',
                'url' => 'https://www.youtube.com/watch?v=Ud9fc2EXxJI',
                'thumbnail' => 'https://img.youtube.com/vi/Ud9fc2EXxJI/hqdefault.jpg',
            ],
            [
                'id' => 'vbzgoR9XskI',
                'title' => 'Penipuan Berkedok Judi Online - Jangan Jadi Korban Perangkap Digital!',
                'series' => 'Security Awareness Edisi 29',
                'date' => '18 Juni 2026',
                'speaker' => 'Imam Sutanto (Universitas Esa Unggul)',
                'description' => 'Edukasi modus operandi penipuan online terorganisir, jebakan rekayasa sosial, manipulasi transaksi rekening, serta pencegahan keterlibatan judi daring.',
                'url' => 'https://www.youtube.com/watch?v=vbzgoR9XskI',
                'thumbnail' => 'https://img.youtube.com/vi/vbzgoR9XskI/hqdefault.jpg',
            ],
            [
                'id' => 'u9KTFlsj7aI',
                'title' => 'Cyber Security & Human Risk - Memahami Peran Manusia dalam Ketahanan Digital',
                'series' => 'Security Awareness Edisi 28',
                'date' => '26 Mei 2026',
                'speaker' => 'Ardian Oktadika (Praktisi Keamanan Siber)',
                'description' => 'Menganalisis faktor kelalaian manusia (human risk) sebagai mata rantai paling rentan dalam keamanan informasi serta cara membudayakan kepatuhan SOP digital.',
                'url' => 'https://www.youtube.com/watch?v=u9KTFlsj7aI',
                'thumbnail' => 'https://img.youtube.com/vi/u9KTFlsj7aI/hqdefault.jpg',
            ],
            [
                'id' => '0fuvqzxfgPU',
                'title' => 'Dark Web - Mengungkap Sisi Tersembunyi Internet & Dampaknya bagi Masyarakat',
                'series' => 'Security Awareness Edisi 27',
                'date' => '30 April 2026',
                'speaker' => 'Taufik Hidayatullah (Cyber Security Specialist)',
                'description' => 'Mengenal struktur deep web dan dark web, bahaya kebocoran data identitas yang diperjualbelikan, serta langkah mitigasi pencegahan pencurian akun.',
                'url' => 'https://www.youtube.com/watch?v=0fuvqzxfgPU',
                'thumbnail' => 'https://img.youtube.com/vi/0fuvqzxfgPU/hqdefault.jpg',
            ],
        ];

        $search = trim($request->query('search', ''));
        $videos = $allVideos;

        if ($search !== '') {
            $videos = array_values(array_filter($allVideos, function ($item) use ($search) {
                return stripos($item['title'], $search) !== false
                    || stripos($item['speaker'], $search) !== false
                    || stripos($item['description'], $search) !== false
                    || stripos($item['series'], $search) !== false;
            }));
        }

        return view('secaw.video', compact('videos', 'search'));
    }

    /**
     * Display the SecAw Tools Cybersecurity directory module.
     */
    public function tools(Request $request)
    {
        // Verified security tools catalogue from SecAw reference
        $allTools = [
            [
                'name' => 'Phishing Quiz by Google',
                'category' => 'Web Authentication Security',
                'category_code' => 'web-auth',
                'provider' => 'Google Jigsaw',
                'badge' => 'Simulasi Interaktif',
                'description' => 'Simulasi interaktif untuk melatih kepekaan mengenali email phishing, tautan palsu, dan teknik manipulasi rekayasa sosial secara aman.',
                'url' => 'https://phishingquiz.withgoogle.com/',
            ],
            [
                'name' => 'Have I Been Pwned',
                'category' => 'Data Breach Monitoring',
                'category_code' => 'data-breach',
                'provider' => 'Troy Hunt',
                'badge' => 'Deteksi Kebocoran',
                'description' => 'Layanan publik terpercaya untuk memeriksa apakah alamat email atau nomor telepon pernah terekspos dalam peristiwa kebocoran data komersial.',
                'url' => 'https://haveibeenpwned.com/',
            ],
            [
                'name' => 'VirusTotal',
                'category' => 'Threat Intelligence',
                'category_code' => 'threat-intelligence',
                'provider' => 'Google Chronicle',
                'badge' => 'Analisis Malware',
                'description' => 'Alat pemindai multi-antivirus untuk menganalisis file mencurigakan, domain, dan alamat IP terhadap indikasi infeksi malware serta domain berbahaya.',
                'url' => 'https://www.virustotal.com/',
            ],
        ];

        $categories = [
            'all' => 'Semua Kategori',
            'threat-intelligence' => 'Threat Intelligence',
            'data-breach' => 'Data Breach Monitoring',
            'web-auth' => 'Web Authentication Security',
        ];

        $selectedCategory = $request->query('kategori', 'all');
        $search = trim($request->query('search', ''));

        $tools = $allTools;

        if ($selectedCategory !== 'all' && array_key_exists($selectedCategory, $categories)) {
            $tools = array_values(array_filter($tools, function ($tool) use ($selectedCategory) {
                return $tool['category_code'] === $selectedCategory;
            }));
        }

        if ($search !== '') {
            $tools = array_values(array_filter($tools, function ($tool) use ($search) {
                return stripos($tool['name'], $search) !== false
                    || stripos($tool['description'], $search) !== false
                    || stripos($tool['category'], $search) !== false
                    || stripos($tool['provider'], $search) !== false;
            }));
        }

        return view('secaw.tools', compact('tools', 'categories', 'selectedCategory', 'search'));
    }
}
