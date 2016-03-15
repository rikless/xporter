<?php

namespace Rikless\Xporter;

use Illuminate\Http\Request;
use Rikless\Xporter\Csv\AbstractExportManager;

/**
 * Class AbstractXportTransformer
 * @package Rikless\Xporter
 */
abstract class Exportable extends AbstractExportManager implements NeedQueryInterface
{

    /**
     * AbstractXportTransformer constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->getModel();
        $this->getModel();
    }

    /**
     * @return mixed
     * @throws ExportClassNeedSetupException
     */
    public function getFields()
    {
        if (property_exists($this, 'xportable')) {
            return $this->xportable;
        }

        throw new ExportClassNeedSetupException('You class need an exportable property');
    }

    /**
     * @return mixed
     * @throws ExportClassNeedSetupException
     */
    public function getModel()
    {
        if (property_exists($this, 'rootModel')) {
            return $this->rootModel;
        }

        throw new ExportClassNeedSetupException('You class need a rootModel property');
    }


    /**
     * @param $item
     * @return array
     * @throws ExportClassNeedSetupException
     * @throws \Exception
     */
    public function transform($item)
    {
        if ($item instanceof $this->rootModel) {

            $output = [];

            $data = $this->convert($item);

            foreach ($this->getFields() as $field) {

                $output[] = $data[$field];

            }

            return $output;
        }

        throw new \Exception('This item is not an instance of the root model');

    }


    /**
     * @param Request $request
     * @return int
     */
    public function build(Request $request)
    {
        return $this->buildCsv($this->query($request));
    }

    /**
     * @param $item
     * @return mixed
     */
    abstract public function convert($item);
}
