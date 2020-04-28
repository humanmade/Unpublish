import React from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { PluginPostStatusInfo } from '@wordpress/edit-post';

export default function Panel() {
	return (
		<PluginPostStatusInfo className="unpublish">
			<span>{ __( 'Unpublish', 'unpublish' ) }</span>
		</PluginPostStatusInfo>
	);
}
