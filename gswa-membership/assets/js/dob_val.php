$dob = DateTime::createFromFormat( 'Y-m-d', $date_of_birth );

if ( ! $dob ) {
    // Invalid date
}

$today = new DateTime();
$age = $today->diff( $dob )->y;

if ( $age < 18 ) {
    // Applicant is not eligible
}