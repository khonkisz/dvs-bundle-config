<?php

namespace spec\Dvs\Core\ConfigBundle\Service;

use Doctrine\ORM\EntityRepository;
use Dvs\Core\ConfigBundle\Entity\Setting;
use Dvs\Core\ConfigBundle\Service\ConfigService;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Doctrine\ORM\EntityManager;

class ConfigServiceSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(ConfigService::class);
    }

    public function let(EntityManager $em, EntityRepository $entityRepository)
    {
        $em->getRepository(Setting::class)->willReturn($entityRepository);
        $entityRepository->findAll()->willReturn([
            new Setting('key1', 'value1'),
            new Setting('key2', 'value2'),
        ]);

        $this->beConstructedWith($em);
    }

    public function it_should_return_default_if_config_not_exists()
    {
        $defaultValue = 'defaultValue';
        $this->get('badKey', $defaultValue)->shouldReturn($defaultValue);
    }

    public function it_should_return_correct_config_value()
    {
        $this->get('key1', 'default')->shouldReturn('value1');
        $this->get('key2', 'default')->shouldReturn('value2');
    }
}
