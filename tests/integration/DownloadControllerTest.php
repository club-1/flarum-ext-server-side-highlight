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

use Flarum\Testing\integration\RetrievesAuthorizedUsers;
use Flarum\Testing\integration\TestCase;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;

class DownloadControllerTest extends TestCase
{
    use RetrievesAuthorizedUsers;
    use MockeryPHPUnitIntegration;

    /** @var \Club1\ServerSideHighlight\Console\DownloadCommand&MockInterface */
    protected $command;

    public function setUp(): void
    {
        parent::setUp();
        $this->extension('club-1-server-side-highlight');
        $this->command = m::mock('overload:Club1\ServerSideHighlight\Console\DownloadCommand');

        $this->prepareDatabase([
            'users' => [
                $this->normalUser(),
            ],
        ]);
    }

    /**
     * @dataProvider responseCodeProvider
     */
    public function testResponseCode(int $user, int $commandCalls, int $code): void
    {
        $this->command->shouldReceive('run')->withAnyArgs()->times($commandCalls);
        $response = $this->send(
            $this->request('GET', '/api/highlight-download', ['authenticatedAs' => $user])
                ->withQueryParams(['name' => 'arta'])
        );

        $this->assertEquals($code, $response->getStatusCode());
    }

    public function responseCodeProvider(): array
    {
        return [
            'admin user' => [1, 1, 200],
            'normal user' => [2, 0, 403],
        ];
    }
}
