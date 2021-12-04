<?php

namespace App\DatasetsConverter;

use App\Exceptions\SourceFileException;
use RuntimeException;
use SplFileObject;

class CsvParser
{
    private string $filename;
    private SplFileObject $fileObject;
    private array $result = [];

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return $this
     * @throws SourceFileException
     */
    public function parse(): static
    {
        if (!file_exists($this->filename)) {
            throw new SourceFileException('Файл не существует');
        }

        try {
            $this->fileObject = new SplFileObject($this->filename);
        } catch (RuntimeException $exception) {
            throw new SourceFileException('Не удалось открыть файл на чтение');
        }

        $this->result['name'] = $this->getCsvName($this->fileObject);
        $this->result['headers'] = $this->getHeaders();

        foreach ($this->getNextLine() as $line) {
            if (count($line) === 1 && is_null($line[0])) {
                continue;
            }
            $this->result['lines'][] = array_map(fn($item) => $this->convertDigitsType($item), $line);
        }

        if (in_array('lat', $this->result['headers']) && in_array('long', $this->result['headers'])) {
            $headers = $this->result['headers'];
            $keyLat = array_search('lat', $headers);
            $keyLong = array_search('long', $headers);
            unset($headers[$keyLat]);
            unset($headers[$keyLong]);
            $headers[$keyLat] = 'coordinates';
            ksort($headers);
            $this->result['headers'] = array_values($headers);

            $lines = $this->result['lines'];
            foreach ($lines as &$line) {
                $lat = $line[$keyLat];
                $long = $line[$keyLong];
                unset($line[$keyLat]);
                unset($line[$keyLong]);
                $line[$keyLat] = "POINT($lat $long)";
                ksort($line);
                $line = array_values($line);
            }
            unset($line);
            $this->result['lines'] = $lines;
        }

        $this->result['linesCount'] = count($this->result['lines']);

        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->result;
    }

    /**
     * @param SplFileObject $fileObject
     * @return string
     */
    private function getCsvName(SplFileObject $fileObject): string
    {
        return $fileObject->getBasename('.csv');
    }

    /**
     * @return array
     */
    private function getHeaders(): array
    {
        $this->fileObject->rewind();
        return $this->fileObject->fgetcsv();
    }

    /**
     * @return iterable
     */
    private function getNextLine(): iterable
    {
        while (!$this->fileObject->eof()) {
            yield $this->fileObject->fgetcsv();
        }
    }

    /**
     * @param string $item
     * @return mixed
     */
    private function convertDigitsType(string $item): mixed
    {
        if (is_numeric($item)) {
            if (str_contains($item, '.')) {
                return (float) $item;
            }

            return (int) $item;
        }

        return $item;
    }
}
