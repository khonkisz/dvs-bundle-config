<?php

namespace Dvs\ConfigBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Dvs\ConfigBundle\Entity\Setting;

class ConfigService
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var array
     */
    private $config;

    /**
     * @param EntityRepository $settingsRepository
     * @param EntityManager $em
     */
    public function __construct(EntityRepository $settingsRepository, EntityManager $em)
    {
        $settings = $settingsRepository->findAll();
        $this->config = $this->parseConfig($settings);

        $this->em = $em;
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
            $newSetting = new Setting($name, $default);
            $newSetting->setDefaultValue($default);

            $this->save($newSetting);

            return $default;
        }
    }

    /**
     * @param Setting $setting
     *
     * @return bool
     */
    public function set(Setting $setting)
    {
        if (isset($this->config[$setting->getName()])) {
            return false;
        } else {
            $this->save($setting);
            return true;
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

    /**
     * @param Setting $newSetting
     */
    protected function save(Setting $newSetting)
    {
        $this->em->persist($newSetting);
        $this->em->flush($newSetting);
    }
}
