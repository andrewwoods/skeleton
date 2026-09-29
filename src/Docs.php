<?php
/**
 *
 * @author Andrew Woods <https://andrewwoods.net>
 *
 * @copyright 2026 Andrew Woods
 * @license   https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Skel;

use UnexpectedValueException;


/**
 * @package Skel
 */
enum Docs: string
{
    case Changelog = 'changelog';
    case Contributing = 'contributing';
    case Humans = 'humans';
    case Readme = 'readme';

    public const CHANGELOG = self::Changelog->value;
    public const CONTRIBUTING = self::Contributing->value;
    public const HUMANS = self::Humans->value;
    public const README = self::Readme->value;

    public static function getSourceFileName($doc)
    {
        $message = 'You have used an unknown file type "' . $doc . '". '
                   . 'Please use one of the following: '
            . implode(
                ', ', [
                self::CHANGELOG, 
                self::CONTRIBUTING, 
                self::HUMANS, 
                self::README ]
            );

        return match ($doc){
            self::Changelog->value => 'CHANGELOG.md',
            self::Contributing->value => 'docs/CONTRIBUTING.md',
            self::Humans->value => 'docs/humans.txt',
            self::Readme->value => 'project.README.md',
            default => throw new UnexpectedValueException($message),
        };
    }

    public static function getDestinationFileName($doc)
    {
        $message = 'You have used an unknown file type "' . $doc . '". '
                   . 'Please use one of the following: '
            . implode(
                ', ', [
                self::CHANGELOG, 
                self::CONTRIBUTING, 
                self::HUMANS, 
                self::README ]
            );

        return match ($doc){
            self::Changelog->value => 'CHANGELOG.md',
            self::Contributing->value => 'CONTRIBUTING.md',
            self::Humans->value => 'humans.txt',
            self::Readme->value => 'README.md',
            default => throw new UnexpectedValueException($message),
        };
    }
}
