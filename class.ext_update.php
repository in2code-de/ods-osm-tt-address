<?php

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ext_update
 */
class ext_update
{
    protected $messageArray = [];

    /**
     * @return bool
     */
    public function access()
    {
        return \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_branch)
            >= \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger('6.0');
    }

    /**
     * Main update function called by the extension manager.
     *
     * @return string
     * @throws \Doctrine\DBAL\DBALException
     */
    public function main()
    {
        $this->processUpdates();
        return $this->generateOutput();
    }

    /**
     * Generates output by using flash messages
     *
     * @return string
     */
    protected function generateOutput()
    {
        $output = '';
        foreach ($this->messageArray as $messageItem) {
            $flashMessage = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
                'TYPO3\CMS\Core\Messaging\FlashMessage',
                $messageItem[2],
                $messageItem[1],
                $messageItem[0]);
            $output .= $flashMessage->render();
        }

        return $output;
    }

    /**
     * The actual update function. Add your update task in here.
     *
     * @return void
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function processUpdates()
    {
        $this->moveField('tt_address', 'tx_odsosm_lon', 'longitude');
        $this->moveField('tt_address', 'tx_odsosm_lat', 'latitude');
    }

    /**
     * Move database field values
     *
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function moveField($table, $from, $to)
    {
        $title = 'Update table "' . $table . '": Move field from "' . $from . '" to "' . $to . '"';
        $status = \TYPO3\CMS\Core\Messaging\FlashMessage::OK;

        $connection = $this->getConnection();
        $queryBuilder = $this->getQueryBuilder();
        $fieldsInDatabase = $connection->executeQuery('show columns from ' . $table)->fetchColumn(0);


        if (is_array($fieldsInDatabase[$from])) {
            $rows = (array)$queryBuilder
                ->select('*')
                ->from($table)
                ->where($from . '>""')
                ->execute()
                ->fetchAll();

            $moved = [];
            foreach ($rows as $row) {
                $moved[] = $row['uid'];
                $connection->update(
                    'tt_address',
                    [
                        $from => null,
                        $to => $row[$from]
                    ],
                    [
                        'uid=' . $row['uid']
                    ]
                );
            }
            if ($moved) {
                $message = 'Move data in item ' . implode(',', $moved);
            } else {
                $message = 'No data to move.';
            }
        } else {
            $message = 'Field does not exist.';
        }

        $this->messageArray[] = [$status, $title, $message];
        return $status;
    }

    /**
     * @return Connection
     */
    public function getConnection(): Connection
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_address');
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_address');
        $queryBuilder->getRestrictions()->removeAll();
        return $queryBuilder;
    }
}
