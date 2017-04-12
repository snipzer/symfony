<?php

namespace BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Nelmio\Alice\Fixtures;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class LoadData
 * @package BlogBundle\DataFixtures\ORM
 */
class LoadData extends Controller implements FixtureInterface
{
    /**
     * @var string
     */
    private $directory;

    /**
     * @var Generator
     */
    private $faker;

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->directory = realpath(__DIR__ . '/../../Resources/fixtures');

        $this->faker = Factory::create('fr');

            Fixtures::load([
            $this->directory.'/fixtures.yml',
        ], $manager, [
            'providers' => [$this]
        ]);
    }

    public function customTitle($words = 6)
    {
        return rtrim($this->faker->sentence($words), '.');
    }

    public function customPassword($words = 6)
    {
        $hash = password_hash("12345", PASSWORD_BCRYPT, ['cost' => 12]);

        return $hash;
    }

    public function customContent()
    {
        $paragraphs = [];

        for ($i = rand(4,7); $i >= 0; $i--) {
            $paragraphs[] = $this->faker->text(rand(100, 300));
        }

        return '<p>' . implode('</p><p>', $paragraphs) . '</p>';
    }

    public function customMessage()
    {
        $sentences = [];

        for ($i = rand(1,2); $i >= 0; $i--) {
            $sentences[] = $this->faker->sentences(rand(1, 3), true);
        }

        return implode("\n\n", $sentences);
    }

    public function setRoles()
    {
        $roles = ["ROLE_USER"];

        $bool = rand(0, 100);

        if($bool <= 10)
        {
            $roles = ["ROLE_ADMIN", "ROLE_USER"];
        }

        return $roles;
    }
}
