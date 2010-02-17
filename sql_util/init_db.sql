DROP DATABASE IF EXISTS login_db;
CREATE DATABASE login_db;
GRANT ALL PRIVILEGES ON login_db.*
TO jkp@jkproject.localhost IDENTIFIED BY 'jkproject';
GRANT ALL PRIVILEGES ON login_db.*
TO jkp@localhost IDENTIFIED BY 'jkproject';
use login_db;
source login_db.sql;

DROP DATABASE IF EXISTS store_db;
CREATE DATABASE store_db;
GRANT ALL PRIVILEGES ON store_db.*
TO jkp@jkproject.localhost IDENTIFIED BY 'jkproject';
GRANT ALL PRIVILEGES ON store_db.*
TO jkp@localhost IDENTIFIED BY 'jkproject';
use store_db;
source store_db.sql;

DROP DATABASE IF EXISTS bbs_db;
CREATE DATABASE bbs_db;
GRANT ALL PRIVILEGES ON bbs_db.*
TO jkp@jkproject.localhost IDENTIFIED BY 'jkproject';
GRANT ALL PRIVILEGES ON bbs_db.*
TO jkp@localhost IDENTIFIED BY 'jkproject';
use bbs_db;
source bbs_db.sql;


