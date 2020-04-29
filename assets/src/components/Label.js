import React from '@wordpress/element';
import PropTypes from 'prop-types';
import { __ } from '@wordpress/i18n';
import { Button } from '@wordpress/components';

function getLabel() {
	return __( 'Schedule' );
}

export default function Label( props ) {
	const { className, isOpen, onToggle } = props;

	return (
		<Button isLink className={ className } onClick={ onToggle } aria-expanded={ isOpen }>
			{ getLabel() }
		</Button>
	);
}

Label.propTypes = {
	className: PropTypes.string.isRequired,
	isOpen: PropTypes.bool.isRequired,
	onToggle: PropTypes.func.isRequired,
};
