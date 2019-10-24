<?php
/**
 * This file is part of the skeleton package.
 *
 * @author Andrew Woods <andrew@andrewwoods.net>
 *
 * @copyright 2019 Andrew Woods
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skel;

use UnexpectedValueException;

class Git
{
    protected $types = [];

    public function __construct()
    {
        $this->types[] = 'ignore';
    }

    public function getSourceFileName($doc)
    {
        switch ($doc){

            case 'ignore':
                return '.gitignore';
                break;

            default:
                $message = 'You have used an unknown file type (' . $doc . '). '
                   . 'Please use one of the following: '
                   . implode(', ', $this->types)
                ;
                throw new UnexpectedValueException($message);
        }
    }


    public function getDestinationFileName($type)
    {
        return '.gitignore';
    }
}
