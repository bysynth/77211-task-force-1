<?php

namespace App\DatasetsConverter;

use App\Exceptions\DestinationFolderException;
use App\Exceptions\SourceFileException;
use App\Exceptions\SqlFileException;

class SqlCreator
{
    private CsvParser $parser;
    private string $destinationFolder;
    private array $additionalColumnsData;

    public function __construct(string $filename, string $destinationFolder, array $additionalColumnsData = [])
    {
        $this->parser = new CsvParser($filename);
        $this->destinationFolder = $destinationFolder;
        $this->additionalColumnsData = $additionalColumnsData;
    }

    /**
     * @return int
     * @throws DestinationFolderException
     * @throws SqlFileException
     * @throws SourceFileException
     */
    public function create(): int
    {
        $rawValues = [];
        $csvData = $this->parser->parse()->getData();
        $tableName = $csvData['name'];
        $elementsCount = $csvData['linesCount'];
        $columnsHeaders = $csvData['headers'];
        $datasetName = "$tableName.sql";

        foreach ($csvData['lines'] as $line) {
            $rawValues[] = array_map(fn($item) => $this->quoteString($item), $line);
        }

        if (!empty($this->additionalColumnsData)) {
            $columnsHeaders = array_merge($columnsHeaders, array_keys($this->additionalColumnsData));
            foreach ($this->additionalColumnsData as $value) {
                $rawValues = array_map(fn($item) => array_merge($item, [random_int(1, $value)]), $rawValues);
            }
        }

        $sqlColumns = implode(', ', $columnsHeaders);

        if (in_array('coordinates', $columnsHeaders)) {
            $pointKey = array_search('coordinates', $columnsHeaders);
            foreach ($rawValues as &$value) {
                $point = $value[$pointKey];
                $value[$pointKey] = "ST_GeomFromText($point)";
            }
            unset($value);
        }

        $sqlValues = array_map(fn($line) => '(' . implode(', ', $line) . ')', $rawValues);
        $sql = "INSERT INTO $tableName ($sqlColumns)\nVALUES\n" . implode(",\n", $sqlValues) . ";\n";

        $this->createSqlFile($datasetName, $sql, $this->destinationFolder);

        return $elementsCount;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private function quoteString(mixed $value): mixed
    {
        return is_string($value) ? "'$value'" : $value;
    }

    /**
     * @param string $datasetName
     * @param string $sql
     * @param string $destinationFolder
     * @return void
     * @throws DestinationFolderException
     * @throws SqlFileException
     */
    private function createSqlFile(string $datasetName, string $sql, string $destinationFolder): void
    {
        $datasetPath = "$destinationFolder/$datasetName";

        if (!file_exists($destinationFolder)) {
            if (!mkdir($destinationFolder)) {
                throw new DestinationFolderException('Не удалось создать каталог для датасетов');
            };
        }

        if (!file_put_contents($datasetPath, $sql)) {
            throw new SqlFileException('Не удалось создать sql файл с датасетом');
        }
    }
}
