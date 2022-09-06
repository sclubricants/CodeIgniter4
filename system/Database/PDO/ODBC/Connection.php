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

use CodeIgniter\Database\PDO\PdoConnection;
use PDO;

/**
 * Connection for PDO ODBC
 */
class Connection extends PdoConnection
{
    /**
     * Database driver
     *
     * @var string
     */
    public $DBDriver = 'ODBC';

    /**
     * Identifier escape character
     *
     * @var string
     */
    public $escapeChar = '"';

    /**
     * PDO object
     *
     * Has to be preserved without being assigned to $conn_id.
     *
     * @var PDO
     */
    public $pdo;
}
