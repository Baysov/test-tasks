# test-tasks
test-tasks

# Required settings for application deployment:

# 1. ����������� php:5.6-7.4-apache + mssql (sqlsrv)
# 2. ������� ������� "test-tasks" ������ ������������� ������ ��������� �������� ����� https:://domain.name/test-tasks/ (��� ����)
# 3. ���������� ������� �������� ���������� � �� �� ��������� ��������� �������� ����� � ��������� ���� � ���� � ����� /test-tasks/index.php
# 4. ����������� ������� ���� ������ � ������ ../test_tasks_secure_code/Database/test_tasks.mdf � test_tasks_log.ldf
# 5. ����������� ������� ������������ ����������� � �� mssql ��� ��������� ���� ������������ ../test_tasks_secure_code/Config/Config.php, ������ ����������� � ����� readme.txt
# 6. ����� ������������� �� � IDE ����������� �������� ��� ����� "test-tasks-db", 
#    ���������� ������ � ����������� ��� ����� � �� "test_tasks" � ����������� �������� � ���� �� ����������� (db_owner, db_datareader, db_datawriter)