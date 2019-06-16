<?php

declare(strict_types=1);

namespace Pararius\EnvChecker\Infrastructure\Implementation\Kubernetes;

use Pararius\EnvChecker\Application\EnvVarLoader;
use Pararius\EnvChecker\Application\VarCollection;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

final class Loader implements EnvVarLoader
{
    /**
     * @var Finder
     */
    private $finder;

    /**
     * Temporary store for references made to secret files.
     *
     * @var array
     */
    private $references;

    /**
     * @param Finder $finder
     */
    public function __construct(Finder $finder)
    {
        $this->finder = $finder;
    }

    /**
     * @inheritdoc
     */
    public function load(string $path): VarCollection
    {
        $finder = $this->finder->in($path)->name(['*.yml', '*.yaml']);
        $result = new VarCollection();

        foreach ($this->loopThroughFiles($finder) as $data) {
            $this->extractSimpleVars($data, $result);
            $this->extractReferences($data);
        }

        foreach ($this->loopThroughFiles($finder) as $data) {
            if (in_array($data['kind'], ['Secret', 'SealedSecret'])) {
                if (in_array($data['metadata']['name'], $this->references)) {
                    foreach (array_keys($data['spec']['encryptedData']) as $var) {
                        $result->add($var);
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @param Finder $finder
     *
     * @return iterable|array[]
     */
    private function loopThroughFiles(Finder $finder): iterable
    {
        $finder->getIterator()->rewind();

        foreach ($finder->getIterator() as $file) {
            // this is a (rather blunt) workaround to support yaml files containing multiple documents
            // @see https://github.com/symfony/symfony/issues/11840
            $yamlDocs = explode('---', file_get_contents($file->getRealPath()));
            $yamlDocs = array_filter($yamlDocs);

            foreach ($yamlDocs as $document) {
                yield Yaml::parse($document);
            }
        }
    }

    /**
     * @param array $document
     * @param VarCollection $collection
     */
    private function extractSimpleVars(array $document, VarCollection $collection): void
    {
        if ($found = $this->findArrayByKeyRecursively('env', $document)) {
            foreach ($found as $varSet) {
                foreach (array_column($varSet, 'name') as $name) {
                    $collection->add($name);
                }
            }
        }
    }

    /**
     * @param array $document
     */
    private function extractReferences(array $document): void
    {
        if ($found = $this->findArrayByKeyRecursively('envFrom', $document)) {
            foreach ($found as $referenceSet) {
                foreach ($referenceSet as $reference) {
                    $this->references[] = $reference['secretRef']['name'];
                }
            }
        }
    }

    /**
     * @param string $string
     * @param array $data
     * @param array $matches
     *
     * @return mixed[]
     */
    private function findArrayByKeyRecursively(string $string, array $data, array &$matches = [])
    {
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                if ($k === $string) {
                    $matches[] = $v;
                } else {
                    $this->findArrayByKeyRecursively($string, $v, $matches);
                }
            }
        }

        return $matches;
    }
}
