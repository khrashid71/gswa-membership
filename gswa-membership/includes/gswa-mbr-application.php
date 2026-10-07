<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


/**
 * Shortcode: [gswa_member_application]
 */
add_shortcode( 'gswa_member_application', 'gswa_member_application_form' );

//Mobile validation
function applicant_validate_mobile( $mobile ) {

    return (bool) preg_match( '/^01[3-9]\d{8}$/', $mobile );

    
}
//Tetsing mobile end
function gswa_member_application_form() {

    ob_start();
    ?>

    <form method="post" enctype="multipart/form-data">
        <h2>Gopalganj Social Welfare Association (GSWA)</h2>
    <p>Membership Application Form</p>
         <?php wp_nonce_field( 'gswa_application_action', 'gswa_application_nonce' ); ?>
        <fieldset>
            <legend>Eligibility & Membership Type</legend>

            <!-- Gopalganj Eligibility Check -->
    <div class="form-group">
        <label for="eligibility">Eligibility:</label>
        <select id="eligibility" name="eligibility" required>
            <option value="" disabled selected>Select how you qualify</option>
            <option value="resident">I am a permanent resident of Gopalganj</option>
            <option value="born">I was born in Gopalganj District</option>
            <option value="living">I am living in Gopalganj for 3 years</option>
            <option value="styding">I am studying in Gopalganj</option>
            <option value="donor">other - want to become donor member</option>
            
        </select>
    </div>

    <div class="form-group">
    <label for="memberType"> Select Membership Type:</label>
        <select id="memberType" name="memberType" required>
            <option value="" disabled selected>Select type</option>
            <option value="donor">Donor Member(DM)</option>
            <option value="life_central">Life Member(LM)-Central</option>
            <option value="general_central">General Member(GM)-Central</option>
            <option value="life_upazila">Life Member(LM)-Upazila</option>
            <option value="general_upazila">General Member(GM)-Upazila</option>
            <option value="volunteer">Volunteer Member(VM)</option>
        </select>
           
</div>

        </fieldset>


          <fieldset>
            <legend>Personal Information</legend>

            
        <div class="form-group">

            <label for="applicant_full_name">Applicant's Name</label><br>
            <input type="text"
                   id="applicant_full_name"
                   name="applicant_full_name"
                   placeholder="As per NID"
                   pattern="[A-Za-z\s'-]+"
                   required>
       </div>
        <div class="form-group">
            <label for="father_full_name">Father's Name</label><br>
            <input type="text"
                   id="father_full_name"
                   name="father_full_name"
                   pattern="[A-Za-z\s'-]+"
                   required
                   >
       </div>

        <div class="form-group">
            <label for="mother_full_name">Mother's Name</label><br>
            <input type="text"
                  id="mother_full_name"
                   name="mother_full_name"
                   pattern="[A-Za-z\s'-]+"
                   required>
        </div>
        <div class="form-group">
            <!--DOB-->
            <label for="birthday">Date of Birth:</label>
            <input type="date" id="birthday" name="birthday" required>

            
</div>
        <div class="form-group">
            <!--NID-->
            
        <label for="nid">National ID (NID) Number:</label>
        <input type="text" id="nid" name="nid" pattern="[0-9]{10,17}" placeholder="10 or 17 digit number" required>
        <label for="nid_photo">Upload NID photo:</label>
        <input type="file" id="photo_nid" name="photo_nid" accept="image/*" required>
</div> 

<div class="form-group">

         
        <label for="applicant_photo"> Passport Size Photo:</label>
         
        <input type="file" id="photo" name="photo" accept="image/*" required>
    
</div>
        <div class="form-group">
            <!--Blood Group-->
            <label for="blood_group">Blood group:</label>
            <input type="text" id="blood_group" name="blood_group">
</div>
    </fieldset>

     <fieldset>

            <legend>Address</legend>
    <div class="form-group">
        <label for="presentAddress">Present Address:</label>
        <textarea id="presentAddress" name="presentAddress" rows="2" placeholder="Current address details" required></textarea>
    </div>

        <!--<legend>Parmanet Address</legend>-->
        <div class="form-group">
        <label for="permanentAddress">Permanent Address:</label>
        <textarea id="permanentAddress" name="permanentAddress" rows="2" placeholder="Permanent address details" required></textarea>
    </div>
       
    </fieldset>


    <fieldset>
            <legend>Education & Occupation</legend>
      
       <div class="form-group">
            <!--Education-->
            <label for="education_level">Education:</label>
            <input type="text" id="mbr_education" name="mbr_education" required>
</div>
<div class="form-group">
            <!--Profession-->
            <label for="profession-occupation">Profession:</label>
            <input type="text" id="mbr_profession" name="mbr_profession" required>
</div>

           

        
    </fieldset>

     <fieldset>
            <legend>Contact Information</legend>
      <div class="form-group">
        <label for="phone">Mobile Number:</label>
        <input type="tel" id="phone" name="phone" pattern="01[3-9][0-9]{8}" placeholder="01XXXXXXXXX" required>
    </div>
        <div class="form-group">

           <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" placeholder="example@mail.com" required>
</div>
        
    </fieldset>
     
        
        
    
     <!-- Declaration Statement -->
    <div class="form-group checkbox">
        <input type="checkbox" id="declaration" name="declaration" required>
        <label for="declaration">
            I declare that I am at least 18 years old, interested in implementing the goals of GSWA, and will abide by the rules and constitution of the association.
        </label>
    </div>

       <div class="form-group">
            <input type="submit"
                   name="gswa_application_submit"
                   value="Submit Application">
</div>

    </form>

    <?php

    return ob_get_clean();
}


