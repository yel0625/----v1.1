@echo off
set "date=%DATE:/=-%"
set "time=%TIME::=-%"
set "filename=backup_%date%_%time%.sql"

mysqldump -u root -pYOUR_PASSWORD qilin_cms > F:\兰州琪霖\甘肃骐霖\backups\%filename%

rem 保留最近7天备份
forfiles -p "F:\兰州琪霖\甘肃骐霖\backups" -m *.sql -d -7 -c "cmd /c del @path"