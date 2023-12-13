--Anzahl Verfügbarkeit:

select count(boote.bootid) from boote
where boote.bootid <> 
IFNULL((select boote.bootid from boote, verleihung, bestellungen
where verleihung.bootid = boote.bootid
and boote.typeid = 1
and bestellungen.BestellungsId = verleihung.BestellungsId
and bestellungen.Anfangsdatum < 2023-12-02
and bestellungen.Enddatum > 2023-12-09),0)
and boote.typeid = 1;

--Kunde anlegen:
insert into kunden(Vorname, Nachname, straße, hausnummer, ortid) values 
("Vorname", "Nachname", "irgendeine Str.", "123", (select ort.ortid from ort where ort.plz = "PLZ"));

--aktuelle KundenID:
Select KundenId from kunden
where Vorname = 'Olaf'
and Nachname = 'Olafson' 
and Straße = 'irgendeine Str.'
and Hausnummer = '123'
and OrtId = (select ort.ortid from ort where ort.plz = 'PLZ');


--neue bestellung:
insert into bestellungen(KundenId, Anzahl, Anfangsdatum, Enddatum) values 
(2, 5, 2023-12-02, 2023-12-05);

--aktuelle BestellungsId:
select bestellungen.BestellungsId
where kundenid = 2
and anzahl = 5
and Anfangsdatum = 2023-12-02
and enddatum  = 2023-12-03;

--neue verleihung:
insert into verleihung(BootId, BestellungsId) values 
(2, 5);