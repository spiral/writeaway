<?php

declare(strict_types=1);

namespace Spiral\Tests\Writeaway\App\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Security\Actor\Guest;
use Spiral\Security\PermissionsInterface;
use Spiral\Security\Rule\AllowRule;

final class GuestBootloader extends Bootloader
{
    public function boot(PermissionsInterface $permissions): void
    {
        if (!$permissions->hasRole(Guest::ROLE)) {
            $permissions->addRole(Guest::ROLE);
        }

        $permissions->associate(Guest::ROLE, '*', AllowRule::class);
        $permissions->associate(Guest::ROLE, '*.*', AllowRule::class);
        $permissions->associate(Guest::ROLE, '*.*.*', AllowRule::class);
    }
}
