import React from '@wordpress/element';
import PropTypes from 'prop-types';
import { getDate, isInTheFuture } from '@wordpress/date';
import { compose } from '@wordpress/compose';
import { Button, Dropdown } from '@wordpress/components';
import { withDispatch, withSelect } from '@wordpress/data';

import Field from './Field';
import Label from './Label';

const CONTENT_CLASSNAME = 'edit-post-post-unpublish__dialog';

export function Form( { date, onUpdateDate } ) {
	return (
		<>
			<Dropdown
				position="bottom left"
				contentClassName={ CONTENT_CLASSNAME }
				renderToggle={ props => <Label className={ CONTENT_CLASSNAME } date={ date } { ...props } /> }
				renderContent={ () => <Field date={ date } onChange={ onUpdateDate } /> }
			/>
			{ date ? (
				<Button isLink onClick={ () => onUpdateDate( 0 ) }>
					Clear
				</Button>
			) : null }
		</>
	);
}

Form.propTypes = {
	date: PropTypes.number.isRequired,
	onUpdateDate: PropTypes.func.isRequired,
};

function addSelectors( select ) {
	const { getEditedPostAttribute } = select( 'core/editor' );
	const { unpublish_timestamp: date } = getEditedPostAttribute( 'meta' );
	const postDate = getEditedPostAttribute( 'date' );

	return {
		date: date * 1000,
		postDate: getDate( postDate ).getTime(),
	};
}

function isDateValid( date, postDate ) {
	return isInTheFuture( date ) && date > postDate;
}

function addUpdater( dispatch ) {
	const { editPost } = dispatch( 'core/editor' );

	return {
		onUpdateDate( date ) {
			if ( date && ! isDateValid( date ) ) {
				return;
			}

			const timestamp = date ? getDate( date ).getTime() / 1000 : 0;

			editPost( {
				meta: {
					unpublish_timestamp: timestamp,
				},
			} );
		},
	};
}

const ComposedForm = compose( [ withSelect( addSelectors ), withDispatch( addUpdater ) ] )( Form );

export default ComposedForm;
