VIEWS :
create or replace view vLivresMieuxNotes as
select a.idLivre, max(l.nomLivre) as nomLivre, round(avg(a.noteAvis), 2) as noteMoyenne
from avis a
inner join livre l on a.idLivre=l.idLivre
group by idLivre
order by noteMoyenne desc;


create or replace view vTotalLivre AS
SELECT li.idCommande, c.idUser, l.nomLivre, l.prixLivre, li.quantiteLigneCommande,
(l.prixLivre * li.quantiteLigneCommande) AS totalLivre
FROM livre l
inner JOIN ligneCommande li
ON l.idLivre = li.idLivre
inner join commande c
on c.idCommande=li.idCommande;


create or replace view vTotalLivreMax as
SELECT li.idCommande, c.idUser, l.nomLivre, l.prixLivre, li.quantiteLigneCommande,
(l.prixLivre * li.quantiteLigneCommande) AS totalLivre
FROM livre l
inner JOIN ligneCommande li
ON l.idLivre = li.idLivre
inner join commande c
on c.idCommande=li.idCommande
order by totalLivre desc;


create or replace view vTotalLivreMin as
SELECT li.idCommande, c.idUser, l.nomLivre, l.prixLivre, li.quantiteLigneCommande,
(l.prixLivre * li.quantiteLigneCommande) AS totalLivre
FROM livre l
inner JOIN ligneCommande li
ON l.idLivre = li.idLivre
inner join commande c
on c.idCommande=li.idCommande
order by totalLivre asc;


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
// Trigger qui sert à vérifier si la quantité de livre d''une ligneCommande est supérieur à la quantité d''exemplaire de ce livre.
DELIMITER $$
CREATE TRIGGER tStockLivre
BEFORE update ON ligneCommande
FOR EACH ROW
BEGIN
    DECLARE t_totalQuantite INT;
    DECLARE t_exemplaireLivre INT;
    DECLARE t_idUser INT;

    SELECT idUser
    INTO t_idUser
    FROM commande
    WHERE idCommande = NEW.idCommande;

    SELECT SUM(lc.quantiteLigneCommande)
    INTO t_totalQuantite
    FROM ligneCommande lc
    INNER JOIN commande c ON lc.idCommande = c.idCommande
    WHERE lc.idLivre = NEW.idLivre
    AND c.idUser = t_idUser
    and c.statutCommande = 'en attente';

    SET t_totalQuantite = IFNULL(t_totalQuantite, 0) - OLD.quantiteLigneCommande + NEW.quantiteLigneCommande;

    SELECT exemplaireLivre
    INTO t_exemplaireLivre
    FROM livre
    WHERE idLivre = NEW.idLivre;

    IF t_totalQuantite > t_exemplaireLivre THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'La quantité totale dépasse le nombre d''exemplaires disponibles pour ce livre.';
    END IF;
END$$
DELIMITER ;


// Trigger qui sert à mettre à jour la quantité d''exemplaire d'' livre après une commande.
DELIMITER //
CREATE TRIGGER tUpdateStockCommande
AFTER UPDATE ON commande
FOR EACH ROW
BEGIN
    IF OLD.statutCommande = 'en attente' AND NEW.statutCommande = 'expédiée' THEN
        UPDATE livre l
        JOIN ligneCommande lc ON l.idLivre = lc.idLivre
        SET l.exemplaireLivre = l.exemplaireLivre - lc.quantiteLigneCommande
        WHERE lc.idCommande = NEW.idCommande;
    END IF;
END//
DELIMITER ;


// Trigger qui sert à insérer les champs commande et ligneCommande dans les tables archiveCommande et archiveLigneCommande après la suppression d''un user
DELIMITER //
CREATE TRIGGER tInsertArchive
before DELETE ON user
FOR EACH ROW
BEGIN
    INSERT INTO archiveCommande
    SELECT c.idCommande, c.dateCommande, c.statutCommande, c.dateLivraisonCommande, c.idUser, NOW()
    FROM commande c
    WHERE c.idUser = OLD.idUser;

    INSERT INTO archiveLigneCommande
    SELECT lc.idLigneCommande, lc.idCommande, lc.idLivre, lc.quantiteLigneCommande, NOW()
    FROM ligneCommande lc
    JOIN commande c ON lc.idCommande = c.idCommande
    WHERE c.idUser = OLD.idUser;

    DELETE FROM ligneCommande
    WHERE idCommande IN (SELECT idCommande FROM commande WHERE idUser = OLD.idUser);

    DELETE FROM commande
    WHERE idUser = OLD.idUser;
END//
DELIMITER ;



