<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


/**
 * Shortcode: [gswa_member_application]
 */
add_shortcode( 'gswa_member_application', 'gswa_member_application_form' );


//Tetsing mobile end
function gswa_member_application_form() {

    //ob_start(); // moving after user login

            // Ensure applicant is logged in.
        if ( ! is_user_logged_in() ) {

            return '<p>Please sign in before submitting your application.</p>';
        }
      ob_start(); // moved here
        // Retrieve logged-in WordPress user.
        $current_user = wp_get_current_user();

        // Registered email.
        $registered_email = $current_user->user_email;

        // Registered mobile.
        $registered_mobile = get_user_meta(
            $current_user->ID,
            'gswa_mobile',
            true
        );
    ?>

    <form id="gswa-membership-application" method="post" enctype="multipart/form-data" novalidate>
        <h2>Gopalganj Social Welfare Association (GSWA)</h2>
    <p style="color: #ff0000;"><strong>Membership Application Form</strong></p>
         <?php wp_nonce_field( 'gswa_application_action', 'gswa_application_nonce' ); ?>
        <fieldset>
            <legend>Eligibility, Native Upazila & Membership Type</legend>

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
    <!-- Select Native Upazila  -->
    <div class="form-group">
        <label for="native_upazila">Native Upazila:</label>
        <select id="native_upazila" name="native_upazila" required>
            <option value="" disabled selected>Select native upazila</option>
            <option value="gopalganj">Gopalganj Sadar</option>
            <option value="muksudpur">Muksudpur</option>
            <option value="kashiani">Kashiani</option>
            <option value="kotalipara">Kotalipara</option>
            <option value="tungipara">Tungipara</option>
            <option value="other">Other - can apply DM or VM</option>
            
        </select>
    </div>

    <div class="form-group">
    <label for="memberType"> Membership Type:</label>
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

           
            <label for="applicant_full_name">Applicant Full Name: <span class="required">*</span></label>
             
            <input type="text"
                   id="applicant_full_name"
                   name="applicant_full_name"

                   value="<?php echo esc_attr( $current_user->display_name ); ?>"
                   placeholder="Name as per NID"
                  
                   pattern="[A-Za-z\s'-]+"
                   required>
       </div>
        <div class="form-group">
            
            <label for="father_full_name">Father Full Name: <span class="required">*</span></label>
            <input type="text"
                   id="father_full_name"
                   name="father_full_name"
                   pattern="[A-Za-z\s'-]+"
                   required
                   >
       </div>

        <div class="form-group">
            <!--<label for="mother_full_name">Mother's Name</label><br>-->
            <label for="mother_full_name">Mother Full Name: <span class="required">*</span></label>
            <input type="text"
                  id="mother_full_name"
                   name="mother_full_name"
                   pattern="[A-Za-z\s'-]+"
                   required>
        </div>
        <div class="form-group">
            <!--DOB-->
            
            <label for="birthday">Date of Birth: <span class="required">*</span></label>
            <input type="date" id="birthday" name="birthday" required>

            
</div>
        <div class="form-group">
            <!--NID-->
            
        <label for="nid">National ID (NID) Number: <span class="required">*</span></label>
         <input 
            type="text"
            id="nid"
            name="nid"
            pattern="([0-9]{10}|[0-9]{17})"
            inputmode="numeric"
            maxlength="17"
            placeholder="Enter your 10 or 17 digit NID number"
            required>
        <!-- NID Image: 100 KB–500 KB -->
        <!--<label for="nid_photo">Upload NID photo:</label>
        <input type="file" id="nid_photo" name="nid_photo" accept=".jpg,.jpeg,.png,.webp" required>-->
</div> 

<div class="form-group">

         
        <label for="pp_photo"> Passport Size Photo: <span class="required">*</span></label>
         <!-- PP size photo Image: 20 KB–300 KB -->
        <input type="file" id="pp_photo" name="pp_photo" accept=".jpg,.jpeg,.png,.webp" required>
        <small class="form-text text-muted">File size min 20 KB max 300 KB.</small>
</div>
 <div class="form-group">
           
            <!--<label for="blood_group">Blood group:</label>
            <input type="text" id="blood_group" name="blood_group" placeholder="Enter your Blood Group">
            -->
            <label for="blood_group">Blood Group: <span class="required">*</span></label>
                <select id="blood_group" name="blood_group" required>
                    <option value="" selected>Select Blood Group</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                </select>
</div>
    </fieldset>

     <fieldset>

            <legend>Address</legend>
    <div class="form-group">
        <label for="presentAddress">Present Address:<span class="required">*</span></label>
        <textarea id="presentAddress" name="presentAddress" rows="2" placeholder="Current address details"
        minlength="5" maxlength="150" pattern="[a-zA-Z0-9\s\.,#\-]+" 
      title="Enter a valid street address (letters, numbers, spaces, commas, periods, #, or hyphens)" required></textarea>
    </div>

        <!--<legend>Parmanet Address</legend>-->
        <div class="form-group">
        <label for="permanentAddress">Permanent Address:<span class="required">*</span></label>
        <textarea id="permanentAddress" name="permanentAddress" rows="2" placeholder="Permanent address details" minlength="5" 
      maxlength="150"  pattern="[a-zA-Z0-9\s\.,#\-]+" 
      title="Enter a valid street address (letters, numbers, spaces, commas, periods, #, or hyphens)" required></textarea>
    </div>
       
    </fieldset>


    <fieldset>
            <legend>Education & Occupation</legend>
      
       <div class="form-group">
            <!--Education-->
            <label for="mbr_education">Education: <span class="required">*</span></label>
            <input type="text" id="mbr_education" name="mbr_education" pattern="[A-Za-z\s'-]+" required>
</div>
<div class="form-group">
            <!--Profession-->
            <label for="mbr_profession">Profession:<span class="required">*</span></label>
            <input type="text" id="mbr_profession" name="mbr_profession" pattern="[A-Za-z\s'-]+" required>
</div>

           
    </fieldset>

     <fieldset>
            <legend>Contact Information</legend>
      <div class="form-group">
       <label for="mobile">Mobile Number</label>
        <input
            type="tel"
            id="mobile"
            name="mobile"
            value="<?php echo esc_attr( $registered_mobile ); ?>"
            readonly
            required
    >
    <small>county code with or without only valid</small>
    </div>
        <div class="form-group">

           <label for="email">Email Address:</label>
        <input type="email" 
        id="email" 
        name="email" 
        value="<?php echo esc_attr( $registered_email ); ?>"
        readonly
        required>
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

