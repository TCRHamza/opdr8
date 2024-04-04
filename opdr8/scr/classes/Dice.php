<?php
    namespace Opdr8\classes;

    class Dice {
        private static int $numberSides = 6; // Aantal zijden van de dobbelsteen (standaard ingesteld op 6)
        private static array $dotPositions = [ // Array die de posities van de stippen op elke zijde van de dobbelsteen bevat
            [],
            [[75, 75]],
            [[50, 50], [100, 100]],
            [[50, 50], [75, 75], [100, 100]],
            // De posities van de stippen voor elke mogelijke waarde van 1 t/m 20
        ];

        private int $faceValue = 0; // Waarde van de bovenste zijde van de dobbelsteen

        // Methode om de dobbelsteen te werpen en een willekeurige waarde toe te kennen
        public function rollDice() {
            $this->faceValue = random_int(1, self::$numberSides);
        }

        // Methode om de waarde van de bovenste zijde van de dobbelsteen op te halen
        public function getFaceValue() {
            return $this->faceValue;
        }

        // Stel het aantal zijden van de dobbelsteen in
        public static function setNumberSides(int $pNumberSides) {
            self::$numberSides = $pNumberSides;
        }

        // Haal het aantal zijden van de dobbelsteen op
        public static function getNumberSides() {
            return self::$numberSides;
        }

        // Methode om een SVG-afbeelding van een specifieke zijde van de dobbelsteen op te halen
        public static function getFaceSVG(int $pNumber, int $pSize = 100) {
            $returnString = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $pSize . '" height="' . $pSize . '" style="border: 1px solid black;" viewBox="0 0 150 150">';

            foreach (self::$dotPositions[$pNumber] as $position) {
                $x = $position[0];
                $y = $position[1];
                $returnString .= '<circle cx="'.$x.'" cy="'.$y.'" r="7.5" fill="black"/>';
            }

            $returnString .= '</svg>';

            return $returnString;
        }
    }
?>
