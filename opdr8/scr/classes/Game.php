<?php
    namespace Opdr8\classes;

    use Opdr8\classes\Dice;

    class Game {
        private array $dice; // Array om de dobbelstenen bij te houden
        private int $numberDice = 2; // Aantal dobbelstenen standaard ingesteld op 2
        private int $numberSides = 6; // Aantal zijden van elke dobbelsteen standaard ingesteld op 6
        private bool $isActive = false; // Geeft aan of het spel actief is of niet
        private int $score = 0; // Score van de speler
        private string $username = ""; // Gebruikersnaam van de speler
        private int $total = 0; // Totaal aantal punten behaald in een worp
        private int $lastTotal = 0; // Totaal aantal punten behaald in de vorige worp
        private string $diceDisplay = ""; // HTML-weergave van de geworpen dobbelstenen

        // Start het spel
        public function start() {
            $this->isActive = true; // Zet het spel op actief
            $this->dice = Array(); // Initialiseer de dobbelstenen array
            Dice::setNumberSides($this->numberSides); // Stel het aantal zijden van de dobbelstenen in
            for ($i=0; $i < $this->numberDice; $i++) { 
                $this->dice[] = new Dice(); // Voeg nieuwe dobbelsteenobjecten toe aan de array
            }
        }

        // Beëindig het spel
        public function end() {
            $this->updateHighscore(); // Werk de highscore bij
            $this->isActive = false; // Zet het spel op niet-actief
            $this->score = 0; // Reset de score van de speler
            $this->lastTotal = 0; // Reset het totaal van de vorige worp
        }

        // Werp de dobbelstenen
        public function rollDice() {
            Dice::setNumberSides($this->numberSides); // Stel het aantal zijden van de dobbelstenen in
            $this->lastTotal = $this->total; // Bewaar het totaal van de vorige worp
            $this->total = 0; // Reset het totaal van de huidige worp
            $this->diceDisplay = ""; // Reset de HTML-weergave van de dobbelstenen
            foreach ($this->dice as $die) {
                $die->rollDice(); // Werp de dobbelsteen
                $this->total += $die->getFaceValue(); // Tel de waarde van de dobbelsteen op bij het totaal
                $this->diceDisplay .= Dice::getFaceSVG($die->getFaceValue()); // Voeg de SVG-weergave van de dobbelsteen toe aan de weergave
            }
        }

        // Stel het aantal zijden van de dobbelstenen in
        public function setNumberSides(int $pNumberSides) {
            $this->numberSides = $pNumberSides;
        }

        // Stel het aantal dobbelstenen in
        public function setNumberDice(int $pNumberDice) {
            $this->numberDice = $pNumberDice;
        }

        // Stel de gebruikersnaam in
        public function setUsername(string $pUsername) {
            $this->username = $pUsername;
        }

        // Werk de highscore bij
        public function updateHighscore() {
            $scoresData = [];
            if (isset($_COOKIE['DiceScores'])) {
                $scoresData = unserialize($_COOKIE['DiceScores']);
            }
        
            $scoresData[] = [$this->username, $this->score];
            usort($scoresData, function($a, $b) {
                return $b[1] - $a[1];
            });
        
            $scoresData = array_slice($scoresData, 0, 10); // Behoud de top 10 scores
        
            setcookie('DiceScores', serialize($scoresData), time() + (86400 * 30), "/"); // Bewaar gedurende 30 dagen
            $_COOKIE['DiceScores'] = serialize($scoresData);
        }

        // Verhoog de score van de speler
        public function addScore() {
            $this->score++;
        }

        // Retourneer het aantal zijden van de dobbelstenen
        public function getNumberSides() {
            return $this->numberSides;
        }

        // Retourneer het aantal dobbelstenen
        public function getNumberDice() {
            return $this->numberDice;
        }

        // Controleer of het spel actief is
        public function isActive() {
            return $this->isActive;
        }

        // Retourneer de gebruikersnaam
        public function getUsername() {
            return $this->username;
        }

        // Retourneer het totaal aantal punten
        public function getTotal() {
            return $this->total;
        }

        // Retourneer het totaal aantal punten van de vorige worp
        public function getlastTotal() {
            return $this->lastTotal;
        }

        // Retourneer de score van de speler
        public function getScore() {
            return $this->score;
        }
        
        // Retourneer de HTML-weergave van de geworpen dobbelstenen
        public function getDiceDisplay() {
            return $this->diceDisplay;
        }

        // Toon de highscores in een tabel
        public static function showHighscoresTable() {
            $scores = '<h2>Highscores:</h2><table border="1"><thead><tr><th>Gebruikersnaam</th><th>Score</th></tr></thead><tbody>';
        
            if (isset($_COOKIE['DiceScores']) && $_COOKIE['DiceScores'] != "") {
                $scoresData = unserialize($_COOKIE['DiceScores']);
                foreach ($scoresData as $score) {
                    $username = htmlspecialchars($score[0]);
                    $scoreValue = htmlspecialchars($score[1]);
                    $scores .= "<tr><td>$username</td><td>$scoreValue</td></tr>";
                }
            }
            $scores .= '</tbody></table><br>';
            echo $scores;
        }
        
    }
?>
