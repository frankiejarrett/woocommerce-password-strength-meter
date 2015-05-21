/* globals pwsL10n, wp */
jQuery( function( $ ) {

	var $checkbox = $( 'input#createaccount' ),
	    $password = $( 'input#account_password' ),
	    $result   = $( '#wc-pass-strength-result' );

	function check_pass_strength() {
		var pass1 = $password.val(),
		    pass2 = pass1;

		$result.removeClass( 'short bad good strong' );
		$password.removeClass( 'short bad good strong' );

		if ( ! pass1 ) {
			$result.html( pwsL10n.empty );
			return;
		}

		var strength = wp.passwordStrength.meter( pass1, wp.passwordStrength.userInputBlacklist(), pass2 );

		switch ( strength ) {
			case 2:
				$result.addClass( 'bad' ).html( pwsL10n.bad );
				$password.addClass( 'bad' );
				break;
			case 3:
				$result.addClass( 'good' ).html( pwsL10n.good );
				$password.addClass( 'good' );
				break;
			case 4:
				$result.addClass( 'strong' ).html( pwsL10n.strong );
				$password.addClass( 'strong' );
				break;
			default:
				$result.addClass( 'short' ).html( pwsL10n['short'] );
				$password.addClass( 'short' );
		}
	}

	$( document ).ready( function() {

		$( $checkbox ).on( 'change', function() {
			if ( $( this ).is( ':checked' ) ) {
				$result.show();
			} else {
				$result.hide();
			}
		});

		$password.on( 'input propertychange', function() {
			if ( '' === $( this ).val() ) {
				$result.hide();
			} else {
				$result.show();
			}
		});

		$password.val( '' ).on( 'input propertychange', check_pass_strength );

	});

});
