<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<?php 
function send_mail($to, $from, $subject, $message, $fname)
{
   
    $header = "From: ".$fname."<".$from."> \r\n";
    $header .= "Reply-To: ".$from."\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-type:text/html; charset=UTF-8\r\n";
    $header .= "Content-Transfer-Encoding: 7bit \r\n";
    $header .= 'X-Mailer: PHP/' . phpversion();

    if(mail($to, '=?UTF-8?B?'.base64_encode($subject).'?=', $message, $header))
    {
        return true;
    }
    else
    {
        return false;
    }
}
?>
<script>
function ValidateEmail(inputText)
{
var mailformat = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
if(inputText.value.match(mailformat))
{
//alert("Valid email address!");
document.send.email.focus();
return true;
}
else
{
//alert("You have entered an invalid email address!");
document.send.email.focus();
return false;
}
}
function checkValid(){
	if(ValidateEmail(document.send.email)){
		document.getElementById("setSb").disabled = false;
	}else{
		document.getElementById("setSb").disabled = true;	
	}
}
</script>
<?php
if(!isset($_POST["submit_otp"]) && !isset($_POST["confirm_otp"])){

	$otp_num = rand(1,99999);
	$opt_num = str_pad($otp_num, 5, '0', STR_PAD_LEFT);
	//echo $opt_num."<br>";

	$string_md5 = md5($otp_num);
	//echo $string_md5;
	$pos = rand(1,9);
	$string_md5 = $pos.substr($string_md5,0,$pos).$otp_num.substr($string_md5,$pos);
	//echo $string_md5."<br>";
	?>
	<div align='center'><form name="send" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
	<input type="text" name="email" placeholder="Input your email..." onChange="checkValid();"><br>
	<input type="hidden" name="otp_check" value="<?php echo $string_md5; ?>">
	<input type="submit" name="submit_otp" id="setSb" disabled value="Send OTP Code">
	</form></div>
	
	<?php
}else if(isset($_POST["submit_otp"]) && !isset($_POST["confirm_otp"])){
	$string_md5 = $_POST["otp_check"];
	$pos = substr($string_md5,0,1);
	$otp_num = substr($string_md5,$pos+1,5);
	$string_md5 = str_replace($otp_num,"",substr($string_md5,1));
	//echo $otp_num."<br>";
	//echo md5($otp_num);
	send_mail($_POST["email"], "dai.ca@buidoicholon.vn", "OTP Code", "Your OTP code is: ".$otp_num, "Big Sugar")
	//mail($_POST["email"],"Your OTP code","Your OTP code is: ".$otp_num);
	?>
	<div align='center'><form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
	<input type="text" name="otp" placeholder="Input your OTP code"><br>
	<input type="hidden" name="otp_check" value="<?php echo $string_md5; ?>">
	<input type="submit" name="confirm_otp" id="btnCounter" value="Submit OTP Code"><br>
	<span id="count"></span> second(s) for input OTP
	<script>
	var spn = document.getElementById("count");
	var btn = document.getElementById("btnCounter");

	var count = 25;     // Set count
	var timer = null;  // For referencing the timer

	(function countDown(){
	  // Display counter and start counting down
	  spn.textContent = count;
	  
	  // Run the function again every second if the count is not zero
	  if(count !== 0){
	    timer = setTimeout(countDown, 1000);
	    count--; // decrease the timer
	  } else {
	    //btn.setAttribute("disabled");
		btn.disabled = true;	
	  }
	}());
	</script>
	</form></div>
	<?php
}else if(isset($_POST["confirm_otp"])){
	$otp_num = $_POST["otp"];
	$string_md5 = $_POST["otp_check"];
	if(md5($otp_num) == $string_md5){
		echo "<div align='center'>Okay.</div>";
	}else{
		echo "<div align='center'><font color='red'>Invalid Code.</font></div>";
	}
}

?>
