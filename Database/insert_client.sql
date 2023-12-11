USE rosemarketplace ;

INSERT INTO client (type_client, nom_client, prenom_client, email_client, numtel_client, datedenaissance_client, password_client, id_adresse) VALUES (0, 'mahmoudi', 'farrah', 'cfm@f.com', '0143036763', '21/04/1999', 'root', (SELECT max(id_adresse) FROM adresse)+1);
INSERT INTO adresse (id_adresse, numetrue_adresse, codepostal_adresse, villeadresse_adresse, id_client) VALUES ((SELECT max(id_adresse) FROM client), '31 allee', '75003', 'paris', LAST_INSERT_ID());
UPDATE client
SET id_adresse = LAST_INSERT_ID()
WHERE id_client = (SELECT max(id_client) FROM client)