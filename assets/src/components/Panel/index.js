import React from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { PluginPostStatusInfo } from '@wordpress/edit-post';
import { Dropdown, Button } from '@wordpress/components';
import { PostSchedule as PostScheduleForm } from '@wordpress/editor';

import UnpublishLabel from '../UnpublishLabel';

export default function Panel() {
	return (
		<PluginPostStatusInfo className="unpublish">
			<span>{ __( 'Unpublish', 'unpublish' ) }</span>
			<Dropdown
				position="bottom left"
				contentClassName="edit-post-post-unpublish__dialog"
				renderToggle={ ( { onToggle, isOpen } ) => (
					<>
						<Button
							className="edit-post-post-unpublish__dialog"
							onClick={ onToggle }
							aria-expanded={ isOpen }
							isLink
						>
							<UnpublishLabel />
						</Button>
					</>
				) }
				renderContent={ () => <PostScheduleForm /> }
			/>
		</PluginPostStatusInfo>
	);
}
