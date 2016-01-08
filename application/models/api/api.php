<?php

/**
* Name:  Api
*
* Description:  class contain function use in api 
*               Modified Function base on requirement 
* @package Api
* @version 1.0
* 
*/

class Api extends CI_Model
{
	public $responsedata = array();
	public $bulkdata     = array();
	public $users = 'iwmf_user.users';
	
	/**
	 * Decode json
	 *
	 * @access 	public
	 * @return array
	 */
	public function iwmf_jsondecode($string="")
	{
		try
		{
			$api_version = $this->uri->segment(1);
			if($api_version == 'api5' || $api_version == 'api6')
			{
				$key = substr($string,0,32);
				$str = substr($string,32,strlen($string)-45);
				$string = $this->AES_Decode($str,$key);
				$string = urldecode($string);
				file_put_contents($filename, $current_checkin_data);
				
			}	
			$this->bulkdata = json_decode($string, true);
			return $this->bulkdata;
		}
		catch (Exception $e)
		{
			echo "Error message: " . $e->getMessage() . "\n";
		}
		return "";
	}
	
	/**
	 * Encode json
	 *
	 * @access 	public
	 * @param array  json string
	 * @return string
	 */
	public function iwmf_jsonencode($input_array=array())
	{
		try
		{
			$input_array = $this->arraywalkfun($input_array,'removenull');
			$json_string = json_encode($input_array);
			
			$api_version = $this->uri->segment(1);
			
			if($api_version == 'api5' || $api_version == 'api6')
			{
				$key = $this->randomPassword(16);
				$peding  = $this->randomPassword(8);
				$peding = substr($peding,0,13);
				$string = $this->AES_Encode($json_string,$key);
				$this->responsedata = $output_json = $key."".$string."".$peding;
			}
			else
			{
				$this->responsedata = $output_json = $json_string;	
			}
			return $output_json;
		}
		catch (Exception $e)
		{
			echo "Error message: " . $e->getMessage() . "\n";
		}
		return 0;
	}
	
	/**
	 * Encode data using aes
	 *
	 * @access 	public
	 * @param string text to Encode
	 * @param string  aes key
	 * @return string
	 */
	function AES_Encode($plain_text,$key)
	{
		return base64_encode(openssl_encrypt($plain_text, "aes-256-cbc", $key, true, str_repeat(chr(0), 16)));
	}
	
	/**
	 * Decode data using aes
	 *
	 * @access 	public
	 * @param string  text to decode
	 * @param string aes key
	 * @return string
	 */
	function AES_Decode($base64_text,$key)
	{
		return openssl_decrypt(base64_decode($base64_text), "aes-256-cbc", $key, true, str_repeat(chr(0), 16));
	}
	
	/**
	 * new genreat random string
	 *
	 * @access 	public
	 * @param string length of random string
	 * @return string
	 */
	public function randomPassword($length = 4)
	{
		$cstrong = TRUE;
		$bytes = openssl_random_pseudo_bytes($length, $cstrong);
		$hex   = bin2hex($bytes);
		
		if(!$cstrong || $bytes == FALSE)
		{
			return $this->randomPassword($length);
		}
		else
		{
			return $hex;
		}
	}
	/**
	 * display json
	 *
	 * @access 	public
	 * @return void
	 */
	public function iwmf_jsonrender()
	{
		echo $this->responsedata;
		exit;
	}
	
	/**
	 * Remove null fron array
	 *
	 * @access 	public
	 * @param array array to remove null
	 * @return array
	 */
	public function arraywalkfun($arr,$fun = '')
	{
		if($fun != "")
		{
			array_walk_recursive($arr,$fun);
			return $arr;
		}
		return $arr;
	}
	
	/**
	 * Genrate heder token
	 *
	 * @access 	public
	 * @param int user id
	 * @return string
	 */
	public function generateheader($user_id)
	{
		$userId = $user_id;
		$currentDateTime = str_pad(time(), 12, "0",STR_PAD_LEFT);
		$secret_key = sha1(KEY);
		
		$s1 = $userId.$currentDateTime;
		
		$s2 = hash_hmac('sha256',$s1, $secret_key);
		$s2 = utf8_encode($s2);
		
		$s2 = (strlen($s2) > 40) ? substr($s2,0,40) : str_pad($s2, 40, "0",STR_PAD_LEFT);
		
		$s3 = $s2.$s1;
		
		$s3 = base64_encode(openssl_encrypt($s3, "aes-256-cbc", $secret_key, true, str_repeat(chr(0), 16)));
		$s3 = urlencode($s3);
		return $s3;
	}
	
