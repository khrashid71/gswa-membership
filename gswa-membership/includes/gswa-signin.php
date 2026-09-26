<?php





if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
//require_once  'forget-passwd.php';



/**
 * Create a custom hook gswa_signup
 */
add_shortcode( 'gswa_signin', 'gswa_membership_signin_form' );


function gswa_membership_signin_form() {

    ob_start();
    //adding here for testing redirect
    if ( isset( $_GET['registered'] ) && '1' === $_GET['registered'] ) {

    echo '<p class="gswa-success">';
    echo 'Registration successful! Please sign in to your GSWA account.';
    echo '</p>';

}

// adding new code at 7:34pm

$email = '';

if ( isset( $_GET['email'] ) ) {
    $email = sanitize_email( wp_unslash( $_GET['email'] ) );
}
//end
    

    if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

    if (
        ! isset( $_POST['gswa_signin_nonce'] ) ||
        ! wp_verify_nonce(
            sanitize_text_field( wp_unslash( $_POST['gswa_signin_nonce'] ) ),
            'gswa_signin_action'
        )
    ) {
        wp_die( 'Security verification failed. Please refresh the page and try again.' );
    }

    // Continue processing...

   

    $email = sanitize_email(
        wp_unslash( $_POST['gswa_email'] ?? '' )
    );
 
    $password = $_POST['gswa_password'] ?? '';
    

// Validate required fields-added mobile
if ( 
     empty( $email )||
     
     empty( $password ) ) {

    echo '<p>Please complete all required fields.</p>';

} elseif ( ! is_email( $email ) ) {

    echo '<p>Please enter a valid email address.</p>';


} elseif ( !email_exists( $email ) ) {

    echo '<p>Email is not matched as userid</p>';
 


}elseif ( ! is_wp_error( $user_id ) && ($password)) {

    

    echo '<p>Signin successful!</p>';
    
}else{
    echo '<p>Unable to Login. Please try again.</p>';
}

    
}

       
    // 2. Render the Form HTML (Inside the buffer)
    ?>

    <div class="gswa-membership-signin">

        <h3>GSWA Membership Sign In</h3>

        <form method="post" action="">
            <!-- Correct implementation of the WP Nonce field -->
<?php wp_nonce_field( 'gswa_signin_action', 'gswa_signin_nonce' ); ?>
           

            <p>
                <label for="gswa_email">Email</label><br>
                <input type="email" name="gswa_email" value="<?php echo esc_attr( $email ); ?>" autocomplete="username" required>
            </p>
            
            <p>
                <label for="gswa_password">Password</label><br>
                <input type="password" name="gswa_password" autocomplete="current-password" required>
            </p>
            <p>
    <a href="http://gswajune20.local/forgot-password/" id="forget-password">Forgot Password?</a>
</p>


            

            <p>
                <button type="submit">Sign In</button>
            </p>
            

        </form>

    </div>

    <?php
   // Return the clean buffer content
    return ob_get_clean();
}
