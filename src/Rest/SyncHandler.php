<?php

namespace MediaWiki\Extension\ContentProvisioning\Rest;

use MediaWiki\Context\RequestContext;
use MediaWiki\Language\FormatterFactory;
use MediaWiki\Rest\Handler;
use MWStake\MediaWiki\Component\ContentProvisioner\EntitySync\WikiPageSync;
use Wikimedia\ParamValidator\ParamValidator;

class SyncHandler extends Handler {
	use UnmaskPageTitleTrait;

	/**
	 * @var WikiPageSync
	 */
	private $wikiPageSync;

	/**
	 * @inheritDoc
	 */
	public function getParamSettings() {
		return [
			'pagePrefixedText' => [
				static::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true
			]
		];
	}

	/**
	 * @param WikiPageSync $wikiPageSync
	 * @param FormatterFactory $formatterFactory
	 */
	public function __construct(
		WikiPageSync $wikiPageSync,
		private readonly FormatterFactory $formatterFactory
	) {
		$this->wikiPageSync = $wikiPageSync;
	}

	/**
	 * @inheritDoc
	 */
	public function execute() {
		$params = $this->getValidatedParams();

		$pagePrefixedText = $this->unmaskPageTitle( $params['pagePrefixedText'] );

		$status = $this->wikiPageSync->sync( $pagePrefixedText );
		if ( !$status->isOK() ) {
			$formatter = $this->formatterFactory->getStatusFormatter( RequestContext::getMain() );
			$res = [
				'success' => false,
				'error' => $formatter->getWikiText( $status ),
			];
		} else {
			$res = [
				'success' => true
			];
		}

		return $this->getResponseFactory()->createJson( $res );
	}
}
