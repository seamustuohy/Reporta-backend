<?php
$lang = array(
				'FORGOTPASSWORD_MESSAGE'=>"Nous avons reçu une demande de réinitialisation du mot de passe correspondant au compte Reporta enregistré avec l’adresse %email%. Votre nom d'utilisateur est : %username%.
											<br/><br/>
											Pour réinitialiser votre mot de passe, cliquez ici.  %link%",
				'FORGOTPASSWORD_SUBJECT'=>'Demande de réinitialisation de mot de passe Reporta',
				
				
				'SOS_REQUEST_SUBJECT' => "Demande formulée par %firstname% %lastname%",
				
				'SOS_REQUEST_MESSAGE' => "Vous recevez ce message car %firstname% %lastname% a créé un profil dans l’application de sécurité des journalistes Reporta et vous a désigné(e) comme contact de déverrouillage de l’application. 
						<br/><br/>
						Pour empêcher tout accès indésirable aux informations stockées dans l’application, Reporta se verrouillera en l’absence d’un check-in ou en cas d’envoi d’un SOS. 
						<br/><br/>
						Si %firstname% %lastname% prend une mesure qui entraîne le verrouillage de l’application, vous recevrez un message contenant un lien vers un code de vérification nécessaire pour déverrouiller Reporta. Il se peut que %firstname% %lastname% vous contacte par téléphone ou par email pour vous demander ce code.  
						<br/><br/>
						Vous et %firstname% %lastname% devez discuter des protocoles de sécurité à activer si jamais vous recevez ce message. 
						<br/><br/>
						Pour accepter cette demande, %link%.
						<br/><br/>
						Merci pour votre participation aux protocoles de sécurité de %firstname% %lastname%.
						<br/><br/>
						Reporta est une application de sécurité mobile pour iPhone et Android développée par l’International Women’s Media Foundation (IWMF). Reporta est la seule application de sécurité complète au monde conçue spécialement pour les journalistes. ",
				
				'WELCOME_SUBJECT' => "Bienvenue dans Reporta !",
				'WELCOME_MESSAGE' =>"Créée par l’International Women’s Media Foundation (IWMF), l’application de sécurité Reporta s’adresse aux journalistes qui travaillent dans des milieux dangereux. Conçue en collaboration avec de grands journalistes et experts en sécurité internationale, Reporta est la seule application de sécurité complète s’adressant expressément à la communauté journalistique à l’échelle mondiale.
				     <br/><br/>
				    Reporta permet aux journalistes de prendre leur sécurité personnelle en main. Avec Reporta, vous pouvez envoyer rapidement des notifications de sécurité contenant des fichiers multimédias à des contacts désignés grâce aux trois fonctions clés de l’application :
				     <br/><br/>
				    1. Activez un système de check-in qui crée une piste claire quand vous travaillez dans des milieux potentiellement dangereux.
				     <br/><br/>
				    2. Créez des messages d’alerte personnalisés qui seront envoyés dans le cas où vous ou un collègue vous retrouveriez en danger.
				     <br/><br/>
				    3. Envoyez un message de détresse SOS à envoyer en un geste depuis votre téléphone.
				     <br/><br/>
				    Prenez quelques instants pour créer votre profil dans l’application et vous familiariser avec ses fonctions. Des informations sont disponibles sur chaque page de l’application si vous cliquez sur le bouton « i ». Vous disposez également d’un jeu complet d’instructions dans l’onglet Mode d’emploi de Reporta. 
				     <br/><br/>
				    Quand vous ajoutez des personnes à vos cercles de contacts, vous devez leur fournir des instructions, de préférence par écrit, leur indiquant ce qu’ils doivent faire en cas d’alerte Reporta. Ces contacts doivent savoir quoi faire si vous manquez un check-in, envoyez une alerte ou activez un SOS.
				     <br/><br/>
				    N’oubliez pas de désigner dans votre cercle privé au moins une personne qui servira de contact de déverrouillage de l’application. Les contacts de déverrouillage de l’application recevront un lien vers un code de vérification obligatoire pour déverrouiller l’application si vous manquez un check-in ou si vous envoyez un SOS. 
				     <br/><br/>
				    L’avis des utilisateurs nous intéresse.  Envoyez-nous un message à reporta@iwmf.org.  
				    <br/><br/>
				    Merci à vous et aux innombrables journalistes qui continuent de nous aider à améliorer Reporta, une application qui permet d’aider la communauté journalistique internationale.
				    <br/><br/>
				    IWMF" ,
					
				'CREATE_CHECKIN_SUBJECT'=>'Check-in créé via Reporta',
				'CREATE_CHECKIN_MESSAGE'=>"%firstname% %lastname% a créé un check-in via Reporta. 
							<br/>
							<br/>Ce message confirme que l'utilisateur a effectué un check-in depuis %location%.
							<br/>
							<br/>%firstname% %lastname% a paramétré une fréquence de check-in de %frequency% minutes.
							<br/>
							<br/>Votre prochain message de Reporta sera une confirmation de check-in, un avis de check-in manqué ou un message indiquant que le check-in a été fermé.
							<br/>
							<br/>%firstname% %lastname% a joint des fichiers multimédias à ce check-in.",
							
				'CREATE_CHECKIN_SMS' => "%firstname% %lastname% a effectué un check-in depuis %location%. La prochaine confirmation aura lieu dans %frequency% minutes. Votre prochain message de Reporta sera une confirmation de check-in, un avis de check-in manqué ou un message indiquant que le check-in a été fermé.",
				
				
				'CLOSE_CHECKIN_SUBJECT'=>'Check-in fermé',
				'CLOSE_CHECKIN_MESSAGE'=>"%firstname% %lastname% a fermé un check-in dans Reporta.",
				'CLOSE_CHECKIN_SMS' => "%firstname% %lastname% a fermé un check-in dans Reporta.",
				
				'CONFIRMED_CHECKIN_SUBJECT'=>'Entrada Confirmado',
				'CONFIRMED_CHECKIN_MESSAGE'=>"%firstname% %lastname% a confirmé un check-in sur Reporta. 
						<br/>
						<br/>Ce message confirme que l'utilisateur a effectué un check-in depuis %location%.
						<br/>
						<br/>%firstname% %lastname% a paramétré une fréquence de check-in de %frequency% minutes.
						<br/>
						<br/>Votre prochain message de Reporta sera une confirmation de check-in, un avis de check-in manqué ou un message indiquant que le check-in a été fermé.
						<br/>
						<br/>%firstname% %lastname% a joint des fichiers multimédias à ce check-in.",
						
				'ALERT_ISSUE_SUBJECT'=>'Alerte émise via Reporta',
				'ALERT_ISSUE_MESSAGE'=>"%firstname% %lastname% a activé une alerte via Reporta le %datetime% depuis %location%,
					<br/>
					<br/>%firstname% %lastname% a signalé le problème suivant : %situation% 
					<br/>
					<br/>Veuillez activer les protocoles de sécurité que vous avez établis avec %firstname% %lastname%.",
				
				
				'ALERT_ISSUE_SMS'=>"%firstname% %lastname% a activé une alerte via Reporta depuis %location% le %datetime%. %firstname% %lastname% a signalé le problème suivant : %situation%",
				
				
				
				'APP_UNLOCK_SUBJECT' => "Code de vérification pour le déverrouillage de l'application",
				
				'APP_UNLOCK_MESSAGE' => "Vous êtes un contact enregistré qui reçoit les messages de déverrouillage de l'application générés par Reporta pour %firstname% %lastname%. 
				<br/>
				<br/>Vous recevrez également un message d'alerte séparé de la part de Reporta contenant plus de détails.
				<br/>
				<br/>Récupération du code de vérification du déverrouillage de l'application : %link% 
				Merci de fournir le code de vérification du déverrouillage de l'application uniquement après avoir vérifié que la demande émane bien de %firstname% %lastname%. Nous recommandons les vérifications par téléphone plutôt que les vérifications par écrit. ",
					
				
				'SOS_ISSUED_SUBJECT' =>'SOS envoyé via Reporta',
				'SOS_ISSUED_MESSAGE' =>"%firstname% %lastname% a activé un SOS via Reporta depuis  %datetime%.
							<br><br>
							Veuillez activer les protocoles de sécurité que vous avez établis avec %firstname% %lastname%",
				
				'SOS_ISSUED_SMS' =>"%firstname% a activé un SOS via Reporta. Veuillez activer les protocoles de sécurité que vous avez établis avec %firstname% %lastname%",
				
				
				'PASSWORD_CHANGE_SUBJECT'=>'Confirmation de modification de mot de passe Reporta',
				
				'PASSWORD_CHANGE_MESSAGE'=>"Le mot de passe pour le compte Reporta enregistré avec l'adresse %email% a été mis à jour<br/>
							 Si vous n’êtes pas à l’origine de cette demande de modification et si vous pensez que votre compte Reporta a été compromis, contactez-nous à l'adresse support@reporta.org.",
							 
				'OTPGENERATOR_TEXT' =>"Demande de code de déverrouillage de l’application",
				'OTPGENERATOR_TEXT1' => "%firstname% %lastname%, vous a désigné comme contact de déverrouillage dans Reporta, l’application de sécurité des journalistes.
				    <br/>
				    <br/>Pour empêcher tout accès indésirable aux informations stockées dans l’application, Reporta se verrouillera en l’absence d’un check-in de la part de %firstname% %lastname% ou en cas d’envoi d’un SOS. %firstname% %lastname% vous demandera ensuite de lui fournir un code qui lui permettra de déverrouiller l’application Reporta.
				    <br/>
				    <br/>Pour générer un code de déverrouillage <span id='otp' ><a href='javascript:void(0)'>cliquez ici.</a></span><label id='otpvalue' ></label>
				    <br/>
				    <br/>Merci de fournir le code de vérification du déverrouillage de l’application uniquement après avoir vérifié que la demande émane bien de %firstname% %lastname%. Nous vous conseillons de lui remettre ce code par téléphone plutôt que par écrit.
				    <br/>",
				
				'OTPGENERATOR_TEXT2' => "Reporta™ est proposé par l’International Women’s Media Foundation<br/> ",
				
				'SOS_REJECT' => "Vous avez refusé ce rôle.",
				
				'SOSREQUEST_TEXT1' => "<br><b>%firstname% %lastname% </b>
			 vous a désigné comme contact de déverrouillage dans Reporta, l’application de sécurité des journalistes.

			Pour empêcher tout accès indésirable aux informations stockées dans l’application, Reporta se verrouillera en l’absence d’un check-in ou en cas d’envoi d’un SOS.

			Si %firstname% %lastname% prend une mesure qui entraîne le verrouillage de l’application, vous recevrez un message contenant un lien vers un code de vérification nécessaire pour déverrouiller Reporta. Il se peut que %firstname% %lastname% vous contacte par téléphone ou par email pour vous demander ce code.

			Vous et %firstname% %lastname% devez discuter des protocoles de sécurité à activer si jamais vous recevez ce message.
			<br>",
				
				'SOSREQUEST_TEXT2' =>"<br><br>
			Reporta est une application de sécurité mobile pour iPhone et Android développée par l’International Women’s Media Foundation (IWMF). Reporta est la seule application de sécurité complète au monde conçue spécialement pour les journalistes.",
				
				'SOSREQUEST_TEXT3' =>"	<br><br>Reporta<sup style='font-size:8px;'>TM</sup> est proposé par l’International Women’s Media Foundation<br>
			<a href='http://www.iwmf.org' ><img   src=%src% width ='300' /></a>",
				
				'SOSREQUEST_TEXT4' => "Demande de déverrouillage de l'application Reporta",
				
				'SOSREQUEST_ALREADY_DECLINED' => "Vous avez déjà refusé ce rôle.",
				'SOSREQUEST_ALREADY_ACCEPTED' =>"Vous avez déjà accepté ce rôle.",
				

				'MISSCHECKIN_PUSH' =>"Reporta a été verrouillée et une alerte a été envoyée.",
				
				'MISSCHECKIN_MAIL_SUBJECT' => "Check-in manqué via Reporta",
				'MISSCHECKIN_MAIL_MESSAGE' => "%firstname% %lastname% a manqué un check-in programmé via Reporta. %firstname% %lastname% a donné signe de vie pour la dernière fois via un check-in le %datetime% depuis %location%. <br/>	<br/>Veuillez activer les protocoles de sécurité que vous avez établis avec %firstname% %lastname%.", 
													   
				'MISSCHECKIN_SMS_MESSAGE'	=>"%firstname% %lastname% a manqué un check-in programmé via Reporta. %firstname% %lastname% a donné signe de vie pour la dernière fois via un check-in le %datetime% depuis %location%. Veuillez activer les protocoles de sécurité que vous avez établis avec %firstname% %lastname%.",
				
				'MISSCHECKIN_SOCIAL_MESSAGE' => "%firstname% %lastname% has missed a scheduled check-in. %firstname% last checked in %datetime% at %location%.",
				
				'CHECKIN_REMINDER_TEN_SUBJECT' =>  "Confirmez votre check-in dans les 10 minutes",
				
				'CHECKIN_REMINDER_TEN_MESSAGE' =>'Confirmez votre check-in dans les 10 minutes ou une alerte sera envoyée.',
				
				'CHECKIN_REMINDER_TEN_SMS' =>'Confirmez votre check-in dans les 10 minutes ou une alerte sera envoyée.',
				
				'CHECKIN_REMINDER_TWO_SUBJECT' =>"Confirmez votre check-in dans les 2 minutes",
				
				'CHECKIN_REMINDER_TWO_MESSAGE' =>'Confirmez votre check-in dans les 2 minutes ou une alerte sera envoyée.',
				
				'CHECKIN_REMINDER_TWO_SMS' =>'Confirmez votre Check-in dans les 2 minutes ou une alerte sera envoyée.',
				
				'DELETE_USER_SUBJECT' =>"Notification : Votre compte Reporta a été supprimé",
				'DELETE_USER_MESSAGE' =>"Cher %firstname%, 
						<br><br>Nous vous écrivons pour vous confirmer que votre compte sur Reporta, l’application de sécurité journalistique, a été supprimé.
						<br><br>Par mesure de précaution, tous les comptes inactifs sont automatiquement supprimés après 12 mois. Veuillez noter que toutes vos informations ont été effacées de nos serveurs et que vous ne pourrez ni restaurer ni récupérer ce compte.
						<br><br>Après vous avoir envoyé ce courrier électronique, nous supprimerons également votre nom de notre base de données. Nous ne vous contacterons plus. 
						<br><br>Si vous souhaitez à nouveau utiliser Reporta, vous pouvez créer un nouveau compte à tout moment. Pour rappel, nous ne pouvons restaurer ni récupérer votre ancien compte.
						<br><br>
						Pour toute question relative à cette procédure, veuillez consulter les Conditions d’utilisation de Reporta.",
						
						
				'DELETE_WEEK_SUBJECT' =>"OBJET : Votre compte Reporta sera supprimé dans une semaine",
				'DELETE_WEEK_MESSAGE' => "Cher %firstname%,
						<br><br>Il y a quelques temps, vous avez créé un compte sur Reporta, l’application de sécurité journalistique. Nous constatons que vous n’avez pas utilisé l’application depuis longtemps. Pour votre protection, votre compte sera fermé dans une semaine à dater d’aujourd’hui, le %date%.
						<br><br>Par mesure de précaution, les comptes sont automatiquement clôturés après 12 mois d’inactivité. Une fois votre compte clôturé, vos informations seront effacées de nos serveurs et ne pourront plus être restaurées ni récupérées.
						<br><br>Si vous souhaitez continuer à utiliser Reporta, il vous suffit de vous connecter à l’application au cours des 6 prochains jours.  Cette opération vous permettra de garder votre compte actif. Si vous ne vous connectez pas à Reporta au cours de la prochaine semaine, votre compte sera clôturé et vos information supprimées.  
						<br><br>
						Pour toute question relative à cette procédure, veuillez consulter les Conditions d’utilisation de Reporta.",
						
				'DELETE_MONTH_SUBJECT' =>"Important : Votre compte Reporta sera supprimé dans un mois",
				'DELETE_MONTH_MESSAGE' => "Cher %firstname%,
						<br><br>Il y a quelques temps, vous avez créé un compte sur Reporta, l’application de sécurité journalistique. Nous constatons que vous n’avez pas utilisé l’application depuis longtemps. Pour votre protection, votre compte sera fermé dans un mois à dater d’aujourd’hui, le %date%.
						<br><br>Par mesure de précaution, les comptes sont automatiquement clôturés après 12 mois d’inactivité. Une fois votre compte supprimé, vos informations seront effacées de nos serveurs et ne pourront plus être restaurées ni récupérées.
						<br><br>Si vous souhaitez continuer à utiliser Reporta, il vous suffit de vous connecter à l’application au cours des 30 prochains jours.  Cette opération vous permettra de garder votre compte actif. Si vous ne vous connectez pas à Reporta, nous vous enverrons un dernier rappel une semaine avant la fermeture définitive de votre compte et la suppression de vos informations.  
						<br><br>
						Pour toute question relative à cette procédure, veuillez consulter les Conditions d’utilisation de Reporta.",
                                
                                'MAIL_FOOTER' =>"<br><br>Reporta<sup style='font-size:8px;'>TM</sup> est proposé par l’International Women&#39;s Media Foundation  <br> <br> <img src='%src%' width='300' >",
			);
//FR
