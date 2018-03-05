<?php

namespace Backend\Modules\Profiles\Tests\Engine;

use Backend\Modules\Profiles\Engine\Model;
use Common\WebTestCase;

final class ModelTest extends WebTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        if (!defined('APPLICATION')) {
            define('APPLICATION', 'Backend');
        }

        $client = self::createClient();
        $this->loadFixtures($client);
    }

    public function testPasswordGetsEncrypted(): void
    {
        $encryptedPassword = Model::encryptPassword($this->getPassword());

        $this->assertTrue(password_verify($this->getPassword(), $encryptedPassword));
    }

    public function testInsertingProfile(): void
    {
        $profileId = $this->addProfile();

        $profileData = $this->getProfileData();
        $addedProfile = Model::get($profileId);

        $this->assertEquals($profileId, $addedProfile['id']);
        $this->assertEquals($profileData['email'], $addedProfile['email']);
        $this->assertEquals($profileData['status'], $addedProfile['status']);
        $this->assertEquals($profileData['display_name'], $addedProfile['display_name']);
        $this->assertEquals($profileData['url'], $addedProfile['url']);
    }

    public function addProfile(): int
    {
        return Model::insert($this->getProfileData());
    }

    public function getPassword(): string
    {
        return 'forkcms';
    }

    public function getProfileData(): array
    {
        return [
            'email' => 'test@fork-cms.com',
            'password' => '$2y$10$1Ev9QQNYZBjdU1ELKjKNqelcV.j2l3CgtVkHl0aMvbNpg1g73S5lC',
            'status' => 'active',
            'display_name' => 'Fork CMS',
            'url' => 'fork-cms',
            'registered_on' => '2018-03-05 09:45:12',
            'last_login' => '1970-01-01 00:00:00',
        ];
    }
}
