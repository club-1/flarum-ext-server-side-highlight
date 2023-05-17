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

use Club1\ServerSideHighlight\Formatter\Renderer;
use Flarum\Testing\unit\TestCase;
use Illuminate\Contracts\Cache\Repository;
use Mockery as m;
use Mockery\MockInterface;
use s9e\TextFormatter\Utils;

class RendererTest extends TestCase
{
    /** @var Repository&MockInterface */
    protected $cache;

    public function setUp(): void
    {
        parent::setUp();
        $this->cache = m::mock(Repository::class);
    }

    /**
     * @dataProvider validProvider
     */
    public function testValid(string $lang, string $code, string $expected): void
    {
        $xml = "<r><CODE hash=\"c00C\" lang=\"$lang\">$code</CODE></r>";

        $this->cache->shouldReceive('get')->once()->andReturn(null);
        $this->cache->shouldReceive('put')->once();
        $renderer = new Renderer($this->cache);
        $result = $renderer(null, null, $xml);
        $attrs = Utils::getAttributeValues($result, 'CODE', 'highlighted');
        $this->assertCount(1, $attrs);
        $this->assertEquals($expected, $attrs[0]);
    }

    public function validProvider(): array
    {
        return [
            'simple' => ['php', '<s></s>"coucou"<e></e>', '<span class="hljs-string">"coucou"</span>'],
            'strip ignored' => ['php', '<s></s><i>&gt; </i>"coucou"<e></e>', '<span class="hljs-string">"coucou"</span>'],
            'missing end' => ['php', '<s></s>"coucou"', '<span class="hljs-string">"coucou"</span>'],
            'missing start' => ['php', '"coucou"<e></e>', '<span class="hljs-string">"coucou"</span>'],
        ];
    }

    public function testNoCODE(): void
    {
        $xml = "<r>Coucou les loulous !</r>";

        $renderer = new Renderer($this->cache);
        $result = $renderer(null, null, $xml);
        $this->assertEquals($xml, $result);
    }

    public function testNoLang(): void
    {
        $code = '$test = "coucou"';

        $xml = "<r><CODE hash=\"c00C\"><s>[C]</s>$code<e>[/C]</e></CODE></r>";

        $renderer = new Renderer($this->cache);
        $result = $renderer(null, null, $xml);
        $this->assertEquals($xml, $result);
    }

    public function testHighlightFailure(): void
    {
        $lang = 'non-existing';
        $code = '$test = "coucou"';

        $xml = "<r><CODE hash=\"c00C\" lang=\"$lang\"><s></s>$code<e></e></CODE></r>";

        $this->cache->shouldReceive('get')->once()->andReturn(null);
        $this->cache->shouldReceive('put')->once();
        $renderer = new Renderer($this->cache);
        $result = $renderer(null, null, $xml);
        $attrs = Utils::getAttributeValues($result, 'CODE', 'highlighted');
        $this->assertCount(1, $attrs);
        $this->assertEquals('', $attrs[0]);
    }

    public function testCache(): void
    {
        $hash = 'c00c';
        $lang = 'php';
        $code = '"coucou"';
        $expected = 'highlighted code';

        $xml = "<r><CODE hash=\"$hash\" lang=\"$lang\"><s></s>$code<e></e></CODE></r>";
        $key = $lang . $hash;

        $this->cache->shouldReceive('get')->with($key)->once()->andReturn($expected);
        $renderer = new Renderer($this->cache);
        $result = $renderer(null, null, $xml);
        $attrs = Utils::getAttributeValues($result, 'CODE', 'highlighted');
        $this->assertCount(1, $attrs);
        $this->assertEquals($expected, $attrs[0]);
    }
}
