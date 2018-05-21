<?php


/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Guest_User
 * @subpackage Guest_User/includes
 * @author     Nesho Sabakov <neshosab16@gmail.com>
 */
class Guest_User {



	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'guest_user_register_menu_page' ) );

	}

	public function guest_user_register_menu_page() {

		add_submenu_page( 'users.php', 'Add Guest User', 'Add Guest User', 'manage_options', 'guest_user', array( $this, 'guest_user_menu_page' ) );

	}

	// Function to display the input
	public function guest_user_menu_page() {

		echo '<div class="wrapper">';
			// check if form was sent
			$this->guest_user_check_for_input();
			echo '<h1>' . __( 'Add Guest User', 'aau' ) . '</h1>';
			echo '<hr>';
			echo '<form method="POST" id="createuser" action="">';
			?>

			<table class="form-table">
				<tbody>
					<tr class="form-field">
						<th scope="row">
							<label for="user_login">Username</label>
						</th>
						<td>
							<input name="user_login" type="text" id="user_login" value="" maxlength="60">
						</td>
					</tr>
					<tr class="form-field">
						<th scope="row">
							<label for="first_name">First Name</label>
						</th>
						<td>
							<input name="first_name" type="text" value="">
						</td>
					</tr>
					
					<tr class="form-field">
						<th scope="row">
							<label for="last_name">Last Name</label>
						</th>
						<td>
							<input name="last_name" type="text" value="">
						</td>
					</tr>

					<tr class="form-field">
						<th scope="row">
							<label for="twitter">Twitter</label>
						</th>
						<td>
							<input name="twitter" id="twitter" type="text" value="">
						</td>
					</tr>

					<tr class="form-field">
						<th scope="row">
							<label for="linkedin">LinkedIn</label>
						</th>
						<td>
							<input name="linkedin" id="linkedin" type="text" value="">
						</td>
					</tr>

					<tr class="form-field">
						<th scope="row">
							<label for="company">Company</label>
						</th>
						<td>
							<input name="company" type="text" value="">
						</td>
					</tr>


					<tr class="form-field">
						<th scope="row">
							<label for="job_title">Job Title</label>
						</th>
						<td>
							<input name="job_title" type="text" value="">
						</td>
					</tr>

					 <tr class="form-field">
						<th scope="row">
							<label for="description">About</label>
						</th>
						<td>
							<textarea name="description" rows="5" cols="30"></textarea>
							<p class="description">Share a little biographical information to fill out your profile. This may be shown publicly.</p>
					</td>
					</tr>
					
					<tr class="form-field">
						<th scope="row">
							<label for="role">Role</label>
						</th>
						<td>
							<select name="role" id="role">
								<?php wp_dropdown_roles( 'subscriber' ); ?>
							</select>
							<p class="description">Use specific role for users with no email ( i.e. person)</p>
						</td>
					</tr>
				</tbody>
			</table>

			<?php

			echo '  <p class="submit"><input type="submit" class="button button-primary" value="Create new user"><span class="acf-spinner"></span></p>';

			echo '</form>';

			echo '</div>';

	}


	// Function to process the input
	public function guest_user_check_for_input() {

		// do nothing if Form was not sent
		if ( ! isset( $_POST['user_login'] ) || $_POST['user_login'] == '' ) {
			return;
		}
		if ( ! isset( $_POST['first_name'] ) || $_POST['first_name'] == '' ) {
			return;
		}
		if ( ! isset( $_POST['last_name'] ) || $_POST['last_name'] == '' ) {
			return;
		}

		// sanitize the input to avoid security breaches
		$username    = sanitize_text_field( $_POST['user_login'] );
		$first_name  = sanitize_text_field( $_POST['first_name'] );
		$last_name   = sanitize_text_field( $_POST['last_name'] );
		$twitter     = sanitize_text_field( $_POST['twitter'] );
		$description = sanitize_text_field( $_POST['description'] );
		$company     = sanitize_text_field( $_POST['company'] );
		$job_title   = sanitize_text_field( $_POST['job_title'] );

		// define the new user
		$args = array(
			'user_login'  => $username,
			'first_name'  => $first_name,
			'last_name'   => $last_name,
			'twitter'     => $twitter,
			'description' => $description,
			'user_pass'   => md5( rand( 1000000, 9999999 ) ), // create hash of randomized number as password
			'role'        => $_POST['role'],
		);

		$user_meta = array(
			'company'   => $company,
			'job_title' => $job_title,
		);

		// try to insert the user
		$user_id = wp_insert_user( $args );

		// check if everything went coorectly
		if ( ! is_wp_error( $user_id ) ) {
			// add a small notice
			foreach ( $user_meta as $key => $value ) {
				if ( $value && $value != '' ) {
					add_user_meta( $user_id, $key, $value );
				}
			}

			echo '<div class="updated"><p>' . __( 'User created', 'aau' ) . ': ' . $username . '</p></div>';
		} else {
			// show error message
			$errors = implode( ', ', $user_id->get_error_messages() );
			echo '<div class="error"><p>' . __( 'Error', 'aau' ) . ': ' . $errors . '</p></div>';
		}

	}

}
