<?php
use PHPUnit\Framework\TestCase;
use PDO;

class AdminTest extends TestCase
{
    private $pdo;

    // Configurer la base de données en mémoire pour les tests
    protected function setUp(): void
    {
        // Utiliser une base de données SQLite en mémoire pour les tests
        $this->pdo = new PDO('sqlite::memory:');
        
        // Création des tables selon la structure que vous avez fournie
        $this->pdo->exec(file_get_contents(__DIR__ . '/../database/schema.sql'));
    }

    // Test pour ajouter un film
    public function testAddFilm()
    {
        // Données du film
        $titre = 'Film Test';
        $genre = 'Action';
        $duree = 120;
        $date_sortie = '2024-12-01';
        $description = 'Description du film test';
        $affiche_url = 'http://example.com/affiche.jpg';

        // Insertion dans la table films
        $stmt = $this->pdo->prepare("INSERT INTO films (titre, genre, duree, date_sortie, description, affiche_url) 
                                    VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$titre, $genre, $duree, $date_sortie, $description, $affiche_url]);

        // Vérification que le film a bien été inséré
        $stmt = $this->pdo->query("SELECT * FROM films WHERE titre = '$titre'");
        $film = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->assertNotEmpty($film);
        $this->assertEquals($titre, $film['titre']);
        $this->assertEquals($genre, $film['genre']);
    }

    // Test pour ajouter une séance
    public function testAddSeance()
    {
        // Créer un film
        $stmt = $this->pdo->prepare("INSERT INTO films (titre, genre, duree, date_sortie, description, affiche_url) 
                                    VALUES ('Film Test', 'Action', 120, '2024-12-01', 'Description du film test', 'http://example.com/affiche.jpg')");
        $stmt->execute();

        // Créer une salle
        $stmt = $this->pdo->prepare("INSERT INTO salles (nom, capacite) VALUES ('Salle 1', 100)");
        $stmt->execute();
        
        // Insertion d'une séance
        $film_id = $this->pdo->lastInsertId();
        $salle_id = $this->pdo->lastInsertId();
        $date_seance = '2024-12-02';
        $heure_debut = '18:00:00';
        $heure_fin = '20:00:00';

        $stmt = $this->pdo->prepare("INSERT INTO seances (film_id, salle_id, date_seance, heure_debut, heure_fin) 
                                    VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$film_id, $salle_id, $date_seance, $heure_debut, $heure_fin]);

        // Vérifier que la séance a été correctement ajoutée
        $stmt = $this->pdo->query("SELECT * FROM seances WHERE film_id = '$film_id' AND salle_id = '$salle_id'");
        $seance = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->assertNotEmpty($seance);
        $this->assertEquals($film_id, $seance['film_id']);
        $this->assertEquals($salle_id, $seance['salle_id']);
    }

    // Test pour ajouter un client
    public function testAddClient()
    {
        // Insertion d'un client
        $client_id = 1;
        $nom_complet = 'John Doe';
        $email = 'john@example.com';
        $numero_telephone = '123456789';
        $mot_de_passe_hash = password_hash('password123', PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("INSERT INTO clients (client_id, nom_complet, email, numero_telephone, mot_de_passe_hash) 
                                    VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$client_id, $nom_complet, $email, $numero_telephone, $mot_de_passe_hash]);

        // Vérification que le client a bien été inséré
        $stmt = $this->pdo->query("SELECT * FROM clients WHERE email = '$email'");
        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotEmpty($client);
        $this->assertEquals($nom_complet, $client['nom_complet']);
        $this->assertEquals($email, $client['email']);
    }

    // Test pour ajouter une réservation
    public function testAddReservation()
    {
        // Créer un film et une salle
        $stmt = $this->pdo->prepare("INSERT INTO films (titre, genre, duree, date_sortie, description, affiche_url) 
                                    VALUES ('Film Test', 'Action', 120, '2024-12-01', 'Description du film test', 'http://example.com/affiche.jpg')");
        $stmt->execute();
        
        $stmt = $this->pdo->prepare("INSERT INTO salles (nom, capacite) VALUES ('Salle 1', 100)");
        $stmt->execute();

        // Créer un client
        $stmt = $this->pdo->prepare("INSERT INTO clients (nom_complet, email, mot_de_passe_hash) 
                                    VALUES ('John Doe', 'john@example.com', ?)");
        $stmt->execute([password_hash('password123', PASSWORD_DEFAULT)]);

        // Créer une séance
        $film_id = $this->pdo->lastInsertId();
        $salle_id = $this->pdo->lastInsertId();
        $date_seance = '2024-12-02';
        $heure_debut = '18:00:00';
        $heure_fin = '20:00:00';

        $stmt = $this->pdo->prepare("INSERT INTO seances (film_id, salle_id, date_seance, heure_debut, heure_fin) 
                                    VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$film_id, $salle_id, $date_seance, $heure_debut, $heure_fin]);

        // Créer une réservation
        $client_id = $this->pdo->lastInsertId();
        $places_reservees = 3;

        $stmt = $this->pdo->prepare("INSERT INTO reservations (client_id, seance_id, places_reservees) 
                                    VALUES (?, ?, ?)");
        $stmt->execute([$client_id, $film_id, $places_reservees]);

        // Vérifier que la réservation a été ajoutée
        $stmt = $this->pdo->query("SELECT * FROM reservations WHERE client_id = '$client_id' AND seance_id = '$film_id'");
        $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotEmpty($reservation);
        $this->assertEquals($client_id, $reservation['client_id']);
        $this->assertEquals($places_reservees, $reservation['places_reservees']);
    }
}