PROCEDURES STOCKEES :
// Procédure qui sert à mettre à jour la quantité d''un livre d''une ligneCommande si ce livre existe déjà dans la commande.
// S''il n''existe pas, alors il y a une nouvelle insertion de ligneCommande avec ce nouveau livre.
// Si pour cette procédure le résultat = 0, ça signifie que le trigger "tStockLivre" stop la procédure
DELIMITER //
CREATE PROCEDURE pInsertOrUpdateLigneCommande(
    IN in_idCommande INT,
    IN in_idLivre INT,
    IN in_quantiteLigneCommande INT
)
BEGIN
    DECLARE p_idLigneCommande INT;
    DECLARE p_quantiteLigneCommande INT;

    SELECT lc.idLigneCommande, lc.quantiteLigneCommande
    INTO p_idLigneCommande, p_quantiteLigneCommande
    FROM ligneCommande lc
    INNER JOIN commande c ON lc.idCommande = c.idCommande
    WHERE lc.idLivre = in_idLivre
      AND c.idUser = (SELECT idUser FROM commande WHERE idCommande = in_idCommande LIMIT 1)
      AND c.statutCommande = 'en attente'
    LIMIT 1;

    IF p_idLigneCommande IS NOT NULL THEN
        UPDATE ligneCommande
        SET quantiteLigneCommande = quantiteLigneCommande + in_quantiteLigneCommande
        WHERE idLigneCommande = p_idLigneCommande;

        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'TEXT 1';
    ELSE
        INSERT INTO ligneCommande
        VALUES (null, in_idCommande, in_idLivre, in_quantiteLigneCommande);

         SIGNAL SQLSTATE '45000'
         SET MESSAGE_TEXT = 'TEXT 2';
    END IF;
END //
DELIMITER ;


// Procédure qui sert à offrir un livre après une commande
DELIMITER $$
CREATE PROCEDURE pOffrirLivre(
    IN in_idUser INT,
    IN in_chiffre INT
)
proc: BEGIN
    DECLARE p_idCommande INT;
    DECLARE p_idLivre INT;
    DECLARE p_abonne_valide BOOLEAN DEFAULT FALSE;

    SELECT COUNT(*) > 0 INTO p_abonne_valide
    FROM abonnement
    WHERE idUser = in_idUser AND dateFinAbonnement > CURDATE();

    IF NOT p_abonne_valide THEN
        LEAVE proc;
    END IF;

    IF in_chiffre = 5 THEN
        SELECT idCommande INTO p_idCommande
        FROM commande
        WHERE idUser = in_idUser AND statutCommande = 'en attente'
        ORDER BY dateCommande DESC
        LIMIT 1;

        IF p_idCommande IS NOT NULL THEN
            SELECT idLivre INTO p_idLivre
            FROM (
                SELECT 9 AS idLivre UNION ALL
                SELECT 10 UNION ALL
                SELECT 11 UNION ALL
                SELECT 12
            ) AS livres
            ORDER BY RAND()
            LIMIT 1;

            INSERT INTO ligneCommande
            VALUES (null, p_idCommande, p_idLivre, 1);

            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Un livre vous a été offert dans votre panier actuel !';
        END IF;
    END IF;
END$$
DELIMITER ;


// Procédure qui va transformer les noms des champs de la table livre en id afin de faire une insertion.
DELIMITER $$
CREATE PROCEDURE pInsertLivre(
    IN in_nomLivre VARCHAR(255),
    IN in_auteurLivre VARCHAR(255),
    IN in_imageLivre VARCHAR(255),
    IN in_exemplaireLivre INT,
    IN in_prixLivre float(10, 2),
    IN in_nomCategorie VARCHAR(255),
    IN in_nomMaisonEdition VARCHAR(255),
    IN in_nomPromotion VARCHAR(255)
)
BEGIN
    DECLARE p_idCategorie INT;
    DECLARE p_idMaisonEdition INT;
    DECLARE p_idPromotion INT;

    SELECT idCategorie INTO p_idCategorie
    FROM categorie
    WHERE nomCategorie = in_nomCategorie;

    SELECT idMaisonEdition INTO p_idMaisonEdition
    FROM maisonEdition
    WHERE nomMaisonEdition = in_nomMaisonEdition;

    SELECT idPromotion INTO p_idPromotion
    FROM promotion
    WHERE nomPromotion = in_nomPromotion;

    INSERT INTO livre (idLivre, nomLivre, auteurLivre, imageLivre, exemplaireLivre, prixLivre, idCategorie, idMaisonEdition, idPromotion)
    VALUES (null, in_nomLivre, in_auteurLivre, in_imageLivre, in_exemplaireLivre, in_prixLivre, p_idCategorie, p_idMaisonEdition, p_idPromotion);
END $$
DELIMITER ;



EVENT :
// Event qui sert à mettre à jour le statutCommande en fonction de la dateLivraisonCommande et de la date actuelle.
DELIMITER //
CREATE EVENT IF NOT EXISTS eUpdateStatutCommande
ON SCHEDULE EVERY 1 DAY
STARTS CURRENT_DATE + INTERVAL 1 DAY
DO
BEGIN
    UPDATE commande
    SET statutCommande = 'arrivée'
    WHERE statutCommande = 'expédiée'
    AND dateLivraisonCommande < CURDATE();
END//
DELIMITER ;