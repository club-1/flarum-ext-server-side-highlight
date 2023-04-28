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

namespace Club1\ServerSideHighlight\Formatter;

use s9e\TextFormatter\Configurator as TextFormatterConfigurator;
use s9e\TextFormatter\Configurator\Items\Tag;
use s9e\TextFormatter\Parser\Tag as ParserTag;

class Configurator
{
    /**
     * @var string Version of the parser.
     *
     * Only change this value if the old blocks need to be reparsed.
     */
    const VERSION = '1';

    public function __invoke(TextFormatterConfigurator $config): void
    {
        $tag = $config->tags['CODE'];
        assert($tag instanceof Tag);
        $tag->setTemplate(
            '<pre>
                <code>
                    <xsl:if test="@lang">
                        <xsl:attribute name="class">
                            <xsl:text>hljs language-</xsl:text>
                            <xsl:value-of select="@lang"/>
                        </xsl:attribute>
                        <xsl:attribute name="data-s9e-livepreview-onupdate">
                            window["hljs"] && hljs.highlightBlock(this)
                        </xsl:attribute>
                    </xsl:if>
                    <xsl:choose>
                        <xsl:when test="@highlighted != \'\'">
                            <xsl:value-of select="@highlighted" disable-output-escaping="yes"/>
                        </xsl:when>
                        <xsl:otherwise>
                            <xsl:apply-templates/>
                        </xsl:otherwise>
                    </xsl:choose>
                </code>
            </pre>'
        );
        $tag->filterChain->append([static::class, 'filterCode']);
    }

    /**
     * Include the current version of this parser in the code block's metadata.
     *
     * This will allow to trigger reparsing of old code blocks if needed.
     */
    public static function filterCode(ParserTag $tag) {
        $tag->setAttribute('version', static::VERSION);
    }
}
