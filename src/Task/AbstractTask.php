<?php
/**
 * @copyright Copyright (c) 2017 Matthias Walter
 *
 * @see LICENSE
 */

namespace Mwltr\Robo\Magento2\Task;

use Robo\Common\ExecOneCommand;
use Robo\Task\BaseTask;

/**
 * AbstractTask
 */
abstract class AbstractTask extends BaseTask
{
    use ExecOneCommand;

    protected $taskInfo = '';

    protected $command;

    protected $action = null;

    protected $ansi = null;

    public function __construct($magentoBin = 'bin/magento')
    {
        $configReader = new \Mwltr\MageDeploy2\Config\ConfigReader();
        $config = $configReader->read(true);

        // Magento commands need to be executed with the configured php_bin, not the one used for running robo.
        $phpbin = $config->get('env/php_bin');
        if (empty($phpbin)) {
            $phpbin = PHP_BINARY;
        }

        $this->command = "$phpbin $magentoBin";
    }

    /**
     * {@inheritdoc}
     */
    public function getCommand()
    {
        if (!isset($this->ansi) && $this->getConfig()->isDecorated()) {
            $this->ansi();
        }
        $this->option($this->ansi);

        return "{$this->command} {$this->action}{$this->arguments}";
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $command = $this->getCommand();

        $this->printTaskInfo("$this->taskInfo : {command}", ['command' => $command]);

        return $this->executeCommand($command);
    }

    /**
     * adds `ansi` option to composer
     *
     * @return $this
     */
    public function ansi()
    {
        $this->ansi = '--ansi';

        return $this;
    }

}
