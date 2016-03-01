<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'TYPO3.' . $_EXTKEY,
	'manageoffers',
	array(
		'Offer' => 'list',
	),
	// non-cacheable actions
	array(
		'Offer' => 'list',
	)
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'TYPO3.' . $_EXTKEY,
	'showoffer',
	array(
		'Offer' => 'show',
	),
	// non-cacheable actions
	array(
		'Offer' => 'show',
	)
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'TYPO3.' . $_EXTKEY,
	'showofferinfo',
	array(
		'Offer' => 'showinfo',
	),
	// non-cacheable actions
	array(
		'Offer' => 'showinfo',
	)
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'TYPO3.' . $_EXTKEY,
	'showoffercontact',
	array(
		'Offer' => 'showcontact',
	),
	// non-cacheable actions
	array(
		'Offer' => 'showcontact',
	)
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'TYPO3.' . $_EXTKEY,
	'showcouncil',
	array(
		'Offer' => 'showcouncilcontact',
	),
	// non-cacheable actions
	array(
		'Offer' => 'showcouncilcontact',
	)
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'TYPO3.' . $_EXTKEY,
	'showoffercategories',
	array(
		'Offer' => 'showcategories',
	),
	// non-cacheable actions
	array(
		'Offer' => 'showcategories',
	)
);

?>