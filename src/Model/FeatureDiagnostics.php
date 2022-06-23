<?php

namespace DTL\GherkinLint\Model;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use RuntimeException;
use Traversable;

/**
 * @implements IteratorAggregate<FeatureDiagnostic>
 */
class FeatureDiagnostics implements IteratorAggregate, Countable
{
    public function __construct(
        /**
         * @var array<FeatureDiagnostic>
         */
        private array $featureDiagnostics
    ) {
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->featureDiagnostics);
    }

    public function first(): FeatureDiagnostic
    {
        if (empty($this->featureDiagnostics)) {
            throw new RuntimeException(
                'There are no diagnostics'
            );
        }
        return $this->featureDiagnostics[array_key_first($this->featureDiagnostics)];
    }

    public function count(): int
    {
        return count($this->featureDiagnostics);
    }
}
