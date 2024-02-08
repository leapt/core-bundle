<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Listener;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use Leapt\CoreBundle\File\CondemnedFile;
use Leapt\CoreBundle\FileStorage\FileStorageManager;
use Leapt\CoreBundle\FileStorage\FilesystemStorage;
use Leapt\CoreBundle\FileStorage\FlysystemStorage;
use Leapt\CoreBundle\Listener\FileSubscriber;
use Leapt\CoreBundle\Tests\Listener\Fixtures\Entity\Novel;
use Leapt\CoreBundle\Tests\Listener\Fixtures\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

class FileSubscriberTest extends TestCase
{
    private EntityManagerInterface $em;
    private FileSubscriber $subscriber;
    private string $rootDir;

    private array $classes = [
        User::class,
        Novel::class,
    ];

    protected function setUp(): void
    {
        $this->em = $this->buildEntityManager();
        $this->createSchema();
        $this->rootDir = sys_get_temp_dir() . '/' . uniqid('', false);

        $fileStorageManager = new FileStorageManager(
            new FilesystemStorage($this->rootDir),
            new FlysystemStorage([]),
        );
        $this->subscriber = new FileSubscriber($fileStorageManager);

        parent::setUp();
    }

    /*
    public function testPreFlushInsert()
    {
        $user = $this->buildUserToInsert();
        $eventArgs = new PreFlushEventArgs($this->em);
        $this->subscriber->preFlush($eventArgs);

        $changeset = $this->em->getUnitOfWork()->getEntityChangeSet($user);
        $this->assertArrayHasKey('cv', $changeset);
        $this->assertNotNull($user->getCv());
    }
    */

    /*
    public function testPreFlushInsertForMappedSuperClass()
    {
        $novel = $this->buildNovelToInsert();
        $eventArgs = new PreFlushEventArgs($this->em);
        $this->subscriber->preFlush($eventArgs);

        $changeset = $this->em->getUnitOfWork()->getEntityChangeSet($novel);
        $this->assertArrayHasKey('attachment', $changeset);
        $this->assertNotNull($novel->getAttachment());
    }
    */

    public function testPreFlushUpdate(): void
    {
        $user = $this->buildUserToUpdate();
        $eventArgs = new PreFlushEventArgs($this->em);
        $this->subscriber->preFlush($eventArgs);

        $changeSet = $this->em->getUnitOfWork()->getEntityChangeSet($user);
        $this->assertArrayHasKey('cv', $changeSet);
        $this->assertNotNull($user->getCv());
    }

    public function testPostPersist(): void
    {
        $user = $this->buildUserToInsert();
        $cvPath = 'uploads/cvs/' . uniqid('', false) . '.txt';
        $user->setCv($cvPath);

        $eventArgs = new PostPersistEventArgs($user, $this->em);
        $this->subscriber->postPersist($eventArgs);

        $this->assertNull($user->getCvFile());
        $this->assertFileExists($this->rootDir . '/' . $cvPath);
    }

    public function testPostPersistForMappedSuperClass(): void
    {
        $novel = $this->buildNovelToInsert();
        $attachmentPath = 'uploads/attachments/' . uniqid('', false) . '.txt';
        $novel->setAttachment($attachmentPath);

        $eventArgs = new PostPersistEventArgs($novel, $this->em);
        $this->subscriber->postPersist($eventArgs);

        $this->assertNull($novel->getAttachmentFile());
        $this->assertFileExists($this->rootDir . '/' . $attachmentPath);
    }

    public function testPostUpdate(): void
    {
        $user = $this->buildUserToUpdate();
        $cvPath = 'uploads/cvs/' . uniqid('', false) . '.txt';
        $user->setCv($cvPath);

        $eventArgs = new PostUpdateEventArgs($user, $this->em);
        $this->subscriber->postUpdate($eventArgs);

        $this->assertNull($user->getCvFile());
        $this->assertFileExists($this->rootDir . '/' . $cvPath);
    }

    public function testPostUpdateWithPrevousFile(): void
    {
        $user = $this->buildUserToUpdate();
        $oldCvPath = 'uploads/cvs/' . uniqid('', false) . '.txt';
        $newCvPath = 'uploads/cvs/' . uniqid('', false) . '.txt';
        $this->copyFile(__DIR__ . '/Fixtures/files/test_file.txt', '/' . $oldCvPath);
        $user->setCv($newCvPath);

        $this->em->getUnitOfWork()->propertyChanged($user, 'cv', $oldCvPath, $newCvPath);

        $this->assertFileExists($this->rootDir . '/' . $oldCvPath);

        $eventArgs = new PostUpdateEventArgs($user, $this->em);
        $this->subscriber->postUpdate($eventArgs);

        $this->assertNull($user->getCvFile());
        $this->assertFileExists($this->rootDir . '/' . $newCvPath);
        $this->assertFileDoesNotExist($this->rootDir . '/' . $oldCvPath);
    }

