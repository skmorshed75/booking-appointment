(function () {

	"use strict";

	Vue.component( 'jet-apb-settings-working-hours', {
		template: '#jet-apb-settings-working-hours',
		props: {
			settings: {
				type: Object,
				default: {},
			}
		},
		components: {
			vuejsDatepicker: window.vuejsDatepicker,
		},
		data: function() {
			return {
				weekdays: this.settings.weekdays || window.JetAPBConfig.weekdays,
				timeSlots: this.settings.time_slots || window.JetAPBConfig.time_slots,
				dateFormat: 'MMM D, YYYY',
				days_off: [],
				working_days: [],
				disabledDate: {},
				editDay: false,
				deleteDayTrigger: null,
				date: {
					start: null,
					startTimeStamp: null,
					end: null,
					endTimeStamp: null,
					name: null,
					type: null,
					editIndex: null,
				},
				workingHours: {},
				isNewSlot: false,
				currentDay: null,
				currentFrom: null,
				currentTo: null,
				currentIndex: null,
				deleteSlotTrigger: null,
			};
		},
		mounted: function() {
			if ( this.settings.working_hours ) {
				this.workingHours = this.settings.working_hours;
			}

			if ( this.settings.days_off ) {
				this.days_off = this.settings.days_off;
			}

			if ( this.settings.working_days ) {
				this.working_days = this.settings.working_days;
			}
		},
		methods: {
			formatDate: function( date ) {
				return moment( date ).format( this.dateFormat );
			},
			getTimeStamp: function( date ) {
				return moment( date ).valueOf();
			},

			showEditDay: function( daysType = false , date = false ) {
				if ( date && daysType ) {
					var index = this[ daysType ].indexOf( date );

					this.$set( this.date, 'editIndex', index );

					for ( var key in this[ daysType ][ index ] ) {
						this.$set( this.date, key, this[ daysType ][ index ][key] );
					}
				}

				this.updateDisabledDates( daysType, date );

				this.date.type  = daysType;
				this.editDay    = true;
			},

			handleDayOk: function() {
				if ( ! this.date.start || this.getTimeStamp( this.date.start ) > this.getTimeStamp( this.date.end ) ) {
					return;
				}

				var startDate = this.formatDate( this.date.start ),
					startTimeStamp = this.getTimeStamp( this.date.start ),
					endTimeStamp = this.date.end ? this.getTimeStamp( this.date.end ) : startTimeStamp,
					dates = this[ this.date.type ],
					index = null !== this.date.editIndex ? this.date.editIndex : dates.length;

				this.$set( dates, index, {
					start: startDate,
					startTimeStamp: startTimeStamp,
					end: this.date.end ? this.formatDate( this.date.end ) : startDate ,
					endTimeStamp: endTimeStamp,
					name: this.date.name,
					type: this.date.type,
				} );

				this.$nextTick( function() {
					this.$emit( 'force-update', {
							key: this.date.type,
							value: dates,
						} );
					this.handleDayCancel();
				} );
			},

			handleDayCancel: function() {
				for ( var key in this.date ) {
					this.$set( this.date, key, null );
				}

				this.editDay       = false;
			},

			confirmDeleteDay: function( dateObject ) {
				this.deleteDayTrigger = dateObject;
			},

			deleteDay: function( daysType = false , date = false  ) {
				var index = this[ daysType ].indexOf( date );

				this.$delete( this[ daysType ], index );

				this.$nextTick( function() {
					this.$emit( 'force-update', {
						key: daysType,
						value: this[ daysType ],
					} );
				} );
			},

			updateDisabledDates: function( daysType = false, excludedDate = false ) {
				var newDisabledDates = [],
					daysFrom,
					toFrom;

				for ( var date in this[ daysType ] ) {
					if( this[ daysType ][ date ] === excludedDate ){
						continue;
					}

					daysFrom = moment( this[ daysType ][ date ].start );
					toFrom   = moment( this[ daysType ][ date ].end ).add( 1, 'days' );

					//Fixes datapicker bug. If set by value, the disabled date is shifted by one day.
					if( excludedDate ){
						daysFrom.add( -1, 'days' )
					}

					newDisabledDates.push( {
						from: daysFrom,
						to: toFrom,
					} );
				}

				this.$set( this.disabledDate, 'ranges', newDisabledDates );
			},


			newSlot: function( day ) {
				this.isNewSlot  = true;
				this.currentDay = day;
			},
			editSlot: function( day, slotIndex, daySlot ) {
				this.isNewSlot    = true;
				this.currentDay   = day;
				this.currentFrom  = daySlot.from;
				this.currentTo    = daySlot.to;
				this.currentIndex = slotIndex;
			},
			confirmDeleteSlot: function( day, slotIndex ) {
				this.deleteSlotTrigger = day + '-' + slotIndex;
			},
			deleteSlot: function( day, slotIndex ) {
				var dayData = this.workingHours[ day ] || [];

				this.deleteSlotTrigger = null;

				dayData.splice( slotIndex, 1 );

				this.$set( this.workingHours, day, dayData );

				this.$nextTick( function() {

					this.$emit( 'force-update', {
						key: 'working_hours',
						value: this.workingHours,
					} );

				} );

			},
			handleCancel: function() {
				this.currentDay   = null;
				this.currentFrom  = null;
				this.currentTo    = null;
				this.currentIndex = null;
			},
			handleOk: function() {

				var dayData = this.workingHours[ this.currentDay ] || [];

				if ( null === this.currentIndex ) {
					dayData.push( {
						from: this.currentFrom,
						to: this.currentTo,
					} );
				} else {
					dayData.splice( this.currentIndex, 1, {
						from: this.currentFrom,
						to: this.currentTo,
					} );
				}

				this.$set( this.workingHours, this.currentDay, dayData );

				this.$nextTick( function() {
					this.handleCancel();
					this.$emit( 'force-update', {
						key: 'working_hours',
						value: this.workingHours,
					} );

				} );

			},
		}
	} );

})();
