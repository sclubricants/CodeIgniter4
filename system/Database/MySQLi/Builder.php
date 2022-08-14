<?php

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace CodeIgniter\Database\MySQLi;

use CodeIgniter\Database\BaseBuilder;

/**
 * Builder for MySQLi
 */
class Builder extends BaseBuilder
{
    /**
     * Identifier escape character
     *
     * @var string
     */
    protected $escapeChar = '`';

    /**
     * Specifies which sql statements
     * support the ignore option.
     *
     * @var array
     */
    protected $supportedIgnoreStatements = [
        'update' => 'IGNORE',
        'insert' => 'IGNORE',
        'delete' => 'IGNORE',
    ];

    /**
     * FROM tables
     *
     * Groups tables in FROM clauses if needed, so there is no confusion
     * about operator precedence.
     *
     * Note: This is only used (and overridden) by MySQL.
     */
    protected function _fromTables(): string
    {
        if (! empty($this->QBJoin) && count($this->QBFrom) > 1) {
            return '(' . implode(', ', $this->QBFrom) . ')';
        }

        return implode(', ', $this->QBFrom);
    }

    /**
     * Generates a platform-specific batch update string from the supplied data
     */
    protected function _updateBatch(string $table, array $keys, array $values): string
    {
        $constraints = $this->QBOptions['constraints'] ?? [];

        if ($constraints === []) {
            if ($this->db->DBDebug) {
                throw new DatabaseException('You must specify a constraint to match on for batch updates.');
            }

            return ''; // @codeCoverageIgnor
        }

        $updateFields = $this->QBOptions['updateFields'] ?? [];

        if ($updateFields === []) {
            $updateFields = array_filter($keys, static fn ($index) => ! in_array($index, $constraints, true));
            
            $this->QBOptions['updateFields'] = $updateFields;
        }

        $sql = 'UPDATE ' . $this->compileIgnore('update') . $table . " AS t\n";

        $sql .= 'INNER JOIN (' . "\n";

        $sql .= implode(
            " UNION ALL\n",
            array_map(
                static fn ($value) => 'SELECT ' . implode(', ', array_map(
                    static fn ($key, $index) => $index . ' ' . $key,
                    $keys,
                    $value
                )),
                $values
            )
        ) . "\n";

        $sql .= ') u' . "\n";

        $sql .= 'ON ' . implode(
            ' AND ',
            array_map(static fn ($key) => 't.' . $key . ' = u.' . $key, $constraints)
        ) . "\n";

        $sql .= 'SET' . "\n";

        return $sql .= implode(
            ",\n",
            array_map(static fn ($key) => 't.' . $key . ' = u.' . $key, $updateFields)
        );
    }
}
