<?php

namespace Mention\Kebab\Pcre;

use Mention\Kebab\Pcre\Exception\PcreException;

/**
 * PcreUtils implements common regular expressions functions.
 */
class PcreUtils
{
    /**
     * Perform a regular expression match.
     *
     * @see https://www.php.net/manual/en/function.preg-match.php
     *
     * @template TFlags of int-mask-of<PREG_OFFSET_CAPTURE|PREG_UNMATCHED_AS_NULL>
     *
     * @param mixed  $matches
     * @param TFlags $flags
     *
     * @param-out (
     *             TFlags is 256
     *             ? array<array-key, array{string, 0|positive-int}|array{'', -1}>
     *             : (TFlags is 512
     *                 ? array<array-key, string|null>
     *                 : (TFlags is 768
     *                     ? array<array-key, array{string, 0|positive-int}|array{null, -1}>
     *                     : array<array-key, string>
     *                     )
     *                 )
     *             ) $matches
     */
    public static function match(
        string $pattern,
        string $subject,
        mixed &$matches = null,
        int $flags = 0,
        int $offset = 0
    ): bool {
        $match = preg_match($pattern, $subject, $matches, $flags, $offset);

        if (false === $match) {
            throw PcreException::fromLastError();
        }

        return (bool) $match;
    }

    /**
     * Perform a global regular expression match.
     *
     * @see https://www.php.net/manual/en/function.preg-match-all.php
     *
     * @template TFlags of int-mask-of<PREG_PATTERN_ORDER|PREG_SET_ORDER|PREG_OFFSET_CAPTURE|PREG_UNMATCHED_AS_NULL>
     *
     * @param mixed $matches
     *
     * @param TFlags $flags
     * @param-out (
     *          TFlags is 1
     *          ? array<list<string>>
     *          : (TFlags is 2
     *              ? list<array<string>>
     *              : (TFlags is 256|257
     *                  ? array<list<array{string, int}>>
     *                  : (TFlags is 258
     *                      ? list<array<array{string, int}>>
     *                      : (TFlags is 512|513
     *                          ? array<list<?string>>
     *                          : (TFlags is 514
     *                              ? list<array<?string>>
     *                              : (TFlags is 770
     *                                  ? list<array<array{?string, int}>>
     *                                  : array
     *                              )
     *                          )
     *                      )
     *                  )
     *              )
     *          )
     *        ) $matches
     */
    public static function matchAll(
        string $pattern,
        string $subject,
        mixed &$matches = null,
        int $flags = 0,
        int $offset = 0
    ): int {
        $hits = preg_match_all($pattern, $subject, $matches, $flags, $offset);

        if (false === $hits) {
            throw PcreException::fromLastError();
        }

        return $hits;
    }

    /**
     * Perform a regular expression search and replace.
     *
     * Accepts one or multiple patterns.
     * Accepts only a single replacement.
     * Accepts only a single subject.
     *
     * @see https://www.php.net/manual/en/function.preg-replace.php
     *
     * @param string|array<string> $pattern
     *
     * @param-out int $count
     */
    public static function replace(
        $pattern,
        string $replacement,
        string $subject,
        int $limit = -1,
        ?int &$count = null
    ): string {
        $replaced = preg_replace($pattern, $replacement, $subject, $limit, $count);

        if (null === $replaced) {
            throw PcreException::fromLastError();
        }

        return $replaced;
    }

    /**
     * Perform a regular expression search and replace.
     *
     * Accepts a map of pattern => replacement.
     * Accepts only a single subject.
     *
     * @see https://www.php.net/manual/en/function.preg-replace.php
     *
     * @param array<string,string> $patternsAndReplacements
     *
     * @param-out int $count
     */
    public static function replaceArray(
        array $patternsAndReplacements,
        string $subject,
        int $limit = -1,
        ?int &$count = null
    ): string {
        $replaced = preg_replace(
            array_keys($patternsAndReplacements),
            array_values($patternsAndReplacements),
            $subject,
            $limit,
            $count
        );

        if (null === $replaced) {
            throw PcreException::fromLastError();
        }

        return $replaced;
    }

