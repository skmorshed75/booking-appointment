(function () {

	"use strict";

	Vue.component( 'jet-apb-settings-general', {
		template: '#jet-apb-settings-general',
		props: {
			settings: {
				type: Object,
				default: {},
			}
		},
		data: function() {
			return {
				postTypes: window.JetAPBConfig.post_types,
				generalSettings: {}
			};
		},
		mounted: function() {
			this.generalSettings = this.settings;
		},
		methods: {
			updateSetting: function( value, key ) {
				this.$emit( 'force-update', {
					key: key,
					value: value,
				} );
			}
		}
	} );

	Vue.component( 'jet-apb-settings-advanced', {
		template: '#jet-apb-settings-advanced',
		props: {
			settings: {
				type: Object,
				default: {},
			}
		},
		data: function() {
			return {
				advancedSettings: {}
			};
		},
		mounted: function() {
			this.advancedSettings = this.settings;
		},
		methods: {
			updateSetting: function( value, key ) {
				this.$emit( 'force-update', {
					key: key,
					value: value,
				} );
			}
		}
	} );

	Vue.component( 'jet-apb-settings-labels', {
		template: '#jet-apb-settings-labels',
		props: {
			settings: {
				type: Object,
				default: {},
			}
		},
		data: function() {
			return {
				labels: {}
			};
		},
		created: function() {
			this.labels = this.settings;
		},
		methods: {
			updateSetting: function( value, key ) {
				this.$emit( 'force-update', {
					key: key,
					value: value,
				} );
			},
			updateLabel: function( value, key ) {

				this.$set( this.labels.custom_labels, key, value )

				this.$emit( 'force-update', {
					key: 'custom_labels',
					value: this.labels.custom_labels,
				} );
			}
		}
	} );

	new Vue({
		el: '#jet-apb-settings-page',
		template: '#jet-apb-settings',
		data: {
			settings: window.JetAPBConfig.settings,
			clearingExcluded: false,
			savingDBColumns: false,
		},
		methods: {
			onUpdateSettings: function( setting, force ) {
				force = force || false;
				this.$set( this.settings, setting.key, setting.value );
				if ( force ) {
					this.$nextTick( function() {
						this.saveSettings();
					} );
				}
			},
			clearExcludedDates: function() {
				var self = this;

				self.clearingExcluded = true;

				jQuery.ajax({
					url: ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: {
						action: 'jet_apb_clear_excluded',
					},
				}).done( function( response ) {
					self.clearingExcluded = false;
					self.$CXNotice.add( {
						message: 'Done!',
						type: 'success',
						duration: 7000,
					} );
				} ).fail( function( jqXHR, textStatus, errorThrown ) {
					self.clearingExcluded = false;
					self.$CXNotice.add( {
						message: errorThrown,
						type: 'error',
						duration: 7000,
					} );
				} );

			},
			saveSettings: function( updateDBColumns ) {
				var self = this;

				updateDBColumns = updateDBColumns || false;

				jQuery.ajax({
					url: ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: {
						action: 'jet_apb_save_settings',
						settings: this.settings,
						update_db_columns: updateDBColumns,
					},
				}).done( function( response ) {

					if ( response.success ) {
						self.$CXNotice.add( {
							message: response.data.message,
							type: 'success',
							duration: 7000,
						} );
					}

					self.savingDBColumns = false;

				} ).fail( function( jqXHR, textStatus, errorThrown ) {

					self.$CXNotice.add( {
						message: errorThrown,
						type: 'error',
						duration: 7000,
					} );

					self.savingDBColumns = false;

				} );
			},
			addNewColumn: function() {
				this.settings.db_columns.push( '' );
			},
			cloneColumn: function( data, index ) {
				var column = this.columnNewName( this.settings.db_columns[ index ], this.settings.db_columns );

				this.$set( this.settings.db_columns, this.settings.db_columns.length, column );
			},
			deleteColumn: function( data, index ) {
				this.settings.db_columns.splice( index, 1 );
			},
			setColumnProp: function( index, column ) {
				if( -1 !== jQuery.inArray( column, this.settings.db_columns ) ){
					this.$CXNotice.add( {
						message: 'This column already exists in the table!',
						type: 'error',
						duration: 7000,
					} );
					column = this.columnNewName( column, this.settings.db_columns )
				}

				this.$set( this.settings.db_columns, index, column );
			},
			saveDBColumns: function() {
				if ( window.confirm( 'Are you sure? If you change or remove any columns, all data stored in this columns will be lost!' ) ) {
					this.savingDBColumns = true;
					this.saveSettings( true );
				}
			},
			columnNewName: function( name, columnArray ){
				var newName = name;

				if( -1 === jQuery.inArray( newName, columnArray ) ){
					return newName;
				}else{
					return this.columnNewName( newName + '-copy', columnArray );
				}
			}
		}
	});

})();