	/**
	 * verify header
	 *
	 * @access 	public
	 * @param string server header
	 * @return array
	 */
	public function verifyheader($header)
	{
		$secret_key = sha1(KEY);
		
		$s3 = urldecode($header['headertoken']);
		$s3 = openssl_decrypt(base64_decode($s3), "aes-256-cbc", $secret_key, true, str_repeat(chr(0), 16));
		$s3 = substr($s3,40);
		
		$timestamp = substr($s3,-12);
		$user_id = substr($s3,0,-12);
		
		/* check user is in dataset or not */
		$expiretime = strtotime(CURRENT_DATETIME) - (60 * TOKENEXPIREMIN);
		
		if($timestamp >  $expiretime )
		{
			
			$result_user = users::getuserdetail($user_id);
			
			$key = substr($header['devicetoken'],0,32);
			$str = substr($header['devicetoken'],32,strlen($header['devicetoken'])-45);
			$devicetoken = $this->AES_Decode($str,$key);
			
			if(isset($result_user) && count($result_user)>0 && $devicetoken == $result_user[0]['devicetoken'] && $result_user[0]['delete'] == 0)
			{
				
				$headertoken = $this->generateheader($user_id);
				$data['user_id'] = $user_id;
				$data['status'] = 1;
				$data['headertoken']=$headertoken;
				$data['user_lock_status'] = $result_user[0]['status'];
			}
			else
			{
				$data['status'] = 0;
			}
		}
		else
		{
			$data['status'] = 0;
		}
		return $data;
	}
	
