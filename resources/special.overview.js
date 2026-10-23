$( function () {
	const $container = $( '#contentProvisioning-overview' );
	if ( $container.length === 0 ) {
		return;
	}

	const panel = new contentProvisioning.ui.panel.ContentProvisioningOverview( {
		expanded: false
	} );

	$container.append( panel.$element );

	panel.connect( this, {
		sync: function ( pagePrefixedText ) {
			contentProvisioning._internal._getApi().done( ( api ) => { // eslint-disable-line no-underscore-dangle
				api.forceSync( pagePrefixedText ).done( ( ( response ) => {
					if ( !response.hasOwnProperty( 'success' ) ) {
						OO.ui.alert( mw.msg( 'contentprovisioning-ui-error-generic' ) );
					}

					panel.store.reload();
					window.panel = panel;
					mw.notify(
						mw.msg( 'contentprovisioning-ui-sync-success', pagePrefixedText ),
						{ type: 'success' }
					);
				} ) ).fail( ( error ) => {
					OO.ui.alert( error );
				} );
			} );
		},
		diff: function ( pagePrefixedText ) {
			contentProvisioning._internal._getApi().done( ( api ) => { // eslint-disable-line no-underscore-dangle
				api.getDiff( pagePrefixedText ).done( ( response ) => {
					let diffHtml = response.diffHtml;

					if ( !response.hasOwnProperty( 'diffHtml' ) ) {
						if ( response.hasOwnProperty( 'error' ) ) {
							diffHtml = response.error;
						} else {
							return;
						}
					}

					const windowManager = new OO.ui.WindowManager();
					$( document.body ).append( windowManager.$element );

					const dialog = new contentProvisioning.ui.dialog.ContentDiff( diffHtml );
					windowManager.addWindows( [ dialog ] );
					windowManager.openWindow( dialog );
				} ).fail( ( error ) => {
					OO.ui.alert( error );
				} );
			} );
		}
	} );
} );
