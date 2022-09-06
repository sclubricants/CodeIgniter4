<?php

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace CodeIgniter\Database\PDO\ODBC;

use CodeIgniter\Database\PDO\PdoResult;
use CodeIgniter\Entity\Entity;
use PDO;
use stdClass;

/**
 * Result for MySQLi
 */
class Result extends PdoResult
{

    /**
     * Moves the internal pointer to the desired offset. This is called
     * internally before fetching results to make sure the result set
     * starts at zero.
     *
     * @return mixed
     */
    public function dataSeek(int $n = 0)
    {
        return $this->resultID->data_seek($n);
    }

    /**
     * Returns the number of rows in the resultID (i.e., mysqli_result object)
     */
    public function getNumRows(): int
    {
        if (! is_int($this->numRows)) {
            $this->numRows = $this->resultID->num_rows;
        }

        return $this->numRows;
    }
}
