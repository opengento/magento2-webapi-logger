<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

declare(strict_types=1);

namespace Opengento\WebapiLogger\Console\Command;

use Magento\Framework\Console\Cli;
use Magento\Framework\Exception\LocalizedException;
use Opengento\WebapiLogger\Model\Clean;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanCommand extends Command
{
    public function __construct(private Clean $clean)
    {
        parent::__construct('webapilogs:clean');
    }

    protected function configure(): void
    {
        $this->setDescription('WebapiLogger Cleaner');
        parent::configure();
    }

    /**
     * @throws LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->clean->clean();

        return Cli::RETURN_SUCCESS;
    }
}
