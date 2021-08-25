<?php

    class SlotMachine {


        private array $values = ["A", "B", "C", "D"];
        private int $cash = 100;

        public array $slots = [
            [" ", " ", " "],
            [" ", " ", " "],
            [" ", " ", " "]
        ];

        private array $betCoefficient = [
            10 => 1,
            20 => 2,
            40 => 3,
            80 => 4
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
                    $this->slots[$i][$j] = rand(0, count($this->values)-1);
                }
            }
        }

        function winningConditions(int $bet){
            $hasWon = 0;
            for($i = 0; $i <= 2; $i++){
                if($this->slots[$i][0] === $this->slots[$i][1] && $this->slots[$i][1] === $this->slots[$i][2]){
                    $this->cash += $this->valueCoefficient[$this->slots[$i][0]] * $this->betCoefficient[$bet];
                    $hasWon++;
                }
            }
            if($this->slots[0][0] === $this->slots[1][1] && $this->slots[1][1] === $this->slots[2][2]){
                $this->cash += $this->valueCoefficient[$this->slots[0][0]] * $this->betCoefficient[$bet];
                $hasWon++;
            }
            if($this->slots[2][0] === $this->slots[1][1] && $this->slots[1][1] === $this->slots[0][2]){
                $this->cash += $this->valueCoefficient[$this->slots[2][0]] * $this->betCoefficient[$bet];
                $hasWon++;
            }
            if($hasWon === 0){
                $this->cash = $this->cash - $bet;
            }
        }

    }

    $newGame = new SlotMachine();

    while(true){
        echo "Cash: {$newGame->getCash()}\n";
        $bet = (int) readline("Choose your bet (10, 20, 40, 80): ");

        $isPromptActive = true;
        while($isPromptActive){
            $bet === 10 || $bet === 20 || $bet === 40 || $bet === 80 || $bet > $newGame->getCash() ?
                $isPromptActive = false :
                $bet = (int) readline("Try again: ");
        }

        if($newGame->getCash() < 10){
            die("Out of Money. Bye!");
        }

        $newGame->spinSlotMachine();
        $newGame->displayBoard();
        $newGame->winningConditions($bet);

        $prompt = readline("Do you want to play again? (Y/N) ");
        $promptActive = true;
        while($promptActive){
            strtoupper($prompt) === "Y" ? $promptActive = false : (strtoupper($prompt) === "N" ? die("Bye") : $prompt = readline("Try again: "));
        }
    }