/**
 * Process application form
 */
add_action( 'init', 'gswa_process_member_application' );

function gswa_process_member_application() {

    if ( ! isset( $_POST['gswa_application_submit'] ) ) {
        return;
    }

    //=======adding nonce=========
     if (
        ! isset( $_POST['gswa_application_nonce'] ) ||
        ! wp_verify_nonce(
            sanitize_text_field( wp_unslash( $_POST['gswa_application_nonce'] ) ),
            'gswa_application_action'
        )
    ) {
        wp_die( 'Security verification failed. Please refresh the page and try again.' );
    }
    //end nonce
   //==========Sanitize===============
    $applicant_name = sanitize_text_field(
        $_POST['applicant_full_name'] ?? ''
    );
    

    $father_name = sanitize_text_field(
        $_POST['father_full_name'] ?? ''
    );


    $mother_name = sanitize_text_field(
        $_POST['mother_full_name'] ?? ''
    );

    $date_of_birth= sanitize_text_field(
        $_POST['birthday'] ?? ''
    );

        
    $national_id= sanitize_text_field(
        $_POST['nid'] ?? ''
    );

    $blood_group = sanitize_text_field(
        $_POST['blood_group'] ?? ''
    );

    $mobile = sanitize_text_field(
         $_POST['phone'] ?? '' 
    );

    $email = sanitize_text_field(
         $_POST['email'] ?? '' 
    );

   //=================Validating======================


// Validate required fields-added mobile
if ( empty( $applicant_name ) ||
     empty( $father_name ) || 
     empty($mother_name)||
     empty( $mobile )||
     empty($email)){
     

    echo '<p>Please complete all required fields.</p>';

   } 
   
   if ( ! preg_match( "/^[A-Za-z\s'-]+$/", $applicant_name ) ) {

    wp_die( 'Applicant Name must contain English letters only.' );

  } 
  if ( ! preg_match( "/^[A-Za-z\s'-]+$/", $father_name) ) {

   wp_die('Father Name must contain English letters only.');

}

  if ( ! preg_match( "/^[A-Za-z\s'-]+$/", $mother_name) ) {

    wp_die( 'Mother Name must contain English letters only.');

} 

if ( ! is_email( $email ) ) {

    wp_die ('Please enter a valid email address.');

//validation mobileno
} 
 if ( ! applicant_validate_mobile( $mobile ) ) {

    wp_die('Please enter a valid Bangladesh mobile number.');

 }



//==============================================



    // Temporary testing
    
    error_log( 'Applicant Name: ' . $applicant_name );
    error_log( 'Father Name: ' . $father_name );
    error_log( 'Mother Name: ' . $mother_name );
    error_log( 'Date of Birth: ' . $date_of_birth );
    error_log( 'NID: ' . $national_id );
    error_log( 'Blood Group: ' . $blood_group );
}