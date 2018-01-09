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
        $this->config = $this->getParsedConfig($settings);

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
        if (isset($this->config[$name])) {
            return $this->config[$name]->getCurrentValue();
        } else {
            $this->add(new Setting($name, $default));

            return $default;
        }
    }

    /**
     * @param Setting $setting
     *
     * @return bool
     */
    public function add(Setting $setting)
    {
        if (isset($this->config[$setting->getName()])) {
            return false;
        } else {
            $setting->setDefaultValue($setting->getCurrentValue());

            $this->save($setting);

            return true;
        }
    }

    /**
     * @param string $name
     * @param $value
     */
    public function set(string $name, $value){
        if (isset($this->config[$name])) {
            $this->config[$name]->setCurrentValue($value);
            $this->em->flush();
        } else {
            $this->add(new Setting($name, $value));
        }
    }

    /**
     * @param array|\Dvs\ConfigBundle\Entity\Setting[] $settings
     *
     * @return array
     */
    private function getParsedConfig($settings)
    {
        $parsedConfig = [];
        foreach ($settings as $setting) {
            $parsedConfig[$setting->getName()] = $setting;
        }

        return $parsedConfig;
    }

    /**
     * @param Setting $newSetting
     */
    protected function save(Setting $newSetting)
    {
        $this->config[$newSetting->getName()] = $newSetting;

        $this->em->persist($newSetting);
        $this->em->flush($newSetting);
    }
}
