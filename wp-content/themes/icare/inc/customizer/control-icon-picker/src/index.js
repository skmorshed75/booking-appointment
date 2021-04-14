/* global wp */

import WPTRTiconPickerControl from './WPTRTiconPickerControl';

// Register control type with Customizer.
wp.customize.controlConstructor['icon-picker'] = WPTRTiconPickerControl;
