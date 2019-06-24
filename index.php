<?php 
    //set default value of variables for initial page load
    if (!isset($loan_amount)) { $loan_amount = ''; } 
    if (!isset($interest_rate)) { $interest_rate = ''; } 
    if (!isset($loan_length)) { $loan_length = ''; }  
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Loan Calculator</title>
    <link rel="stylesheet" type="text/css" href="styles/main.css">
  </head>
  <body>
    <header>
      <img src="img/loancalc.png" alt="Loan Calculator">
    </header>
    <main>
      <?php if (!empty($error_message)) { ?>
      <p class="error">
        <?php echo htmlspecialchars($error_message); ?>
      </p>
      <?php } ?>
      <form class="myForm" action="display_results.php" method="post">
        <label><div class="tooltip">Loan Amount:
  <span class="tooltiptext">If this is a new loan, this number will be the total amount you are borrowing. If you have already made payments,
  
   look at your current statement, and enter the remaining amount of principle owed after the last payement.</span>
</div><span class="dollar_sign">$</span></label>

        <input type="text" name="loan_amount" value="<?php echo htmlspecialchars($loan_amount); ?>" placeholder="How much?">
        <label><div class="tooltip">Interest Rate:
  <span class="tooltiptext">Your interest rate is typically stated as a yearly rate, such as 5.5%.</span>
</div></label>
        <input type="text" class="interest_rate" name="interest_rate" value="<?php echo htmlspecialchars($interest_rate); ?>" placeholder="% interest rate">
        <label><div class="tooltip">Loan Length:
  <span class="tooltiptext">This is the full length of your loan in years or months. If this is an existing loan, subtract the number of payments you have already made from the original number of payments.</span>
</div></label>
        <input type="text" name="loan_length" placeholder="How long?" value="<?php echo htmlspecialchars($loan_length); ?>">
        <label><div class="tooltip">Years or Months:
  <span class="tooltiptext">If entering years, enter whole years. If you need to break it down to partial years, enter it as months. For
  
   instance, 10 1/2 years would be 12 x 10 = 120 months plus another 6 months, for a total of 126 months.</span>
</div></label>
        <select name="time_unit" id="time_unit">
    <option value="years" selected="selected">Years</option>
    <option value="months" >Months</option>
  </select>
        <label><div class="tooltip">Additional Principle: 
  <span class="tooltiptext">If you intend to add an additional principle payment each month, enter that amount here, otherwise, leave it blank. Additional principle payments will result in significant savings over the life of your loan.</span>
</div><span class="dollar_sign">&nbsp; &nbsp;$</span></label>
        <input type="text" name="extra_payment" placeholder="Pay if off faster!" value="<?php echo htmlspecialchars($extra_pmt); ?>"> 
        <button>Calculate</button>
      </form>
	  
      <?php include '../../footer.php' ?>
      </main>
 </body>
</html>
