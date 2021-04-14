<?php
namespace JET_APB;

/**
 * Upgrader class
 */
class Upgrade {

	private $found_users = array();

	public function __construct() {
		$this->to_1_1_0();
	}

	/**
	 * Check DB requirements for 2.0 version and show upgrade notice
	 *
	 * @return [type] [description]
	 */
	public function to_1_1_0() {

		add_action( 'admin_init', function() {

			if ( ! Plugin::instance()->db->appointments->is_table_exists() ) {
				return;
			}

			if ( ! Plugin::instance()->db->appointments->column_exists( 'user_id' ) ) {
				Plugin::instance()->db->appointments->insert_table_columns( array( 'user_id' ) );
				$this->add_users_to_appointments();
			}

		} );

	}

	/**
	 * Add users to an existing aapointments
	 */
	public function add_users_to_appointments() {
		
		$appointments = Plugin::instance()->db->appointments->query();
		
		if ( empty( $appointments ) ) {
			return;
		}

		foreach ( $appointments as $app ) {
			
			$email = ! empty( $app['user_email'] ) ? $app['user_email'] : false;

			if ( ! $email ) {
				continue;
			}

			$user_id = ! empty( $this->found_users[ $email ] ) ? $this->found_users[ $email ] : false;

			if ( ! $user_id ) {
				$users_table = Plugin::instance()->db->appointments->wpdb()->users;
				$users = Plugin::instance()->db->appointments->wpdb()->get_results( "SELECT ID FROM $users_table WHERE `user_email` = '$email';" );

				if ( ! empty( $users ) ) {
					$user_id = $users[0]->ID;
				}

			}

			if ( ! $user_id ) {
				continue;
			}

			$this->found_users[ $email ] = $user_id;
			Plugin::instance()->db->appointments->update( array( 'user_id' => $user_id ), array( 'ID' => $app['ID'] ) );

		}

	}

}
