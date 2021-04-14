<div>
	<h3 class="cx-vui-subtitle"><?php esc_html_e( 'Appointments Booking Settings', 'jet-appointments-booking' ); ?></h3>
	<br>
	<div class="cx-vui-panel">
		<cx-vui-tabs
			:in-panel="false"
			value="general"
			layout="vertical"
		>
			<cx-vui-tabs-panel
				name="general"
				label="<?php esc_html_e( 'General', 'jet-appointments-booking' ); ?>"
				key="general"
			>
				<keep-alive>
					<jet-apb-settings-general
						:settings="settings"
						@force-update="onUpdateSettings( $event, true )"
					></jet-apb-settings-general>
				</keep-alive>
			</cx-vui-tabs-panel>
			<cx-vui-tabs-panel
				name="working-hours"
				label="<?php esc_html_e( 'Working Hours', 'jet-appointments-booking' ); ?>"
				key="working-hours"
			>
				<keep-alive>
					<div>
						<div class="jet-apb-working-hours__heading">
							<h4 class="cx-vui-subtitle"><?php esc_html_e( 'Booking Schedule', 'jet-appointments-booking' ); ?></h4>
						</div>
						<cx-vui-select
							class="jet-apb-working-hours__main-settings"
							label="<?php esc_html_e( 'Default Duration', 'jet-appointments-booking' ); ?>"
							description="<?php esc_html_e( 'Select the default duration for each service and provider time slot', 'jet-appointments-booking' ); ?>"
							:options-list="settings.slot_option"
							:wrapper-css="[ 'equalwidth' ]"
							:size="'fullwidth'"
							:value="settings.default_slot"
							@input="onUpdateSettings( {
								key: 'default_slot',
								value: $event
							}, true )"
						></cx-vui-select>
						<cx-vui-select
							class="jet-apb-working-hours__main-settings"
							label="<?php esc_html_e( 'Buffer Time Before Slot', 'jet-appointments-booking' ); ?>"
							:options-list="settings.buffer_option"
							:wrapper-css="[ 'equalwidth' ]"
							:size="'fullwidth'"
							:value="settings.default_buffer_before"
							@input="onUpdateSettings( {
								key: 'default_buffer_before',
								value: $event
							}, true )"
						></cx-vui-select>
						<cx-vui-select
							class="jet-apb-working-hours__main-settings"
							label="<?php esc_html_e( 'Buffer Time After Slot', 'jet-appointments-booking' ); ?>"
							:options-list="settings.buffer_option"
							:wrapper-css="[ 'equalwidth' ]"
							:size="'fullwidth'"
							:value="settings.default_buffer_after"
							@input="onUpdateSettings( {
								key: 'default_buffer_after',
								value: $event
							}, true )"
						></cx-vui-select>
						<jet-apb-settings-working-hours
							:settings="settings"
							@force-update="onUpdateSettings( $event, true )"
						></jet-apb-settings-working-hours>
					</div>
				</keep-alive>
			</cx-vui-tabs-panel>
			<cx-vui-tabs-panel
				name="labels"
				label="<?php esc_html_e( 'Labels', 'jet-appointments-booking' ); ?>"
				key="labels"
			>
				<keep-alive>
					<jet-apb-settings-labels
						:settings="settings"
						@force-update="onUpdateSettings( $event, true )"
					></jet-apb-settings-labels>
				</keep-alive>
			</cx-vui-tabs-panel>
			<cx-vui-tabs-panel
				name="advanced"
				label="<?php esc_html_e( 'Advanced', 'jet-appointments-booking' ); ?>"
				key="advanced"
			>
				<keep-alive>
					<jet-apb-settings-advanced
						:settings="settings"
						@force-update="onUpdateSettings( $event, true )"
					></jet-apb-settings-advanced>
				</keep-alive>
			</cx-vui-tabs-panel>
			<cx-vui-tabs-panel
				name="tools"
				label="<?php esc_html_e( 'Tools', 'jet-appointments-booking' ); ?>"
				key="tools"
			>
				<div class="cx-vui-component cx-vui-component--equalwidth">
					<div class="cx-vui-component__meta">
						<label class="cx-vui-component__label"><?php esc_html_e( 'Clear excluded dates cache', 'jet-appointments-booking' ); ?></label>
						<div class="cx-vui-component__desc"><?php esc_html_e( 'Remove all dates from excluded dates table', 'jet-appointments-booking' ); ?></div>
					</div>
					<div class="cx-vui-component__control">
						<cx-vui-button
							@click="clearExcludedDates"
							:loading="clearingExcluded"
						>
							<span slot="label"><?php esc_html_e( 'Clear', 'jet-appointments-booking' ); ?></span>
						</cx-vui-button>
					</div>
				</div>
				<cx-vui-component-wrapper
					:wrapper-css="[ 'fullwidth-control' ]"
				>
					<div class="cx-vui-inner-panel">
						<div class="cx-vui-subtitle"><?php esc_html_e( 'Additional table columns' ); ?></div>
						<div class="cx-vui-component__desc"><?php esc_html_e( 'You can add any columns you want to Appointments table. We recommend add cloumns only on plugin set up. When you added new columns, you need to map these columns to apopriate form field in related booking form.' ); ?></div><br>

						<cx-vui-repeater
							button-label="<?php esc_html_e( 'New DB Column', 'jet-appointments-booking' ); ?>"
							button-style="accent"
							button-size="mini"
							v-model="settings.db_columns"
							@add-new-item="addNewColumn"
						>
							<cx-vui-repeater-item
								v-for="( column, columnIndex ) in settings.db_columns"
								:title="settings.db_columns[ columnIndex ]"
								:index="columnIndex"
								:collapsed="false"
								@clone-item="cloneColumn( $event, columnIndex )"
								@delete-item="deleteColumn( $event, columnIndex )"
								:key="'column' + columnIndex"
							>
								<cx-vui-input
									label="<?php esc_html_e( 'Column name', 'jet-appointments-booking' ); ?>"
									description="<?php esc_html_e( 'Name for additional DB column', 'jet-appointments-booking' ); ?>"
									:wrapper-css="[ 'equalwidth' ]"
									size="fullwidth"
									:value="settings.db_columns[ columnIndex ]"
									@on-input-change="setColumnProp( columnIndex, $event.target.value )"
								></cx-vui-input>
							</cx-vui-repeater-item>
						</cx-vui-repeater>
						<div style="margin: 20px 0 -5px;"><?php
							printf( '<b>%s</b> %s', esc_html__( 'Warning:', 'jet-appointments-booking' ), esc_html__( 'If you change or remove any columns, all data stored in this columns will be lost!', 'jet-appointments-booking' ) );
						?></div>
					</div>
					<br>
					<cx-vui-button
						@click="saveDBColumns"
						:loading="savingDBColumns"
						button-style="accent"
					>
						<span slot="label"><?php
							esc_html_e( 'Save and update appointments table', 'jet-appointments-booking' );
						?></span>
					</cx-vui-button>
				</cx-vui-component-wrapper>
			</cx-vui-tabs-panel>
		</cx-vui-tabs>
	</div>
</div>
