<?php

namespace App\Support;

class MathCaptcha
{
    public const SESSION_QUESTION = 'captcha_q';
    public const SESSION_ANSWER = 'captcha_answer';

    /**
     * Generate a new math question, store answer in session, return question string.
     * Uses only + and - with non-negative results (just numbers, e.g. "12 + 7 = ?").
     */
    public static function generate(): string
    {
        $a = random_int(1, 20);
        $b = random_int(1, 20);
        $operator = random_int(0, 1) === 1 ? '+' : '-';

        // For subtraction ensure non-negative result to avoid confusion
        if ($operator === '-' && $a < $b) {
            [$a, $b] = [$b, $a];
        }

        $question = sprintf('%d %s %d = ?', $a, $operator, $b);
        $answer = $operator === '+' ? $a + $b : $a - $b;

        session([
            self::SESSION_QUESTION => $question,
            self::SESSION_ANSWER => $answer,
        ]);

        return $question;
    }

    /**
     * Get current question, generating one if missing.
     */
    public static function question(): string
    {
        $q = session(self::SESSION_QUESTION);
        $a = session(self::SESSION_ANSWER);

        if ($q === null || $a === null) {
            return self::generate();
        }

        return $q;
    }

    /**
     * Get current answer (null if not generated yet).
     */
    public static function answer(): ?int
    {
        $a = session(self::SESSION_ANSWER);
        return $a === null ? null : (int) $a;
    }

    /**
     * Verify user input against session answer.
     */
    public static function verify(mixed $input): bool
    {
        $expected = session(self::SESSION_ANSWER);
        if ($expected === null) {
            return false;
        }
        // Strict integer comparison after trimming
        return (int) trim((string) $input) === (int) $expected;
    }

    /**
     * Forget current captcha (call after success or before regenerate).
     */
    public static function forget(): void
    {
        session()->forget([self::SESSION_QUESTION, self::SESSION_ANSWER]);
    }

    /**
     * Regenerate and return new question.
     */
    public static function regenerate(): string
    {
        self::forget();
        return self::generate();
    }
}
