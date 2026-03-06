# Movie Wishlist

Een **PHP webapplicatie** om een persoonlijke lijst van films en series bij te houden.
Gebruikers kunnen bijhouden wat ze al hebben gekeken, items toevoegen aan hun watchlist, beoordelingen geven en zoeken door hun collectie.

## Functionaliteiten

* **Gebruikerssysteem** — registreren, inloggen en uitloggen
* **Twee secties** — *Watched* en *Watchlist*
* **Volledige CRUD** — films en series toevoegen, bekijken, aanpassen en verwijderen
* **Beoordelingen** — geef bekeken items een score van **1–10**
* **Afbeeldingen uploaden** — voeg een poster toe aan een film of serie
* **Zoekfunctie** — zoek door je collectie
* **Categorieën** — organiseer items op genre of categorie

---

## Gebruikte technologieën

* **Backend:** PHP (Object-Oriented Programming)
* **Database:** MySQL
* **Frontend:** HTML, CSS
* **Server:** XAMPP (Apache + MySQL)

---

## Projectstructuur

```
movie-wishlist/
├── index.php          # Hoofdpagina (overzicht van Watched + Watchlist)
├── add_movie.php      # Nieuwe film of serie toevoegen
├── update.php         # Item aanpassen
├── delete.php         # Item verwijderen
├── search.php         # Zoekfunctionaliteit
│
├── login.php          # Inlogpagina
├── register.php       # Registratiepagina
├── logout.php         # Uitloggen
│
├── connection.php     # Database connectie
├── database.php       # PDO database class
├── styles.css         # CSS stylesheet
│
├── classes/
│   ├── Media.php      # Media model (CRUD operaties)
│   └── User.php       # User model
│
└── uploads/           # Geüploade poster afbeeldingen
```

---

## Vereisten

* **PHP 7.4 of hoger**
* **MySQL**
* **XAMPP** (of een andere Apache + PHP + MySQL omgeving)

---

## Installatie

1. Clone de repository naar je **XAMPP `htdocs` map**:

```bash
git clone https://github.com/jouw-gebruikersnaam/movie-wishlist.git
```

2. Start **Apache** en **MySQL** in XAMPP.

3. Maak een database aan in **phpMyAdmin**:

```
http://localhost/phpmyadmin
```

Database naam:

```sql
wishlist
```

4. Maak de tabellen aan:

```sql
CREATE TABLE account (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    category VARCHAR(100),
    rating DECIMAL(3,1),
    image VARCHAR(255),
    section ENUM('watched','watchlist') NOT NULL
);
```

5. Controleer de database instellingen in:

```
connection.php
```

```php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "wishlist";
```

6. Open de applicatie in je browser:

```
http://localhost/movie-wishlist/login.php
```

---

## Gebruik

1. Maak een account aan of log in.
2. Voeg films of series toe aan **Watched** of **Watchlist**.
3. Upload optioneel een poster afbeelding.
4. Geef bekeken items een **rating van 1–10**.
5. Gebruik de **zoekfunctie** om snel items te vinden.

---

## Beveiliging

* Wachtwoorden worden opgeslagen met **PHP `password_hash()`**
* Afbeeldingen worden opgeslagen in de map **uploads/**
* Ondersteunde afbeeldingsformaten: **JPG, JPEG, PNG, GIF**
