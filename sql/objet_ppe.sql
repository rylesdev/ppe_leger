VIEWS :

create or replace view vTotalLivre AS
SELECT li.idCommande, c.idUser, l.nomLivre, l.prixLivre, li.quantiteLigneCommande,
(l.prixLivre * li.quantiteLigneCommande) AS totalLivre
FROM livre l
inner JOIN ligneCommande li
ON l.idLivre = li.idLivre
inner join commande c
on c.idCommande=li.idCommande;


create or replace view vTotalCommandeEnAttente as
select c.idUser, sum(l.prixLivre * li.quantiteLigneCommande) as totalCommande
from commande c
inner join ligneCommande li
on c.idCommande=li.idCommande
inner join livre l
on li.idLivre=l.idLivre
where c.statutCommande = 'en attente'
group by c.idUser;


create or replace view vTotalCommandeExpediee as
select c.idUser, sum(l.prixLivre * li.quantiteLigneCommande) as totalCommande
from commande c
inner join ligneCommande li
on c.idCommande=li.idCommande
inner join livre l
on li.idLivre=l.idLivre
where c.statutCommande = 'expédiée'
group by c.idUser;


CREATE OR REPLACE VIEW vTotalLivreEnAttente AS
SELECT
    li.idCommande,
    c.idUser,
    l.nomLivre,
    l.prixLivre,
    li.quantiteLigneCommande,
    (l.prixLivre * li.quantiteLigneCommande) AS totalLivre
FROM
    livre l
INNER JOIN
    ligneCommande li ON l.idLivre = li.idLivre
INNER JOIN
    commande c ON c.idCommande = li.idCommande
WHERE
    c.statutCommande = 'en attente';


CREATE OR REPLACE VIEW vTotalLivreExpediee AS
SELECT li.idCommande, c.idUser, l.idLivre, l.nomLivre, l.prixLivre, li.quantiteLigneCommande, (l.prixLivre * li.quantiteLigneCommande) AS totalLivre
FROM livre l
INNER JOIN ligneCommande li
ON l.idLivre = li.idLivre
INNER JOIN commande c ON c.idCommande = li.idCommande
WHERE c.statutCommande = 'expédiée';


create or replace view vNbMinLivre as
select *
from vTotalLivre
order by totalLivre asc;


create or replace view vNbMaxLivre as
select *
from vTotalLivre
order by totalLivre desc


create or replace view vNomMinLivre as
select *
from vTotalLivre
order by nomLivre asc;


create or replace view vNomMaxLivre as
select *
from vTotalLivre
order by nomLivre desc;


create or replace view vCommandesEnAttente as
select count(c.idCommande) as nbCommandeEnAttente
from commande c
where c.statutCommande = 'en attente';


create or replace view vMeilleuresVentes as
select l.idLivre,l.nomLivre,
sum(li.quantiteLigneCommande) as totalVendu
from ligneCommande li
inner join livre l on li.idLivre = l.idLivre
group by l.idLivre, l.nomLivre
order by totalVendu desc;


create or replace view vLivresEnStock as
select l.idLivre, l.nomLivre, l.prixLivre, l.exemplaireLivre
from livre l
where l.exemplaireLivre <= 5;



TRIGGERS :

delimiter $$
create trigger tExemplaireLivreLigneCommande
before insert on ligneCommande
for each row
begin
    declare existingQuantity int;
    select lc.quantiteLigneCommande
    into existingQuantity
    from ligneCommande lc
    inner join commande c on lc.idCommande = c.idCommande
    where lc.idLivre = NEW.idLivre
      and c.idUser = (select idUser from commande where idCommande = NEW.idCommande LIMIT 1)
      and c.statutCommande = 'en attente'
    LIMIT 1;
    if existingQuantity is not null then
        SIGNAL SQLSTATE "45000"
        SET MESSAGE_TEXT = "Erreur : Livre déjà dans le panier de cet utilisateur. Veuillez modifier le nombre d'exemplaires.";
    end if;
end $$
delimiter ;


delimiter $$
create trigger tStockNull
before insert on ligneCommande
for each row
begin
declare exemplaireLivreDisponible int;
select exemplaireLivre
into exemplaireLivreDisponible
from livre
where idLivre = NEW.idLivre;
if exemplaireLivreDisponible < NEW.quantiteLigneCommande then
    SIGNAL SQLSTATE '45000'
    set MESSAGE_TEXT = 'Erreur : Stock insuffisant pour le livre.';
end IF;
end $$
delimiter ;


delimiter $$
create trigger tInsertStockCommande
after insert on ligneCommande
for each row
begin
update livre
set exemplaireLivre = exemplaireLivre - NEW.quantiteLigneCommande
where idLivre = NEW.idLivre;
end $$
delimiter ;


delimiter $$
create trigger tUpdateStockCommande
after update on ligneCommande
for each row
begin
update livre
set exemplaireLivre = exemplaireLivre - (NEW.quantiteLigneCommande - OLD.quantiteLigneCommande)
where idLivre = NEW.idLivre;
end $$
delimiter ;



PROCEDURES STOCKEES :

