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

use Club1\ServerSideHighlight\Consts;
use Club1\ServerSideHighlight\Frontend\Admin;
use Club1\ServerSideHighlight\Frontend\Forum;
use Flarum\Frontend\Document;
use Flarum\Testing\unit\TestCase;
use Illuminate\Contracts\Filesystem\Cloud;
use Illuminate\Contracts\Filesystem\Factory;
use Mockery as m;
use Mockery\MockInterface;

class AdminTest extends TestCase
{
    /** @var Cloud&MockInterface */
    protected $disk;

    /** @var Factory&MockInterface */
    protected $factory;

    /** @var Document&MockInterface */
    protected $doc;

    public function setUp(): void
    {
        parent::setUp();
        $this->disk = m::mock(Cloud::class);
        $this->factory = m::mock(Factory::class);
        $this->factory->shouldReceive('disk')->andReturn($this->disk);
        $this->doc = m::mock(Document::class);
    }

    public function testBasic(): void
    {
        $jsPath = Consts::ASSETS_PATH . 'styles.min.js';
        $this->disk->shouldReceive('url')->once()->with($jsPath)->andReturn($jsPath);

        $content = new Admin($this->factory);
        $content($this->doc);
        $this->assertCount(1, $this->doc->js);
        $this->assertEquals($jsPath, $this->doc->js[0]);
    }
}
