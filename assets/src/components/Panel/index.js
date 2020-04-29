import React from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { PluginPostStatusInfo } from '@wordpress/edit-post';
import { Dropdown, Button } from '@wordpress/components';

import Field from '../Field';
import Label from '../Label';

function Toggle( { onToggle, isOpen } ) {
	return (
		<Button className="edit-post-post-unpublish__dialog" onClick={ onToggle } aria-expanded={ isOpen } isLink>
			<Label />
		</Button>
	);
}

export default function Panel() {
	return (
		<PluginPostStatusInfo className="unpublish">
			<span>{ __( 'Unpublish', 'unpublish' ) }</span>
			<Dropdown
				position="bottom left"
				contentClassName="edit-post-post-unpublish__dialog"
				renderToggle={ props => <Toggle { ...props } /> }
				renderContent={ () => <Field /> }
			/>
		</PluginPostStatusInfo>
	);
}
