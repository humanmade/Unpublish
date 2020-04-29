import React from '@wordpress/element';
import PropTypes from 'prop-types';
import { __experimentalGetSettings, getDate, isInTheFuture } from '@wordpress/date';
import { withDispatch } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { DateTimePicker } from '@wordpress/components';

function is12HourTime() {
	const { formats } = __experimentalGetSettings();
	const { time } = formats;
	// To know if the current timezone is a 12 hour time with look for "a" in the time format
	// We also make sure this a is not escaped by a "/"
	const result = /a(?!\\)/i.test(
		time
			.toLowerCase() // Test only the lower case a
			.replace( /\\\\/g, '' ) // Replace "//" with empty strings
			.split( '' )
			.reverse()
			.join( '' ), // Reverse the string and test for "a" not followed by a slash
	);

	return result;
}

function isDateValid( date ) {
	return isInTheFuture( date );
}

export function Field( { date, onUpdateDate } ) {
	return (
		<DateTimePicker
			key="unpublish-date-time-picker"
			currentDate={ date }
			onChange={ onUpdateDate }
			is12Hour={ is12HourTime() }
		/>
	);
}

Field.propTypes = {
	date: PropTypes.number.isRequired,
	onUpdateDate: PropTypes.func.isRequired,
};

const FieldWithData = compose( [
	withDispatch( dispatch => {
		const { editPost } = dispatch( 'core/editor' );

		return {
			onUpdateDate( date ) {
				if ( ! isDateValid( date ) ) {
					return;
				}

				const timestamp = getDate( date ).getTime() / 1000;

				editPost( {
					meta: {
						unpublish_timestamp: timestamp,
					},
				} );
			},
		};
	} ),
] )( Field );

export default FieldWithData;
