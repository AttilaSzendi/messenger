<?php

namespace Stilldesign\Messenger\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Stilldesign\Messenger\MessengerServiceProvider;
use Stilldesign\Messenger\Models\User;

abstract class TestCase extends BaseTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->loadLaravelMigrations(['--database' => 'messenger']);
        $this->loadMigrationsFrom(__DIR__.'/../src/database/migrations');
        $this->withFactories(__DIR__.'/../src/database/factories');
    }

    protected function getPackageProviders($app)
    {
        return [MessengerServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        // Setup default database to use sqlite :memory:
        $app['config']->set('app.key', 'sF5r4kJy5HEcOEx3NWxUcYj1zLZLHxuu');
        $app['config']->set('database.default', 'messenger');
        $app['config']->set('database.connections.messenger', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        
        $app['config']->set('filesystems.cloud', 'public');
        $app['config']->set('filesystems.disks.local.driver', 'local');
        $app['config']->set('filesystems.disks.local.root', realpath(__DIR__.'/storage'));
        $app['config']->set('filesystems.disks.public.driver', 'local');
        $app['config']->set('filesystems.disks.public.root', realpath(__DIR__.'/storage/public'));
        $app['config']->set('filesystems.default', 'public');
        $app['config']->set('messengerAllowedFiles', [
            'documents' => [
                'pdf' => 'application/pdf',
                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'xls' => 'application/vnd.ms-excel',
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'ppt' => 'application/vnd.ms-powerpoint',
                'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            ],
            'images' => [
                'bmp' => 'image/bmp',
                'gif' => 'image/gif',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/pjpeg',
                'png' => 'image/png',
            ]
        ]);

        $app['config']->set('messenger', [
            'user' => User::class
        ]);
    }
}