<!DOCTYPE html>
<!-- saved from url=(0059)https://preview.colorlib.com/theme/bootstrap/login-form-17/ -->
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Login 07</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="css" rel="stylesheet">
    <link rel="stylesheet" href="font-awesome.min.css">
    <link rel="stylesheet" href="style.css">

</head>

<body cz-shortcut-listen="true">

    <section class="ftco-section">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-md-12 col-lg-10">
                    <div class="wrap d-md-flex">

                        <div class="login-wrap p-4 p-lg-5">
                            <center class="mb-5">
                                <img src="icon.png" class="avatar">
                            </center>
                            <!-- done -->


                            <form name="loginManagerForm" action="index.php" method="post"
                                enctype="multipart/form-data">
                                <h4>Online Report User Login</h4>
                                <div class="form-group">
                                    <input type="text" name="billnumber" class="form-control" placeholder="ID Number"
                                        required>
                                </div>
                                <input type="text" name='AYEAR' id='AYEAR' value='<?php echo date("Y") ?>'
                                    style="display: none;" />


                                <div class="form-group">
                                    <input type="text" name="mobilenumber" class="form-control"
                                        placeholder="Mobile Number" required>
                                </div>

                                <center>
                                <input type="submit" class="button" name="submit" value="LOGIN">
                                </center>
                                    
                               
                            </form>
                            <center>
                                <h4>RETIRED GOVERNMENT EMPLOYEES MEDICAL CONSULTANCY AND DIAGNOSTIC CENTER</h4>
                            </center>
                        </div>



                        <?php 
				session_start();
				if(isset($_POST["submit"]))
				{
				include('connection_hospital.php');

				$billnumber=trim($_POST['billnumber']); 
				$mobilenumber=trim($_POST['mobilenumber']); 
				$AYEAR=$_POST['AYEAR'];
				$billnumber = stripslashes($billnumber);
				$mobilenumber = stripslashes($mobilenumber);
				$qCheckPath="Select COUNT(*) as TotalTest
				  from PATIENT_DTL_INFO D
				  right join PATIENT_MST_INFO M
				  on D.PATIENT_MST_INFO_ID=M.PATIENT_MST_INFO_ID
				  inner join REPORT_TYPE_INFO R
				  on D.REPORT_TYPE_INFO_ID=R.REPORT_TYPE_INFO_ID
				  where R.PATH_REPORT=1 
				  and M.BILL_NO=?
				  and M.PATIENT_MST_INFO_ID=
				  (Select top 1 PATIENT_MST_INFO_ID from PATIENT_MST_INFO 
				   where BILL_NO=? order by 1 desc)";
				$parmscheckpath=array($billnumber,$billnumber);
				$optionscheckpath=array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
				$resultcheckpatch= sqlsrv_query($conn, $qCheckPath,$parmscheckpath,$optionscheckpath);

				if( $resultcheckpatch === false ) {
					print( print_r( sqlsrv_errors() ) );
				}
				$rowcheckpatch = sqlsrv_fetch_array( $resultcheckpatch, SQLSRV_FETCH_ASSOC);
				$IsReport=$rowcheckpatch['TotalTest'];

				if($IsReport === 0)
				{   
					echo "<span style='color:blue; margin-top: 25px;align=center;'>Dear Customer,You can see only pathological report.</span> <br>";

				}
				else {
				//for due strSQL = "SELECT BILL_AMT-PAID-MR_AMT+RF_AMT as DUE_AMT,RF_AMT,MR_AMT FROM PATH_BILL_VIEW WHERE PATIENT_MST_INFO_ID=" & txtPATIENT_MST_INFO_ID
				$sql="SELECT BILL_NO,Cast(ISNULL(BILL_AMT,0)-ISNULL(PAID,0)-ISNULL(MR_AMT,0)+ISNULL(RF_AMT,0) as int) as due
				 FROM PATH_BILL_VIEW_ONLINE WHERE BILL_NO=? and ADDRESS=?
				  and PATIENT_MST_INFO_ID=
				  (Select top 1 PATIENT_MST_INFO_ID from PATIENT_MST_INFO 
				   where BILL_NO=? order by 1 desc)";

				$params = array($billnumber,$mobilenumber,$billnumber);
				$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

				$result= sqlsrv_query($conn, $sql,$params,$options);

				if( $result === false ) {
					print( print_r( sqlsrv_errors() ) );
				}
				else
				{
				$count= sqlsrv_num_rows($result);

				$row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC);
					
				$user_name = trim($billnumber);

				if ($count === 1)
				{
				$due=$row['due'];
				 if($due>0)
				 {
					 //if($due!=$mrAmount)
					 //{         
								 echo "<span style='color:blue; margin-top: 25px;align=center;'>প্রিয় গ্রাহক, আপনার বিলটি অপরিশোধিত</span> <br>";
								 /*echo "<tr>
									<td style='color:blue;font-weight:bold; font-size:16px;' align='center'>
									<span>প্রিয় গ্রাহক, আপনার বিলটি অপরিশোধিত</span>";
									//<br>report.ibnsinatrust.com ভিজিট করার জন্য ধন্যবাদ ";
									echo "
									 <br><br></td>
								   </tr>";*/
							 //echo("<SCRIPT LANGUAGE='JavaScript'>
							 //window.alert('You Have a due, please paid first then login and see report')
							 //</SCRIPT>");
							 //echo"<script language='javascript'>window.location.href='index.php'</script>";
							 //die();
					 //}

				 }
				 else{
							//for otp check start
							 $sqlotp="SELECT Bill_NO FROM ONLINE_REPORT_OTP
								   WHERE BILL_NO=? and MOBILE_NO=?";

							$paramsotp = array($billnumber,$mobilenumber);
							$optionsotp =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

							$resultotp= sqlsrv_query($conn, $sqlotp,$paramsotp,$optionsotp);

							if( $resultotp === false ) {
								print( print_r( sqlsrv_errors() ) );
								//echo 'Invalid bill no or mobile number';
							}
							else
							{
									$countotp= sqlsrv_num_rows($resultotp);

									$rowotp = sqlsrv_fetch_array( $resultotp, SQLSRV_FETCH_ASSOC);
									if ($countotp >= 1)
									{
										$_SESSION['billnumber']=$user_name;
										$_SESSION['AYEAR'] = $AYEAR;
										header("location:otpindexprevious.php");
									}
									else{

											$_SESSION['billnumber']=$user_name;
											$_SESSION['AYEAR'] = $AYEAR;
											$otp=mt_rand(100000,999999);
											$mobilenumber=trim($_POST['mobilenumber']); 
											$txtMessage="Your otp is ".$otp." Please keep this number to view the report again.";
											
											$message  = urlencode($txtMessage);
											//$URL ="http://message.iatlbd.com:8080/smsapiv3?apikey=67c6a1e7ce56d3d6fa748ab6d9af3fd7&sender=IBN+SINA&msisdn=".$mobilenumber."&smstext=".$message;
											//$URL ="http://bangladeshsms.com/smsapi?api_key=C20012725aa780bfabf0e5.85622132&type=text&senderid=IBN+SINA&contacts=".$mobilenumber."&msg=".$message;			
											//$URL ="http://smpp.ajuratech.com:7788/sendtext?apikey=J8HZyp82oe7DHoIA&callerID=IBN+SINA&secretkey=8ad9d8e4&toUser=88".$mobilenumber."&messageContent=".$message;			
											
											   $URL ="http://bulksmsbd.net/api/smsapi?api_key=3mYSLdCtNoTY53Z94lOa&type=text&senderid=03590900025&number=".$mobilenumber."&message=".$otp;

												function SendSMS($URL)
												{
													$ch = curl_init();               
													curl_setopt($ch, CURLOPT_URL,$URL);
													curl_setopt($ch, CURLOPT_HEADER, 0);
													curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
													curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);            
													curl_setopt($ch, CURLOPT_POST, 0);

												try{ 
													$output =  $content=curl_exec($ch);
													print_r($output);
												}catch(Exception $ex){  
												$output = "-100";
												}
												return $output;
												}
											 SendSMS($URL);
											
											$sqlotp = "INSERT INTO ONLINE_REPORT_OTP (BILL_NO, MOBILE_NO,OTP) VALUES (?, ?, ?)";
											$paramsotp = array(strtoupper($billnumber), $mobilenumber, $otp);

											$stmtotp = sqlsrv_query( $conn, $sqlotp, $paramsotp);
											if( $stmtotp === false ) {
												 die( print_r("Server failed", true));
											}
											   header("location:otpindex.php");
													
									   }
							}
					 }
				}
				else {
							
							 echo "<tr>
									<td style='color:blue;font-weight:bold; font-size:16px;' align='center'>
									<span style='color:red;font-weight:bold; font-size:16px;'>Invalid ID NO or Mobile Number</span> <br>";
									echo "
									 </td>
								</tr>";
				}
				sqlsrv_free_stmt($result);
				sqlsrv_close($conn);
				}
				}
				}
					?>




                        </form>








                        <div class="text-wrap p-4 p-lg-5  d-flex  bg">
                            <div class="text w-100 mt">
                                <h4 class="text-white">Login Instruction</h4>
                                <div>
                                    <ul>
                                        <li><span>ওয়েব সাইট থেকে কেবল মাত্র প্যাথোলজি রিপোর্ট সংগ্রহ করা
                                                যাবে।</span>
                                        </li>
                                        <li><span>সর্ম্পূণ বিল পরিশোধ সাপেক্ষে এই ওয়েব সাইট থেকে রিপোর্ট সংগ্রহ
                                                করা
                                                যাবে।</span></li>
                                        <li><span>মানি রিসিটে উল্লিখিত নাম্বারটি আইডি নাম্বার এবং আপনার ফোন
                                                নাম্বার
                                                দিয়ে লগ ইন করুন, সাথে সাথে আপনার ফোনে একটি কোড নং পাবেন।</span></li>
                                        <li><span>প্রাপ্ত কোড নং ব্যবহার করে রিপোর্ট সংগ্রহ করুন, প্রয়োজনে প্রিন্ট
                                                করে
                                                নিন।</span></li>
                                        <li><span>Online এ প্রাপ্ত সকল রিপোর্ট machine generated এবং Verified,
                                                consultant কতৃক signature কৃত কপি প্রয়োজন হলে সংশ্লিষ্ট শাখার রিপোর্ট
                                                ডেলিভারি ডেস্ক
                                                থেকে
                                                সংগ্রহ করতে পারবেন।</span></li>
                                    </ul>
                                </div>

                            </div>
                        </div>





                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="jquery.min.js.download"></script>
    <script src="popper.js+bootstrap.min.js+main.js.pagespeed.jc.iqDpUqoRp9.js.download"></script>
</body>

</html>