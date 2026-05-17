<?php

namespace MediaWiki\Extension\ContentProvisioning\Special;

use MediaWiki\Html\Html;
use MediaWiki\SpecialPage\SpecialPage;

class ContentProvisioning extends SpecialPage {

	public function __construct() {
		parent::__construct( 'ContentProvisioning' );
	}

	/** @inheritDoc */
	public function getRestriction(): string {
		// TODO: Fix permissions here, for some reason permission 'contentprovisioning-viewspecialpage' does not work
		//return 'contentprovisioning-viewspecialpage';
		return 'edit';
	}

	/**
	 * @inheritDoc
	 */
	public function execute( $subPage ) {
		parent::execute( $subPage );

		$this->getOutput()->enableOOUI();
		$this->getOutput()->addModules( 'ext.contentProvisioning.special.overview' );

		$this->getOutput()->addHTML( Html::element( 'div', [ 'id' => 'contentProvisioning-overview' ] ) );
	}
}
