<?php

/*
 * This file is part of club-1/flarum-ext-server-side-highlight.
 *
 * Copyright (c) 2023 Nicolas Peugnet <nicolas@club1.fr>.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace Club1\ServerSideHighlight\Tests\unit;

use Club1\ServerSideHighlight\Formatter\Configurator;
use Flarum\Testing\unit\TestCase;
use RuntimeException;
use s9e\TextFormatter\Configurator as TextFormatterConfigurator;
use s9e\TextFormatter\Parser;

class ConfiguratorTest extends TestCase
{
    /** @var TextFormatterConfigurator */
    protected $configurator;

    public function setUp(): void
    {
        parent::setUp();
        $this->configurator = new TextFormatterConfigurator;
    }

    public function getParser(bool $markdown = true, bool $bbcode = true): Parser
    {
        $markdown && $this->configurator->Litedown;
        $bbcode && $this->configurator->BBCodes->addFromRepository('CODE');
        $configurator = new Configurator();
        $configurator($this->configurator);
        extract($this->configurator->finalize());
        return $parser;
    }

    public function testNoCODE(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Tag 'CODE' does not exist");
        $this->getParser(false, false);
    }

    /**
     * @dataProvider validProvider
     */
    public function testValid($source, $expected): void
    {
        $parser = $this->getParser();
        $this->assertEquals($expected, $parser->parse($source));
    }

    public function validProvider(): array
    {
        return [
            [
'```php
$test = "coucou";
```',
'<r><CODE hash="c00ce2d1d03fc767e9c74348c64651c8" lang="php"><s>```php</s><i>
</i>$test = "coucou";<i>
</i><e>```</e></CODE></r>'
            ],
            [
'```
no lang
```',
'<r><CODE><s>```</s><i>
</i>no lang<i>
</i><e>```</e></CODE></r>'
            ],
            [
'[CODE lang="php"]
$test = "coucou";
[/CODE]',
'<r><CODE hash="c00ce2d1d03fc767e9c74348c64651c8" lang="php"><s>[CODE lang="php"]</s><i>
</i>$test = "coucou";
<e>[/CODE]</e></CODE></r>'
            ],
            [
'[CODE lang=""]
no lang
[/CODE]',
'<r><CODE><s>[CODE lang=""]</s><i>
</i>no lang
<e>[/CODE]</e></CODE></r>'
            ],
        ];
    }

}
