# Datenbankanbindung
webtech_project.sql importieren 
db_host, db_username und db_password in config/config.ini entsprechend anpassen
# Automatische Email senden
php.ini und sendmail.ini entsprechend nachfolgender Konfiguration anpassen

php.ini:  [mail function]
    SMTP=smtp.gmail.com
    smtp_port=587
    sendmail_from = crew3068@gmail.com
    sendmail_path = "\"C:\xampp\sendmail\sendmail.exe\" -t"

sendmail.ini: [sendmail]
    smtp_server=smtp.gmail.com
    smtp_port=587
    error_logfile=error.log
    debug_logfile=debug.log
    auth_username=crew3068@gmail.com
    auth_password=Id5GuuAXoOLksbxkTMVf
    force_sender=crew3068@gmail.com

# Zugangsdaten der vordefinierten User

Username: "admin"
Passswort: "admin"
 
Username: "chris"
Passswort: "chris1234"

Username: "raphi"
Passswort: "raphi1234"

Username: "will"
Passswort: "will1234"