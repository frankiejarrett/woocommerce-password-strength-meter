/* globals pwsL10n, wp */
jQuery( function( $ ) {

	if ( $( 'input#password_1' ).length && $( 'input#password_2' ).length ) {
		var $password1 = $( 'input#password_1' ),
		    $password2 = $( 'input#password_2' );
	} else {
		var $password1 = $( 'input#account_password' ),
		    $password2 = $password1;
	}

	$password2.parent().after( '<p id="wc-pass-strength-result"></p>' );

	var $checkbox = $( 'input#createaccount' ),
	    $result   = $( '#wc-pass-strength-result' );

	function check_pass_strength() {
		var pass1 = $password1.val(),
		    pass2 = $password2.val();

		$result.removeClass( 'short mismatch bad good strong' );
		$password1.removeClass( 'short mismatch bad good strong' );
		$password2.removeClass( 'short mismatch bad good strong' );

		if ( ! pass1 ) {
			$result.html( pwsL10n.empty );
			return;
		}

		var strength = wp.passwordStrength.meter( pass1, wp.passwordStrength.userInputBlacklist(), pass2 );

		switch ( strength ) {
			case 2:
				$result.addClass( 'bad' ).html( pwsL10n.bad );
				$password1.addClass( 'bad' );
				$password2.addClass( 'bad' );
				break;
			case 3:
				$result.addClass( 'good' ).html( pwsL10n.good );
				$password1.addClass( 'good' );
				$password2.addClass( 'good' );
				break;
			case 4:
				$result.addClass( 'strong' ).html( pwsL10n.strong );
				$password1.addClass( 'strong' );
				$password2.addClass( 'strong' );
				break;
			case 5:
				$result.addClass( 'mismatch' ).html( pwsL10n.mismatch );
				$password1.addClass( 'mismatch' );
				$password2.addClass( 'mismatch' );
				break;
			default:
				$result.addClass( 'short' ).html( pwsL10n['short'] );
				$password1.addClass( 'short' );
				$password2.addClass( 'short' );
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

		$password1.on( 'input propertychange', function() {
			if ( '' === $( this ).val() ) {
				$result.hide();
			} else {
				$result.show();
			}
		});

		$password1.val( '' ).on( 'input propertychange', check_pass_strength );
		$password2.val( '' ).on( 'input propertychange', check_pass_strength );

	});

});
