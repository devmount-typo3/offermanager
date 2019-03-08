<?php
namespace TYPO3\Offermanager\Domain\Repository;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 *
 *
 * @package offermanager
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class OfferRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

    /**
     * returns all events
     *
     * @param  integer $limit limits count of events
     * @param  integer $cuid uid of cal category
     *
     * @return array events
     */
    public function findAllOffers($limit, $cuid=-1) {
        // get conf for non-events (offers without dates)
        $conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['offermanager']);
        $nocalender = $conf['nocalender'];
        // get current date
        $date = date("Ymd");

        // >> handle single events
        // $query = $this->createQuery();
        // $query->getQuerySettings()->setReturnRawQueryResult(TRUE);
        // display only category events
        if ($cuid >= 0) {
            // $sql =
            //     "SELECT
            //         e.uid,
            //         e.start_date,
            //         e.end_date,
            //         e.start_time,
            //         e.end_time,
            //         e.title,
            //         e.teaser,
            //         e.description,
            //         e.dates_description
            //     FROM
            //         tx_cal_event e
            //     LEFT JOIN tx_cal_event_category_mm m ON e.uid = m.uid_local
            //     LEFT JOIN tx_cal_category c ON c.uid = m.uid_foreign
            //     WHERE
            //         c.uid = ? AND
            //         e.hidden = ? AND
            //         e.deleted = ? AND
            //         e.freq = ? AND
            //         e.start_date >= ? AND
            //         e.calendar_id <> ?
            //     ORDER BY
            //         e.start_date ASC";
            // $query->statement($sql, array($cuid, 0, 0, 'none', $date, $nocalender));
            // $result_single = $query->execute();
            $result_single = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
              'e.uid,e.start_date,e.end_date,e.start_time,e.end_time,e.title,e.teaser,e.description,e.dates_description',
              'tx_cal_event e LEFT JOIN tx_cal_event_category_mm m ON e.uid = m.uid_local LEFT JOIN tx_cal_category c ON c.uid = m.uid_foreign',
              'c.uid = ' . intval($cuid) . ' AND e.hidden = 0 AND e.deleted = 0 AND e.freq = "none" AND e.start_date >= "' . $date . '" AND e.calendar_id <> ' . intval($nocalender),
              '',
              'e.start_date ASC'
            );
        }
        // display all events
        else {
            // $sql =
            //     "SELECT
            //         e.uid,
            //         e.start_date,
            //         e.end_date,
            //         e.start_time,
            //         e.end_time,
            //         e.title,
            //         e.teaser,
            //         e.description,
            //         e.dates_description
            //     FROM
            //         tx_cal_event e
            //     WHERE
            //         e.hidden = ? AND
            //         e.deleted = ? AND
            //         e.freq = ? AND
            //         e.start_date >= ? AND
            //         e.calendar_id <> ? ?
            //     ORDER BY
            //         e.start_date ASC";
            // $query->statement($sql, array(0, 0, 'none', $date, $nocalender, $limitSql));
            // $result_single = $query->execute();
            $result_single = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
              'e.uid,e.start_date,e.end_date,e.start_time,e.end_time,e.title,e.teaser,e.description,e.dates_description',
              'tx_cal_event e',
              'e.hidden = 0 AND e.deleted = 0 AND e.freq = "none" AND e.start_date >= "' . $date . '" AND e.calendar_id <> ' . intval($nocalender),
              '',
              'e.start_date ASC',
              ($limit > 0) ? $limit : ''
            );
        }

        // >> handle repeated events
        // $query = $this->createQuery();
        // $query->getQuerySettings()->setReturnRawQueryResult(TRUE);
        // display only category events
        if ($cuid >= 0) {
            // $sql =
            //     "SELECT
            //         e.uid,
            //         e.start_date,
            //         e.end_date,
            //         e.start_time,
            //         e.end_time,
            //         e.title,
            //         e.teaser,
            //         e.description,
            //         e.dates_description,
            //         e.freq,
            //         e.until,
            //         e.cnt,
            //         e.intrval,
            //         e.rdate
            //     FROM
            //         tx_cal_event e
            //     LEFT JOIN tx_cal_event_category_mm m ON e.uid = m.uid_local
            //     LEFT JOIN tx_cal_category c ON c.uid = m.uid_foreign
            //     WHERE
            //         c.uid = ? AND
            //         e.hidden = ? AND
            //         e.deleted = ? AND
            //         (e.freq <> ? OR e.rdate <> ?) AND
            //         e.calendar_id <> ?
            //     ORDER BY
            //         e.start_date ASC";
            // $query->statement($sql, array($cuid, 0, 0, 'none', 'null', $nocalender));
            // $result_repeated_temp = $query->execute();
            $result_repeated_temp = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
              'e.uid,e.start_date,e.end_date,e.start_time,e.end_time,e.title,e.teaser,e.description,e.dates_description,e.freq,e.until,e.cnt,e.intrval,e.rdate',
              'tx_cal_event e LEFT JOIN tx_cal_event_category_mm m ON e.uid = m.uid_local LEFT JOIN tx_cal_category c ON c.uid = m.uid_foreign',
              'c.uid = ' . intval($cuid) . ' AND e.hidden = 0 AND e.deleted = 0 AND (e.freq <> "none" OR e.rdate <> "null") AND e.calendar_id <> ' . intval($nocalender),
              '',
              'e.start_date ASC'
            );
        }
        // display all events
        else {
            // $sql =
            //     "SELECT
            //         e.uid,
            //         e.start_date,
            //         e.end_date,
            //         e.start_time,
            //         e.end_time,
            //         e.title,
            //         e.teaser,
            //         e.description,
            //         e.dates_description,
            //         e.freq,
            //         e.until,
            //         e.cnt,
            //         e.intrval,
            //         e.rdate
            //     FROM
            //         tx_cal_event e
            //     WHERE
            //         e.hidden = ? AND
            //         e.deleted = ? AND
            //         (e.freq <> ? OR e.rdate <> ?) AND
            //         e.calendar_id <> ? ?
            //     ORDER BY
            //         e.start_date ASC";
            // $query->statement($sql, array(0, 0, 'none', 'null', $nocalender, $limitSql));
            // $result_repeated_temp = $query->execute();
            $result_repeated_temp = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
              'e.uid,e.start_date,e.end_date,e.start_time,e.end_time,e.title,e.teaser,e.description,e.dates_description,e.freq,e.until,e.cnt,e.intrval,e.rdate',
              'tx_cal_event e',
              'e.hidden = 0 AND e.deleted = 0 AND (e.freq <> "none" OR e.rdate <> "null") AND e.calendar_id <> ' . intval($nocalender),
              '',
              'e.start_date ASC',
              ($limit > 0) ? $limit : ''
            );
        }
        
        // handle repeatance - check if repeatance is in future
        $result_repeated = array();
        foreach ($result_repeated_temp as $key => $event) {
            if ($event['until'] and $event['until'] < $date) {
                continue;
            }
            $nextdate = '';
            // check freq
            // TODO: order?
            $count = $event['cnt'] > 0 ? $event['cnt'] : 99;
            $interval = $event['intrval'] > 1 ? $event['intrval'] : 1;
            for ($i=1; $i <= $count; $i++) {
                $freqdate = date('Ymd', strtotime($event['start_date'] . ' +' . $i*$interval . ' ' . $event['freq']));
                // if (($event['until'] and $event['until'] >= $freqdate) or !$event['until']) {
                $event['rdate'] .= $event['rdate'] != null ? ',' . $freqdate : $freqdate;
                // }
            }
            // check rdate
            if ($event['rdate']) {
                foreach (explode(',', $event['rdate']) as $rdate) {
                    if ($rdate >= $date) {
                        $nextdate = $rdate;
                        break;
                    }
                }
            }
            // if repeated date in future,add event to list
            if ($nextdate) {
                $event['start_date'] = $nextdate;
                $result_repeated[] = $event;
            }
        }

        // >> handle no date offers
        // $query = $this->createQuery();
        // $query->getQuerySettings()->setReturnRawQueryResult(TRUE);
        // display only category events
        if ($cuid >= 0) {
            // $sql =
            //     "SELECT
            //         e.uid,
            //         e.start_date,
            //         e.end_date,
            //         e.start_time,
            //         e.end_time,
            //         e.title,
            //         e.teaser,
            //         e.description,
            //         e.dates_description
            //     FROM
            //         tx_cal_event e
            //     LEFT JOIN tx_cal_event_category_mm m ON e.uid = m.uid_local
            //     LEFT JOIN tx_cal_category c ON c.uid = m.uid_foreign
            //     WHERE
            //         c.uid = ? AND
            //         e.hidden = ? AND
            //         e.deleted = ? AND
            //         e.calendar_id = ? ";
            // $query->statement($sql, array($cuid, 0, 0, $nocalender));
            // $result_nodate = $query->execute();
            $result_nodate = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
              'e.uid,e.start_date,e.end_date,e.start_time,e.end_time,e.title,e.teaser,e.description,e.dates_description',
              'tx_cal_event e LEFT JOIN tx_cal_event_category_mm m ON e.uid = m.uid_local LEFT JOIN tx_cal_category c ON c.uid = m.uid_foreign',
              'c.uid = ' . intval($cuid) . ' AND e.hidden = 0 AND e.deleted = 0 AND e.calendar_id = ' . intval($nocalender)
            );
        }
        // display all events
        else {
            // $sql =
            //     "SELECT
            //         e.uid,
            //         e.start_date,
            //         e.end_date,
            //         e.start_time,
            //         e.end_time,
            //         e.title,
            //         e.teaser,
            //         e.description,
            //         e.dates_description
            //     FROM
            //         tx_cal_event e
            //     WHERE
            //         e.hidden = ? AND
            //         e.deleted = ? AND
            //         e.calendar_id = ? ? ";
            // $query->statement($sql, array(0, 0, $nocalender, $limitSql));
            // $result_nodate = $query->execute();
            $result_nodate = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
              'e.uid,e.start_date,e.end_date,e.start_time,e.end_time,e.title,e.teaser,e.description,e.dates_description',
              'tx_cal_event e',
              'e.hidden = 0 AND e.deleted = 0 AND e.calendar_id = ' . intval($nocalender),
              '',
              '',
              ($limit > 0) ? $limit : ''
            );
        }

        // get complete result and sort by next occuring date
        $result = array_merge($result_single, $result_repeated);
        usort($result, $this->event_sorter('start_date'));
        // add images and categories
        foreach ($result as $key => $event) {
            // add first and second image
            $result[$key]['images'] = $this->getEventImages($event['uid']);
            // add categories
            $result[$key]['categories'] = $this->getEventCategories($event['uid']);
        }
        // add no date offers at the end of the list
        $result = array_merge($result, $result_nodate);
        
        // preprocess textfield contents
        
        return $result;
    }


    /**
     * returns list of existing categories
     *
     * @return array of categories
     */
    public function getCategoryList() {
        // get categories
        return $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
          'title, own_pid',
          'tx_cal_category',
          'deleted = 0 AND hidden = 0',
          '',
          'sorting ASC'
        );
    }

    /**
     * returns image data of given event
     *
     * @param  integer $uid uid of cal event
     *
     * @return array image data
     */
    public function getEventImages($uid) {
        $fileRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\FileRepository');
        $fileObjects = $fileRepository->findByRelation('tx_cal_event', 'image', $uid);

        // get imageobject information
        $files = array();
        foreach ($fileObjects as $key => $value) {
            $files[$key]['reference'] = $value->getReferenceProperties();
            $files[$key]['original'] = $value->getOriginalFile()->getProperties();
        }
        $imagedata = array_values($files);
        $firstimage = $imagedata[0];
        $secondimage = $imagedata[1];
        $firstimagepath = $firstimage['original']['identifier'];
        if ($firstimagepath) {
            $firstimagepath = 'fileadmin' . $firstimagepath;
        }
        $secondimagepath = $secondimage['original']['identifier'];
        if ($secondimagepath) {
            $secondimagepath = 'fileadmin' . $secondimagepath;
        }
        $images = array(
            'firstimagepath' => $firstimagepath,
            'secondimagepath' => $secondimagepath,
        );
        return $images;
    }

    /**
     * returns categories of given event
     *
     * @param  integer $uid uid of cal event
     *
     * @return array category names
     */
    public function getEventCategories($uid) {
        // get categories
        return $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
          'c.uid,c.title,c.own_pid',
          'tx_cal_event e LEFT JOIN tx_cal_event_category_mm m ON e.uid = m.uid_local LEFT JOIN tx_cal_category c ON c.uid = m.uid_foreign',
          'e.uid = ' . intval($uid)
        );
    }

    /**
     * returns event
     *
     * @param  integer $uid uid of cal event
     *
     * @return array event properties
     */
    public function getEvent($uid) {
        // get event
        $event = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow(
          'uid,start_date,end_date,start_time,end_time,title,teaser,description,dates_description,calendar_id,organizer,freq,until,cnt,intrval,rdate',
          'tx_cal_event',
          'uid = ' . intval($uid)
        );

        $categories = $this->getEventCategories($uid);
        $images = $this->getEventImages($uid);
        $event['categories'] = $categories;
        $event['images'] = $images;

        $event['title'] = html_entity_decode($event['title']);
        $event['dates_description'] = html_entity_decode($event['dates_description']);
        // handle date formats for fluid
        $event['start_date'] = date("d.m.Y", strtotime($event['start_date']));
        $event['end_date'] = date("d.m.Y", strtotime($event['end_date']));
        return $event;
    }

    /**
     * returns contact
     *
     * @param  integer $uid uid of cal event
     *
     * @return array contact properties
     */
    public function getContactFromEvent($uid) {
        // get contact from event
        $contact = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow(
          'o.uid,o.name,o.email,o.facebook,o.twitter, e.organizer',
          'tx_cal_organizer o INNER JOIN tx_cal_event e ON o.uid = e.organizer_id',
          'e.uid = ' . intval($uid)
        );

        // get image
        if ($contact) {
            $fileRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\FileRepository');
            $fileObjects = $fileRepository->findByRelation('tx_cal_organizer', 'image', $contact['uid']);
            $files = array();
            foreach ($fileObjects as $key => $value) {
                $files[$key]['reference'] = $value->getReferenceProperties();
                $files[$key]['original'] = $value->getOriginalFile()->getProperties();
            }
            $firstimage = reset($files);
            $firstimagepath = $firstimage['original']['identifier'];
            if ($firstimagepath) {
                $firstimagepath = 'fileadmin' . $firstimagepath;
            }
            $contact['imagepath'] = $firstimagepath;
        }

        return $contact;
    }

    /**
     * returns contact
     *
     * @param  integer $uid uid of cal event
     *
     * @return array contact properties
     */
    public function getContact($uid) {
        // get contact
        $contact = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow(
          'uid,name,description,email,facebook,twitter',
          'tx_cal_organizer',
          'uid = ' . intval($uid)
        );
        
        // get image
        if ($contact) {
            $fileRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\FileRepository');
            $fileObjects = $fileRepository->findByRelation('tx_cal_organizer', 'image', $contact['uid']);
            $files = array();
            foreach ($fileObjects as $key => $value) {
                $files[$key]['reference'] = $value->getReferenceProperties();
                $files[$key]['original'] = $value->getOriginalFile()->getProperties();
            }
            $firstimage = reset($files);
            $firstimagepath = $firstimage['original']['identifier'];
            if ($firstimagepath) {
                $firstimagepath = 'fileadmin' . $firstimagepath;
            }
            $contact['imagepath'] = $firstimagepath;
        }

        return $contact;
    }

    /**
     * sorts events by given attribute key
     *
     * @param  string $key
     *
     * @return boolean comparison
     */
    function event_sorter($key) {
        return function ($a, $b) use ($key) {
            return strnatcmp($a[$key], $b[$key]);
        };
    }
}
?>