    /**
     * Perform a regular expression search and replace.
     *
     * Accepts one or multiple patterns.
     * Accepts only a single replacement.
     * Accepts multiple subjects.
     *
     * @see https://www.php.net/manual/en/function.preg-replace.php
     *
     * @param string|array<string> $pattern
     * @param array<string>        $subjects
     *
     * @param-out int $count
     *
     * @return array<string>
     */
    public static function replaceMultiple(
        $pattern,
        string $replacement,
        array $subjects,
        int $limit = -1,
        ?int &$count = null
    ): array {
        $replaced = preg_replace($pattern, $replacement, $subjects, $limit, $count);

        if (null === $replaced) {
            throw PcreException::fromLastError();
        }

        return $replaced;
    }

    /**
     * Perform a regular expression search and replace.
     *
     * Accepts a map of pattern => replacement.
     * Accepts multiple subjects.
     *
     * @see https://www.php.net/manual/en/function.preg-replace.php
     *
     * @param array<string,string> $patternsAndReplacements
     * @param array<string>        $subjects
     *
     * @param-out int $count
     *
     * @return array<string>
     */
    public static function replaceArrayMultiple(
        array $patternsAndReplacements,
        array $subjects,
        int $limit = -1,
        ?int &$count = null
    ): array {
        $replaced = preg_replace(
            array_keys($patternsAndReplacements),
            array_values($patternsAndReplacements),
            $subjects,
            $limit,
            $count
        );

        if (null === $replaced) {
            throw PcreException::fromLastError();
        }

        return $replaced;
    }

    /**
     * Perform a regular expression search and replace using a callback.
     *
     * Accepts one or multiple patterns.
     * Accepts only a single subject.
     *
     * @see https://www.php.net/manual/en/function.preg-replace-callback.php
     *
     * @param string|array<string> $pattern
     *
     * @param-out int $count
     */
    public static function replaceCallback(
        $pattern,
        callable $callback,
        string $subject,
        int $limit = -1,
        ?int &$count = null
    ): string {
        $replaced = preg_replace_callback($pattern, $callback, $subject, $limit, $count);

        if (null === $replaced) {
            throw PcreException::fromLastError();
        }

        return $replaced;
    }

    /**
     * Perform a regular expression search and replace using a callback.
     *
     * Accepts one or multiple patterns.
     * Accepts multiple subjects.
     *
     * @see https://www.php.net/manual/en/function.preg-replace-callback.php
     *
     * @param string|array<string> $pattern
     * @param array<string>        $subjects
     *
     * @param-out int $count
     *
     * @return array<string>
     */
    public static function replaceCallbackMultiple(
        $pattern,
        callable $callback,
        array $subjects,
        int $limit = -1,
        ?int &$count = null
    ): array {
        $replaced = preg_replace_callback($pattern, $callback, $subjects, $limit, $count);

        if (null === $replaced) {
            throw PcreException::fromLastError();
        }

        return $replaced;
    }

    /**
     * Perform a regular expression search and replace using callbacks.
     *
     * Accepts a map of pattern => callback.
     * Accepts only a single subject.
     *
     * @see https://www.php.net/manual/en/function.preg-replace-callback-array.php
     *
     * @param-out int $count
     *
     * @param array<string,callable> $patternsAndCallbacks
     */
    public static function replaceCallbackArray(
        array $patternsAndCallbacks,
        string $subject,
        int $limit = -1,
        ?int &$count = null
    ): string {
        $replaced = preg_replace_callback_array($patternsAndCallbacks, $subject, $limit, $count);

        if (null === $replaced) {
            throw PcreException::fromLastError();
        }

        return $replaced;
    }

    /**
     * Perform a regular expression search and replace using callbacks.
     *
     * Accepts a map of pattern => callback.
     * Accepts multiple subjects.
     *
     * @see https://www.php.net/manual/en/function.preg-replace-callback-array.php
     *
     * @param array<string,callable> $patternsAndCallbacks
     * @param array<string>          $subjects
     *
     * @param-out int $count
     *
     * @return array<string>
     */
    public static function replaceCallbackArrayMultiple(
        array $patternsAndCallbacks,
        array $subjects,
        int $limit = -1,
        ?int &$count = null
    ): array {
        $replaced = preg_replace_callback_array($patternsAndCallbacks, $subjects, $limit, $count);

        if (null === $replaced) {
            throw PcreException::fromLastError();
        }

        return $replaced;
    }

