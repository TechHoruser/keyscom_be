CREATE USER keyscom_test WITH PASSWORD 'keyscom_test' CREATEDB;
CREATE DATABASE keyscom_test
    WITH OWNER = keyscom_test
    ENCODING = 'UTF8'
    CONNECTION LIMIT = -1;
