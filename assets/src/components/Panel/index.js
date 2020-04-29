import React from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { PluginPostStatusInfo } from '@wordpress/edit-post';

import Form from '../Form';

export default function Panel() {
	return (
		<PluginPostStatusInfo className="unpublish">
			<span>{ __( 'Unpublish', 'unpublish' ) }</span>
			<Form />
		</PluginPostStatusInfo>
	);
}
