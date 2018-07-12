use atp_test;

CREATE USER 'atp_user_test'@'localhost';

GRANT ALL PRIVILEGES ON atp_test.* To 'atp_user_test'@'localhost' IDENTIFIED BY 'atp_pass';


CREATE TABLE venta (
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
  ) ENGINE=INNODB CHARACTER SET utf8 COLLATE utf8_general_ci;


create table estado (
    id int not null primary key,
    codigo varchar(255) not null,
    descripcion varchar(255) not null
)ENGINE=INNODB CHARACTER SET utf8 COLLATE utf8_general_ci;

insert into estado (id, codigo, descripcion) value (1, 'checkout-mp', 'Botón de Pago en checkout accedido para MercadoPago.');
insert into estado (id, codigo, descripcion) value (2, 'checkout-efectivo', 'Botón de Pago en checkout accedido para efectivo.');

insert into estado (id, codigo, descripcion) value (3, 'checkout-mp-pagado', 'Pago acreditado en MercadoPago.');
insert into estado (id, codigo, descripcion) value (4, 'checkout-efectivo-pagado', 'Pagado en efectivo.');

insert into estado (id, codigo, descripcion) value (5, 'checkout-mp-pendiente', 'El usuario no completó el pago en MercadoPago.');
insert into estado (id, codigo, descripcion) value (6, 'checkout-mp-revision', 'El pago está siendo revisado por MercadoPago.');
insert into estado (id, codigo, descripcion) value (7, 'checkout-mp-mediacion', 'Los usuarios tienen iniciada una disputa en MercadoPago.');

insert into estado (id, codigo, descripcion) value (8, 'checkout-mp-rechazado', 'El pago fué rechazado por MercadoPago, el usuario puede intentar nuevamente el pago.');
insert into estado (id, codigo, descripcion) value (9, 'checkout-mp-cancelado', 'El pago fue cancelado por una de las partes en MercadoPago, o porque el tiempo expiró.');

insert into estado (id, codigo, descripcion) value (10, 'checkout-mp-refunded', 'El pago fue devuelto al usuario en MercadoPago.');
insert into estado (id, codigo, descripcion) value (11, 'checkout-mp-charged-back', 'Fue hecho un contracargo en la tarjeta del pagador en MercadoPago.');

insert into estado (id, codigo, descripcion) value (999, 'desconocido', 'Desconocido.');


create table venta_estado (
    id_venta int not null,
    id_estado int not null,
    mp_id int UNSIGNED,
    comentario varchar(255),
    fechaYhora datetime not null,
    CONSTRAINT venta_estado_venta_fk FOREIGN KEY (id_venta) REFERENCES venta(id),
    CONSTRAINT venta_estado_estado_fk FOREIGN KEY (id_estado) REFERENCES estado(id)
)ENGINE=INNODB CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE suscripcion (
      id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
      nombre varchar(255) NOT NULL,
      mail varchar(255) NOT NULL,
      campania varchar(255) not null,
      fechaAgregado datetime not null
) ENGINE=INNODB CHARACTER SET utf8 COLLATE utf8_general_ci;



  //1
  //2
  //3
  //4
  //5
  //6
  //7
