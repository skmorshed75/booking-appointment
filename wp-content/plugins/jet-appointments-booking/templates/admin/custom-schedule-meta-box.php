<div id='jet-apb-custom-schedule-meta-box'>
	<cx-vui-switcher
		label="<?php esc_html_e( 'Use Custom Schedule', 'jet-appointments-booking' ); ?>"
		description="<?php esc_html_e( 'You can use a custom schedule for services or providers.', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:return-true="true"
		:return-false="false"
		v-model="settings.custom_schedule.use_custom_schedule"
		@input="updateSettings( {
			metaKey: 'custom_schedule',
			optionKey: 'use_custom_schedule',
			value: $event
		}, true )"
	></cx-vui-switcher>
	<cx-vui-select
		v-if="settings.custom_schedule.use_custom_schedule"
		class="jet-apb-working-hours__main-settings"
		label="<?php esc_html_e( 'Duration', 'jet-appointments-booking' ); ?>"
		description="<?php esc_html_e( 'Select the duration for each service time slot', 'jet-appointments-booking' ); ?>"
		:options-list="settings.custom_schedule.slot_option"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		:value="settings.custom_schedule.default_slot"
		@input="updateSettings( {
			metaKey: 'custom_schedule',
			optionKey: 'default_slot',
			value: $event
		}, true )"
	></cx-vui-select>

	<cx-vui-select
		v-if="settings.custom_schedule.use_custom_schedule"
		class="jet-apb-working-hours__main-settings"
		label="<?php esc_html_e( 'Buffer Time Before Slot', 'jet-appointments-booking' ); ?>"
		:options-list="settings.custom_schedule.buffer_option"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		:value="settings.custom_schedule.buffer_before"
		@input="updateSettings( {
			metaKey: 'custom_schedule',
			optionKey: 'buffer_before',
			value: $event
		}, true )"
	></cx-vui-select>
	<cx-vui-select
		v-if="settings.custom_schedule.use_custom_schedule"
		class="jet-apb-working-hours__main-settings"
		label="<?php esc_html_e( 'Buffer Time After Slot', 'jet-appointments-booking' ); ?>"
		:options-list="settings.custom_schedule.buffer_option"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		:value="settings.custom_schedule.buffer_after"
		@input="updateSettings( {
			metaKey: 'custom_schedule',
			optionKey: 'buffer_after',
			value: $event
		}, true )"
	></cx-vui-select>

	<jet-apb-settings-working-hours
		v-if="settings.custom_schedule.use_custom_schedule"
		:settings="settings.custom_schedule"
		@force-update="updateSettings( {
			metaKey: 'custom_schedule',
			optionKey: $event.key,
			value: $event.value
		}, true )"
	></jet-apb-settings-working-hours>
</div>
