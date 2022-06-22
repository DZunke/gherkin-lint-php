<?php

namespace DTL\BehatLint\Tests\Unit\Model;

use Closure;
use Cucumber\Gherkin\GherkinParser;
use DTL\BehatLint\Model\FeatureDiagnostics;
use DTL\BehatLint\Model\Linter;
use Generator;
use PHPUnit\Framework\TestCase;

class LinterTest extends TestCase
{
    /**
     * @dataProvider provideLint
     */
    public function testLint(string $content): void
    {
        $diagnostics = (new Linter(new GherkinParser()))->lint('/path', $content);
        self::assertInstanceOf(FeatureDiagnostics::class, $diagnostics);
    }

    /**
     * @return Generator<array{string}>
     */
    public function provideLint(): Generator
    {
        yield [
            <<<'EOT'
                Feature: Foobar

                    Scenario: Foobar
                        Given this happened
                        When I do this
                        Then that should happen
            EOT
        ];
    }
}
