<?php

namespace Dvs\ConfigBundle\Service;

use Doctrine\ORM\EntityRepository;

class ConfigService
{
    /**
     * @var array
     */
    private $config;

    /**
     * Config constructor.
     *
     * @param EntityRepository $settingsRepository
     */
    public function __construct(EntityRepository $settingsRepository)
    {
        $settings = $settingsRepository->findAll();

        $this->config = $this->parseConfig($settings);
    }

    /**
     * @param string     $name
     * @param mixed|null $default
     *
     * @return mixed|null
     */
    public function get(string $name, $default = null)
    {
        if (isset($this->config[$name]) && $this->config[$name] != null) {
            return $this->config[$name];
        } else {
            return $default;
        }
    }

    /**
     * @param array|\Dvs\ConfigBundle\Entity\Setting[] $settings
     *
     * @return array
     */
    private function parseConfig($settings)
    {
        $parsedConfig = [];
        foreach ($settings as $setting) {
            $parsedConfig[$setting->getName()] = $setting->getCurrentValue();
        }

        return $parsedConfig;
    }
}
