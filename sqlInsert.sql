insert into boottypen values 
(1,'Kajak (1 Person)'), 
(2,'Kajak (2 Personen)'), 
(3, 'Kanadier (2 Personen)'), 
(4, 'Kanadier (3 Personen)');

insert into boote (BootId, TypeID) values 
(1,1), 
(2,1), 
(3,1), 
(4,1),
(5,1),
(6,2),
(7,2),
(8,2),
(9,2),
(10,2),
(11,3),
(12,3),
(13,3),
(14,3),
(15,3),
(16,4),
(17,4),
(18,4),
(19,4),
(20,4);

insert into ort values 
(1, 75172, "Pforzheim"),
(2, 75175, "Pforzheim");

insert into kunden values 
(1, "Olaf", "Olafson", "irgendeine Str.", "123", 1),
(2, "Man", "Fred", "the other street","2", 2);

insert into bestellungen values 
(2, 1, 2, "2023-12-01", "2023-12-08"),
(1, 2, 1, "2022-10-01", "2022-10-08");

insert into verleihung values 
(1, 1, "Verfügbar"),
(6, 2, "Verliehen"),
(7, 2, "Verliehen");
