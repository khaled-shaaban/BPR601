<?php

declare(strict_types=1);

/**
 * IDE stubs for Pest globals. Not loaded at runtime (see .vscode/settings.json).
 *
 * Intelephense does not resolve Pest’s real definitions because they live inside
 * `if (! function_exists(...))` blocks in vendor.
 */

namespace {
    /**
     * @param class-string ...$classAndTraits
     *
     * @return \Pest\PendingCalls\UsesCall
     */
    function uses(string ...$classAndTraits) {}

    /** @return \Pest\Configuration */
    function pest() {}

    /**
     * @param-closure-this \Pest\PendingCalls\TestCall $closure
     *
     * @return ($description is null ? \Pest\Support\HigherOrderTapProxy|\Pest\PendingCalls\TestCall : \Pest\PendingCalls\TestCall)
     */
    function test(?string $description = null, ?\Closure $closure = null) {}

    /**
     * @param-closure-this \Pest\PendingCalls\TestCall $closure
     *
     * @return \Pest\PendingCalls\TestCall
     */
    function it(string $description, ?\Closure $closure = null) {}

    /** @return \Pest\Expectation */
    function expect(mixed $value = null) {}

    /**
     * @param-closure-this \Pest\PendingCalls\TestCall $closure
     *
     * @return \Pest\PendingCalls\BeforeEachCall
     */
    function beforeEach(?\Closure $closure = null) {}

    /**
     * @param-closure-this \Pest\PendingCalls\TestCall $closure
     *
     * @return \Pest\PendingCalls\AfterEachCall
     */
    function afterEach(?\Closure $closure = null) {}

    function beforeAll(\Closure $closure): void {}

    function afterAll(\Closure $closure): void {}

    /** @return \Pest\PendingCalls\DescribeCall */
    function describe(string $description, \Closure $tests) {}

    /**
     * @param \Closure|iterable<int|string, mixed> $dataset
     */
    function dataset(string $name, \Closure|iterable $dataset): void {}

    /** @return \Pest\PendingCalls\TestCall */
    function todo(string $description) {}
}
