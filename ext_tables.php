<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$extensionName = strtolower(\TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($_EXTKEY));

// manageoffers
$pluginName = strtolower('manageoffers');
$pluginSignature = $extensionName.'_'.$pluginName;
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'manageoffers',
	'Angebote auflisten'
);
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,pages';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:'.$_EXTKEY . '/Configuration/FlexForms/manageoffers.xml');

// showoffer
$pluginName = strtolower('showoffer');
$pluginSignature = $extensionName.'_'.$pluginName;
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    $_EXTKEY,
    'showoffer',
    'Angebot anzeigen'
);
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,pages';
// $TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
// \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:'.$_EXTKEY . '/Configuration/FlexForms/showoffer.xml');

// showofferinfo
$pluginName = strtolower('showofferinfo');
$pluginSignature = $extensionName.'_'.$pluginName;
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    $_EXTKEY,
    'showofferinfo',
    'Angebot Informationen'
);
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,pages';
// $TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
// \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:'.$_EXTKEY . '/Configuration/FlexForms/showofferinfo.xml');

// showoffercontact
$pluginName = strtolower('showoffercontact');
$pluginSignature = $extensionName.'_'.$pluginName;
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    $_EXTKEY,
    'showoffercontact',
    'Angebot Kontakt'
);
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,pages';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:'.$_EXTKEY . '/Configuration/FlexForms/showoffercontact.xml');

// showcouncil
$pluginName = strtolower('showcouncil');
$pluginSignature = $extensionName.'_'.$pluginName;
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'showcouncil',
	'Gemeinderat Kontakt'
);
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,pages';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:'.$_EXTKEY . '/Configuration/FlexForms/showcouncil.xml');

// showoffercategories
$pluginName = strtolower('showoffercategories');
$pluginSignature = $extensionName.'_'.$pluginName;
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    $_EXTKEY,
    'showoffercategories',
    'Angebot Kategorien Liste'
);
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,pages';
// $TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
// \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:'.$_EXTKEY . '/Configuration/FlexForms/showoffercategories.xml');

// add TS
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Angebote Verwalten');

// add tx_cal_category: own_pid and sorting
$tempColumns = Array (
    "own_pid" => Array (
		'exclude' => 1,
		'label' => 'Kategorieseite dieser Kategorie',
		'config' => Array (
			'type' => 'group',
			'internal_type' => 'db',
			'allowed' => 'pages',
			'size' => '1',
			'maxitems' => '1',
			'minitems' => '0',
			'show_thumbs' => '1',
			'wizards' => Array (
				'suggest' => array (
					'type' => 'suggest',
					'default' => $wizzardSuggestDefaults
				)
			)
		)
	),
    "sorting" => Array (
        "exclude" => 1,
        "label" => "Sortierung",
        "config" => Array (
            "type" => "input",
            "size" => "30",
            "eval" => "trim",
        )
    ),
);
t3lib_div::loadTCA('tx_cal_category');
t3lib_extMgm::addTCAcolumns('tx_cal_category',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('tx_cal_category','own_pid,sorting;;;;','','after:single_pid');

// add tx_cal_organizer socials
$tempColumns = Array (
    "facebook" => Array (
        "exclude" => 1,
        "label" => "Facebook",
        "config" => Array (
            "type" => "input",
            "size" => "30",
            "eval" => "trim",
        )
    ),
    "twitter" => Array (
        "exclude" => 1,
        "label" => "Twitter",
        "config" => Array (
            "type" => "input",
            "size" => "30",
            "eval" => "trim",
        )
    ),
);
t3lib_div::loadTCA('tx_cal_organizer');
t3lib_extMgm::addTCAcolumns('tx_cal_organizer',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('tx_cal_organizer','facebook, twitter;;;;','','after:link');

// add tx_cal_event multiple dates description
$tempColumns = Array (
    "dates_description" => Array (
        "exclude" => 1,
        "label" => "Beschreibung für wiederholende Events",
        "config" => Array (
            'type' => 'text',
            'cols' => '40',
            'rows' => '6',
        )
    ),
);
t3lib_div::loadTCA('tx_cal_event');
t3lib_extMgm::addTCAcolumns('tx_cal_event',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('tx_cal_event','dates_description;;;;','','after:rdate_type');

?>