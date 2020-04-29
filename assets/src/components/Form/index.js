import React from '@wordpress/element';
import { Dropdown, Button } from '@wordpress/components';

import Field from '../Field';
import Label from '../Label';

const CONTENT_CLASSNAME = 'edit-post-post-unpublish__dialog';

function Toggle( { onToggle, isOpen } ) {
	return (
		<Button className={ CONTENT_CLASSNAME } onClick={ onToggle } aria-expanded={ isOpen } isLink>
			<Label />
		</Button>
	);
}

export default function Form() {
	return (
		<Dropdown
			position="bottom left"
			contentClassName={ CONTENT_CLASSNAME }
			renderToggle={ props => <Toggle { ...props } /> }
			renderContent={ () => <Field /> }
		/>
	);
}
