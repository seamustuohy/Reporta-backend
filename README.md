# Reporta Admin
The server side Source code of IWMF's Reporta App which is written in PHP.

## Description:

* Developed 'Reporta', a mobile application designed to help protect journalists working in dangerous and high-risk settings. This mobile application aims to ensure reporters' safety.
* The app will allow journalists to create an account and alert their connections and followers when they are in danger or need of immediate assistance. 


## Primary Features:

* An Alert tool that geolocates users and signals the need for immediate assistance.
* An Check In tool that geolocates users and signals the need for assistance when missed
* An SOS button that sends an immediate alert to users private contacts to enable a protocol of action to assist the user


## Contact Us

We encourage you to provide us your feedback and thoughts about the project. Feel free to leave comments via the Github project page or email them to us directly at techfeedback@reporta.org

We’d love to hear your comments and suggestions on how to improve the code and additional features you’d like to see in it. All we ask is that comments and contributions be reasonably respectful and made in the spirit of improving Reporta and the functions is provides users.

We will absolutely take them to heart as we work on future releases.

## License

All code has been released under GPL 3 and users are free to review, comment and reuse the code any way they like.

# Technical Details

## Minimum requirements configuration

* PHP 5.4.0+
* HTML5
* DATABASE MySql(4.1+), MySqli


## Environment Configuration steps

* Create database with name iwmf_user
* SQL File Location : "admin/db_script/iwmf_user.sql", import this file in to your database
* Create database with name iwmf
* SQl File Location : "admin/db_script/iwmf.sql", import this file in to your database
* Configuration File Location : "admin/application/config/settings.xml"
* Set all tag values based on you requirements.
* Configuration File Location : "admin/application/xcrud/xcrud_config.php"
* Set database configuration based on you requirements.


### Tags  in settings.xml

* <config>....</config> :

  Set Server Configuration

 * <base_url>..</base_url> :  server url

  Below tags will use to send email

   * <protocol>smtp</protocol>
   * <smtp_host>smtp.XXX.com</smtp_host> : smtp host of email service provider
   * <smtp_port>587</smtp_port>  : port number
   * <smtp_user>XXX@XXX.com</smtp_user> :email id which will use to send eamil
   * <smtp_pass>XXXX</smtp_pass> : email password
   * <mailtype>html</mailtype> : type of email
   * <email_name>IWMF</email_name>  : name on each mail

* <db>  <default> .... </default></db>

    Set Database Configuration

 * <hostname>DBHOST</hostname>  : database host name
 * <username>username</username> : database username
 * <password>****</password>  : database password
 * <database>XXXXX</database>  : database name (iwmf)
 * <dbdriver>mysqli</dbdriver> : database driver (mysql/mysqli)

* <constants> .. </constants>

   configuration of constants use in project

 * <TOKENEXPIREMIN>960</TOKENEXPIREMIN>  : token validation time in minute
 * <SUB_DIR>assets/</SUB_DIR>  : use to upload file
 * <EMAIL_FROM>admin@reporta.org</EMAIL_FROM> : from email address
 * <EMAIL_NAME>Reporta</EMAIL_NAME>  : From email name
 * <SMS_FROM>(201) 885-6452</SMS_FROM> : from phone number to send SMS
 * <ANDROID_API_ACCESS_KEY>***</ANDROID_API_ACCESS_KEY> :android api key to send notification
 * <KEY>***</KEY> : key use in encoding and decoding
 * <FACEBOOK_APP_ID>***</FACEBOOK_APP_ID> : facebook app id use to post on facebook
 * <FACEBOOK_APP_SECRET>**</FACEBOOK_APP_SECRET> : facebook app SECRET use to post on facebook
 * <TWITTER_CONSUMER_KEY>**</TWITTER_CONSUMER_KEY>  : Twitter key
 * <TWITTER_CONSUMER_SECRET>*** </TWITTER_CONSUMER_SECRET> : Twitter secret key


* <twilio>....</twilio>

   Twilio configuration to send sms

 * <account_sid>***</account_sid> : account sid given by twilio
 * <auth_token>***</auth_token> : account token given by twilio
 * <number>+12026013865</number>  : twilio phone number


## Code Skeleton:

* Admin/Application/Controllers contains all logical code.
* Admin/Application/Controllers/api contains all webservices used for communication with the mobile application.
* Admin/Application/Controllers/userInfo and Admin/Application/Controllers/ contains code blocks used for two type of admin user (superadmin and admin).

## Code Explained:

### API

Total six API in the api directory.

* checkin
    Where user can register for the event he is going for and he can also set the safety time interval at which user will receive an 'checkinconfirm' alert.

* checkincron
       If user does not respond to 'checkinconfirm' alert then this cron will fire an email and sms to contact added by user.
* contact
        User can add or delete emergency contact information.
* deletedatacron
        As we do not save any important data on server we delete the data saved (i.e. alerts, checkin, sos), this deletes the data from server.
* media
        When user does not respond to 'checkinconfirm' alert then this service will fire an email with attached media  video/image/audio which user has uploaded in the checkin time.
* user
        This will manage user Create, Update, Signin, Signout, Forgotpassword, Updatepassword operations.

#### Admin

There are two types of admin user:

* Superadmin
    * Special Privileges
            Superadmin can add, edit, delete any type of user.
* Admin
    * Special Privileges
             Admin can only view but can not edit or delete any user.
* Common Privileges
      * Can view Alert List and Detailed view
      * Can view Checkin List and Detailed view