delimiter $$
create procedure pHashMdpUser(
in p_idUser int (10),
in p_nomUser varchar(50),
in p_prenomUser varchar(50),
in p_emailUser varchar(50),
in p_mdpUser varchar(255),
in p_adresseUser varchar(50),
in p_roleUser enum('admin', 'client'),
in p_dateInscriptionUser date
)
begin
insert into user (idUser, nomUser, prenomUser, emailUser, mdpUser, adresseUser, roleUser, dateInscriptionUser)
values (
p_idUser,
p_nomUser,
p_prenomUser,
p_emailUser,
SHA1(p_mdpUser),
p_adresseUser,
p_roleUser,
p_dateInscriptionUser
);
end $$
delimiter ;


DELIMITER $$
CREATE PROCEDURE pOffrirLivre(
    IN p_idUser INT
)
BEGIN
    DECLARE totalQuantite INT;
    DECLARE p_dateCommande DATETIME;
    DECLARE p_dateLivraisonCommande DATE;
    DECLARE idLivre INT DEFAULT 1;
    DECLARE newIdCommande INT;
    IF NOT EXISTS (
        SELECT 1
        FROM abonnement
        WHERE idUser = p_idUser
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Vous devez être abonné pour bénéficier de cette offre.';
    END IF;
    IF NOT EXISTS (
        SELECT 1
        FROM abonnement
        WHERE idUser = p_idUser AND dateFinAbonnement > CURDATE()
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Votre abonnement a expiré. Vous ne pouvez pas bénéficier de cette offre.';
    END IF;
    SELECT SUM(quantiteLigneCommande)
    INTO totalQuantite
    FROM ligneCommande l
    INNER JOIN commande c
        ON l.idCommande = c.idCommande
    WHERE c.statutCommande = 'expédiée' AND c.idUser = p_idUser;
    IF totalQuantite > 10 THEN
        INSERT INTO commande (idCommande, dateCommande, statutCommande, dateLivraisonCommande, idUser)
        VALUES (null, NOW(), 'expédiée', DATE_ADD(NOW(), INTERVAL 7 DAY), p_idUser);
        SET newIdCommande = LAST_INSERT_ID();
        INSERT INTO ligneCommande (idLigneCommande, idCommande, idLivre, quantiteLigneCommande)
        VALUES (null, newIdCommande, 6, 1);
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Un livre vous a été offert et va vous être envoyé directement chez vous !';
    END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE pOffrirLivre(
    IN p_idUser INT
)
BEGIN
    DECLARE totalQuantite INT;
    DECLARE p_dateCommande DATETIME;
    DECLARE p_dateLivraisonCommande DATE;
    DECLARE idLivre INT DEFAULT 1;
    DECLARE newIdCommande INT;
    DECLARE seuil INT DEFAULT 10;
    IF NOT EXISTS (
        SELECT 1
        FROM abonnement
        WHERE idUser = p_idUser
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Vous devez être abonné pour bénéficier de cette offre.';
    END IF;
    IF NOT EXISTS (
        SELECT 1
        FROM abonnement
        WHERE idUser = p_idUser AND dateFinAbonnement > CURDATE()
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Votre abonnement a expiré. Vous ne pouvez pas bénéficier de cette offre.';
    END IF;
    SELECT SUM(quantiteLigneCommande)
    INTO totalQuantite
    FROM ligneCommande l
    INNER JOIN commande c
        ON l.idCommande = c.idCommande
    WHERE c.statutCommande = 'expédiée' AND c.idUser = p_idUser;
    IF totalQuantite >= seuil AND (totalQuantite MOD seuil) = 0 THEN
        INSERT INTO commande (idCommande, dateCommande, statutCommande, dateLivraisonCommande, idUser)
        VALUES (null, NOW(), 'expédiée', DATE_ADD(NOW(), INTERVAL 7 DAY), p_idUser);
        SET newIdCommande = LAST_INSERT_ID();
        INSERT INTO ligneCommande (idLigneCommande, idCommande, idLivre, quantiteLigneCommande)
        VALUES (null, newIdCommande, 6, 1);
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Un livre vous a été offert et va vous être envoyé directement chez vous !';
    END IF;
END$$
DELIMITER ;



DELIMITER $$
CREATE PROCEDURE pOffrirLivre(
    IN p_idUser INT,
    IN chiffre INT
)
BEGIN
    DECLARE newIdCommande INT;
    IF NOT EXISTS (
        SELECT 1
        FROM abonnement
        WHERE idUser = p_idUser
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Vous devez être abonné pour bénéficier de cette offre.';
    END IF;
    IF NOT EXISTS (
        SELECT 1
        FROM abonnement
        WHERE idUser = p_idUser AND dateFinAbonnement > CURDATE()
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Votre abonnement a expiré. Vous ne pouvez pas bénéficier de cette offre.';
    END IF;
    IF chiffre = 5 THEN
        UPDATE livre
        SET prixLivre = 0
        WHERE idLivre = 6;
        INSERT INTO commande (idCommande, dateCommande, statutCommande, dateLivraisonCommande, idUser)
        VALUES (null, NOW(), 'expédiée', DATE_ADD(NOW(), INTERVAL 7 DAY), p_idUser);
        SET newIdCommande = LAST_INSERT_ID();
        INSERT INTO ligneCommande (idLigneCommande, idCommande, idLivre, quantiteLigneCommande)
        VALUES (null, newIdCommande, 6, 1);
        UPDATE livre
        SET prixLivre = 22.00
        WHERE idLivre = 6;
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Un livre vous a été offert et va vous être envoyé directement chez vous !';
    END IF;
END$$
DELIMITER ;