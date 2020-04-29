import React, { useEffect } from '@wordpress/element';
import PropTypes from 'prop-types';
import { __ } from '@wordpress/i18n';
import { getDate, isInTheFuture } from '@wordpress/date';
import { compose } from '@wordpress/compose';
import { Button, Dropdown } from '@wordpress/components';
import { withDispatch, withSelect } from '@wordpress/data';

import Field from './Field';
import Label from './Label';

const CONTENT_CLASSNAME = 'edit-post-post-unpublish__dialog';

export function Form( { date, postDate, onUpdateDate } ) {
	useEffect( () => {
		if ( date <= postDate ) {
			onUpdateDate( 0 );
		}
	}, [ date, postDate ] );

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
					{ __( 'Clear', 'unpublish' ) }
				</Button>
			) : null }
		</>
	);
}

Form.propTypes = {
	date: PropTypes.number.isRequired,
	postDate: PropTypes.number.isRequired,
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

function addUpdater( dispatch, ownProps ) {
	const { postDate } = ownProps;
	const { editPost } = dispatch( 'core/editor' );

	const update = timestamp => {
		editPost( {
			meta: {
				unpublish_timestamp: timestamp,
			},
		} );
	};

	return {
		onUpdateDate( date ) {
			if ( ! date ) {
				update( 0 );
				return;
			}

			const timestamp = getDate( date ).getTime();

			if ( ! isDateValid( timestamp, postDate ) ) {
				return;
			}

			update( timestamp / 1000 );
		},
	};
}

const ComposedForm = compose( [ withSelect( addSelectors ), withDispatch( addUpdater ) ] )( Form );

export default ComposedForm;
