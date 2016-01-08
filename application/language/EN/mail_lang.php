<?php
$lang = array(
				'FORGOTPASSWORD_MESSAGE'=>"We received a request to reset the password for the Reporta account registered to %email%. Your user name is: %username%
					    <br>
					    <br> To reset your password, %link%",
				'FORGOTPASSWORD_SUBJECT'=>'Reporta password reset request',
				
				'SOS_REQUEST_SUBJECT' =>"Request from %firstname% %lastname%",
				
				'SOS_REQUEST_MESSAGE' => 'You are receiving this email because %firstname% %lastname% has created a profile on Reporta, a journalist safety app, and has designated you as an App-Unlock contact. 
						<br>
						<br>To prevent unwanted access to information stored on the app, Reporta will lock if a Check-in is missed or an SOS is issued. 
						<br>
						<br>If %firstname% %lastname% takes an action that locks the app, you will be contacted by email with a link to a verification code required to unlock Reporta. %firstname% %lastname% may contact you by phone or email to request this code.  
						<br>
						<br>You and %firstname% %lastname% may want to discuss what security protocols to put in place in the event you receive this message. 
						<br>
						<br>To accept this request, please %link%
						<br>
						<br>Thank you for taking part in the security protocols for %firstname% %lastname%.
						<br>
						<br>Reporta is a mobile security app developed by the International Women&#39;s Media Foundation (IWMF) for iPhones and Android devices. Reporta is the only comprehensive security app available worldwide designed specifically for journalists.";',
						
				'WELCOME_SUBJECT' =>"Welcome to Reporta!",
				'WELCOME_MESSAGE' => "Reporta is a security app created by the International Women&#39;s Media Foundation (IWMF) for journalists working in dangerous environments. Designed in consultation with leading journalists and global security experts, Reporta is the only comprehensive global security app created specifically for the media community.
				    <br><br> 
				    Reporta empowers journalists to take control of their personal safety. Using Reporta, you can quickly issue security notifications containing multimedia files to designated contacts through the app’s three key functions:
				     <br><br>
				    1. Activate a Check-in system that creates a trail when you are working in potentially dangerous environments.
				     <br><br>
				    2. Create customized Alert messages when you or a colleague may be at risk.
				     <br><br>
				    3. Issue an SOS distress message with one simple touch of the phone.
				     <br><br>
				    Please take a moment to create your profile through the app and familiarize yourself with the features. There is information available on each page of the app if you click on the iButton (i). A complete set of instructions is also available in the app under the How to Use Reporta tab. 
				     <br><br>
				    If you are adding individuals to your contact Circles, you should provide them with instructions, preferably written, about what to do if alerted by Reporta. You will want these contacts to know what to do if you have missed a Check-in, sent an Alert, or activated an SOS.
				     <br><br>
				    Don’t forget to designate at least one contact in your Private Circle to serve as your App-Unlock contact. App-Unlock contacts will receive a link to a verification code required to unlock the app if you miss a Check-in or issue an SOS. 
				     <br><br>
				    We are always interested in user feedback.  Please email reporta@iwmf.org.  
				    <br><br>
				    Thank you to you and the countless journalists who continue to help us make Reporta an even better app to support the journalism community worldwide.
				    <br><br>
				    IWMF
				    ",
					
				'CREATE_CHECKIN_SUBJECT'=>'Check-in created through Reporta',
				'CREATE_CHECKIN_MESSAGE'=>'%firstname% %lastname% has created a Check-in using Reporta.
					    <br>
					    <br>This message confirms that the user has Checked-in at %location%
					    <br><br>%firstname% set the Check-in frequency to %frequency% Minutes
					    <br><br>Your next message from Reporta will be either a Check-in confirmation, a missed Check-in notice, or a message indicating that the Check-in has been closed.',
				
				'CREATE_CHECKIN_SMS' => "%firstname% %lastname has Checked-in at %location%. The next confirmation is in %frequency% minutes. Your next message from Reporta will be either a Check-in confirmation, a missed Check-in notice, or a message indicating that the Check-in has been closed.",
				
				
				
				'CLOSE_CHECKIN_SUBJECT'=>'Check-in closed',
				'CLOSE_CHECKIN_MESSAGE'=>"%firstname% %lastname% has closed a Check-in on Reporta.",
				'CLOSE_CHECKIN_SMS' => "%firstname% %lastname% has closed a Check-in on Reporta.",
				
				'CONFIRMED_CHECKIN_SUBJECT'=>"Check-in Confirmed",
				'CONFIRMED_CHECKIN_MESSAGE'=>"%firstname% %lastname% has Confirmed a Check-in on Reporta.<br><br>%firstname% %lastname% set the Check-in frequency to %frequency% Minutes <br><br>Your next message from Reporta will be either a Check-in confirmation, a missed Check-in notice, or a message indicating that the Check-in has been closed.",
				
				
				'ALERT_ISSUE_SUBJECT'=>"Alert issued through Reporta",
				'ALERT_ISSUE_MESSAGE'=>"%firstname% has activated an Alert using Reporta on %datetime% at %location%
						<br><br>%firstname% has reported the following issue:<br><br>%situation%
						<br><br>Please activate the security protocols you have established with %firstname%",
				'ALERT_ISSUE_SMS'=>"%firstname% %lastname% has activated an Alert using Reporta at %location% on %datetime%. %firstname% %lastname% has reported the following issue: %situation%",
				
				
				'APP_UNLOCK_SUBJECT' => 'App-Unlock Verification Code',
				
				'APP_UNLOCK_MESSAGE' => "You are registered to receive an App-Unlock message generated by Reporta on behalf of %firstname% %lastname%. 
					<br>
					<br>You will also receive a separate alert message from Reporta with more details.
					<br>
					<br>App-Unlock verification retrieval code: %link%
					<br>
					<br>Please provide the App-Unlock verification code only after you are sure that the request is from %firstname% %lastname%. We recommend that this verification take place by telephone rather than in writing. 
					<br>
					<br>Reporta is a mobile security app developed by the International Women's Media Foundation (IWMF) for iPhones and Android devices. Reporta is the only comprehensive security app available worldwide designed specifically for journalists.",
					
				
				'SOS_ISSUED_SUBJECT' =>'SOS issued through Reporta',
				'SOS_ISSUED_MESSAGE' =>"%firstname% %lastname% has activated an SOS using Reporta on %datetime%.
				<br><br>
				Please activate the security protocols you have established with %firstname% %lastname%",
				
				'SOS_ISSUED_SMS' =>"%firstname% %lastname% has activated an SOS using Reporta . Please activate the security protocols you have established with %firstname% %lastname%",
				
				
				'PASSWORD_CHANGE_SUBJECT'=>'Reporta password change confirmation',
				
				'PASSWORD_CHANGE_MESSAGE'=>"The password for the Reporta account registered to %email% has been updated.
								    <br/>
								    <br/>If you did not request this change and believe your account has been compromised, please contact us at support@reporta.org.",
									
									
				'OTPGENERATOR_TEXT' => "App-Unlock Code Request",
				'OTPGENERATOR_TEXT1' => "%firstname% %lastname% has designated you as an App-Unlock contacts for Reporta, the journalist safety app.
				    <br/>
				    <br/>To prevent unwanted access to information stored on the app, Reporta will lock to prevent access if %firstname% %lastname% misses a secheduled Check-in or issues an SOS alert. %firstname% %lastname% will then need you to provide an App-Unlock code to unlock Reporta.
				    <br/>
				    <br/>To generate an App-Unlock code <span id='otp' ><a href='javascript:void(0)'>click here.</a></span><label id='otpvalue' ></label>
				    <br/>
				    <br/>Please provide the App-Unlock verification code only after you are sure that the request is from %firstname% %lastname%. We recommend you provide this code by telephone rather than in writing.",
				
				'OTPGENERATOR_TEXT2' => "<br>Reporta<sup style='font-size:8px;'>TM</sup> is powered by the International Women’s Media Foundation<br/>",
				
				'SOS_REJECT' => "You have declined this role.",
					
				'SOSREQUEST_TEXT1' => "<br><b>%firstname% %lastname% </b>
				has designated you as an App-Unlock contact on Reporta, the journalist safety app.
				
				<br><br>To prevent unwanted access to information stored on the app, Reporta will lock if a Check-in is missed or
				an SOS is issued.
				<br><br>If %firstname% %lastname%  takes an action that locks the app, you will be contacted by email with a link to a
				verification code required to unlock Reporta. %firstname% %lastname%  may contact you by phone or email to request
				this code.
				<br><br>You and %firstname% %lastname% may want to discuss what security protocols to put in place in the event you receive
				this message.<br>",
					
				'SOSREQUEST_TEXT2' =>"<br><br>
				Reporta is a mobile security app developed by the International Women&#39;s Media Foundation (IWMF) for
				iPhones and Android devices. Reporta is the only comprehensive security app available worldwide
				designed specifically for journalists.",
					
				'SOSREQUEST_TEXT3' =>"	<br><br>Reporta<sup style='font-size:8px;'>TM</sup> is powered by the International Women&#39;s Media Foundation<br>
				<a href='http://www.iwmf.org' ><img   src=%src% width ='300' /></a>",
					
				'SOSREQUEST_TEXT4' => 'Reporta App-Unlock Request',
				
				'SOSREQUEST_ALREADY_DECLINED' =>"You have already declined this role.",
				'SOSREQUEST_ALREADY_ACCEPTED' =>"You have already accepted this role.",
				
				
				'MISSCHECKIN_PUSH' =>"Reporta has been locked and an alert has been sent.",
				
				'MISSCHECKIN_MAIL_SUBJECT' => "Missed Check-in through Reporta",
				'MISSCHECKIN_MAIL_MESSAGE' => "%firstname% %lastname% has missed a scheduled Check-in using Reporta. %firstname% %lastname% last Checked-in %datetime% at %location%.
											<br/>
											   <br/> Please activate the security protocols you have established with %firstname% %lastname%.", 
													   
				'MISSCHECKIN_SMS_MESSAGE'	=>"%firstname% %lastname% has missed a scheduled Check-in using Reporta. %firstname% %lastname% last Checked-in. %datetime%  at %location%. Please activate the security protocols you have established with %firstname% %lastname%.",
				
				'MISSCHECKIN_SOCIAL_MESS AGE' => "%firstname% %lastname% has missed a scheduled check-in. %firstname% last checked in %datetime% at %location%.",
				
				'CHECKIN_REMINDER_TEN_SUBJECT' => "Confirm Your Check-in Within 10 Minutes",
				
				'CHECKIN_REMINDER_TEN_MESSAGE' =>'Confirm your Check-in within 10 minutes or an alert will be sent.',
				
				'CHECKIN_REMINDER_TEN_SMS' =>'Confirm your Check-in within 10 minutes or an alert will be sent.',
				
				'CHECKIN_REMINDER_TWO_SUBJECT' =>"Confirm Your Check-in Within 2 Minutes",
				
				'CHECKIN_REMINDER_TWO_MESSAGE' =>'Confirm your Check-in within 2 minutes or an alert will be sent.',
				
				'CHECKIN_REMINDER_TWO_SMS' =>'Confirm your Check-in within 2 minutes or an alert will be sent.',
				
				
				'DELETE_USER_SUBJECT' =>"Notice: Your Reporta Account Has Been Deleted",
				'DELETE_USER_MESSAGE' => "Dear %firstname%
						<br><br>We are writing to confirm that your account on Reporta, the journalist security application, has been deleted.
						<br><br>As a security precaution, all inactive accounts are automatically deleted after 12 months. Please be aware that your information has been removed from our servers and it will not be possible to restore or retrieve this account.
						<br><br>After sending you this email, we will also remove your name from our database. We will not contact you again. 
						<br><br>
						<br><br>If you decide you want to use Reporta again, you may create a new account at any time. But remember that we cannot restore or retrieve your old account.
						<br><br>
						If you have any questions about this process, please see Reporta’s Terms of Use.",
						
				'DELETE_WEEK_SUBJECT' =>"Last call: Your Reporta Account Will Be Deleted in One Week",
				'DELETE_WEEK_MESSAGE' => "Dear %firstname%
						<br><br>Some time ago, you created an account on Reporta, the journalist security application. We see that you haven’t used the app in quite a while. For your protection, your account will be closed in one week, on %date%.
						<br><br>As a security precaution, accounts are automatically closed after 12 months of inactivity. Once your account is closed your information will be removed from our servers and it will not be possible to restore or retrieve it.
						<br><br>If you want to continue to use Reporta, all you need to do is log into the app within the next 6 days. That new activity will keep your account active. If you don’t log into Reporta within the next week, your account will be closed and information deleted.  
						<br><br>
						If you have any questions about this process, please see Reporta’s Terms of Use.",
						
						
				'DELETE_MONTH_SUBJECT' =>"Important: Your Reporta Account Will Be Deleted in One Month",
				'DELETE_MONTH_MESSAGE' => "Dear %firstname%
						<br><br>Some time ago, you created an account on Reporta, the journalist security application. We see that you havenât used the app in quite a while. For your protection, your account will be closed one month from today, on %date%.
						<br><br>As a security precaution, accounts are automatically closed after 12 months of inactivity. Once your account is deleted your information will be removed from our servers and it will not be possible to restore or retrieve it.
						<br><br>If you want to continue to use Reporta, all you need to do is log into the app within the next 30 days. That new activity will keep your account active. If you donât log into Reporta, we will send you a final reminder one week before your account is closed and information deleted.  
						<br><br>
						If you have any questions about this process, please see Reportaâs Terms of Use.",
                                                
                                'MAIL_FOOTER' =>"<br><br>Reporta<sup style='font-size:8px;'>TM</sup> is powered by the International Women&#39;s Media Foundation <br> <br> <img src='%src%' width='300' >",
			);

//EN
