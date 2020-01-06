<?php

namespace Rikless\Xporter\Csv;

use Illuminate\Database\Eloquent\Builder;
use League\Csv\Writer as CSV;
use Illuminate\Support\Str;

/**
 * Class AbstractExportManager
 * @package Rikless\Xporter\Csv
 */
abstract class AbstractExportManager
{

    /**
     * @var string
     */
    protected $delimiter = ';';


    /**
     * @var static
     */
    protected $csvManager;

    /**
     * AbstractExportManager constructor.
     */
    public function __construct()
    {
        $this->csvManager = CSV::createFromFileObject(new \SplTempFileObject());

        $this->configureWriter();
    }

    /**
     * We need to setup the league package
     * to define basic file information
     */
    public function configureWriter()
    {
        $this->csvManager->setDelimiter($this->delimiter);

        $this->csvManager->insertOne($this->getFields());
    }


    /**
     * Chunk query result to save time
     * @param Builder $data
     * @return int
     */
    public function buildCsv(Builder $data)
    {
        $data->chunk(200, function ($rows) {

            foreach ($rows as $row) {
                $this->csvManager->insertOne($this->transform($row));
            }

        });

        return $this->csvManager->output(
            Str::snake(class_basename(get_class($this))) . '-' . time() . '.csv'
        );
    }
}
