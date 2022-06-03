<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use DivineOmega\SSHConnection\SSHConnection;

class ManagePublicKeysService implements \App\Application\Shared\Service\ManagePublicKeysServiceInterface
{
    public function __construct(
        private readonly int $port = 2222,
        private readonly string $username = 'keyscom',
        private readonly string $privateKey = '/opt/config/id_rsa_keyscom',
    ) {}

    public function add(string $machineIp, string $publicKey): void
    {
        $connection = $this->getConnection($machineIp);
        try {
            $this->runAddCommands($connection, $publicKey);
        } finally {
            $connection->disconnect();
        }
    }

    public function remove(string $machineIp, string $publicKey): void
    {
        $connection = $this->getConnection($machineIp);
        try {
            $this->runRemoveCommands($connection, $publicKey);
        } finally {
            $connection->disconnect();
        }
    }

    private function getConnection(string $ip): SSHConnection
    {
        return (new SSHConnection())
            ->to($ip)
            ->onPort($this->port)
            ->as($this->username)
            ->withPrivateKey($this->privateKey)
//            ->timeout(0)
            ->connect();
    }

    private function runAddCommands(SSHConnection $connection, string $publicKey): void
    {
        $connection->run("mkdir -p ~/.ssh/");
        $connection->run("chmod 700 ~/.ssh/");
        $connection->run(sprintf('echo "%s" >> ~/.ssh/authorized_keys', $publicKey));
        $connection->run("chmod 644 ~/.ssh/authorized_keys");
    }

    private function runRemoveCommands(SSHConnection $connection, string $publicKey): void
    {
        $connection->run(sprintf("sed -i.bak 's#%s##' ~/.ssh/authorized_keys", $publicKey));
    }
}
