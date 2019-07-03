<?php
  ini_set('display_errors', 1);

/* functions file */
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
       include 'index.php';
       exit(); }
    
    
//make sure extra payment is not a negative number
   if ($extra_payment < 0){
       $extra_payment = 0;
   }


//initialize the monthly interest rate
   $interest = calc_monthly_int($interest_rate);

//if the loan is entered as years, convert to months
   loan_months($time_unit, $loan_length);


//create a time stamp for current date
   $date = new DateTime('now');


//calculate the monthly payment using A = P((r(1+r)^n)/((1+r)^n - 1))
   $monthly_payment = $loan_amount * (($interest * pow($interest + 1, $loan_length)) / (pow($interest + 1, $loan_length) - 1));


//calculate the total interest paid when there is no extra payment
   $total_interest = ($monthly_payment * ($loan_length)) - $loan_amount;


//format variables for the summary table
   $loan_amount_f = '$'. number_format($loan_amount, 2);
   $monthly_payment_f = '$' . number_format($monthly_payment, 2);
   $extra_payment_f = '$' . number_format($extra_payment, 2);
   $interest_rate_f = $interest_rate.'%';
   $months_saved_f = $loan_length;

include 'includes/header.php';
?>
        <main class="grid">
            <div class="box">
                <img src="img/amort_sched/amortizationschedule.png" alt="Amortization Schedule" height="40">
                <div id="grid_amortization">
                    <div>
                        <img src="img/amort_sched/pmtno.png" alt="payment number">
                    </div>
                    <div>
                        <img src="img/amort_sched/duedate.png" alt="due date">
                    </div>
                    <div>
                        <img src="img/amort_sched/monthlypayment1.png" alt="monthlypayment">
                    </div>
                    <div>
                        <img src="img/amort_sched/paymentinterest.png" alt="payment interest">
                    </div>
                    <div>
                        <img src="img/amort_sched/paymentprinciple.png" alt="payment principle">
                    </div>
                    <div>
                        <img src="img/amort_sched/extraprinciple.png" alt="extra principle">
                    </div>
                    <div>
                        <img src="img/amort_sched/loanbalance.png" alt="loan balance">
                    </div>
                    <?php 
                    
//Set a remaining balance variable and a variable for adding the interest
  $remaining_balance = $loan_amount;
  $full_interest = 0;

//This for loop will calculate all of the variables that will populate the table.
//It will take into account the extra payment option and calculate the variations
//that will occur when the last payment is reached.
   for ($i = 1; $i <= $loan_length; $i++) {
   
    //if there is an extra payment, these two options will calculate the final numbers for the last payment
    //taking into account whether or not the extra payment is needed for that final payment
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
       
    //if there is no extra payment, this will populate the complete summary table
       $monthly_interest = $remaining_balance * $interest;
       $principle = $monthly_payment - $monthly_interest;
       $remaining_balance = $remaining_balance - $principle - $extra_payment;
       $full_interest += $monthly_interest;
       display_table($date, $i, $monthly_payment, $monthly_interest, $principle, $extra_payment, $remaining_balance);
   }
   
//because of the way the for loop is structured, if there is an extra payment, the loan length could be off by one
//this corrects that problem
   if ($i > $loan_length){
       $i = $loan_length;
   }
   
//format variables for the summary table
   $full_interest_f = '$' . number_format($full_interest, 2);
   $total_amt_f = '$' . number_format($loan_amount + $full_interest, 2);
   $interest_saved_f = '$' . number_format(abs($total_interest - $full_interest), 2);
   $months_saved_f -= $i;
                    ?>  
                </div>
            </div>
            <div class="box">
                <img src="img/loan_summary/loansummary.png" alt="Loan Summary" height="40">
                <div id="grid_summary">
                    <div class="1">
                        <img src="img/loan_summary/loanamount.png" alt="loan amount">
                    </div>
                    <div class="2">
                        <?php echo $loan_amount_f; ?></div>
                    <div>
                        <img src="img/loan_summary/interestrate.png" alt="interest rate">
                    </div>
                    <div>
                        <?php echo $interest_rate_f; ?></div>
                    <div>
                        <img src="img/loan_summary/loanmonths.png" alt="number of months">
                    </div>
                    <div>
                        <?php echo $i; ?></div>
                    <div>
                        <img src="img/loan_summary/monthlypayment.png" alt="monthly payment">
                    </div>
                    <div>
                        <?php echo $monthly_payment_f; ?></div>
                    <div>
                        <img src="img/loan_summary/totalinterest.png" alt="total interest">
                    </div>
                    <div>
                        <?php echo $full_interest_f; ?></div>
                    <div>
                        <img src="img/loan_summary/extrapayment.png" alt="extra payment">
                    </div>
                    <div>
                        <?php echo $extra_payment_f; ?></div>
                    <div>
                        <img src="img/loan_summary/totalpaid.png" alt="total amount paid">
                    </div>
                    <div>
                        <?php echo $total_amt_f; ?></div>
                    <div>
                        <img src="img/loan_summary/interestsaved.png" alt="interest saved">
                    </div>
                    <div>
                        <?php echo $interest_saved_f ?></div>
                        <div>
                        <img src="img/loan_summary/monthssaved.png" alt="months saved">
                    </div>
                    <div>
                        <?php echo $months_saved_f; ?></div>
                </div>
            </div>
        </main>
        <?php include 'includes/footer.php'; ?>
    </body>
</html>