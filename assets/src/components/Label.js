import React from '@wordpress/element';
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
