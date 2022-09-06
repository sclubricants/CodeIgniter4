<?php

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace CodeIgniter\Database\PDO;

use CodeIgniter\Database\BaseResult;
use CodeIgniter\Entity\Entity;
use PDO;

/**
 * Result for MySQLi
 */
abstract class PdoResult extends BaseResult
{
    /**
     * Frees the current result.
     */
    public function freeResult()
    {
        if (is_object($this->resultID)) {
            $this->resultID->closeCursor();
            $this->resultID = false;
        }
    }

    /**
     * Generates an array of column names in the result set.
     */
    public function getFieldNames(): array
    {
        // PDO doesn't allow getting field names

        $sql = $this->resultID->queryString;

        $fieldNames = array_keys($this->getUnbufferedRow('array'));

        $this->freeResult();

        $handle = $this->connID->prepare($sql);

        $handle->execute();

        $this->resultID = $handle;

        return $fieldNames;
    }

    /**
     * Gets the number of fields in the result set.
     */
    public function getFieldCount(): int
    {
        return count($this->getFieldNames());
    }

    /**
     * Returns the result set as an object.
     *
     * Overridden by child classes.
     *
     * @return bool|Entity|object
     */
    protected function fetchObject(string $className = 'stdClass')
    {
        if (is_subclass_of($className, Entity::class)) {
            return empty($data = $this->fetchAssoc()) ? false : (new $className())->setAttributes($data);
        }

        return $this->resultID->fetchObject($className);
    }

    /**
     * Returns the result set as an array.
     *
     * Overridden by driver classes.
     *
     * @return mixed
     */
    protected function fetchAssoc()
    {
        return $this->resultID->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Generates an array of objects representing field meta-data.
     *
     * May throw a database error if driver doesn't support this PDOException #IM001
     */
    public function getFieldData(): array
    {
        $retVal = [];
        $fields = array_keys(current($this->getResultArray()));

        foreach ($fields as $i => $field) {
            $data                    = $this->resultID->getColumnMeta($i);
            $retVal[$i]              = new stdClass();
            $retVal[$i]->name        = $data->name;
            $retVal[$i]->type        = $data->native_type;
            $retVal[$i]->type_name   = $data->pdo_type;
            $retVal[$i]->max_length  = null;
            $retVal[$i]->primary_key = null;
            $retVal[$i]->length      = $data->len;
            $retVal[$i]->default     = null;
            $retVal[$i]->precision   = $data->precision;
            $retVal[$i]->flags       = $data->flags;
        }

        return $retVal;
    }
}
