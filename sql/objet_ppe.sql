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
// Trigger suivant à supprimer après vérification que la procédure pQuantiteLigneCommande fonctionne correctement
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


// Faire un trigger qui va empêcher le user d''insérer une ligneCommande (insertLigneCommande) si la somme de toutes les quantiteLigneCommande (old et new) pour chaque idLivre est supérieur ou égal au nombre exemplaire d''un livre
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
DELIMITER //
CREATE PROCEDURE pQuantiteLigneCommande(
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
    ELSE
        INSERT INTO ligneCommande
        VALUES (null, in_idCommande, in_idLivre, in_quantiteLigneCommande);
    END IF;
END //
DELIMITER ;


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
    IN in_nomLivre VARCHAR(255),
    IN in_reductionPromotion INT,
    IN in_dateFinPromotion DATE
)
BEGIN
    DECLARE p_idLivre INT;
    DECLARE p_newIdPromotion INT;
    DECLARE p_message VARCHAR(255);

    SELECT idLivre INTO p_idLivre
    FROM livre
    WHERE nomLivre = in_nomLivre
    LIMIT 1;

    IF p_idLivre IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erreur : Le livre spécifié n''existe pas';
    END IF;

    SELECT idPromotion INTO p_newIdPromotion
    FROM promotion
    WHERE reductionPromotion = in_reductionPromotion
    ORDER BY idPromotion DESC
    LIMIT 1;

    IF p_newIdPromotion IS NOT NULL THEN
        UPDATE promotion
        SET dateFinPromotion = in_dateFinPromotion
        WHERE idPromotion = p_newIdPromotion;

        UPDATE livre
        SET idPromotion = p_newIdPromotion
        WHERE idLivre = p_idLivre;

        SET p_message = CONCAT('Promotion existante de ', in_reductionPromotion, '% mise à jour et appliquée au livre ', in_nomLivre);
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = p_message;
    ELSE
        INSERT INTO promotion (idPromotion, nomPromotion, dateDebutPromotion, dateFinPromotion, reductionPromotion)
        VALUES (NULL, CONCAT(in_reductionPromotion, '%'), CURDATE(), in_dateFinPromotion, in_reductionPromotion);

        SELECT LAST_INSERT_ID() INTO p_newIdPromotion;

        UPDATE livre
        SET idPromotion = p_newIdPromotion
        WHERE idLivre = p_idLivre;

        SET p_message = CONCAT('Nouvelle promotion de ', in_reductionPromotion, '% créée et appliquée au livre ', in_nomLivre);
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = p_message;
    END IF;
END $$
DELIMITER ;
