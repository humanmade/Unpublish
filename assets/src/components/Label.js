import React from '@wordpress/element';
import PropTypes from 'prop-types';
import { __ } from '@wordpress/i18n';
import { Button } from '@wordpress/components';
import { __experimentalGetSettings, dateI18n } from '@wordpress/date';

function getLabel( date ) {
	if ( ! date ) {
		return __( 'Schedule' );
	}

	const { formats } = __experimentalGetSettings();
	const { date: dateFormat, time: timeFormat } = formats;

	return dateI18n( `${ dateFormat } ${ timeFormat }`, date );
}

export default function Label( props ) {
	const { className, date, isOpen, onToggle } = props;

	return (
		<Button isLink className={ className } onClick={ onToggle } aria-expanded={ isOpen }>
			{ getLabel( date ) }
		</Button>
	);
}

Label.propTypes = {
	className: PropTypes.string.isRequired,
	date: PropTypes.number.isRequired,
	isOpen: PropTypes.bool.isRequired,
	onToggle: PropTypes.func.isRequired,
};
