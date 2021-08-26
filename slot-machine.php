<?php

    class SlotMachine {


        private array $values = ["A", "B", "C", "D"];
        private int $cash = 100;

        private array $slots = [
            [" ", " ", " "],
            [" ", " ", " "],
            [" ", " ", " "]
        ];

        private array $betCoefficient = [
            1 => 10,
            2 => 20,
            3 => 40,
            4 => 80
        ];

        private array $valueCoefficient = [
            0 => 5,
            1 => 10,
            2 => 20,
            3 => 40
        ];

        function getCash(): int {
            return $this->cash;
        }

        function getBetCoefficient(): array {
            return $this->betCoefficient;
        }

        function displayBoard(){
            echo " {$this->slots[0][0]} | {$this->slots[0][1]} | {$this->slots[0][2]} \n";
            echo "-----------\n";
            echo " {$this->slots[1][0]} | {$this->slots[1][1]} | {$this->slots[1][2]} \n";
            echo "-----------\n";
            echo " {$this->slots[2][0]} | {$this->slots[2][1]} | {$this->slots[2][2]} \n";
        }

        function spinSlotMachine(): void {
            for($i = 0; $i < count($this->slots); $i++){
                for($j = 0; $j < count($this->slots[$i]); $j++){
                    $this->slots[$i][$j] = $this->values[rand(0, count($this->values)-1)];
                }
            }
        }

        function winningConditions(int $bet): string {
            $hasWon = 0;
            for($i = 0; $i <= 2; $i++){
                if($this->slots[$i][0] === $this->slots[$i][1] && $this->slots[$i][1] === $this->slots[$i][2]){
                    $hasWon += $this->valueCoefficient[array_search($this->slots[$i][0], $this->values)] * array_search($bet, $this->betCoefficient);
                }
            }
            if($this->slots[0][0] === $this->slots[1][1] && $this->slots[1][1] === $this->slots[2][2]){
                $hasWon += $this->valueCoefficient[array_search($this->slots[0][0], $this->values)] * array_search($bet, $this->betCoefficient);
            }
            if($this->slots[2][0] === $this->slots[1][1] && $this->slots[1][1] === $this->slots[0][2]){
                $hasWon += $this->valueCoefficient[array_search($this->slots[2][0], $this->values)] * array_search($bet, $this->betCoefficient);
            }
            if($hasWon > 0){
                $this->cash += $hasWon;
                return "You won $hasWon\n";
            }

            $this->cash = $this->cash - $bet;
            return "You lost $bet\n";
        }

    }

    $newGame = new SlotMachine();

    echo "Cash: {$newGame->getCash()}\n";
    $listOfBets = implode(", ", $newGame->getBetCoefficient());

    while(true){

        $bet = (int) readline("Choose your bet ($listOfBets): ");

        $isPromptActive = true;
        while($isPromptActive){

            foreach($newGame->getBetCoefficient() as $coefficient){
                if($coefficient === $bet && $bet <= $newGame->getCash()){
                    $isPromptActive = false;
                }
            }
            if($isPromptActive){
                $bet = (int) readline("Try again: ");
            }
        }

        $newGame->spinSlotMachine();
        $newGame->displayBoard();
        echo $newGame->winningConditions($bet);

        echo "Cash: {$newGame->getCash()}\n";
        if($newGame->getCash() < 10){
            die("Out of Money. Bye!");
        }

        $prompt = readline("Do you want to play again? (Y/N) ");
        $promptActive = true;
        while($promptActive){
            strtoupper($prompt) === "Y" ? $promptActive = false : (strtoupper($prompt) === "N" ? die("Bye") : $prompt = readline("Try again: "));
        }
    }


