<?php
/**
 * This file is part of the skeleton package.
 *
 * @author Andrew Woods <andrew@andrewwoods.net>
 *
 * @copyright 2019 Andrew Woods
 * @license   https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skel;

use UnexpectedValueException;

class Document
{
    protected $docTypes = [];

    public function __construct()
    {
        $this->docTypes[] = 'changelog';
        $this->docTypes[] = 'contributing';
        $this->docTypes[] = 'humans';
        $this->docTypes[] = 'readme';
    }

    public function getSourceFileName($doc)
    {
        $message = 'You have used an unknown file type "' . $doc . '". '
                   . 'Please use one of the following: '
                   . implode(', ', $this->docTypes);

        return match ($doc){
            'changelog' => 'CHANGELOG.md',
            'contributing' => 'docs/CONTRIBUTING.md',
            'humans' => 'docs/humans.txt',
            "readme" => 'project.README.md',
            default => throw new UnexpectedValueException($message),
        };
    }


    public function getDestinationFileName($doc)
    {
        $message = 'You have used an unknown file type. '
                   . 'Please use one of the following: '
                   . implode(', ', $this->docTypes);

        return match ($doc){
            'changelog' => 'CHANGELOG.md',
            'contributing' => 'CONTRIBUTING.md',
            'humans' => 'humans.txt',
            'readme' => 'README.md',
            default => throw new UnexpectedValueException($message),
        };
    }
}
