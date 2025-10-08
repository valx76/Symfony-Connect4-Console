<?php

namespace App\Command;

use App\Entity\Game;
use App\Entity\Grid;
use App\Entity\PlayerValue;
use App\Event\OnGameFull;
use App\Event\OnPlayerDropCoin;
use App\Event\OnPlayerWin;
use App\Event\OnSwitchPlayer;
use App\Service\GameState;
use App\Service\GridFormatter;
use App\Validator\GridColumnNotFull;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(name: 'app:game', description: 'Play a game of Connect Four')]
final readonly class GameCommand
{
    public const int COLUMNS_COUNT = 7;
    public const int ROWS_COUNT = 6;
    public const int WINNING_COINS_COUNT = 4;

    public function __construct(
        private GameState $gameState,
        private GridFormatter $gridFormatter,
        private EventDispatcherInterface $eventDispatcher,
        private ValidatorInterface $validator,
    ) {
    }

    public function __invoke(InputInterface $input, OutputInterface $output): int
    {
        $grid = new Grid(self::COLUMNS_COUNT, self::ROWS_COUNT);
        $game = new Game($grid, self::WINNING_COINS_COUNT, PlayerValue::RED);
        $this->gameState->game = $game;

        $table = new Table($output);
        $table->setHeaders(range(0, self::COLUMNS_COUNT - 1));

        $redPlayerStyle = new OutputFormatterStyle('#000', '#FF0000');
        $output->getFormatter()->setStyle('red', $redPlayerStyle);
        $redTag = '<red> </red>';

        $yellowPlayerStyle = new OutputFormatterStyle('#000', '#FFFF00');
        $output->getFormatter()->setStyle('yellow', $yellowPlayerStyle);
        $yellowTag = '<yellow> </yellow>';

        $columnIndexValidator = Validation::createCallable(
            $this->validator,
            new NotBlank(),
            new Range(min: 0, max: self::COLUMNS_COUNT - 1),
            new GridColumnNotFull(),
        );

        $io = new SymfonyStyle($input, $output);

        while (!$game->isFinished) {
            $this->render($output, $table, $game, $redTag, $yellowTag);

            /** @var int $columnIndex */
            $columnIndex = $io->ask(
                sprintf('Player %s, please choose a column to play: ', $game->currentPlayer->name),
                validator: $columnIndexValidator,
            );

            $this->eventDispatcher->dispatch(new OnPlayerDropCoin($game->currentPlayer, $columnIndex));

            if ($this->gameState->isWinningLastMove($game->currentPlayer, $columnIndex)) {
                $this->eventDispatcher->dispatch(new OnPlayerWin());
            } elseif ($this->gameState->game->grid->isFull()) {
                $this->eventDispatcher->dispatch(new OnGameFull());
            }

            $this->eventDispatcher->dispatch(new OnSwitchPlayer());
        }

        $this->render($output, $table, $game, $redTag, $yellowTag);

        if (null !== $game->winner) {
            $output->writeln(sprintf('Winner is %s!', $game->winner->name));
        } else {
            $output->writeln('It\'s a draw!');
        }

        return Command::SUCCESS;
    }

    private function render(OutputInterface $output, Table $table, Game $game, string $redTag, string $yellowTag): void
    {
        // Clear screen
        $output->write("\033\143");

        $table->setRows(
            $this->gridFormatter->format($game->grid, $redTag, $yellowTag)
        );
        $table->render();

        $output->writeln('');
    }
}
