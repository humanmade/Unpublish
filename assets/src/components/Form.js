import React from '@wordpress/element';
import PropTypes from 'prop-types';
import { compose } from '@wordpress/compose';
import { withSelect } from '@wordpress/data';
import { Dropdown, Button } from '@wordpress/components';

import Field from './Field';
import Label from './Label';

const CONTENT_CLASSNAME = 'edit-post-post-unpublish__dialog';

export function Form( { date } ) {
	return (
		<Dropdown
			position="bottom left"
			contentClassName={ CONTENT_CLASSNAME }
			renderToggle={ props => <Label className={ CONTENT_CLASSNAME } date={ date } { ...props } /> }
			renderContent={ () => <Field date={ date } /> }
		/>
	);
}

Form.propTypes = {
	date: PropTypes.number.isRequired,
};

function addCurrentValue( select ) {
	const { getEditedPostAttribute } = select( 'core/editor' );
	const { unpublish_timestamp: date } = getEditedPostAttribute( 'meta' );

	return { date };
}

const FormWithData = compose( [ withSelect( addCurrentValue ) ] )( Form );

export default FormWithData;
