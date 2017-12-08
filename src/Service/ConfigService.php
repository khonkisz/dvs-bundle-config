<?php

namespace Dvs\Core\ConfigBundle\Service;

use Doctrine\ORM\EntityManager;
use Dvs\Core\ConfigBundle\Entity\Setting;

class ConfigService
{
    /**
     * @var array
     */
    private $config;

    /**
     * Config constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $settingsRepository = $em->getRepository(Setting::class);
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
     * @param array|\Dvs\Core\ConfigBundle\Entity\Setting[] $settings
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
