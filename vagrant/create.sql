
CREATE USER 'covidtrack'@'%' IDENTIFIED BY 'jSuZ7ugR7SKB9Afm';
CREATE DATABASE IF NOT EXISTS `covidtrack`;
GRANT ALL PRIVILEGES ON `covidtrack`.* TO 'covidtrack'@'%';GRANT ALL PRIVILEGES ON `covidtrack\_%`.* TO 'covidtrack'@'%';
flush privileges;
