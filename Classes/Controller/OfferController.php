<?php

namespace TYPO3\Offermanager\Controller;

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
class OfferController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	* offerRepository
	*
	* @var \TYPO3\Offermanager\Domain\Repository\OfferRepository
	* @inject
	*/
	protected $offerRepository;

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		// get conf
		$category = $this->settings['category'];
		$limit = $this->settings['limit'];

		$events = array();
		// check if category selected, if not: find all
		if ($category > 0) {
			$events = $this->offerRepository->findAllOffers(0, $category);
		} else {
			$events = $this->offerRepository->findAllOffers($limit);
		}
		// check if events exists
		if (!empty($events)) {
			$this->view->assign('events', $events);
			// $this->view->assign('count', count($events) . ' Angebote vorhanden');
			$this->view->assign('count', '');
		} else {
			$this->view->assign('count', 'Es existieren momentan keine Angebote in dieser Kategorie.');
		}
	}

	/**
	 * action show
	 *
	 * @return void
	 */
	public function showAction() {
		$cal = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP("tx_cal_controller");
		$event = $this->offerRepository->getEvent($cal['uid']);
		$this->view->assign('event', $event);
	}

	/**
	 * action showinfo
	 *
	 * @return void
	 */
	public function showinfoAction() {
		$cal = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP("tx_cal_controller");
		$event = $this->offerRepository->getEvent($cal['uid']);
        $conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['offermanager']);
        $nocalender = $conf['nocalender'];
		$this->view->assign('event', $event);
		$this->view->assign('showinfo', $event['calendar_id'] != $nocalender);
		$this->view->assign(
			'times',
			array(
				'start' => sprintf("%d:%02d", $event['start_time']/3600, $event['start_time']%3600*60/3600),
				'end' => sprintf("%d:%02d", $event['end_time']/3600, $event['end_time']%3600*60/3600),
			)
		);
	}

	/**
	 * action showcontact
	 *
	 * @return void
	 */
	public function showcontactAction() {
		// check single contact view
		if ($this->settings['contact'] != '') {
			$contact = $this->offerRepository->getContact($this->settings['contact']);
		} else {
			$cal = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP("tx_cal_controller");
			$contact = $this->offerRepository->getContactFromEvent($cal['uid']);
		}
		$this->view->assign('contact', $contact);
		$this->view->assign('heading', $this->settings['heading']);
	}

	/**
	 * action showcouncilcontact
	 *
	 * @return void
	 */
	public function showcouncilcontactAction() {
		// check council contact view
		if ($this->settings['contact'] != '') {
			$contact = $this->offerRepository->getContact($this->settings['contact']);
		}

		$this->view->assign('contact', $contact);
		$this->view->assign('name', array(
			'first' => substr($contact['name'], 0, strrpos($contact['name'], ' ')),
			'last' => strrchr($contact['name'], ' '),
		));
		$this->view->assign('title', $this->settings['title']);
		$this->view->assign('color', $this->settings['color']);
	}

	/**
	 * action showcategories
	 *
	 * @return void
	 */
	public function showcategoriesAction() {
		$this->view->assign('categories', $this->offerRepository->getCategoryList());
	}

}

?>