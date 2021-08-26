<?php

    class SlotMachine
    {

        private array $values = [
            "A" => 5,
            "B" => 10,
            "C" => 20,
            "D" => 40
        ];

        private int $cash = 100;
        private int $won = 0;

        private int $rows = 3;
        private int $columns = 4;

        private array $slots = [];

        private array $winConditions = [
            [[0, 0], [0, 1], [0, 2], [0, 3]],
            [[1, 0], [1, 1], [1, 2], [1, 3]],
            [[2, 0], [2, 1], [2, 2], [2, 3]],
            [[0, 0], [0, 1], [1, 2], [2, 3]],
            [[2, 0], [2, 1], [1, 2], [0, 3]],
            [[0, 0], [1, 1], [2, 2], [2, 3]],
            [[2, 0], [1, 1], [0, 2], [0, 3]]
        ];

        private array $betCoefficients = [
            1 => 10,
            2 => 20,
            3 => 40,
            4 => 80
        ];

        public function getCash(): int
        {
            return $this->cash;
        }

        public function getBetCoefficients(): array
        {
            return $this->betCoefficients;
        }

        public function createGrid(): void
        {
            $grid = [];
            for ($rows = 0; $rows < $this->rows; $rows++) {
                for ($columns = 0; $columns < $this->columns; $columns++) {
                    $grid[$rows][$columns] = "";
                }
            }
            $this->slots = $grid;
        }

        public function displayBoard(): string
        {
            $board = str_repeat(" ---", $this->columns) . PHP_EOL;
            for ($i = 0; $i < $this->rows; $i++) {
                for ($j = 0; $j < $this->columns; $j++) {
                    $board .= "| {$this->slots[$i][$j]} ";
                }
                $board .= "|\n" . str_repeat(" ---", $this->columns) . "\n";
            }
            return $board;
        }

        public function spinSlotMachine(): void
        {
            for ($i = 0; $i < count($this->slots); $i++) {
                for ($j = 0; $j < count($this->slots[$i]); $j++) {
                    $this->slots[$i][$j] = array_rand($this->values);
                }
            }
        }

        public function winningConditions(int $bet): void
        {
            $hasWon = 0;
            foreach ($this->winConditions as $condition) {
                $combo = [];
                foreach ($condition as $position) {
                    $combo[] = $this->slots[$position[0]][$position[1]];
                }

                if (count(array_unique($combo)) == 1) {
                    $hasWon += $this->values[$combo[0]] * array_search($bet, $this->betCoefficients);
                }
            }
            $this->won = $hasWon;
        }

        public function displayResult(int $bet): string
        {
            if ($this->won === 0) {
                $this->cash = $this->cash - $bet;
                return "You lost $$bet\n";
            } else {
                $this->cash = $this->cash + $this->won;
                return "You won $$this->won\n";
            }
        }

        public function exitProgram($input): void
        {
            if (strtoupper($input) === "Q") {
                die("Bye!");
            }
        }

    }

    $newGame = new SlotMachine();
    $newGame->createGrid();

    echo "Type q at any point within a program to exit!\n";
    echo "Cash: {$newGame->getCash()}\n";
    $listOfBets = implode(", ", $newGame->getBetCoefficients());

    while (true) {

        $bet = readline("Choose your bet ($listOfBets): ");

        $isPromptActive = true;
        while ($isPromptActive) {

            $newGame->exitProgram($bet);

            if (in_array((int)$bet, $newGame->getBetCoefficients()) && (int)$bet <= $newGame->getCash()) {
                $isPromptActive = false;
                continue;
            }

            $bet = readline("Try again: ");

        }

        $isGameActive = true;
        while ($isGameActive) {
            $newGame->spinSlotMachine();
            echo $newGame->displayBoard();
            $newGame->winningConditions($bet);
            echo $newGame->displayResult($bet);

            echo "Cash: \${$newGame->getCash()}\n";
            if ($newGame->getCash() < min($newGame->getBetCoefficients())) {
                die("Out of Money. Bye!");
            }

            $prompt = readline("Play again? (Y/N) ");
            $promptActive = true;
            while ($promptActive) {

                $newGame->exitProgram($prompt);

                strtoupper($prompt) === "Y" ? $promptActive = false :
                    (strtoupper($prompt) === "N" ? $isGameActive = $promptActive = false :
                        $prompt = readline("Try again: "));

                if ($bet > $newGame->getCash()) {
                    echo "Not enough cash to place a bet!\n";
                    $promptActive = $isGameActive = false;
                }
            }
        }

    }


