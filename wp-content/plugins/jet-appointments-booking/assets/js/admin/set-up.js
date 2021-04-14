(function () {

	"use strict";

	new Vue({
		el: '#jet-apb-set-up-page',
		template: '#jet-apb-set-up',
		data: {
			isSet: window.JetAPBConfig.setup.is_set,
			isReset: window.JetAPBConfig.reset.is_reset,
			resetURL: window.JetAPBConfig.reset.reset_url,
			postTypes: window.JetAPBConfig.post_types,
			dbFields: window.JetAPBConfig.db_fields,
			currentStep: 1,
			lastStep: 4,
			loading: false,
			setupData: {
				create_single_form: true,
				create_page_form: true,
				working_hours: {
					monday: [],
					tuesday: [],
					wednesday: [],
					thursday: [],
					friday: [],
					saturday: [],
					sunday: [],
				},
				days_off: [],
				working_days: [],
			},
			log: false,
			additionalDBColumns: [],
		},
		methods: {
			setWorkingHoursData: function( data ) {
				this.$set( this.setupData, data.key, data.value );
			},
			nextStep: function() {

				var self = this;

				if ( 1 === self.currentStep ) {

					if ( ! self.setupData.services_cpt ) {

						self.$CXNotice.add( {
							message: 'Please select post type for provided services.',
							type: 'error',
							duration: 7000,
						} );

						return;
					}

					if ( self.setupData.add_providers && ! self.setupData.providers_cpt ) {

						self.$CXNotice.add( {
							message: 'Please select post type for service providers.',
							type: 'error',
							duration: 7000,
						} );

						return;

					}

				}

				if ( self.currentStep === self.lastStep ) {

					self.loading = true;

					jQuery.ajax({
						url: ajaxurl,
						type: 'POST',
						dataType: 'json',
						data: {
							action: 'jet_apb_setup',
							setup_data: self.setupData,
							db_columns: self.additionalDBColumns,
						},
					}).done( function( response ) {

						self.loading = false;

						if ( response.success ) {
							self.currentStep++;
							self.log = response.data;
						}
					} ).fail( function( jqXHR, textStatus, errorThrown ) {

						self.loading = false;

						self.$CXNotice.add( {
							message: errorThrown,
							type: 'error',
							duration: 7000,
						} );
					} );

				} else {
					self.currentStep++;
				}

			},
			prevStep: function() {
				if ( 1 < this.currentStep ) {
					this.currentStep--;
				}
			},
			addNewColumn: function( event ) {

				var col = {
					column: '',
					collapsed: false,
				};

				this.additionalDBColumns.push( col );

			},
			setColumnProp: function( index, key, value ) {
				var double = jQuery.grep( this.additionalDBColumns, function( item ){ return item.column === value; } );

				if ( ! double[0] ) {
					this.additionalDBColumns[ index ][ key ] = value;
				}else{
					this.additionalDBColumns[ index ][ key ] = this.columnNewName( value, this.additionalDBColumns );
					this.$CXNotice.add( {
						message: 'This column already exists in the table!',
						type: 'error',
						duration: 7000,
					} );
				}
			},
			cloneColumn: function( index ) {

				var col    = this.additionalDBColumns[ index ],
					newCol = {
						'column': col.column + '_copy',
					};

				this.additionalDBColumns.splice( index + 1, 0, newCol );

			},
			deleteColumn: function( index ) {
				this.additionalDBColumns.splice( index, 1 );
			},
			isCollapsed: function( object ) {
				if ( undefined === object.collapsed || true === object.collapsed ) {
					return true;
				} else {
					return false;
				}
			},
			goToReset: function() {
				if ( confirm( 'Are you sure? All previously booked appoinments will be removed!' ) ) {
					window.location = this.resetURL;
				}
			},
			columnNewName: function( name, columnArray ){
				var double = jQuery.grep( columnArray, function( item ){ return item.column === name; } );

				if( ! double[0] ){
					return name;
				}else{
					return this.columnNewName( name + '-copy', columnArray );
				}
			}
		}
	});

})();
