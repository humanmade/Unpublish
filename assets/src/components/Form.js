import React from '@wordpress/element';
import PropTypes from 'prop-types';
import { getDate, isInTheFuture } from '@wordpress/date';
import { compose } from '@wordpress/compose';
import { Dropdown } from '@wordpress/components';
import { withDispatch, withSelect } from '@wordpress/data';

import Field from './Field';
import Label from './Label';

const CONTENT_CLASSNAME = 'edit-post-post-unpublish__dialog';

export function Form( { date, onUpdateDate } ) {
	return (
		<Dropdown
			position="bottom left"
			contentClassName={ CONTENT_CLASSNAME }
			renderToggle={ props => <Label className={ CONTENT_CLASSNAME } date={ date } { ...props } /> }
			renderContent={ () => <Field date={ date } onChange={ onUpdateDate } /> }
		/>
	);
}

Form.propTypes = {
	date: PropTypes.number.isRequired,
	onUpdateDate: PropTypes.func.isRequired,
};

function addCurrentValue( select ) {
	const { getEditedPostAttribute } = select( 'core/editor' );
	const { unpublish_timestamp: date } = getEditedPostAttribute( 'meta' );

	return {
		date: date * 1000,
	};
}

function isDateValid( date ) {
	return isInTheFuture( date );
}

function addUpdater( dispatch ) {
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
}

const ComposedForm = compose( [ withSelect( addCurrentValue ), withDispatch( addUpdater ) ] )( Form );

export default ComposedForm;
