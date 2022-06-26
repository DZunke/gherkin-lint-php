<?php

namespace DTL\GherkinLint\Model;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Path;

final class ConfigLoader
{
    private function __construct(
        private OutputInterface $output,
        private ConfigMapper $configMapper,
        private string $cwd
    ) {
    }

    public static function create(OutputInterface $output, string $cwd): self
    {
        return new self($output, ConfigMapper::create(), $cwd);
    }

    public function load(string $name): Config
    {
        $configPath = Path::join($this->cwd, $name);

        if (!file_exists($configPath)) {
            $this->output->writeln(sprintf('Config file "%s" not found, using defaults', $name));
            return new Config();
        }
        $this->output->writeln(sprintf('Using config file "%s"', $name));

        $contents = file_get_contents($configPath);

        /**
         * @var array<string,mixed>
         */
        $config = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);

        $config['rules'] = $this->transformRules($config, $name);

        return $this->configMapper->map(Config::class, $config);
    }

    private function transformRules(array $config, string $name): array
    {
        $nodes = [];
        /** @psalm-suppress MixedAssignment */
        foreach ($config['rules'] ?? [] as $name => $rule) {
            if (!is_string($name)) {
                continue;
            }
            if (!is_array($rule)) {
                continue;
            }
            $enabled = $rule['enabled'] ?? false;
            unset($rule['enabled']);
            $nodes[$name] = [
                'enabled' => $enabled,
                'config' => $rule,
            ];
        }
        return $nodes;
    }
}
