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

namespace Club1\ServerSideHighlight\Controller;

use Club1\ServerSideHighlight\Console\DownloadCommand;
use Flarum\Http\RequestUtil;
use Flarum\Settings\SettingsRepositoryInterface;
use Laminas\Diactoros\Response\TextResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class DownloadController implements RequestHandlerInterface
{
    /** @var SettingsRepositoryInterface */
    protected $settings;

    /** @var DownloadCommand */
    protected $command;

    public function __construct(SettingsRepositoryInterface $settings, DownloadCommand $command)
    {
        $this->settings = $settings;
        $this->command = $command;
    }

    public function handle(Request $request): Response
    {
        $actor = RequestUtil::getActor($request);
        $actor->assertAdmin();
        $name = $request->getQueryParams()['name'];
        $this->command->run(new ArrayInput(['name' => $name]), new NullOutput);
        return new TextResponse(
            $this->settings->get('club-1-server-side-highlight.available_themes'),
            200,
            ['Content-Type' => ['application/json']]
        );
    }
}
