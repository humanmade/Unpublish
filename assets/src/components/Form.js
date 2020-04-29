import React from '@wordpress/element';
import { compose } from '@wordpress/compose';
import { withSelect } from '@wordpress/data';
import { Dropdown, Button } from '@wordpress/components';

import Field from './Field';
import Label from './Label';

const CONTENT_CLASSNAME = 'edit-post-post-unpublish__dialog';

function Toggle( { onToggle, isOpen } ) {
	return (
		<Button className={ CONTENT_CLASSNAME } onClick={ onToggle } aria-expanded={ isOpen } isLink>
			<Label />
		</Button>
	);
}

export function Form( { date } ) {
	return (
		<Dropdown
			position="bottom left"
			contentClassName={ CONTENT_CLASSNAME }
			renderToggle={ props => <Toggle { ...props } /> }
			renderContent={ () => <Field date={ date } /> }
		/>
	);
}

function addCurrentValue( select ) {
	const { getEditedPostAttribute } = select( 'core/editor' );
	const { unpublish_timestamp: date } = getEditedPostAttribute( 'meta' );

	return { date };
}

const FormWithData = compose( [ withSelect( addCurrentValue ) ] )( Form );

export default FormWithData;
