<?php
include 'includes/functions.php';
// get the data from the form
$loan_amount = filter_input(INPUT_POST, 'loan_amount',
                            FILTER_VALIDATE_FLOAT);
$interest_rate = filter_input(INPUT_POST, 'interest_rate',
                              FILTER_VALIDATE_FLOAT);
$loan_length = filter_input(INPUT_POST, 'loan_length',
                            FILTER_VALIDATE_INT);
$time_unit = filter_input(INPUT_POST, 'time_unit');
$extra_payment = filter_input(INPUT_POST, 'extra_payment',
                              FILTER_VALIDATE_FLOAT);
// validate Loan amount
validate($error_message, $loan_amount, $interest_rate, $loan_length, $time_unit);
// if an error message exists, go to the index page
if ($error_message != '') {
    include'index.php';
    exit(); }
//make sure extra payment is not a negative number
if ($extra_payment < 0){
$extra_payment = 0;
}
//initialize the monthly interest rate
$interest = calc_monthly_int($interest, $interest_rate);
//make sure the loan length is in months and not years
loan_months($time_unit, $loan_length);
//create a time stamp for current date
$date = new DateTime('now');
//calculate the monthly payment
$monthly_payment = $loan_amount * (($interest * pow($interest + 1, $loan_length)) / (pow($interest + 1, $loan_length) - 1));
//calculate the total interest paid when there is no extra payment
$total_interest = ($monthly_payment * ($loan_length)) - $loan_amount;

//format for the summary table
$loan_amount_f = '$'. number_format($loan_amount, 2);
$monthly_payment_f = '$' . number_format($monthly_payment, 2);
$extra_payment_f = '$' . number_format($extra_payment, 2);
$interest_rate_f = $interest_rate.'%'
?>
<!DOCTYPE html>
<html>
    <head>
        <title>
            Loan Amortization Calculator
        </title>
        <link rel="stylesheet" type="text/css" href="styles/main.css">
    </head>
    <body>
        <header>
            <img src="img/loancalc.png" alt="Loan Calculator">
        </header>
        <main class="grid">
            <div class="box">
                <img src="img/amortizationschedule.png" alt="Amortization Schedule" height="50px">
                <div id="grid_amortization">
                    <div>
                        Pmt
                        <br>
                        No.
                    </div>
                    <div>
                        Due
                        <br>
                        m-d-y
                    </div>
                    <div>
                        Monthly
                        <br>
                        Payment
                    </div>
                    <div>
                        Payment
                        <br>
                        Interest
                    </div>
                    <div>
                        Payment
                        <br>
                        Principle
                    </div>
                    <div>
                        Extra
                        <br>
                        Principle
                    </div>
                    <div>
                        Loan
                        <br>
                        Balance
                    </div>
                    <?php 
//Set the remaining balance variable
$remaining_balance = $loan_amount;

//This for loop will calculate all of the variables that will populate the table.
//It will take into account the extra payment option and calculate the variations
//that will occur when the last payment is reached.
for ($i = 1; $i <= $loan_length; $i++) {
    if ($monthly_payment > ($remaining_balance * $interest) + $remaining_balance){
        $monthly_interest = $remaining_balance * $interest;
        $principle = $remaining_balance;
        $monthly_payment = $remaining_balance + $monthly_interest;
        $extra_payment = 0;
        $remaining_balance = 0;
        $full_interest += $monthly_interest;
        display_table($date, $i, $monthly_payment, $monthly_interest, $principle, $extra_payment, $remaining_balance);
        break;
    }elseif($monthly_payment + $extra_payment > ($remaining_balance * $interest) + $remaining_balance ) {
        $monthly_interest = $remaining_balance * $interest;
        $principle = $monthly_payment - $monthly_interest;
        $extra_payment = $remaining_balance - $monthly_payment;
        $remaining_balance = 0;
        $full_interest += $monthly_interest;
        display_table($date, $i, $monthly_payment, $monthly_interest, $principle, $extra_payment, $remaining_balance);
        break;
    } 
    $monthly_interest = $remaining_balance * $interest;
    $principle = $monthly_payment - $monthly_interest;
    $remaining_balance = $remaining_balance - $principle - $extra_payment;
    $full_interest += $monthly_interest;
    display_table($date, $i, $monthly_payment, $monthly_interest, $principle, $extra_payment, $remaining_balance);
}
if ($i > $loan_length){
	$i = $loan_length;
}
//format for the summary table
$full_interest_f = '$' . number_format($full_interest, 2);
$total_amt_f = '$' . number_format($loan_amount + $full_interest, 2);
$interest_saved_f = '$' . number_format(abs($total_interest - $full_interest), 2);
                     ?>  
                </div>
            </div>
            <div class="box">
                <img src="img/loansummary.png" alt="Loan Summary" height="50px">
                <div id="grid_summary">
                    <div class="1">
                        Loan Amount:
                    </div>
                    <div class="2">
                        <?php echo $loan_amount_f; ?></div>
                    <div>
                        Yearly Interest Rate:
                    </div>
                    <div>
                        <?php echo $interest_rate_f; ?></div>
                    <div>
                        Number of Months:
                    </div>
                    <div>
                        <?php echo $i; ?></div>
                    <div>
                        Monthly Payment:
                    </div>
                    <div>
                        <?php echo $monthly_payment_f; ?></div>
                    <div>
                        Total Interest:
                    </div>
                    <div>
                        <?php echo $full_interest_f; ?></div>
                    <div>
                        Additional Principle:
                    </div>
                    <div>
                        <?php echo $extra_payment_f; ?></div>
                    <div>
                        Total Amount Paid:
                    </div>
                    <div>
                        <?php echo $total_amt_f; ?></div>
                    <div>
                        Interest Saved:
                    </div>
                    <div>
                        <?php echo $interest_saved_f ?></div>
                </div>
            </div>
        </main>
        <?php include '../../footer.php'; ?>
    </body>
</html>
