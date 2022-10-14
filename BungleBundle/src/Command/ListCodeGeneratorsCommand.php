<?php
declare(strict_types=1);

namespace Bungle\FrameworkBundle\Command;

use Bungle\Framework\Ent\Code\CodeGenerator;
use Bungle\Framework\Ent\Code\GeneratorInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand('bungle:code-generator')]
class ListCodeGeneratorsCommand extends Command
{
    /** @var GeneratorInterface[]  */
    private array $codeGenerators;

    public function __construct(CodeGenerator $codeGenerator)
    {
        parent::__construct();

        $this->codeGenerators = $codeGenerator->getGenerators();
    }

    protected function configure(): void
    {
        $this->setDescription('List Code Generators');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $items = array_map(fn (GeneratorInterface $o) => get_class($o), $this->codeGenerators);
        $io->listing($items);

        return 0;
    }
}
