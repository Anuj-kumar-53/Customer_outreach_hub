<?php

namespace App\Services;

/**
 * Lightweight heuristic spam scoring for comments (admin moderation support).
 * Higher score = more likely spam / needs review.
 */
class CommentSpamAnalyzer
{
    public function score(string $text): int
    {
        $text = trim($text);
        $score = 0;

        if ($text === '') {
            return 100;
        }

        if (strlen($text) < 4) {
            $score += 15;
        }

        $linkCount = preg_match_all('/https?:\/\/|www\./i', $text) ?: 0;
        $score += min(40, $linkCount * 12);

        if (preg_match('/(.)\1{8,}/u', $text)) {
            $score += 20;
        }

        $letters = preg_replace('/[^a-zA-Z]/', '', $text);
        if (strlen($letters) >= 10) {
            $upper = preg_replace('/[^A-Z]/', '', $text);
            $ratio = strlen($upper) / max(1, strlen($letters));
            if ($ratio > 0.85) {
                $score += 15;
            }
        }

        $spammy = ['viagra', 'crypto', 'click here', 'free money', 'winner', 'bitcoin'];
        $lower = strtolower($text);
        foreach ($spammy as $needle) {
            if (str_contains($lower, $needle)) {
                $score += 12;
            }
        }

        return min(100, $score);
    }

    /**
     * @return array{status: string, score: int}
     */
    public function evaluate(string $text): array
    {
        $score = $this->score($text);
        $status = $score >= 8 ? 'pending_review' : 'approved';

        return ['status' => $status, 'score' => $score];
    }
}
