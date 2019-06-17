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

use unexpectedvalueexception;

class License
{
    protected $types = [];

    public function __construct()
    {
        $this->types[] = 'gpl-2.0';
        $this->types[] = 'mit';
    }

    public function getSourceFileName($doc)
    {
        switch ($doc){

            case 'gpl-2.0':
                return 'licenses/gpl-2.0.txt';
                break;

            case 'mit':
                return 'licenses/mit.txt';
                break;

            default:
                $message = 'You have used an unknown file type (' . $doc . '). '
                   . 'Please use one of the following: '
                   . implode(', ', $this->types)
                ;
                throw new unexpectedvalueexception($message);
        }
    }


    public function getDestinationFileName()
    {
        return 'LICENSE';
    }
}
