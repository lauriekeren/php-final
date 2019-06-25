<?php 
//validate input from the form
function validate(&$error_message, $loan_amount, $interest_rate, $loan_length, $time_unit, $extra_payment){
// validate Loan amount
if ($loan_amount === FALSE ) {
    $error_message = 'Loan amount must be a valid number.'; 
} else if ( $loan_amount <= 0 ) {
    $error_message = 'Loan Amount must be greater than zero.'; 
    // validate interest rate
} else if ( $interest_rate === FALSE )  {
    $error_message = 'Interest rate must be a valid number.'; 
} else if ( $interest_rate <= 0 ) {
    $error_message = 'Interest rate must be greater than zero.'; 
} else if ( $interest_rate > 15 ) {
    $error_message = 'Interest rate must be 15 or less.';
    // validate years
} else if ( $loan_length === FALSE ) {
    $error_message = 'Loan length must be a valid whole number.';
} else if ( $loan_length <= 0 ) {
    $error_message = 'Loan length must be greater than zero.';
} else if ( $time_unit == 'years' && $loan_length > 30 ) {
    $error_message = 'Years must be less than 31.';
} else if ($time_unit =='months' && $loan_length > 360 ) {
    $error_message = 'Months must be less than than 361.';
    // set error message to empty string if no invalid entries
} else {
    $error_message = ''; 
	}
}
//calculate the monthly interest rate 
function calc_monthly_int($interest, $interest_rate){
// calculate the monthly interest rate
$interest = ($interest_rate / 100) / 12;
return $interest;
}
//convert loan years to loan months
function loan_months($time_unit, &$loan_length){
//make sure the loan length is in months
if ($time_unit == 'years'){
    $loan_length = $loan_length * 12;
	}
}
//display the amortization chart
function display_table($date, $i, $monthly_payment, $monthly_interest, $principle, $extra_payment, $remaining_balance){
$date->modify('first day of next month');
echo '<div>' . $i . '</div>
<div>' .$date->format('m/d/Y') .'</div>
<div>$' . number_format($monthly_payment,2) . '</div>
<div>$' . number_format($monthly_interest,2) . '</div>
<div>$' . number_format($principle,2) . '</div>
<div>$' . number_format($extra_payment,2) . '</div>
<div>$' . number_format($remaining_balance,2) . '</div>';
}
?>
