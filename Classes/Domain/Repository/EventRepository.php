<?php
namespace Roquin\RoqNewsevent\Domain\Repository;

/**
 * Copyright (c) 2012, ROQUIN B.V. (C), http://www.roquin.nl
 *
 * @author:         J. de Groot
 * @file:           EventRepository.php
 * @description:    News event Repository, extending functionality from the News Repository
 */

use GeorgRinger\News\Domain\Model\DemandInterface;
use GeorgRinger\News\Domain\Model\Dto\NewsDemand;
use GeorgRinger\News\Utility\ConstraintHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * @package TYPO3
 * @subpackage roq_newsevent
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class EventRepository extends \GeorgRinger\News\Domain\Repository\NewsRepository
{

    /**
     * Returns the constraint to determine if a news event is active or not (archived)
     *
     * @param QueryInterface $query
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface $constraint
     */
    protected function createIsActiveConstraint(QueryInterface $query)
    {
        /** @var $constraint \TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface */
        $constraint = null;
        $timestamp = time(); // + date('Z');

        $constraint = $query->logicalOr(
        // future events:
            $query->greaterThan('tx_roqnewsevent_startdate + tx_roqnewsevent_starttime', $timestamp),
            // current multiple day events:
            $query->logicalAnd(
                $query->lessThan('tx_roqnewsevent_startdate + tx_roqnewsevent_starttime', $timestamp),
                $query->greaterThan('tx_roqnewsevent_enddate + tx_roqnewsevent_endtime', $timestamp)
            ),
            // current single day events:
            $query->logicalAnd(
                $query->lessThan('tx_roqnewsevent_startdate + tx_roqnewsevent_starttime', $timestamp),
                $query->greaterThan('tx_roqnewsevent_startdate + tx_roqnewsevent_endtime', $timestamp),
                $query->equals('tx_roqnewsevent_enddate', 0)
            ),
            // current single day event without time:
            $query->logicalAnd(
                $query->greaterThan('tx_roqnewsevent_startdate + 86399', $timestamp),
                $query->equals('tx_roqnewsevent_starttime', 0),
                $query->equals('tx_roqnewsevent_enddate', 0),
                $query->equals('tx_roqnewsevent_endtime', 0)
            )
        );

        return $constraint;
    }

    /**
     * @param QueryInterface $query
     * @param DemandInterface $demand
     * @return array
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    protected function createConstraintsFromDemand(
        QueryInterface $query,
        DemandInterface $demand
    ) {
        /** @var NewsDemand $demand */
        $constraints = [];

        if ($demand->getCategories() && $demand->getCategories() !== '0') {
            $constraints[] = $this->createCategoryConstraint(
                $query,
                $demand->getCategories(),
                $demand->getCategoryConjunction(),
                $demand->getIncludeSubCategories()
            );
        }

        if ($demand->getAuthor()) {
            $constraints[] = $query->equals('author', $demand->getAuthor());
        }

        if ($demand->getTypes()) {
            $constraints['author'] = $query->in('type', $demand->getTypes());
        }

        // archived
        // TODO visol: Unclear why we overwrite the original archived constraint here without replacement
        if ($demand->getArchiveRestriction() == 'archived') {
            $constraints['archived'] = $query->logicalNot($this->createIsActiveConstraint($query));
            // non-archived (active)
        } elseif ($demand->getArchiveRestriction() == 'active') {
            $constraints['archived'] = $this->createIsActiveConstraint($query);
        }


        // archived
        if ($demand->getArchiveRestriction() == 'archived') {
            $constraints['archived'] = $query->logicalAnd(
                $query->lessThan('archive', $GLOBALS['EXEC_TIME']),
                $query->greaterThan('archive', 0)
            );
        } elseif ($demand->getArchiveRestriction() == 'active') {
            $constraints['active'] = $query->logicalOr(
                $query->greaterThanOrEqual('archive', $GLOBALS['EXEC_TIME']),
                $query->equals('archive', 0)
            );
        }

        // Time restriction greater than or equal
        $timeRestrictionField = $demand->getDateField();
        $timeRestrictionField = (empty($timeRestrictionField)) ? 'datetime' : $timeRestrictionField;

        if ($demand->getTimeRestriction()) {
            $timeLimit = ConstraintHelper::getTimeRestrictionLow($demand->getTimeRestriction());

            $constraints['timeRestrictionGreater'] = $query->greaterThanOrEqual(
                $timeRestrictionField,
                $timeLimit
            );
        }

        // Time restriction less than or equal
        if ($demand->getTimeRestrictionHigh()) {
            $timeLimit = ConstraintHelper::getTimeRestrictionHigh($demand->getTimeRestrictionHigh());

            $constraints['timeRestrictionLess'] = $query->lessThanOrEqual(
                $timeRestrictionField,
                $timeLimit
            );
        }

        // top news
        if ($demand->getTopNewsRestriction() == 1) {
            $constraints[] = $query->equals('istopnews', 1);
        } elseif ($demand->getTopNewsRestriction() == 2) {
            $constraints[] = $query->equals('istopnews', 0);
        }

        // storage page
        if ($demand->getStoragePage() != 0) {
            $pidList = GeneralUtility::intExplode(',', $demand->getStoragePage(), true);
            $constraints[] = $query->in('pid', $pidList);
        }

        // month & year OR year only
        if ($demand->getYear() > 0) {
            if (is_null($demand->getDateField())) {
                throw new \InvalidArgumentException('No Datefield is set, therefore no Datemenu is possible!');
            }
            if ($demand->getMonth() > 0) {
                if ($demand->getDay() > 0) {
                    $begin = mktime(0, 0, 0, $demand->getMonth(), $demand->getDay(), $demand->getYear());
                    $end = mktime(23, 59, 59, $demand->getMonth(), $demand->getDay(), $demand->getYear());
                } else {
                    $begin = mktime(0, 0, 0, $demand->getMonth(), 1, $demand->getYear());
                    $end = mktime(23, 59, 59, ($demand->getMonth() + 1), 0, $demand->getYear());
                }
            } else {
                $begin = mktime(0, 0, 0, 1, 1, $demand->getYear());
                $end = mktime(23, 59, 59, 12, 31, $demand->getYear());
            }
            $constraints[] = $query->logicalAnd(
                $query->greaterThanOrEqual($demand->getDateField(), $begin),
                $query->lessThanOrEqual($demand->getDateField(), $end)
            );
        }

        // Tags
        $tags = $demand->getTags();
        if ($tags && is_string($tags)) {
            $tagList = explode(',', $tags);

            $subConstraints = [];
            foreach ($tagList as $singleTag) {
                $subConstraints[] = $query->contains('tags', $singleTag);
            }
            if (count($subConstraints) > 0) {
                $constraints['tags'] = $query->logicalOr($subConstraints);
            }
        }

        // Search
        $searchConstraints = $this->getSearchConstraints($query, $demand);
        if (!empty($searchConstraints)) {
            $constraints[] = $query->logicalAnd($searchConstraints);
        }

        // Exclude already displayed
        if ($demand->getExcludeAlreadyDisplayedNews() && isset($GLOBALS['EXT']['news']['alreadyDisplayed']) && !empty($GLOBALS['EXT']['news']['alreadyDisplayed'])) {
            $constraints['excludeAlreadyDisplayedNews'] = $query->logicalNot(
                $query->in(
                    'uid',
                    $GLOBALS['EXT']['news']['alreadyDisplayed']
                )
            );
        }

        // Hide id list
        $hideIdList = $demand->getHideIdList();
        if ($hideIdList) {
            $constraints['excludeAlreadyDisplayedNews'] = $query->logicalNot(
                $query->in(
                    'uid',
                    GeneralUtility::intExplode(',', $hideIdList)
                )
            );
        }

        // events only
        $constraints[] = $query->logicalAnd($query->equals('tx_roqnewsevent_is_event', 1));

        // the event must have an event start date
        $constraints[] = $query->logicalAnd(
            $query->logicalNot(
                $query->equals('tx_roqnewsevent_startdate', 0)
            )
        );

        // Clean not used constraints
        foreach ($constraints as $key => $value) {
            if (is_null($value)) {
                unset($constraints[$key]);
            }
        }

        return $constraints;
    }
}
