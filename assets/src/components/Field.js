import React from '@wordpress/element';
import PropTypes from 'prop-types';
import { __experimentalGetSettings } from '@wordpress/date';
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

export default function Field( { date, onChange } ) {
	return (
		<DateTimePicker
			key="unpublish-date-time-picker"
			currentDate={ date }
			onChange={ onChange }
			is12Hour={ is12HourTime() }
		/>
	);
}

Field.propTypes = {
	date: PropTypes.number.isRequired,
	onChange: PropTypes.func.isRequired,
};
