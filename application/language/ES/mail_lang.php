<?php
$lang = array(
				'FORGOTPASSWORD_MESSAGE'=>"Recibimos una solicitud para reconfigurar la contraseña de la cuenta de Reporta registrada con la dirección %email%. Su nombre de usuario es: %username%.
										<br/>
										Para reconfigurar su contraseña, haga clic aquí.   %link%",
				'FORGOTPASSWORD_SUBJECT'=>'Solicitud de reconfiguración de contraseña de Reporta',
				
				'SOS_REQUEST_SUBJECT' =>"Solicitud de %firstname% %lastname%",
				
				'SOS_REQUEST_MESSAGE' => "Usted está recibiendo este mensaje porque %firstname% %lastname% ha creado un perfil en Reporta, una aplicación de seguridad para periodistas, y lo/a ha designado como contacto para Desbloqueo de la Aplicación. 
						<br/><br/>
						Para evitar accesos no deseados a información almacenada en la aplicación, Reporta se bloqueará si el usuario no responde a un Registro o envía un SOS. 
						<br/><br/>
						Si %firstname% %lastname% realiza una acción que bloquea la aplicación, usted será contactado/a por correo electrónico con un enlace a un código de verificación para desbloquear Reporta. %firstname% %lastname% puede contactarse con usted por teléfono o por correo electrónico para solicitarle este código.  
						<br/><br/>
						Es posible que usted y %firstname% %lastname% quieran hablar sobre qué protocolos de seguridad deben poner en práctica en caso de que usted reciba este mensaje. 
						<br/><br/>
						Para aceptar esta solicitud,%link%.
						<br/><br/>
						Gracias por participar en los protocolos de seguridad de %firstname% %lastname%.
						<br/><br/>
						Reporta es una aplicación de seguridad móvil desarrollada por la Asociación Internacional de Mujeres en los Medios (International Women’s Media Foundation, IWMF) para dispositivos iPhone y Android. Reporta es la única aplicación de seguridad integral disponible en todo el mundo y diseñada especialmente para periodistas.   ",
						
						
				'WELCOME_SUBJECT' => "¡Bienvenido/a a Reporta!",
				'WELCOME_MESSAGE' =>"señada en colaboración con periodistas destacados y expertos en seguridad global, Reporta es la única aplicación de seguridad global integral creada específicamente para la comunidad periodística.
							    <br/> 
							    <br/>Reporta permite que los periodistas tomen el control de su seguridad personal. Al usar Reporta, pueden enviar rápidamente notificaciones de seguridad con archivos multimedia a contactos designados a través de las tres funciones principales de la aplicación:
							    <br/> 
							    1. Activar un sistema de Registro (Check-in) que realiza un rastreo cuando está trabajando en entornos potencialmente peligrosos.
							     <br/><br/>
							    2. Crear mensajes personalizados de Alerta (Alert) cuando usted o un colega estén en riesgo potencial riesgo.
							     <br/><br/>
							    3. Emitir un mensaje de peligro (SOS) simplemente tocando la pantalla del teléfono.
							     <br/><br/>
							    <br/>Por favor, tómese un momento para crear su perfil a través de la aplicación y para familiarizarse con las funciones. Hay información disponible en cada página de la aplicación si hace clic en el iButton (i). También puede consultar las instrucciones completas dentro de la aplicación en la pestaña Cómo usar Reporta. 
							     <br/>
							    <br/>Si agrega una persona a sus Círculos de contactos, debe darle instrucciones, preferentemente por escrito, sobre lo que debe hacer si recibe un mensaje de alerta de Reporta. Seguramente querrá que estos contactos sepan lo que deben hacer si usted no responde a un Registro, envía una Alerta, o emite un SOS.
							     <br/>
							    <br/>No olvide designar por lo menos a un contacto en su Círculo Privado para que actúe como contacto de Desbloqueo de la Aplicación. Los contactos de Desbloqueo de la Aplicación recibirán un enlace a un código de verificación para desbloquear la aplicación si usted no responde un mensaje de Registro o envía un SOS. 
							     <br/>
							    <br/>Siempre estamos interesados en recibir las opiniones de los usuarios. Sírvase escribirnos a reporta@iwmf.org.  
							    <br/>
							    <br/>Gracias a usted y a los incontables periodistas que continúan ayudándonos a lograr que Reporta sea una mejor aplicación para apoyar a la comunidad periodística en todo el mundo.
							    <br/>
							    <br/>IWMF" ,
					
				'CREATE_CHECKIN_SUBJECT'=>'Registro creado a través de Reporta',
				'CREATE_CHECKIN_MESSAGE'=>"%firstname% %lastname% ha creado un Registro usando Reporta. 
							<br/>
							<br/>Este mensaje confirma que el usuario respondió a un Registro en %location%.
							<br/>
							<br/>%firstname% %lastname% configuró la frecuencia de Registro cada %frequency% minutes. 
							<br/>
							<br/>Su siguiente mensaje de Reporta será una confirmación de Registro, una notificación de que el usuario no respondió a un Registro, o una notificación de que el usuario cerró la función de Registro.   
							<br/>
							<br/>%firstname% %lastname% ha adjuntado archivos multimedia a este Registro.",
							
				'CREATE_CHECKIN_SMS' => "%firstname% %lastname% ha respondido a un Registro en %location%. La siguiente confirmación se producirá en %frequency% minutes. Su siguiente mensaje de Reporta será una confirmación de Registro, una notificación de que el usuario no respondió al Registro o una notificación de que el usuario ha cerrado la función de Registro.",
				
				'CLOSE_CHECKIN_SUBJECT'=>'Registro cerrado',
				'CLOSE_CHECKIN_MESSAGE'=>"%firstname% %lastname% ha cerrado la función de Registro en Reporta.",
				'CLOSE_CHECKIN_SMS' => "%firstname% %lastname% ha cerrado la función de Registro en Reporta.",
				
				
				'CONFIRMED_CHECKIN_SUBJECT'=>"Registro creado a través de Reporta",
				'CONFIRMED_CHECKIN_MESSAGE'=>"%firstname% %lastname% ha confirmado un check-in en Reporta.
						<br/>
						<br/>Este mensaje confirma que el usuario respondió a un Registro en %location%.
						<br/>
						<br/>%firstname% %lastname% configuró la frecuencia de Registro cada %frequency% minutes. 
						<br/>
						<br/>Su siguiente mensaje de Reporta será una confirmación de Registro, una notificación de que el usuario no respondió a un Registro, o una notificación de que el usuario cerró la función de Registro.   
						<br/>
						<br/>%firstname% %lastname% ha adjuntado archivos multimedia a este Registro.",
						
						
				'ALERT_ISSUE_SUBJECT'=>'Alerta emitida a través de Reporta',
				'ALERT_ISSUE_MESSAGE'=>"%firstname% %lastname% ha activado una Alerta de Reporta el %datetime% en %location%
					<br/>
					<br/>%firstname% %lastname% ha reportado la siguiente situación: %situation% 
					<br/>
					<br/>Por favor, active los protocolos de seguridad que acordó con %firstname% %lastname%.",
				
				
				'ALERT_ISSUE_SMS'=>"%firstname% %lastname% ha activado una Alerta de Reporta en %location% el día %datetime%. %firstname% %lastname% ha reportado la siguiente situación: %situation%",
				
				
				
				'APP_UNLOCK_SUBJECT' => 'Código de verificación de Desbloqueo de la Aplicación',
				
				'APP_UNLOCK_MESSAGE' => "Usted fue designado/a para recibir un mensaje de Desbloqueo de la Aplicación generado por Reporta en nombre de %firstname% %lastname%. 
						<br/>
						<br/>También recibirá un mensaje de Alerta por separado de parte de Reporta con más detalles.
						<br/>	
						<br/>Código de verificación de Desbloqueo de la Aplicación: %link% 
						Por favor, proporcione el código de verificación de Desbloqueo de la Aplicación solo si se ha asegurado de que el pedido proviene de %firstname% %lastname%. Recomendamos que haga esta verificación por teléfono, oralmente, no por escrito.",
					
				
				'SOS_ISSUED_SUBJECT' =>'SOS enviado a través de Reporta',
				'SOS_ISSUED_MESSAGE' =>"%firstname% %lastname% ha activado un SOS con Reporta en  día %datetime%.
				<br><br>
				Por favor, active los protocolos de seguridad que acordó con %firstname% %lastname%",
				
				'SOS_ISSUED_SMS' =>"%firstname% %lastname% ha activado un SOS con Reporta .Por favor, active los protocolos de seguridad que acordó con %firstname% %lastname%",
				
				'PASSWORD_CHANGE_SUBJECT'=>'Confirmación de cambio de contraseña de Reporta',
				
				'PASSWORD_CHANGE_MESSAGE'=>"La contraseña de la cuenta de Reporta registrada con la dirección %email% ha sido actualizada.
							    <br/>
							    Si usted no solicitó este cambio y cree que su cuenta ha sido interceptada, sírvase escribirnos a support@reporta.org.",
								
				
				'OTPGENERATOR_TEXT' => "Solicitud de código de Desbloqueo de la Aplicación",
				'OTPGENERATOR_TEXT1' => "%firstname% %lastname% lo ha elegido como contacto de Desbloqueo de la Aplicación para Reporta, la aplicación de seguridad para periodistas.
				    <br/>
				    <br/>Para prevenir el acceso no deseado a información almacenada en la aplicación, Reporta se bloqueará impidiendo el acceso si %firstname% %lastname% no responde a un Registro programado o envía una alerta SOS. Luego, %firstname% %lastname% necesitará que usted le proporcione un código de Desbloqueo de la Aplicación para desbloquear Reporta.
				    <br/>
				    <br/>Para generar un código de Desbloqueo de la Aplicación <span id='otp' ><a href='javascript:void(0)'>haga clic aquí.</a></span><label id='otpvalue' ></label>
				    <br/>
				    <br/>Por favor, proporcione el código de verificación de Desbloqueo de la Aplicación solo si se ha asegurado de que el pedido proviene de %firstname% %lastname%. Le recomendamos que proporcione este código por vía telefónica, no por escrito.
				    <br/>",
				
				'OTPGENERATOR_TEXT2' => "<br/>Reporta™ es una aplicación respaldada por la Asociación Internacional de Mujeres en los Medios ",
				
				'SOS_REJECT' => "Usted ha rechazado este rol.",
				
				
				'SOSREQUEST_TEXT1' => "<br><b>%firstname% %lastname% </b>lo/a ha designado como contacto de Desbloqueo de la Aplicación en Reporta, la aplicación de seguridad para periodistas.Para evitar accesos a la información almacenada en la aplicación no deseados, Reporta se bloqueará si el usuario falla en un Registro o envía un SOS. Si %firstname% %lastname% realiza una acción que bloquea la aplicación, usted será contactado/a por correo electrónico con un enlace a un código de verificación para desbloquear Reporta. %firstname% %lastname% puede contactarse con usted por teléfono o por correo electrónico para solicitarle este código. Es posible que usted y %firstname% %lastname% quieran hablar sobre qué protocolos de seguridad deben poner en práctica en caso de que usted reciba este mensaje.<br>",
				
				'SOSREQUEST_TEXT2' =>"<br><br>Reporta es una aplicación de seguridad móvil desarrollada por la Asociación Internacional de Mujeres en los Medios (International Women’s Media Foundation, WMF) para dispositivos iPhone y Android. Reporta es la única aplicación de seguridad integral disponible en todo el mundo y diseñada especialmente para periodistas.",
				
				'SOSREQUEST_TEXT3' =>"<br><br>Reporta<sup style='font-size:8px;'>TM</sup> es una aplicación respaldada por la Asociación Internacional de Mujeres en los Medios <br><a href='http://www.iwmf.org' ><img   src=%src% width ='300' /></a>",
				
				'SOSREQUEST_TEXT4' => 'Solicitud de Desbloqueo de la Aplicación de Reporta',
				
				'SOSREQUEST_ALREADY_DECLINED' =>"Usted ya ha rechazado este rol.",
				'SOSREQUEST_ALREADY_ACCEPTED' =>"Usted ya ha aceptado este rol.",
				
				'MISSCHECKIN_PUSH' => "Reporta ha sido bloqueado y se ha enviado un mensaje de alerta.",
				
				'MISSCHECKIN_MAIL_SUBJECT' => "No se respondió a un Registro a través de Reporta",
				'MISSCHECKIN_MAIL_MESSAGE' => "%firstname% %lastname% no ha respondido a un Registro programado con Reporta. La última vez que %firstname% %lastname% respondió a un Registro fue el %datetime% en %location%.<br/><br/>Por favor, active los protocolos de seguridad que acordó con %firstname% %lastname%.", 
													   
				'MISSCHECKIN_SMS_MESSAGE'=> "%firstname% %lastname% no ha respondido a un Registro programado con Reporta. La última vez que %firstname% %lastname% respondió a un Registro fue el %datetime% en %location%. Por favor, active los protocolos de seguridad que acordó con %firstname% %lastname%.",
				
				'MISSCHECKIN_SOCIAL_MESSAGE' => "%firstname% %lastname% has missed a scheduled check-in. %firstname% last checked in %datetime% at %location%.",
				
				'CHECKIN_REMINDER_TEN_SUBJECT' =>  "Confirme su Registro dentro de los próximos 10 minutos",
				
				'CHECKIN_REMINDER_TEN_MESSAGE' =>'Confirme su Registro dentro de los próximos 10 minutos o se enviará una alerta.',
				
				'CHECKIN_REMINDER_TEN_SMS' =>'Confirme su Registro dentro de los próximos 10 minutos o se enviará una alerta.',
				
				'CHECKIN_REMINDER_TWO_SUBJECT' =>"Confirme su Registro dentro de los próximos 2 minutos",
				
				'CHECKIN_REMINDER_TWO_MESSAGE' =>'Confirme su Registro dentro de los próximos 2 minutos o se enviará una alerta.',
				
				'CHECKIN_REMINDER_TWO_SMS' =>'Confirmar Su Registro Dentro de los Próximos 2 Minutos o se Enviará una Alerta.',
				
				'DELETE_USER_SUBJECT' =>"Aviso: Su cuenta de Reporta se ha eliminado",
				'DELETE_USER_MESSAGE' => "Estimado/a %firstname%,
						<br><br>Le escribimos para confirmarle que su cuenta de Reporta, la aplicación de seguridad para periodistas, se ha eliminado.
						<br><br>Como precaución de seguridad, todas las cuentas inactivas se eliminan automáticamente después de 12 meses sin uso. Queremos que sepa que su información ha sido eliminada de nuestros servidores y que no será posible restaurar o recuperar esta cuenta.
						<br><br>Después de enviarle este mensaje, también eliminaremos su nombre de nuestra base de datos. No volveremos a contactarnos con usted. 
						<br><br>
						<br><br>Si decide que quiere utilizar Reporta nuevamente, puede crear otra cuenta cuando lo desee. Pero recuerde que no podemos restaurar o recuperar su cuenta anterior.
						<br><br>
						Si tiene alguna pregunta sobre este proceso, por favor consulte los Términos de Uso de Reporta.",
						
				'DELETE_WEEK_SUBJECT' =>"Último aviso Su cuenta de Reporta se eliminará en una semana",
				'DELETE_WEEK_MESSAGE' => "Estimado/a %firstname%:
						<br><br>Hace un tiempo, usted creó una cuenta de Reporta, la aplicación de seguridad para periodistas. Hemos identificado que no ha usado la aplicación por bastante tiempo. Para su protección, su cuenta se cerrará dentro de una semana, el %date%.
						<br><br>Como precaución de seguridad, las cuentas se cierran automáticamente luego de 12 meses de inactividad. Al borrar su cuenta, su información será eliminada de nuestros servidores y no será posible restaurarla o recuperarla.
						<br><br>Si desea continuar usando Reporta, lo único que debe hacer es iniciar sesión en la aplicación dentro de los próximos 6 días. Esa nueva actividad hará que su cuenta se mantenga activa. Si no inicia sesión en Reporta dentro de la próxima semana, se cerrará su cuenta y se eliminará su información.  
						<br><br>
						Si tiene alguna pregunta sobre este proceso, por favor consulte los Términos de Uso de Reporta.",
						
				'DELETE_MONTH_SUBJECT' =>"Importante: Su cuenta de Reporta se eliminarÃ¡ en un mes",
				'DELETE_MONTH_MESSAGE' => "Estimado/a %firstname%:
						<br><br>Hace un tiempo, usted creÃ³ una cuenta de Reporta, la aplicaciÃ³n de seguridad para periodistas. Hemos identificado que no ha usado la aplicaciÃ³n por bastante tiempo. Para su protecciÃ³n, su cuenta se cerrarÃ¡ dentro de un mes, el %date%.
						<br><br>Como precauciÃ³n de seguridad, las cuentas se cierran automÃ¡ticamente luego de 12 meses de inactividad. Al borrar su cuenta, su informaciÃ³n serÃ¡ eliminada de nuestros servidores y no serÃ¡ posible restaurarla o recuperarla.
						<br><br>Si desea continuar usando Reporta, lo Ãºnico que debe hacer es iniciar sesiÃ³n en la aplicaciÃ³n dentro de los prÃ³ximos 30 dÃ­as. Esa nueva actividad harÃ¡ que su cuenta se mantenga activa. Si no inicia sesiÃ³n en Reporta, le enviaremos un recordatorio final, una semana antes de que se cierre su cuenta y se elimine su informaciÃ³n.  
						<br><br>
						Si tiene alguna pregunta sobre este proceso, por favor consulte los TÃ©rminos de Uso de Reporta.",
                                
                                'MAIL_FOOTER' =>"<br><br>Reporta<sup style='font-size:8px;'>TM</sup>  es una aplicación respaldada por la Asociación Internacional de Mujeres en los Medios <br> <br> <img src='%src%' width='300' >",
				
			);
//ES
