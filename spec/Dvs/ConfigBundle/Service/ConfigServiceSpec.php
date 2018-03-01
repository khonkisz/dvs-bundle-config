<?php

namespace spec\Dvs\ConfigBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Dvs\ConfigBundle\Entity\Setting;
use Dvs\ConfigBundle\Service\ConfigService;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConfigServiceSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(ConfigService::class);
    }

    public function let(EntityRepository $entityRepository, EntityManager $entityManager)
    {
        $entityRepository->findAll()->willReturn([
            new Setting('key1', 'value1'),
            new Setting('key2', 'value2'),
        ]);

        $this->beConstructedWith($entityRepository, $entityManager);
    }

    public function it_should_return_default_setting_value_if_not_exists()
    {
        $defaultValue = 'defaultValue';
        $this->get('badKey', $defaultValue)->shouldReturn($defaultValue);
    }

    public function it_should_create_setting_if_not_exists(EntityManager $entityManager)
    {
        $defaultValue = 'defaultValue';
        $badKey = 'badKey';
        $this->get($badKey, $defaultValue)->shouldReturn($defaultValue);
        $setting = new Setting($badKey, $defaultValue);
        $setting->setDefaultValue($defaultValue);

        $entityManager->persist($setting)->shouldHaveBeenCalled();
        $entityManager->flush($setting)->shouldHaveBeenCalled();
    }

    public function it_should_return_correct_setting_value()
    {
        $this->get('key1', 'default')->shouldReturn('value1');
        $this->get('key2', 'default')->shouldReturn('value2');
    }

    public function it_should_update_setting_if_already_exist(EntityManager $entityManager)
    {
        $this->set('key1', 'example');
        $this->get('key1')->shouldReturn('example');

        $entityManager->flush()->shouldHaveBeenCalled();
    }

    public function it_should_set_new_setting(EntityManager $entityManager)
    {
        $setting = new Setting('new', $value = 'example');
        $setting->setDefaultValue($value);

        $this->set('new', 'example');
        $this->get('new')->shouldReturn('example');

        $entityManager->persist($setting)->shouldHaveBeenCalled();
        $entityManager->flush($setting)->shouldHaveBeenCalled();
    }
}
