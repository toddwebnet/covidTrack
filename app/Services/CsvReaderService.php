<?php

namespace App\Services;

use App\Helpers\ArrayHelpers;
use AppExceptions\GeneralException;
use League\Csv\Reader;

class CsvReaderService
{
    protected $headers;
    protected $reader;
    protected $curRow;
    private $errors = [];
    private $fileLoaded;

    /**
     * CsvReaderService constructor.
     */
    public function __construct()
    {
        // initialize the file has not been loaded
        $this->fileLoaded = false;
    }

    /**
     * loadFile
     *
     * @param string $csvPath
     * @param string $delimiter
     * @throws \Exception
     */
    public function loadFile(string $csvPath, string $delimiter = ',')
    {
        // load the file, get the headers and mark the starting row at 0
        try {
            $this->reader = Reader::createFromPath($csvPath);
            $this->reader->setDelimiter($delimiter);
            $this->fileLoaded = true;
            $this->extractHeaders();
            $this->reset();
        } catch (\Exception $e) {
            throw new GeneralException($e->getMessage(), $e->getCode());
        }
    }

    /**
     *  populates header property of class with first row headers of csv file
     */
    private function extractHeaders()
    {
        // do nothing if not loaded
        if (!$this->fileLoaded) {
            return;
        }
        // store headers
        $this->headers = $this->reader->fetchOne(0);
    }

    /**
     * resets counter for "next" function (pun intended!)
     */
    public function reset()
    {
        $this->curRow = 0;
    }

    /**
     * reads the next line in the csv file
     *
     * @return array|bool
     */
    public function next()
    {
        // no row if file not loaded
        if (!$this->fileLoaded) {
            return false;
        }
        $this->curRow++;
        $row = $this->reader->fetchOne($this->curRow);
        if (count($row) > 0) {
            return array_combine($this->headers, $row);
        } else {
            // returns false if empty row
            return false;
        }
    }

    /**
     * validates header values
     *
     * @param array $allowedHeaders
     * @param array $requiredHeaders
     * @return bool
     */
    public function validateHeaders(array $allowedHeaders = [], array $requiredHeaders = [])
    {
        // validation
        $errs = [];

        if (!$this->fileLoaded) {
            $errs = ['File not loaded'];
        }
        // combine all error categories
        $this->errors = array_merge(
            $errs,
            $this->getHeaderDupeErrors(),
            $this->getHeaderRequiredErrors($requiredHeaders),
            $this->getHeaderOutlierErrors($allowedHeaders)
        );
        return (count($this->errors) == 0);
    }

    /**
     * returns errors if required headers are not present
     *
     * @param array $requiredHeaders
     * @return array
     */
    private function getHeaderRequiredErrors(array $requiredHeaders)
    {
        if (!$this->fileLoaded || $requiredHeaders == []) {
            return [];
        }
        $errs = [];
        foreach (array_diff($requiredHeaders, $this->headers) as $required) {
            $errs[] = 'Missing required header: ' . $required;
        }
        return $errs;
    }

    /**
     * returns errors for unexpected headers
     *
     * @param array $allowedHeaders
     * @return array
     */
    private function getHeaderOutlierErrors(array $allowedHeaders)
    {
        if (!$this->fileLoaded) {
            return [];
        }
        $errs = [];
        if (count($allowedHeaders) > 0) {
            foreach (array_diff($this->headers, $allowedHeaders) as $outlier) {
                $errs[] = 'Unexpected header: ' . $outlier;
            }
        }
        return $errs;
    }

    /**
     * determines if there are duplicate headers
     *
     * @return array
     */
    private function getHeaderDupeErrors()
    {
        if (!$this->fileLoaded) {
            return [];
        }
        $errs = [];
        $dupes = ArrayHelpers::getDupes($this->headers);

        if (count($dupes) > 0) {
            foreach ($dupes as $dupe) {
                $errs[] = 'Duplicate header: ' . $dupe;
            }
        }
        return $errs;
    }

    /**
     * returns errors property
     *
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }
}
