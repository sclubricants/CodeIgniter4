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

use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Database\Exceptions\DatabaseException;
use PDO;
use PDOException;

/**
 * Connection for PDO
 */
class PdoConnection extends BaseConnection
{
    /**
     * Database driver
     *
     * @var string
     */
    public $DBDriver = 'PDO';

    /**
     * Identifier escape character
     *
     * @var string
     */
    public $escapeChar = '"';

    /**
     * PDO object
     *
     * @var PDO
     */
    public $pdo;

    /**
     * Connect to the database.
     *
     * @throws DatabaseException
     *
     * @return mixed
     */
    public function connect(bool $persistent = false)
    {//$pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, 1); make pdoSetAttribute() method
        if ($persistent === true) {
            $this->options[PDO::ATTR_PERSISTENT] = true;
        }

        $this->pdo = new PDO($this->DSN, $this->username, $this->password, ['PDO::ATTR_ERRMODE' => 'PDO::ERRMODE_EXCEPTION']);

        return $this->pdo;
    }

    public function _close()
    { // PDOStatement::closeCursor()
        $this->connID = null;
        $this->pdo    = null;
    }

    public function reconnect()
    {
        $this->close();
        $this->initialize();
    }

    public function setDatabase(string $databaseName)
    {
    }

    public function getVersion(): string
    {
    }

    protected function execute(string $sql)
    {// echo $sql.PHP_EOL;
        try {
            // PDO has no way to free result from connection. Need a better solution
            $this->reconnect();

            return $this->connID->query($sql);
        } catch (PDOException $e) {
            log_message('error', (string) $e);

            if ($this->DBDebug) {
                throw new DatabaseException($e->getMessage(), (int) $e->getCode(), $e);
            }
        }

        return false;
    }

    protected function _transBegin(): bool
    {
    }

    protected function _transCommit(): bool
    {
    }

    protected function _transRollback(): bool
    {
    }

    public function affectedRows(): int
    {
    }

    public function error(): array
    {
    }

    protected function _listTables(bool $constrainByPrefix = false, ?string $tableName = null)
    {
        if ($tableName !== false) {
            return "SELECT table_name FROM INFORMATION_SCHEMA.TABLES WHERE table_name = '" . $tableName . "'";
        }

        return 'SELECT table_name FROM INFORMATION_SCHEMA.TABLES';
    }

    public function insertID()
    {
    }

    protected function _listColumns(string $table = '')
    {
    }

    protected function _fieldData(string $table): array
    {
    }

    protected function _indexData(string $table): array
    {
    }

    protected function _foreignKeyData(string $table): array
    {
    }

    /**
     * Returns platform-specific SQL to disable foreign key checks.
     *
     * @return string
     */
    protected function _disableForeignKeyChecks()
    {
        return ''; // "SELECT 'something' as nothing";
    }

    /**
     * Disables foreign key checks temporarily.
     */
    public function disableForeignKeyChecks()
    {
        return true;
    }

    /**
     * Enables foreign key checks temporarily.
     */
    public function enableForeignKeyChecks()
    {
        return true;
    }
}
