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

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Database\PDO\PdoUtils;

/**
 * Utils for PDO ODBC
 */
class Utils extends PdoUtils
{
    /**
     * List databases statement
     *
     * @var string
     */
    protected $listDatabases = 'EXEC sp_helpdb'; // Can also be: EXEC sp_databases

    /**
     * OPTIMIZE TABLE statement
     *
     * @var string
     */
    protected $optimizeTable = 'ALTER INDEX all ON %s REORGANIZE';

    public function __construct(ConnectionInterface $db)
    {
        parent::__construct($db);

        $this->optimizeTable = 'ALTER INDEX all ON  ' . $this->db->schema . '.%s REORGANIZE';
    }
}
