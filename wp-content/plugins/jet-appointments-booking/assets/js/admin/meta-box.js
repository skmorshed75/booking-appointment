(function () {
	"use strict";

	new Vue( {
		el: '#jet-apb-custom-schedule-meta-box',
		data: {
			settings: jetApbPostMeta,
		},

		methods: {
			updateSettings: function( setting, force ) {
				force = force || false;

				this.$set( this.settings[ setting.metaKey ], setting.optionKey, setting.value );
				if ( force ) {
					this.$nextTick( function() {
						this.saveSettings();
					} );
				}
			},
			saveSettings: function() {
				var self = this;

				jQuery.ajax({
					url: ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: {
						action: 'jet_apb_save_post_meta',
						jet_apb_post_meta: self.settings,
					},
				}).done( function( response ) {

					if ( response.success ) {
						self.$CXNotice.add( {
							message: response.data.message,
							type: 'success',
							duration: 7000,
						} );
					}

				} ).fail( function( jqXHR, textStatus, errorThrown ) {

					self.$CXNotice.add( {
						message: errorThrown,
						type: 'error',
						duration: 7000,
					} );

				} );
			}
		}
	});

})();
