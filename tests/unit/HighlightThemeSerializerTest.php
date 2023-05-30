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

use Club1\ServerSideHighlight\Serializer\HighlightThemeSerializer;
use Flarum\Testing\unit\TestCase;
use Illuminate\Contracts\Filesystem\Cloud;
use Illuminate\Contracts\Filesystem\Factory;
use Mockery as m;
use Mockery\MockInterface;

class HighlightThemeSerializerTest extends TestCase
{
    public function testBasic(): void
    {
        $theme = 'theme1';
        $expected = 'extensions/club-1-server-side-highlight/theme1.min.css';

        /** @var Cloud&MockInterface */
        $disk = m::mock(Cloud::class);
        /** @var Factory&MockInterface */
        $factory = m::mock(Factory::class);
        $factory->shouldReceive('disk')->andReturn($disk);
        $serializer = new HighlightThemeSerializer($factory);

        $disk->shouldReceive('url')->with($expected)->once()->andReturn('url');
        $this->assertEquals('url', $serializer($theme));

    }
}
