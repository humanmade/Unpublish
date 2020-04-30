import domReady from '@wordpress/dom-ready';
import { registerPlugin } from '@wordpress/plugins';

import Panel from './components/Panel';

domReady( () => {
	registerPlugin( 'unpublish-panel', {
		icon: null,
		render: Panel,
	} );
} );