    /**
     * Perform a regular expression search and replace.
     *
     * Accepts only a single pattern and replacement.
     * Accepts only one single subject.
     *
     * @see https://www.php.net/manual/en/function.preg-filter.php
     *
     * @param-out int $count
     */
    public static function filter(
        string $pattern,
        string $replacement,
        string $subject,
        int $limit = -1,
        ?int &$count = null
    ): string {
        $matched = preg_filter($pattern, $replacement, $subject, $limit, $count);

        if (null === $matched) {
            throw PcreException::fromLastError();
        }

        return $matched;
    }

    /**
     * Perform a regular expression search and replace.
     *
     * Accepts a map of pattern => replacement.
     * Accepts only one single subject.
     *
     * @see https://www.php.net/manual/en/function.preg-filter.php
     *
     * @param array<string,string> $patternsAndReplacements
     * @param string               $subject
     *
     * @param-out int $count
     */
    public static function filterArray(
        array $patternsAndReplacements,
        string $subject,
        int $limit = -1,
        ?int &$count = null
    ): string {
        $matched = preg_filter(
            array_keys($patternsAndReplacements),
            array_values($patternsAndReplacements),
            $subject,
            $limit,
            $count
        );

        if (null === $matched) {
            throw PcreException::fromLastError();
        }

        return $matched;
    }

    /**
     * Perform a regular expression search and replace.
     *
     * Accepts only a single pattern and replacement.
     * Accepts multiple subjects.
     *
     * @see https://www.php.net/manual/en/function.preg-filter.php
     *
     * @param array<string> $subjects
     *
     * @param-out int $count
     *
     * @return array<string>
     */
    public static function filterMultiple(
        string $pattern,
        string $replacement,
        array $subjects,
        int $limit = -1,
        ?int &$count = null
    ): array {
        return preg_filter($pattern, $replacement, $subjects, $limit, $count);
    }

    /**
     * Perform a regular expression search and replace.
     *
     * Accepts a map of pattern => replacement.
     * Accepts multiple subjects.
     *
     * @see https://www.php.net/manual/en/function.preg-filter.php
     *
     * @param array<string,string> $patternsAndReplacements
     * @param array<string>        $subjects
     *
     * @param-out int $count
     *
     * @return array<string>
     */
    public static function filterArrayMultiple(
        array $patternsAndReplacements,
        array $subjects,
        int $limit = -1,
        ?int &$count = null
    ): array {
        return preg_filter(
            array_keys($patternsAndReplacements),
            array_values($patternsAndReplacements),
            $subjects,
            $limit,
            $count
        );
    }

    /**
     * Split string by a regular expression.
     *
     * @see https://www.php.net/manual/en/function.preg-split.php
     *
     * @return array<string>
     */
    public static function split(
        string $pattern,
        string $subject,
        int $limit = -1,
        int $flags = 0
    ): array {
        $fragments = preg_split($pattern, $subject, $limit, $flags);

        if (false === $fragments) {
            throw PcreException::fromLastError();
        }

        return $fragments;
    }

    /**
     * Quote regular expression characters.
     *
     * @see https://www.php.net/manual/en/function.preg-quote.php
     */
    public static function quote(
        string $string,
        string $delimiter = null
    ): string {
        return null === $delimiter
            ? preg_quote($string)
            : preg_quote($string, $delimiter);
    }

    /**
     * Return array entries that match the pattern.
     *
     * @see https://www.php.net/manual/en/function.preg-grep.php
     *
     * @param array<string> $input
     *
     * @return array<string>
     */
    public static function grep(
        string $pattern,
        array $input,
        int $flags = 0
    ): array {
        $matched = preg_grep($pattern, $input, $flags);

        if (false === $matched) {
            throw PcreException::fromLastError();
        }

        return $matched;
    }
}
