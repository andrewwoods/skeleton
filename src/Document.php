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

class Document
{
    protected $docTypes = [];

    public function __construct()
    {
        $this->docTypes[] = 'changelog';
        $this->docTypes[] = 'contributing';
        $this->docTypes[] = 'license-gpl-2.0';
        $this->docTypes[] = 'license-mit';
        $this->docTypes[] = 'readme';
    }

    public function getSourceFileName($doc)
    {
        switch ($doc){
            case 'changelog':
                return 'CHANGELOG.md';
                break;

            case 'contributing':
                return 'CONTRIBUTING.md';
                break;

            case 'license-mit':
                return 'licenses/mit.txt';
                break;

            case "readme":
                return 'README.md';
                break;

            default:
                $message = 'You have used an unknown file type (' . $doc . '). '
                   . 'Please use one of the following: '
                   . implode(', ', $this->docTypes)
                ;
                throw new unexpectedvalueexception($message);
        }
    }


    public function getDestinationFileName($doc)
    {
        switch ($doc){
            case 'changelog':
                return 'CHANGELOG.md';
                break;

            case 'contributing':
                return 'CONTRIBUTING.md';
                break;

            case 'license-mit':
                return 'LICENSE';
                break;

            case "readme":
                return 'README.md';
                break;

            default:
                $message = 'You have used an unknown file type. '
                    . 'Please use one of the following: '
                    . implode(', ', $this->docTypes)
                ;
                throw new UnexpectedValueException($message);

        }
    }
}
