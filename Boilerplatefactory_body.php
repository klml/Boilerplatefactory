<?php 
class Boilerplatefactory extends SpecialPage {
	function __construct() {
		parent::__construct( 'Boilerplatefactory' );
		wfLoadExtensionMessages('Boilerplatefactory');
	}
	function execute( $par ) {
		global $wgRequest, $wgOut, $wgBoilerplatefactorydefaultpage, $wgBoilerplatefactorycategorie ;
 
		$this->setHeaders();
 
		### new page name
		$wgOut->addHTML( 	' <form name="boilerplatefactory" id="boilerplatefactory" class="boilerplatefactory" action="/index.php" method="get">
		  <input name="title" class="createboxInput" value="'.$wgBoilerplatefactorydefaultpage.'" type="text"><br>' );
 
		### list of existing pages using as Boilerplate
		$params = new FauxRequest(array ( # [[API:Calling_internally]] down from rootcat
				'action' => 'query',
				'list' => 'categorymembers',
				'cmlimit' => 100 ,
				'cmtitle'  => $wgBoilerplatefactorycategorie ,
			));
		$api = new ApiMain($params);
		$api->execute();
		$wgBoilerplatefactorycategories = & $api->getResultData();
 
		foreach ( $wgBoilerplatefactorycategories[query][categorymembers] as $categ ) {
			$params = new FauxRequest(array ( # [[API:Calling_internally]] categories content
				'action' => 'query',
				'list' => 'categorymembers',
				'cmlimit' => 100 ,
				'cmtitle'  => $categ[title],
			));
			$api = new ApiMain($params);
			$api->execute();
			$boilerarray = & $api->getResultData();
 
			$wgOut->addHTML( "\n <div>\n  <h2 class=\"$categ[title]\" >$categ[title]</h2>\n" );
 
			foreach ($boilerarray[query][categorymembers] as $boiler) {
				$wgOut->addHTML( "  <input type='checkbox' name='blrchc[]' value='".$boiler[title]."' > <a href='http://".$_SERVER[HTTP_HOST]."/index.php?title=".$boiler[title]."' >".$boiler[title]."</a><br />\n");
			}
			$wgOut->addHTML( "</div>\n" );
		}
		### subst check send
		$wgOut->addHTML( " <fieldset><legend>".wfMsg( 'boilerplatefactory-setting')."</legend>\n
		  <input type='checkbox' name='blrsubst' value='' checked >".wfMsg( 'boilerplatefactory-subst')."<br />
		  <input type='checkbox' name='blrnotoc' value='' checked >".wfMsg( 'boilerplatefactory-notoc')."<br />
		  <input type='checkbox' name='blrndtscton' value='' checked >".wfMsg( 'boilerplatefactory-noeditsection')."<br />
		  <input type='checkbox' name='blrnoNSh2' value='' checked >".wfMsg( 'boilerplatefactory-nonamespaceh2')."<br />
		  <input name='action' value='edit' type='hidden' >\n  
		  <input name='create' class='createboxButton' value='".wfMsg( 'boilerplatefactory-send')."' type='submit'>\n </fieldset>\n </form>");
    return true;
	}
}
?>
