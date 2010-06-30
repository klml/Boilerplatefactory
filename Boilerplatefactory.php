<?php
# Alert the user that this is not a valid entry point to MediaWiki if they try to access the special pages file directly.
if (!defined('MEDIAWIKI')) {
        echo <<<EOT
To install my extension, put the following line in LocalSettings.php:
require_once( "\$IP/extensions/Boilerplatefactory/Boilerplatefactory.php" );
EOT;
        exit( 1 );
}
 
$wgExtensionCredits['specialpage'][] = array(
	'name' => 'Boilerplatefactory',
	'author' => 'klml',
	'url' => 'http://www.mediawiki.org/wiki/Extension:Boilerplatefactory',
	'description' => 'Allows a boilerplate to be selected from Categories in an extra Specialpage bevor editing new pages.',
	'descriptionmsg' => 'boilerplatefactory-desc',
	'version' => '0.0.2',
);
 
$dir = dirname(__FILE__) . '/';
$includable = true ;
$wgAutoloadClasses['Boilerplatefactory'] = $dir . 'Boilerplatefactory_body.php';
$wgExtensionMessagesFiles['Boilerplatefactory'] = $dir . 'Boilerplatefactory.i18n.php';
$wgSpecialPages['Boilerplatefactory'] = 'Boilerplatefactory'; # Let MediaWiki know about your new special page.

$wgBoilerplatefactorycategorie = 'Category:Boilerplatefactory' ; ## rootcategorie overrideable by LocalSettings.php

######## [[Manual:Hooks/EditFormPreloadText]]
if (isset($_GET['blrchc'])) {
$wgHooks['EditFormPreloadText'][] = array('prefill');
function prefill(&$textbox, &$title) {
	$title_str = $title->getText();
	$boilerchoice = $_GET['blrchc'];
	if (isset ($_GET['blrsubst']) ) { $blrsubst = 'subst' ;} ;
	if (isset ($_GET['blrnotoc']) ) { $blrnotoc = "\n__NOTOC__" ;} ;
	if (isset ($_GET['blrndtscton']) ) { $blrndtscton = "\n__NOEDITSECTION__" ;} ;
	if (isset ($_GET['blrnoNSh2']) ) { $blrnoNSh2 = '/^(.*?):/' ;} ; # remove NS; will delete Lemma with ':'

	foreach ( $boilerchoice as $boilersingle ) {
		if (isset ($blrnoNSh2) ) { $boilersingleH = preg_replace($blrnoNSh2, '', $boilersingle) ; } else { $boilersingleH = $boilersingle ;};
		$textbox = $textbox."== $boilersingleH ==\n{{".$blrsubst.":"."$boilersingle}}\n\n" ;
		} ;
		$textbox = $textbox.$blrnotoc.$blrndtscton ;
	return true;
	}
}
