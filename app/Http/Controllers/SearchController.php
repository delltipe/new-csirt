<?php

namespace App\Http\Controllers;

use App\Models\CybersecurityNews;
use App\Models\WarningPost;
use App\Models\Event;
use App\Models\Infographic;
use App\Models\LawRulePost;
use App\Models\CybersecurityGuide;

class SearchController extends Controller
{
    public function index()
    {
        $query = trim(request('q', ''));
        $results = [];
        $highlights = [];

        if (strlen($query) < 2) {
            return view('search.index', compact('query', 'results', 'highlights'));
        }

        $terms = $this->tokenize($query);

        $results['news'] = $this->ranked(CybersecurityNews::query(), $terms, ['title' => 3, 'description' => 1], 'date');
        $results['warnings'] = $this->ranked(WarningPost::query(), $terms, ['title' => 3, 'description' => 1], 'date');
        $results['events'] = $this->ranked(Event::query(), $terms, ['title' => 3, 'description' => 1], 'event_date');
        $results['infographics'] = $this->ranked(Infographic::query(), $terms, ['title' => 3], 'id');
        $results['laws'] = $this->ranked(LawRulePost::query(), $terms, ['title' => 3, 'description' => 1], 'date');
        $results['guides'] = $this->ranked(CybersecurityGuide::query(), $terms, ['title' => 3, 'author' => 2], 'id');

        foreach ($results as $type => $collection) {
            foreach ($collection as $item) {
                $highlights[$type][$item->getKey()] = [
                    'title' => $this->highlight($item->title ?? '', $terms),
                    'excerpt' => $this->highlight($this->excerpt($item, $terms), $terms),
                ];
            }
        }

        return view('search.index', compact('query', 'results', 'highlights', 'terms'));
    }

    private function tokenize(string $query): array
    {
        $terms = preg_split('/\s+/', mb_strtolower(trim($query)));
        $terms = array_filter(array_map(fn($t) => trim($t), $terms), fn($t) => mb_strlen($t) >= 2);
        $terms = array_values(array_unique($terms));
        return array_slice($terms, 0, 5);
    }

    private function ranked($query, array $terms, array $weights, string $orderBy)
    {
        $candidates = $query->where(function ($q) use ($terms, $weights) {
            foreach ($terms as $term) {
                $like = '%' . $term . '%';
                $q->orWhere(function ($sub) use ($like, $weights) {
                    foreach (array_keys($weights) as $field) {
                        $sub->orWhere($field, 'LIKE', $like);
                    }
                });
            }
        })->limit(50)->get();

        $scored = $candidates->map(function ($item) use ($terms, $weights) {
            $score = 0;
            foreach ($terms as $term) {
                foreach ($weights as $field => $weight) {
                    $value = mb_strtolower((string) ($item->{$field} ?? ''));
                    if ($value === '') continue;
                    if (mb_strpos($value, $term) !== false) {
                        $score += $weight;
                        if (mb_strtolower(trim($value)) === $term) $score += 2;
                        if (preg_match('/\b' . preg_quote($term, '/') . '\b/u', $value)) $score += 1;
                    }
                }
            }
            $item->search_score = $score;
            return $item;
        })->filter(fn($i) => $i->search_score > 0)
          ->sortByDesc(fn($i) => [$i->search_score, $i->{$orderBy} ?? $i->created_at ?? 0])
          ->values()
          ->take(10);

        return $scored;
    }

    private function excerpt($item, array $terms): string
    {
        $text = $item->description ?? $item->author ?? '';
        $text = strip_tags($text);
        if (mb_strlen($text) <= 180) return $text;
        foreach ($terms as $term) {
            $pos = mb_stripos($text, $term);
            if ($pos !== false) {
                $start = max(0, $pos - 60);
                $snippet = mb_substr($text, $start, 180);
                if ($start > 0) $snippet = '…' . ltrim($snippet);
                if ($start + 180 < mb_strlen($text)) $snippet .= '…';
                return $snippet;
            }
        }
        return mb_substr($text, 0, 180) . '…';
    }

    private function highlight(string $text, array $terms): string
    {
        $escaped = e($text);
        if (empty($terms)) return $escaped;
        $pattern = '/(' . implode('|', array_map(fn($t) => preg_quote($t, '/'), $terms)) . ')/iu';
        return preg_replace($pattern, '<mark style="background:var(--navy-tint); color:var(--navy); padding:0 2px; border-radius:0;">$1</mark>', $escaped);
    }
}
