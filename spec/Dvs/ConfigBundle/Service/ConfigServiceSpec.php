<?php

namespace spec\Dvs\ConfigBundle\Service;

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

    public function let(EntityRepository $entityRepository)
    {
        $entityRepository->findAll()->willReturn([
            new Setting('key1', 'value1'),
            new Setting('key2', 'value2'),
        ]);

        $this->beConstructedWith($entityRepository);
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
