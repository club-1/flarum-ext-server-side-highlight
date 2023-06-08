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

use Club1\ServerSideHighlight\Consts;
use Flarum\Http\RequestUtil;
use Highlight\Highlighter;
use Illuminate\Contracts\Filesystem\Cloud;
use Illuminate\Contracts\Filesystem\Factory as FsFactory;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;

class PreviewController implements RequestHandlerInterface
{
    const CODE = <<<'EOD'
<!DOCTYPE html>
<title>Title</title>

<style>body {width: 500px;}</style>

<script type="application/javascript">
  function $init() {return true;}
</script>

<body>
  <p checked class="title" id='title'>Title</p>
  <!-- here goes the rest of the page -->
</body>
EOD;

    /** @var Cloud */
    protected $assetsDisk;

    /** @var ViewFactory */
    protected $view;

    public function __construct(ViewFactory $view, FsFactory $filesystemFactory)
    {
        $this->view = $view;
        $this->assetsDisk = $filesystemFactory->disk('flarum-assets');
    }

    public function handle(Request $request): Response
    {
        $actor = RequestUtil::getActor($request);
        $actor->assertAdmin();
        $params = $request->getQueryParams();
        $hl = new Highlighter();
        $view = $this->view->make('club-1.server-side-highlight::preview', [
            'bg' => $params['bg'],
            'text' => $params['text'],
            'theme' => $this->assetsDisk->url(Consts::ASSETS_PATH . $params['theme'] . '.min.css'),
            'code' => $hl->highlight('html', self::CODE)->value,
        ]);
        return new HtmlResponse($view->render());
    }
}
