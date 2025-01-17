VIEWS :

create or replace view vTotalLivre AS
SELECT li.idCommande, c.idUser, l.nomLivre, l.prixLivre, li.quantiteLigneCommande,
(l.prixLivre * li.quantiteLigneCommande) AS totalLivre
FROM livre l
inner JOIN ligneCommande li
ON l.idLivre = li.idLivre
inner join commande c
on c.idCommande=li.idCommande;


create or replace view vTotalCommande as
select c.idUser, sum(l.prixLivre * li.quantiteLigneCommande) as totalCommande
from commande c
inner join ligneCommande li
on c.idCommande=li.idCommande
inner join livre l
on li.idLivre=l.idLivre
group by c.idUser;


create or replace view vNomMinLivre as
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
select quantiteLigneCommande
into existingQuantity
from ligneCommande
where idLivre = NEW.idLivre
LIMIT 1;
if existingQuantity is not null then
    SIGNAL SQLSTATE "45000"
    set MESSAGE_TEXT = "Erreur : Livre déjà dans le panier. Veuillez modifier le nombre d'exemplaire.";
end if;
end $$
delimiter ;


delimiter $$
create trigger tExemplaireLivreCommande
before insert on commande
for each row
begin
    declare compteurCommande int;
    declare compteurLigneCommande int;

    select count(idCommande)
    into compteurCommande
    from commande
    where idCommande = NEW.idCommande;

    select count(idCommande)
    into compteurLigneCommande
    from ligneCommande
    where idCommande = NEW.idCommande;

    if compteurCommande >= compteurLigneCommande then
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = "Erreur : Livre déjà dans le panier. Veuillez modifier le nombre d'exemplaire.";
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
end;
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


delimiter $$
create trigger tInsertNotification
after insert on commande
for each row
begin
insert into notification
values (null, CONCAT('Nouvelle commande. ID : ', NEW.idCommande), curdate());
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


(PAS FONCTIONNELLE)
delimiter $$
create procedure pVerifierDisponibiliteLivre(
in idLivre int,
                            in quantiteDemandee int,
                            out disponible boolean
                        )
                        begin
                            declare stock int;
                            select exemplaireLivre
                            into stock
                            from livre
                            WHERE idLivre = idLivre;
                            if stock >= quantiteDemandee then
                                set disponible = TRUE;
                            else
                                set disponible = FALSE;
                            end if;
                        end $$
                        delimiter ;