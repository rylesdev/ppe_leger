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
SELECT li.idLivre, li.idCommande, li.idLigneCommande, c.idUser, l.nomLivre, l.prixLivre, li.quantiteLigneCommande, (l.prixLivre * li.quantiteLigneCommande) AS totalLivre
FROM livre l
INNER JOIN ligneCommande li
ON l.idLivre = li.idLivre
INNER JOIN commande c
ON c.idCommande = li.idCommande
WHERE
c.statutCommande = 'en attente';


CREATE OR REPLACE VIEW vTotalLivreExpediee AS
SELECT li.idCommande, c.idUser, l.idLivre, l.nomLivre, l.prixLivre, li.quantiteLigneCommande, (l.prixLivre * li.quantiteLigneCommande) AS totalLivre
FROM livre l
INNER JOIN ligneCommande li
ON l.idLivre = li.idLivre
INNER JOIN commande c ON c.idCommande = li.idCommande
WHERE c.statutCommande = 'expédiée';


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

create view vMeilleursAvis as
select l.idLivre, l.nomLivre, avg(a.noteAvis) as moyenneNote
from avis a
inner join  livre l
on a.idLivre = l.idLivre
group by l.idLivre, l.nomLivre
order by moyenneNote desc;


CREATE VIEW vNbLivreAcheteUser AS
SELECT u.emailUser, SUM(l.quantiteLigneCommande) AS nbLivreAchete
FROM ligneCommande l
INNER JOIN commande c ON l.idCommande = c.idCommande
INNER JOIN user u ON c.idUser = u.idUser
WHERE c.statutCommande = 'expédiée'
GROUP BY u.emailUser;



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
create trigger tUpdateStockCommandeExpediee
after update on commande
for each row
begin
    if OLD.statutCommande = 'en attente' and NEW.statutCommande = 'expédiée' then
        update livre
        set exemplaireLivre = exemplaireLivre - (
            select sum(quantiteLigneCommande)
            from ligneCommande
            where idCommande = NEW.idCommande
            and ligneCommande.idLivre = livre.idLivre
        )
        where idLivre in (select idLivre from ligneCommande where idCommande = NEW.idCommande);
    end if;
end $$
delimiter ;



PROCEDURES STOCKEES :
DELIMITER $$
CREATE PROCEDURE pOffrirLivre(
    IN p_idUser INT,
    IN p_chiffre INT
)
BEGIN
    DECLARE newIdCommande INT;
    DECLARE randomLivreId INT;
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
    IF p_chiffre = 5 THEN
        INSERT INTO commande (idCommande, dateCommande, statutCommande, dateLivraisonCommande, idUser)
        VALUES (null, NOW(), 'expédiée', DATE_ADD(NOW(), INTERVAL 7 DAY), p_idUser);
        SET newIdCommande = LAST_INSERT_ID();
        SELECT idLivre
        INTO randomLivreId
        FROM (
            SELECT 9 AS idLivre UNION ALL
            SELECT 10 UNION ALL
            SELECT 11 UNION ALL
            SELECT 12
        ) AS livres
        ORDER BY RAND()
        LIMIT 1;
        INSERT INTO ligneCommande (idLigneCommande, idCommande, idLivre, quantiteLigneCommande)
        VALUES (null, newIdCommande, randomLivreId, 1);
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Un livre vous a été offert et va vous être envoyé directement chez vous !';
    END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE pInsertLivre(
    IN p_nomLivre VARCHAR(255),
    IN p_auteurLivre VARCHAR(255),
    IN p_imageLivre VARCHAR(255),
    IN p_exemplaireLivre INT,
    IN p_prixLivre DECIMAL(10, 2),
    IN p_nomCategorie VARCHAR(255),
    IN p_nomMaisonEdition VARCHAR(255)
)
BEGIN
    DECLARE v_idCategorie INT;
    DECLARE v_idMaisonEdition INT;
    SELECT idCategorie INTO v_idCategorie
    FROM categorie
    WHERE nomCategorie = p_nomCategorie;
    SELECT idMaisonEdition INTO v_idMaisonEdition
    FROM maisonEdition
    WHERE nomMaisonEdition = p_nomMaisonEdition;
    INSERT INTO livre (nomLivre, auteurLivre, imageLivre, exemplaireLivre, prixLivre, idCategorie, idMaisonEdition)
    VALUES (p_nomLivre, p_auteurLivre, p_imageLivre, p_exemplaireLivre, p_prixLivre, v_idCategorie, v_idMaisonEdition);
END $$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE pInsertOrUpdatePromotion(
    IN p_nomLivre VARCHAR(255),
    IN p_prixPromotion DECIMAL(10,2),
    IN p_dateFinPromotion date
)
BEGIN
    DECLARE v_idLivre INT;
    SELECT idLivre INTO v_idLivre FROM livre WHERE nomLivre = p_nomLivre LIMIT 1;
    IF v_idLivre IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Le livre spécifié n\'existe pas.';
    END IF;
    IF EXISTS (SELECT 1 FROM promotion WHERE idLivre = v_idLivre) THEN
        UPDATE promotion
        SET prixPromotion = p_prixPromotion
        WHERE idLivre = v_idLivre;
    ELSE
        INSERT INTO promotion (idPromotion, idLivre, dateDebutPromotion, dateFinPromotion, prixPromotion)
        VALUES (null, v_idLivre, curdate(), p_dateFinPromotion, p_prixPromotion);
    END IF;
END $$
DELIMITER ;