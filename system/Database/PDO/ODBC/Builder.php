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

use CodeIgniter\Database\PDO\PdoBuilder;

/**
 * Builder for PDO
 */
class Builder extends PdoBuilder
{
    /**
     * Identifier escape character
     *
     * @var string
     */
    protected $escapeChar = '"';

    /**
     * Generates a platform-specific truncate string from the supplied data
     *
     * If the database does not support the truncate() command,
     * then this method maps to 'DELETE FROM table'
     */
    protected function _truncate(string $table): string
    {
        return 'TRUNCATE TABLE ' . $table;
    }
}
