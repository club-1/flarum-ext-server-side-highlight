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

use Club1\ServerSideHighlight\Exception\InvalidArgumentException;
use Club1\ServerSideHighlight\Exception\IOException;
use Club1\ServerSideHighlight\Exception\KnownException;
use Club1\ServerSideHighlight\Exception\KnownExceptionHandler;
use Flarum\Testing\unit\TestCase;

class KnownExceptionHandlerTest extends TestCase
{
    /**
     * @dataProvider basicProvider
     */
    public function testBasic(KnownException $e, string $type, int $statusCode, array $details): void
    {
        $handler = new KnownExceptionHandler();
        $error = $handler->handle($e);
        $this->assertEquals($type, $error->getType());
        $this->assertEquals($statusCode, $error->getStatusCode());
        $this->assertEquals($details, $error->getDetails());
    }

    public function basicProvider(): array
    {
        return [
            [new InvalidArgumentException('msg'), 'invalid_parameter', 400, [['message' => 'msg']]],
            [new IOException('msg'), 'io_error', 409, [['message' => 'msg']]],
        ];
    }
}
