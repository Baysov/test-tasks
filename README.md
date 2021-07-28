# test-tasks
test-tasks

# Required settings for application deployment:

# 1. Используйте php:5.6-7.4-apache + mssql (sqlsrv)
# 2. Каталог проекта "test-tasks" должен располагаться внутри основного каталога сайта https:://domain.name/test-tasks/ (или ниже)
# 3. Разместите каталог хранения исходников и БД за пределами основного каталога сайта и настройте путь к нему в файле /test-tasks/index.php
# 4. Используйте готовую базу данных с именем ../test_tasks_secure_code/Database/test_tasks.mdf и test_tasks_log.ldf
# 5. Используйте готовую конфигурацию подключения к БД mssql или настройте файл конфигурации ../test_tasks_secure_code/Config/Config.php, следуя инструкциям в файле readme.txt
# 6. После присоединения БД в IDE обязательно создайте имя входа "test-tasks-db", 
#    установите пароль и сопоставьте имя входа с БД "test_tasks" с настройками членства в роли БД настройками (db_owner, db_datareader, db_datawriter)