    public function testPostUpdateWithCondemnedFile(): void
    {
        $user = $this->buildUserToUpdate();
        $cvPath = 'uploads/cvs/' . uniqid('', false) . '.txt';
        $this->copyFile(__DIR__ . '/Fixtures/files/test_file.txt', '/' . $cvPath);
        $user->setCv($cvPath);

        $this->assertFileExists($this->rootDir . '/' . $cvPath);
        $user->setCvFile(new CondemnedFile());

        $preFlushEventArgs = new PreFlushEventArgs($this->em);
        $eventArgs = new PostUpdateEventArgs($user, $this->em);
        $this->subscriber->preFlush($preFlushEventArgs);
        $this->subscriber->postUpdate($eventArgs);

        $this->assertNull($user->getCvFile());
        $this->assertFileDoesNotExist($this->rootDir . '/' . $cvPath);
    }

    public function testPostRemove(): void
    {
        $user = $this->buildUserToDelete();
        $cvPath = 'uploads/cvs/' . uniqid('', false) . '.txt';
        $this->copyFile(__DIR__ . '/Fixtures/files/test_file.txt', '/' . $cvPath);
        $user->setCv($cvPath);

        $this->assertFileExists($this->rootDir . '/' . $cvPath);

        $preRemoveEventArgs = new PreRemoveEventArgs($user, $this->em);
        $postRemoveEventArgs = new PostRemoveEventArgs($user, $this->em);

        $this->subscriber->preRemove($preRemoveEventArgs);
        $this->subscriber->postRemove($postRemoveEventArgs);

        $this->assertFileDoesNotExist($this->rootDir . '/' . $cvPath);
    }

    private function buildEntityManager(): EntityManagerInterface
    {
        $config = ORMSetup::createConfiguration(true, sys_get_temp_dir());
        $config->setMetadataDriverImpl(new AttributeDriver([__DIR__ . '/Fixtures']));
        $config->setAutoGenerateProxyClasses(true);

        $params = [
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ];

        return new EntityManager(DriverManager::getConnection($params), $config);
    }

    private function createSchema(): void
    {
        $em = $this->em;
        $schema = array_map(static function ($class) use ($em) {
            return $em->getClassMetadata($class);
        }, $this->classes);

        $schemaTool = new SchemaTool($em);
        $schemaTool->dropSchema([]);
        $schemaTool->createSchema($schema);
    }

    private function copyFile(string $from, string $to): string
    {
        $fs = new Filesystem();
        $targetPath = $this->rootDir . $to;
        $fs->copy($from, $targetPath);

        return $targetPath;
    }

    private function buildUserToInsert(): User
    {
        $user = new User();
        $user->setUserName('johndoe');
        $user->setCvFile(new File($this->copyFile(__DIR__ . '/Fixtures/files/test_file.txt', '/test_file.txt')));

        $this->em->getUnitOfWork()->scheduleForInsert($user);

        return $user;
    }

    private function buildNovelToInsert(): Novel
    {
        $novel = new Novel();
        $novel->setTitle('Dancing with the frogs');
        $novel->setSubtitle('An epic tale of man-frog love');
        $novel->setAttachmentFile(new File($this->copyFile(__DIR__ . '/Fixtures/files/test_file.txt', '/test_file.txt')));

        $this->em->getUnitOfWork()->scheduleForInsert($novel);

        return $novel;
    }

    private function buildUserToUpdate(): User
    {
        $userName = 'johndoe';
        $cvFile = new File($this->copyFile(__DIR__ . '/Fixtures/files/test_file.txt', '/test_file.txt'));

        $user = new User();
        $user->setUserName($userName);
        $user->setCvFile($cvFile);

        $this->em->getUnitOfWork()->registerManaged($user, [1], [
            'userName' => $userName,
            'cvFile'   => $cvFile,
        ]);
        $this->em->getUnitOfWork()->scheduleForUpdate($user);

        return $user;
    }

    private function buildUserToDelete(): User
    {
        $userName = 'johndoe';
        $cvFile = new File($this->copyFile(__DIR__ . '/Fixtures/files/test_file.txt', '/test_file.txt'));

        $user = new User();
        $user->setUserName($userName);
        $user->setCvFile($cvFile);

        $this->em->getUnitOfWork()->registerManaged($user, [1], [
            'userName' => $userName,
            'cvFile'   => $cvFile,
        ]);
        $this->em->getUnitOfWork()->scheduleForDelete($user);

        return $user;
    }
}
