use atp_test;

CREATE USER 'atp_user_test'@'localhost';

GRANT ALL PRIVILEGES ON atp_test.* To 'atp_user_test'@'localhost' IDENTIFIED BY 'atp_pass';


CREATE TABLE ventas (
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    producto varchar(255) NOT NULL,
    precioProducto DECIMAL(7,2) not null,
    nombre varchar(255) NOT NULL,
    mail varchar(255) NOT NULL,
    sessionId varchar(255) NOT NULL,
    idEnvio int not null,
    precioEnvio DECIMAL(7,2) not null,
    efectivo BOOLEAN not null,
    suscribirse BOOLEAN not null,
    fechaYhora datetime not null
  );

  CREATE TABLE suscripciones (
      id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
      nombre varchar(255) NOT NULL,
      mail varchar(255) NOT NULL,
      campania varchar(255) not null,
      fechaAgregado datetime not null
    );
