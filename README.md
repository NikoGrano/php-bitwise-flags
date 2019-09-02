# PHP Flags
  
The number of flags you can use is limited to the architecture of your 
system, e.g.: 32 flags on a 32-bit system or 64 flags on 64-bit system. 
To store 64-bits flags in a database, you will need to store it as 
UNSIGNED BIGINT in MySQL.


## Install

Via [composer](http://getcomposer.org):

```shell
composer require niko9911/bitwise-flags
```

## Usage

Below some example usage code


```php
<?php
declare(strict_types=1);

use Niko9911\Flags\Bits;
use Niko9911\Flags\Flags;

final class User extends Flags
{
    public const BANNED = Bits::BIT_1;              // 0x1
    public const ADMIN = Bits::BIT_2;               // 0x2
    public const ACTIVATED = Bits::BIT_3;           // 0x4
}
/** @var User|Flags $entity */
$entity = new User();

/** Usage when using single flag. */
$entity->addFlag(User::BANNED);

var_dump($entity->matchFlag(User::ADMIN));          // False
var_dump($entity->matchFlag(User::BANNED));         // True

$entity->removeFlag(User::BANNED);
var_dump($entity->matchFlag(User::BAR));            // False

/** Usage when using multiple flags. */
$entity->addFlag(User::ACTIVATED | User::ADMIN);

var_dump($entity->matchFlag(User::ACTIVATED));      // True
var_dump($entity->matchFlag(User::ACTIVATED | User::BANNED)); // False (Banned not set.)
var_dump($entity->matchFlag(User::ACTIVATED | User::ADMIN));  // True (Both set)
var_dump($entity->matchAnyFlag(User::ACTIVATED | User::BANNED)); // True. (One is set.)

/** Usage with flag names. */
// Flag name is taken from constant name
$entity = new User();

$entity->addFlag(User::BANNED | User::ADMIN | User::ACTIVATED);

var_dump($entity->getFlagNames()); // [Banned, Admin, Activated]
var_dump($entity->getFlagNames(User::ACTIVATED | User::BANNED)); // [Activated, Banned]

/** Overriding automatically defined flag names. */
final class UserWCustomNames extends Flags
{
    public const BANNED = Bits::BIT_1;
    public const ADMIN = Bits::BIT_2;
    public const ACTIVATED = Bits::BIT_3;
    
    // Implementing this specific function you can register
    // flags with custom naming. 
    public static function registerFlags(): array
    {
        return [
            static::BANNED => 'IsUserBanned',
            static::ADMIN => 'IsUserAdmin',
            static::ACTIVATED => 'IsUserActivated',
        ];
    }
}

$entity = new UserWCustomNames();
$entity->addFlag(
    UserWCustomNames::BANNED | 
    UserWCustomNames::ADMIN | 
    UserWCustomNames::ACTIVATED
);

var_dump($entity->getFlagNames()); 
// [
//      0 => IsUserBanned,
//      1 => IsUserAdmin,
//      2 => IsUserActivated,
// ]
```
