<?php 
ini_set('display_errors', 1);

//set default value of variables for initial page load
if (!isset($loan_amount)) { $loan_amount = ''; } 
if (!isset($interest_rate)) { $interest_rate = ''; } 
if (!isset($loan_length)) { $loan_length = ''; }  
if (!isset($extra_payment)) { $extra_payment = ''; }  

/* include header */

include 'includes/header.php'; 

?>
<main>

    <!-- error message -->
    
    <?php if (!empty($error_message)) { ?>
    <p class="error">
        <?php echo htmlspecialchars($error_message); ?>
    </p>
    <?php } ?>
    
    <!-- form -->
    
    <form class="myForm" action="display_results.php" method="post">
    
        <!-- Enter Loan Amount -->
        
        <label>
        <div class="tooltip">
            <img src="img/loan_summary/loanamount.png" align="left" alt="loan amount">
            <span class="tooltiptext">This number will be the total amount you are borrowing.</span>
        </div>
            <span class="dollar_sign"><img src="img/index/dollarsign.png" alt="dollar sign"></span>
        </label>
        <input type="text" name="loan_amount" value="<?php echo htmlspecialchars($loan_amount); ?>" placeholder="How much?">

        <!-- Enter Interest Rate -->
        
        <label>
        <div class="tooltip">
            <img src="img/loan_summary/interestrate.png" align="left" alt="interest rate">
            <span class="tooltiptext">Your interest rate is typically stated as a yearly rate, such as 5.5%.</span>
        </div>
        </label>
        <input type="text" class="interest_rate" name="interest_rate" value="<?php echo htmlspecialchars($interest_rate); ?>" placeholder="% interest rate">

        <!-- Enter Loan Length -->
        
        <label>
        <div class="tooltip">
            <img src="img/loan_summary/loanlength.png" align="left" alt="loan length">
            <span class="tooltiptext">This is the full length of your loan in years or months. Enter whole years, or you may break it down to partial years and enter it as total months. 
            For instance, 10 years, 6 months would be 126 months (10 x 12 plus 6).</span>
        </div>
        </label>
        <input type="text" name="loan_length" placeholder="How long?" value="<?php echo htmlspecialchars($loan_length); ?>">

        <!-- Select Years or months -->
        
        <label>
        <div class="tooltip">
            <img src="img/loan_summary/yearsmonths.png" align="left" alt="choose years or months">
            <span class="tooltiptext">Select years or months.</span>
        </div>
        </label>
        <select name="time_unit" id="time_unit">
            <option value="years" selected="selected">Years</option>
            <option value="months" >Months</option>
        </select>
        
        <!-- Enter Optional Extra Payment -->
        
        <label>
        <div class="tooltip">
            <img src="img/loan_summary/extrapayment.png" align="left" alt="additional principle">
            <span class="tooltiptext">If you intend to add an additional principle payment each month, enter that amount here, otherwise, leave it blank. 
                Additional principle payments will result in significant savings over the life of your loan.</span>
        </div><span class="dollar_sign">&nbsp; &nbsp;<img src="img/index/dollarsign.png" alt="dollar sign"></span>
        </label>
        <input type="text" name="extra_payment" placeholder="Pay if off faster!" value="<?php echo htmlspecialchars($extra_payment); ?>">
        <button><img src="img/index/calculate1.png"></button>
    </form>
    
    <!-- Include footer -->
    
    <?php include 'includes/footer.php' ?>
    
</main>
</body>
</html>