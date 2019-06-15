<?php

declare(strict_types=1);

namespace Pararius\EnvChecker\Infrastructure\Loader;

use Pararius\EnvChecker\Application\Loader\EnvVarLoader;
use Pararius\EnvChecker\Application\Loader\VarCollection;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

final class KubernetesYamlEnvVarLoader implements EnvVarLoader
{
    /**
     * @inheritdoc
     */
    public function load(string $path): VarCollection
    {
        $finder = (new Finder())->in($path)->name(['*.yml', '*.yaml']);
        $result = new VarCollection();

        foreach ($finder->getIterator() as $file) {
            // this is a (rather blunt) workaround to support yaml files containing multiple documents
            // @see https://github.com/symfony/symfony/issues/11840
            $docs = explode('---', file_get_contents($file->getRealPath()));
            $docs = array_filter($docs);

            foreach ($docs as $doc) {
                $dataInDocument = Yaml::parse($doc);

                $this->extractEnv($dataInDocument, $result);
                $this->extractEncryptedData($dataInDocument, $result);
            }
        }

        return $result;
    }

    /**
     * @param array $document
     * @param VarCollection $collection
     */
    private function extractEnv(array $document, VarCollection $collection): void
    {
        if ($found = $this->findByKeyRecursively('env', $document)) {
            foreach (array_column($found, 'name') as $name) {
                $collection->add($name);
            }
        }
    }

    /**
     * @param array $document
     * @param VarCollection $collection
     */
    private function extractEncryptedData(array $document, VarCollection $collection): void
    {
        if ($found = $this->findByKeyRecursively('encryptedData', $document)) {
            foreach (array_keys($found) as $name) {
                $collection->add($name);
            }
        }
    }

    /**
     * @param string $string
     * @param array $data
     *
     * @return mixed
     */
    private function findByKeyRecursively(string $string, array $data)
    {
        foreach ($data as $k => $v) {
            if ($k === $string) {
                return $v;
            }

            if (is_array($v)) {
                if ($match = $this->findByKeyRecursively($string, $v)) {
                    return $match;
                }
            }
        }

        return null;
    }
}
