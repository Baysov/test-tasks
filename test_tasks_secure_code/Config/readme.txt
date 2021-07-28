Please create a configuration file to connect to your database named "Config.php" or ask the author https://github.com/Baysov

<?php
 
 // connect to db (mysql\mssql\other)
 config::db_name = 'db-name';
 config::db_user = 'db-user';
 config::db_pasw = 'db-password';

 // connect to account Test.Serv.Mls@gmail.com (test account for sending email messages)
 config::account_from_name = 'Sender name';
 config::account_gmail_name = 'Test.Serv.Mls@gmail.com';
 config::account_gmail_pasw = '***********';

 // settings secure hash of generating registration password (specify any character set for your project)
 config::hash_pasw_project() { return md5('special hash of generating registration password'); } // do not change, keep secret (in the database, other storages)

?>