	/**
	 * Responce message 
	 *
	 * @access 	public
	 * @param string language of message
	 * @return array
	 */
	public function response_message($language = '')
	{  
		if($language && $language == 'ES') 
		{
			$response_message = array(
				'INVALID_PARAMS' => 'Parámetros no válidos',
				'ADDED' => 'Agregado con Éxito',
				'CREATED' => 'Creado con Éxito',
				'UPDATED' => 'Actualizado con Éxito',
				'LISTED' => 'Incluido con Éxito',
				'NODATA' => 'No hay datos disponibles',
				'WRONG' => 'Hay un error',
				'DELETED' => 'Eliminado con Éxito',
				'AUTHFAIL' => 'Falla de autenticación',
				'AUTHENTICATED' => 'Autenticado con Éxito',
				'FILL_ALL_INFO'=> 'Por favor, complete toda la información',
				'USEREXITS' => 'El nombre de usuario ya existe',
				'EMAILEXITS' => 'El correo electrónico ya existe',
				'PASSWORD_CHANGE' => 'Contrasena cambiar exito. conocer tu contrasena revise su correo electronico',
				'PASSWORD_NOT_CHANGE' => '¡No se pudo realizar el cambio de contraseña!',
				'AVAILABLE' => 'Nombre de usuario y correo electrónico disponibles',
				'CHECKIN_ALREADY_MISSED' => 'Usted no respondió  este registro',
				'CHECKIN_ALREADY_CLOSED' => 'Usted ya cerró la función de registro',
				'SIGN_OUT_SUCCESSFULY' => 'Firmado a cabo con exito',
				'ENTER_CORRECT_PASSWORD' => 'Ingresar contraseña correcta',
				'EMAIL_NOT_EXIST_ENTER_CORRECT_EMAIL' => 'Esta dirección de correo electrónico no es válida. Por favor, vuelva a intentarlo.',
				'INVALID_USERNAME' => 'Nombre de usuario no válido',
				'CONFIRMED_SOS_IN_ACTIVE_CIRCLE' => 'Debe confirmar al menos un contacto de desbloqueo de la aplicación de su Círculo privado para usar Reporta.',
				'CONFIRMED_SOS_IN_CONTACTS' => 'Debe confirmar al menos un contacto de desbloqueo de la aplicación para usar Reporta.',
				'EMAIL_SEND' => 'Correo electrónico enviar éxito',
				'PASSWORD_REQUEST_SEND' => 'Por favor, verifique su correo electrónico para recibir un vínculo para cambiar su contraseña.',
				'CONTACT_EXIST' => 'El email del contacto ya existe',
				'SOS_APP_LOCK' => 'Debe confirmar un contacto de Desbloqueo de la Aplicación para habilitar esta función.',
				'APP_LOCKING' => 'Para permitir el desbloqueo de la aplicación, debe confirmar un contacto de desbloqueo de la aplicación.',
				'REMOVING_CONTACT_APP_LOCK'=>'Si elimina la función de desbloqueo de la aplicación de este contacto, la función de bloqueo de la aplicación quedará desactivada.',
				'CONFIRMED_SOS_CONTACTS' => 'Debe tener por lo menos un contacto de desbloqueo de la aplicación para poder usar Reporta.',
				'INVALID_PASSWORD' => 'Contraseña no válida',
				"DUPLICATE_NAME"=> "Ya existe un contacto con ese nombre.",
				"DUPLICATE_EMAIL" => "Ya existe un contacto con esa dirección de email.",
				"DUPLICATE_NUMBER" => "Ya existe un contacto con ese nro. de teléfono.",
				"SESSION_LOGOUT" => "Cerrar otros dispositivos   \nSolo puede usar Reporta en un dispositivo por vez.",
				"FORCE_LOGOUT" => "Caducó la sesión \nInicie sesión nuevamente en Reporta",
				"LOCK_USER" => "Reporta se bloqueó debido a múltiples intentos fallidos. Inténtelo más tarde.",
				"LAST_ATTEMPT_REMAIN" => "URGENTE: Reporta se bloqueará por 24 horas si ingresa datos incorrectos otra vez.",
				"LOGIN_ATTEMPTS_WARNING" => "Tenga en cuenta que Reporta se bloqueará por 24 hrs. después de 6 intentos fallidos. Deberá esperar 24 hrs. para volver a intentarlo.",
				"INVALID_EXTENSION" => "Archivo inválido",
				"CLOSEPRECHECKIN" => 'Cerrar el Registro previo',
				'PASS_UPDATED' => 'Se actualizó su contraseña',
				'MATCH_OLD_PASS' => 'No puede usar sus últimas 3 contraseñas!',
				'PASSWORD_NOT_NAME' =>'Su contraseña no puede incluir su usuario!',
				);
}
		elseif($language && $language == 'FR')  
		{
			$response_message = array(
				'INVALID_PARAMS' => 'Paramètres non valides',
				'ADDED' => 'Ajouté avec succès',
				'CREATED' => 'Créé avec succès',
				'UPDATED' => 'Mis À jour avec succès',
				'LISTED' => 'Répertorié avec succès',
				'NODATA' => 'Aucune donnée disponible',
				'WRONG' => 'Il y a un problème',
				'DELETED' => 'Supprimé avec succès',
				'AUTHFAIL' => "Échec de l'Authentification",
				'AUTHENTICATED' => 'Authentification Réussie',
				'FILL_ALL_INFO'=> 'Veuillez fournir toutes les informations',
				'USEREXITS' => "Le nom d'utilisateur existe déjà",
				'EMAILEXITS' => "L'Adresse Électronique existe Déjà",
				'PASSWORD_CHANGE' => 'Mot de passe le changement avec succes. Pour connaitre votre mot de passe verifier votre Email',
				'PASSWORD_NOT_CHANGE' => 'Échec de la modification du mot de passe !',
				'AVAILABLE' => "Le nom d'utilisateur et l'adresse Électronique sont disponibles",
				'CHECKIN_ALREADY_MISSED' => 'Vous avez déjà manqué ce check-in',
				'CHECKIN_ALREADY_CLOSED' => 'Ce Check-in A Déjà Été Fermé',
				'SIGN_OUT_SUCCESSFULY' => 'Signe avec succes',
				'ENTER_CORRECT_PASSWORD' => 'Saisissez un Mot de Passe Correct',
				'EMAIL_NOT_EXIST_ENTER_CORRECT_EMAIL' =>  "Cette adresse électronique n'est pas valide. Veuillez réessayer.",
				'INVALID_USERNAME' => "Nom d'utilisateur non valide",
				'CONFIRMED_SOS_IN_ACTIVE_CIRCLE' => "Vous devez avoir au moins un contact de Déverrouillage de l'Application confirmé dans votre Cercle Privé pour utiliser Reporta.",
				'CONFIRMED_SOS_IN_CONTACTS' => "Vous devez avoir au moins un contact de Déverrouillage de l'Application confirmé pour utiliser Reporta.",
				'EMAIL_SEND' => 'Email envoyer succès',
				'PASSWORD_REQUEST_SEND' => 'Veuillez vérifier votre messagerie pour trouver le lien qui vous permettra de modifier votre mot de passe.',
				'CONTACT_EXIST' => "L'adresse électronique de contact existe déjà",
				'SOS_APP_LOCK' => 'Vous devez confirmer un contact de déverrouillage de l’application pour activer cette fonction.',
				'APP_LOCKING' => 'Vous devez confirmer un contact de déverrouillage pour activer le verrouillage de l’application.',
				'REMOVING_CONTACT_APP_LOCK'=>'Le verrouillage de l’application sera désactivé si vous retirez le déverrouillage pour ce contact.',
				'CONFIRMED_SOS_CONTACTS' => "Vous devez avoir au moins un contact de déverrouillage de l'application confirmé pour utiliser Reporta.",
				'INVALID_PASSWORD' => 'Mot de passe non valide',
				"DUPLICATE_NAME"=> "Un contact avec ce nom existe déjà.",
				"DUPLICATE_EMAIL" => "Un contact avec cette adresse e-mail existe déjà.",
				"DUPLICATE_NUMBER" => "Un contact avec ce numéro de tél. existe déjà.",
				"SESSION_LOGOUT" => "Déconnexion autres app. ? \nVous ne pouvez utiliser Reporta que sur 1 app.",
				"FORCE_LOGOUT" => "Session expirée \nReconnectez-vous à Reporta",
				"LOCK_USER" => "Reporta est verrouillé suite à des échecs d'identification. Réessayez plus tard.",
				"LAST_ATTEMPT_REMAIN" => "URGENT ! Reporta se verrouillera pdt 24 heures si votre identification échoue.",
				"LOGIN_ATTEMPTS_WARNING" => "Reporta se verrouillera pdt 24 heures après 6 échecs d'identification. Il faudra patienter 24 heures pour tenter de se reconnecter.",
				"INVALID_EXTENSION" => "Type non valide",
				"CLOSEPRECHECKIN" => 'Fermer le Check-in précédant',
				'PASS_UPDATED' => 'Mot de passe mis à jour',
				'MATCH_OLD_PASS' => 'Les 3 mots de passe précédant sont interdits',
				'PASSWORD_NOT_NAME' =>"Nom d' d'utilisateur interdit dans le mot de passe",
				);
}

		elseif($language && $language == 'AR')
		{
			$response_message = array(
				'INVALID_PARAMS' => 'معلمات غير صالحة',
				'ADDED' => 'تمت الإضافة بنجاح',
				'CREATED' => 'تم الإنشاء بنجاح',
				'UPDATED' => 'تم التحديث بنجاح',
				'LISTED' => 'تم الإدراج بنجاح',
				'NODATA' => 'لا تتوفر بيانات',
				'WRONG' => 'شيء ما خطأ',
				'DELETED' => 'تم الحذف بنجاح',
				'AUTHFAIL' => 'فشل التصديق',
				'AUTHENTICATED' => 'نجح التصديق',
				'FILL_ALL_INFO'=> 'يُرجى ملء جميع المعلومات',
				'USEREXITS' => "اسم المستخدم موجود بالفعل",
				'EMAILEXITS' => 'البريد الإلكتروني موجود بالفعل',
				'PASSWORD_CHANGE' => 'تم تغيير كلمة المرور بنجاح. لمعرفة كلمة المرور الخاصة بك راقب بريدك الالكتروني',
				'PASSWORD_NOT_CHANGE' => 'لم ينجح تغيير كلمة المرور!',
				'AVAILABLE' => "اسم المستخدم والبريد الإلكتروني متوفران",
				'CHECKIN_ALREADY_MISSED' => 'لقد تم بالفعل تفويت عملية تسجيل الوصول هذه',
				'CHECKIN_ALREADY_CLOSED' => 'لقد تم بالفعل إغلاق عملية تسجيل الوصول هذه',
				'SIGN_OUT_SUCCESSFULY' => 'تم تسجيل الخروج بنجاح',
				'ENTER_CORRECT_PASSWORD' => 'أدخل كلمة المرور الصحيحة',
				'EMAIL_NOT_EXIST_ENTER_CORRECT_EMAIL' => ' هذا ليس عنوان بريد إلكتروني صالح. يُرجى إعادة المحاولة.',
				'INVALID_USERNAME' => 'اسم مستخدم غير صالح',
				'CONFIRMED_SOS_IN_ACTIVE_CIRCLE' => 'يجب أن يكون لديك على الأقل جهة اتصال مؤكدة واحدة لإلغاء قفل التطبيق في دائرة جهات اتصالك الخاصة لكي يتم استخدام Reporta.',
				'CONFIRMED_SOS_IN_CONTACTS' => 'يجب أن يكون لديك على الأقل جهة اتصال مؤكدة واحدة لإلغاء قفل التطبيق لكي يتم استخدام Reporta.',
				'EMAIL_SEND' => 'البريد الإلكتروني أرسل بنجاح',
				'PASSWORD_REQUEST_SEND' => 'يُرجى فحص بريدك الإلكتروني بحثًا عن رابط لتغيير كلمة مرورك.',
				'CONTACT_EXIST' => 'البريد الإلكتروني لجهة الاتصال موجود بالفعل',
				'SOS_APP_LOCK' => 'يجب تأكيد جهة اتصال إلغاء قفل التطبيق لتمكين هذه الوظيفة.',
				'APP_LOCKING' => 'لتمكين قفل التطبيق، يجب أن تقوم بتأكيد جهة اتصال لإلغاء قفل التطبيق.',
				'REMOVING_CONTACT_APP_LOCK'=>'إزالة إلغاء قفل التطبيق لجهة الاتصال هذه سيؤدي إلى إلغاء تنشيط التطبيق.',
				'CONFIRMED_SOS_CONTACTS' => 'يجب أن يكون لديك على الأقل جهة اتصال واحدة لإلغاء قفل التطبيق لكي يتم استخدام Reporta.',
				'INVALID_PASSWORD' => 'كلمة المرور غير صالحة',
				"DUPLICATE_NAME"=> "جهة اتصال بهذا الاسم موجودة مسبقًا.",
				"DUPLICATE_EMAIL" => "جهة اتصال بهذا البريد الإلكتروني موجودة فعلا. ",
				"DUPLICATE_NUMBER" => "جهة اتصال برقم الهاتف هذا موجودة مسبقًا. ",
				"SESSION_LOGOUT" => "تسجيل خروج أجهزة أخرى؟ \n	يمكن استخدام Reporta على جهاز واحد فقط في كل مرة.",
				"FORCE_LOGOUT" => "انتهت الجلسة\nيرجى تسجيل الدخول مرة أخرى لاستخدام Reporta.",
				"LOCK_USER" => "تم قفل Reporta بسبب محاولات تسجيل دخول فاشلة. يرجى إعادة المحاولة لاحقًا.",
				"LAST_ATTEMPT_REMAIN" => "عاجل! سيقفل Reporta لمدة 24 ساعة إذا أدخلت بيانات غير صحيحة مرة أخرى. ",
				"LOGIN_ATTEMPTS_WARNING" => "انتبه إلى أن Reporta سيقفل لمدة 24 ساعة بعد ست محاولات فاشلة لتسجيل الدخول. ستحتاج إلى الانتظار 24 ساعة لمحاولة تسجيل الدخول مرة أخرى.",
				"INVALID_EXTENSION" => "نوع ملف غير صالح",
				"CLOSEPRECHECKIN" => 'إغلاق تسجيل وصول سابق',
				'PASS_UPDATED' => 'تم تحديث كلمة المرور بنجاح',
				'MATCH_OLD_PASS' => 'لا يمكنك استخدام أخر 3 كلمات مرور!',
				'PASSWORD_NOT_NAME' =>'لا يمكن أن تضم كلمة المرور اسم المستخدم!',
				);
}

		elseif($language && $language == 'IW') 
		{
			$response_message = array(
				'INVALID_PARAMS' => 'פרמטרים לא חוקיים',
				'ADDED' => 'נוסף בהצלחה',
				'CREATED' => 'נוצר בהצלחה',
				'UPDATED' => 'עודכן בהצלחה',
				'LISTED' => 'פורסם בהצלחה',
				'NODATA' => 'אין נתונים זמינים',
				'WRONG' => 'משהו לא בסדר',
				'DELETED' => 'נמחק בהצלחה',
				'AUTHFAIL' => 'אימות נכשל',
				'AUTHENTICATED' => 'אימות הצליח',
				'FILL_ALL_INFO'=> 'מלא את כל האינפורמציה',
				'USEREXITS' => 'שם משתמש כבר קיים',
				'EMAILEXITS' => 'דואר אלקטרוני כבר קיים',
				'PASSWORD_CHANGE' => 'הסיסמה שונתה בהצלחה. כדי לדעת את סיסמתך בדוק את הדוא"ל שלך',
				'PASSWORD_NOT_CHANGE' => 'שינוי סיסמה לא הצליח!',
				'AVAILABLE' => 'שם משתמש וסיסמה זמינים',
				'CHECKIN_ALREADY_MISSED' => 'בדיקת אות חיים זו כבר פוספסה',
				'CHECKIN_ALREADY_CLOSED' => 'בדיקת אות חיים זו כבר נסגרה',
				'SIGN_OUT_SUCCESSFULY' => 'יציאה הושלמה בהצלחה',
				'ENTER_CORRECT_PASSWORD' => 'הזן סיסמה נכונה',
				'EMAIL_NOT_EXIST_ENTER_CORRECT_EMAIL' => 'זו אינה כתובת דואר אלקטרוני חוקית. נסה שנית.',
				'INVALID_USERNAME' => 'שם משתמש לא חוקי',
				'CONFIRMED_SOS_IN_ACTIVE_CIRCLE' => 'כדי להשתמש ב-Reporta צריך להיות לך איש קשר מאושר אחד לפחות בעל סטטוס שחרור נעילת ישומון במעגל הפרטי שלך.',
				'CONFIRMED_SOS_IN_CONTACTS' => 'כדי להשתמש ב-Reporta צריך להיות לך איש קשר מאושר אחד לפחות בעל סטטוס שחרור נעילת ישומון.',
				'EMAIL_SEND' => 'דוא"ל שלח בהצלחה',
				'PASSWORD_REQUEST_SEND' => 'בדוק אם קיבלת הודעת דואר אלקטרוני עם קישור לשינוי סיסמתך.',
				'CONTACT_EXIST' => 'דואר אלקטרוני ליצירת קשר כבר קיים',
				'SOS_APP_LOCK' => 'על מנת להפעיל פונקציה זו עליך לאשר איש קשר לשחרור נעילת הישומון.',
				'APP_LOCKING' => 'כדי לאפשר נעילת יישומון, עליך לאשר איש קשר לשחרור נעילת הישומון.',
				'REMOVING_CONTACT_APP_LOCK'=>'הסרת נעילת יישומון עבור איש קשר זה תשבית את נעילת הישומון.',
				'CONFIRMED_SOS_CONTACTS' => 'יש צורך בלפחות איש קשר אחד לשחרור נעילת הישומון כדי להשתמש ב-Reporta.',
				'INVALID_PASSWORD' => 'סיסמה לא חוקית',
				"DUPLICATE_NAME"=> "כבר קיים איש קשר בשם זה.",
				"DUPLICATE_EMAIL" => 'כבר קיים איש קשר עם כתובת דוא"ל זו.',
				"DUPLICATE_NUMBER" => "כבר קיים איש קשר עם מספר טלפון זה.",
				"SESSION_LOGOUT" => "לצאת ממכשירים אחרים?  \n אפשר להשתמש ב-Reporta במכשיר אחד בכל פעם.",
				"FORCE_LOGOUT" => "פג תוקף ההפעלה \n היכנס שוב כדי להשתמש ב-Reporta.",
				"LOCK_USER" => "Reporta ננעלה בגלל ניסיונות כניסה כושלים מרובים. נסה שנית מאוחר יותר.",
				"LAST_ATTEMPT_REMAIN" => "דחוף! Reporta תינעל ל-24 שעות אם תזין שוב פרטים שגויים. ",
				"LOGIN_ATTEMPTS_WARNING" => "לידיעתך, Reporta תינעל ל-24 שעות אחרי שישה ניסיונות כניסה כושלים. תידרש להמתין 24 שעות כדי לנסות להיכנס שנית.",
				"INVALID_EXTENSION" => "סוג קובץ לא חוקי",
				"CLOSEPRECHECKIN" => 'סגור כניסה קודמת',
				'PASS_UPDATED' => 'הסיסמה עודכנה בהצלחה',
				'MATCH_OLD_PASS' => 'לא ניתן להשתמש בשלוש הסיסמאות הקודמות!',
				'PASSWORD_NOT_NAME' =>'הסיסמה אינה יכולה לכלול את שם המשתמש שלך!',
				);
}

		elseif($language && $language == 'TR')
		{
			$response_message = array(
				'INVALID_PARAMS' => 'Geçersiz Parametreler',
				'ADDED' => 'Başarıyla Eklendi',
				'CREATED' => 'Başarıyla Oluşturuldu',
				'UPDATED' => 'Başarıyla Güncellendi',
				'LISTED' => 'Başarıyla Listelendi',
				'NODATA' => 'Mevcut Veri Yok',
				'WRONG' => 'Bir Sorun Oluştu',
				'DELETED' => 'Başarıyla Silindi',
				'AUTHFAIL' => 'Kimlik Doğrulama Başarısız',
				'AUTHENTICATED' => 'Kimlik Doğrulama Başarılı',
				'FILL_ALL_INFO'=> 'Lütfen Bütün Bilgileri Doldurun',
				'USEREXITS' => "Kullanıcı adı zaten mevcut",
				'EMAILEXITS' => 'E-posta zaten mevcut',
				'PASSWORD_CHANGE' => 'Şifre Başarıyla Değiştirildi. Şifreniz e-posta kontrol bilme',
				'PASSWORD_NOT_CHANGE' => 'Parola değişikliği başarısız!',
				'AVAILABLE' => "Kullanıcı Adı ve E-posta Mevcut",
				'CHECKIN_ALREADY_MISSED' => 'Bu Check in Zaten Atlandı',
				'CHECKIN_ALREADY_CLOSED' => 'Bu Check in Zaten Kapatıldı',
				'SIGN_OUT_SUCCESSFULY' => 'Başarıyla Çıkan İmzalı',
				'ENTER_CORRECT_PASSWORD' => 'Doğru Parolayı Girin',
				'EMAIL_NOT_EXIST_ENTER_CORRECT_EMAIL' => 'Bu geçerli bir e-posta adresi değil. Lütfen tekrar deneyin.',
				'INVALID_USERNAME' => 'Geçersiz Kullanıcı Adı',
				'CONFIRMED_SOS_IN_ACTIVE_CIRCLE' => 'Reporta’yı kullanmak için Özel Halkanızda en az bir onaylı Uygulama Kilidi Açma kişisi bulunmalıdır.',
				'CONFIRMED_SOS_IN_CONTACTS' => 'Reporta’yı kullanmak için en az bir onaylı Uygulama Kilidi Açma kişiniz bulunmalıdır.',
				'EMAIL_SEND' => 'E-posta Başarıyla Gönder',
				'PASSWORD_REQUEST_SEND' => 'Parola değiştirme bağlantısı için lütfen e-postanızı kontrol edin.',
				'CONTACT_EXIST' => 'İletişim E-posta Adresi Zaten Mevcut',
				'SOS_APP_LOCK' => 'Bu işlevi etkinleştirmek için bir Uygulama Kilidini Açma kişisini onaylamalısınız.',
				'APP_LOCKING' => 'Uygulama kilitlemeyi etkinleştirmek için Uygulama Kilidi Açma kişisini onaylamalısınız.',
				'REMOVING_CONTACT_APP_LOCK'=>'Bu kişi için Uygulama Kilidi Açmanın kaldırılması, uygulama kilitlemeyi devre dışı bırakır.',
				'CONFIRMED_SOS_CONTACTS' => 'Reporta’yı kullanabilmek için en az bir Uygulama Kilidi Açma kişiniz olmalıdır.',
				'INVALID_PASSWORD' => 'Geçersiz Parola',
				"DUPLICATE_NAME"=> "Bu ada sahip bir kişi zaten mevcut.",
				"DUPLICATE_EMAIL" => "Bu e-posta adresine sahip bir kişi mevcut.",
				"DUPLICATE_NUMBER" => "Bu telefon numarasına sahip bir kişi mevcut.",
				"SESSION_LOGOUT" => "Diğer oturum kapatılsın?  \nReporta’yı sadece bir cihazda kullanabilirsiniz.",
				"FORCE_LOGOUT" => "Oturum sona erdi \nReporta kullanmak için oturum açın.",
				"LOCK_USER" => "Reporta başarısız oturum açma işlemlerinden kilitlendi. Sonra tekrar deneyin.",
				"LAST_ATTEMPT_REMAIN" => "ACİL! Yanlış kullanıcı bilgisi tekrar girilirse Reporta 24 saat kilitlenir.",
				"LOGIN_ATTEMPTS_WARNING" => "Altı başarısız oturum açma denemesinin ardından Reporta 24 saat kilitlenir. Tekrar oturum açmak için 24 saat beklemeniz gerekir.",
				"INVALID_EXTENSION" => "Geçersiz dosya türü",
				"CLOSEPRECHECKIN" => 'Önceki Check in’i kapat',
				'PASS_UPDATED' => 'Parola başarıyla yüklendi',
				'MATCH_OLD_PASS' => 'Son 3 parolanızı kullanamazsınız!',
				'PASSWORD_NOT_NAME' =>'Parolanız kullanıcı adınızı içeremez!',
				);
}
else
{
	$response_message = array(
		'INVALID_PARAMS' => 'Invalid Parameters',
		'ADDED' => 'Added Successfully',
		'CREATED' => 'Created Successfully',
		'UPDATED' => 'Updated Successfully',
		'LISTED' => 'Listed Successfully',
		'NODATA' => 'No Data Available',
		'WRONG' => 'Something is Wrong',
		'DELETED' => 'Deleted Successfully',
		'AUTHFAIL' => 'Authentication Fail',
		'AUTHENTICATED' => 'Authentication Successfully',
		'FILL_ALL_INFO'=> 'Please Fill In All Information',
		'USEREXITS' => 'Username Already Exists.',
		'EMAILEXITS' => 'Email Already Exists',
		'PASSWORD_CHANGE' => 'Password Change Successfully. To know your password check your Email',
		'PASSWORD_NOT_CHANGE' => 'Password change was not successful!',
		'AVAILABLE' => 'Username and Email Are Available.',
		'CHECKIN_ALREADY_MISSED' => 'This Check-in Has Already Been Missed',
		'CHECKIN_ALREADY_CLOSED' => 'This Check-in Has Already Been Closed',
		'SIGN_OUT_SUCCESSFULY' => 'Signed Out Successfully',
		'ENTER_CORRECT_PASSWORD' => 'Enter Correct Password',
		'EMAIL_NOT_EXIST_ENTER_CORRECT_EMAIL' => ' is not a valid email address. Please re-try.',
		'INVALID_USERNAME' => 'Invalid Username',
		'CONFIRMED_SOS_IN_ACTIVE_CIRCLE' => 'You must have at least one confirmed App-Unlock contact in your Private Circle to use Reporta.',
		'CONFIRMED_SOS_IN_CONTACTS' => 'You must have at least one confirmed App-Unlock contact to use Reporta.',
		'EMAIL_SEND' => 'Email Send Successfully',
		'PASSWORD_REQUEST_SEND' => 'Please check your email for a link to change your password.',
		'CONTACT_EXIST' => 'Contact Email Already Exists',
		'SOS_APP_LOCK' => 'You must confirm an App-Unlock contact to enable this function.',
		'APP_LOCKING' => 'To enable app locking, you must confirm an App-Unlock contact.',
		'REMOVING_CONTACT_APP_LOCK'=>'Removing App-Unlock for this contact will deactivate app locking.',
		'CONFIRMED_SOS_CONTACTS' => 'You must have at least one App-Unlock contact to use Reporta.',
		'INVALID_PASSWORD' => 'Invalid password.',
		"DUPLICATE_NAME"=> "A contact with that name already exists.",
		"DUPLICATE_EMAIL" => "A contact with that email address already exists.",
		"DUPLICATE_NUMBER" => "A contact with that phone number already exists.",
		"SESSION_LOGOUT" => "Log out of other devices? \nYou can only use Reporta on one device at a time.",
		"FORCE_LOGOUT" => "Session Expired \nPlease log in again to use Reporta.",
		"LOCK_USER" => "Reporta has locked due to multiple failed login attempts. Please try again later.",
		"LAST_ATTEMPT_REMAIN" => "URGENT! Reporta will lock for 24 hours if you enter incorrect credentials again.",
		"LOGIN_ATTEMPTS_WARNING" => "Be aware that Reporta will lock for 24 hours after six failed login attempts. You will need to wait 24 hours to attempt another login.",
		"INVALID_EXTENSION" => "Invalid File Type",
		"CLOSEPRECHECKIN" => 'Close Previous Check-in',
		'PASS_UPDATED' => 'Password Successfully Updated',
		'MATCH_OLD_PASS' => 'You cannot use your last 3 passwords!',
		'PASSWORD_NOT_NAME' =>'Your password cannot contain your username!',
		);
}

return $response_message;
}

}
/**
*
* use to remove null
* @param string valuein array
* @param string key to replace
*/
function removenull(&$value, $key)
{
	if ($value === null)
	{
		$value = "";
	}
}
