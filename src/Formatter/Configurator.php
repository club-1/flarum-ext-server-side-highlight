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

use Highlight\Highlighter;
use s9e\TextFormatter\Configurator as TextFormatterConfigurator;
use s9e\TextFormatter\Configurator\Items\Tag;
use s9e\TextFormatter\Parser;
use s9e\TextFormatter\Parser\Tag as ParserTag;

class Configurator
{
    public function __invoke(TextFormatterConfigurator $config): void
    {
        $tag = $config->tags['CODE'];
        assert($tag instanceof Tag);
        $tag->setTemplate(
            '<pre>
                <code>
                    <xsl:if test="@lang">
                        <xsl:attribute name="class">
                            <xsl:text>language-</xsl:text>
                            <xsl:value-of select="@lang"/>
                        </xsl:attribute>
                    </xsl:if>
                    <xsl:choose>
                        <xsl:when test="@highlighted">
                            <xsl:value-of select="@highlighted" disable-output-escaping="yes"/>
                        </xsl:when>
                        <xsl:otherwise>
                            <xsl:apply-templates/>
                        </xsl:otherwise>
                    </xsl:choose>
                </code>
            </pre>'
        );
        $tag->filterChain
            ->append('Club1\ServerSideHighlight\Formatter\Configurator::filterCode($tag, $parser)');
    }

    public static function filterCode(ParserTag $tag, Parser $parser) {
        $lang = $tag->getAttribute('lang');
        if ($lang == '') {
            return;
        }
        $text = $parser->getText();
        $hl = new Highlighter();
        $start = $tag->getPos() + $tag->getLen();
        if ($tag->getFlags() & Parser::RULE_TRIM_FIRST_LINE && $text[$start] === "\n") {
            $start += 1;
        }
        $end = $tag->getEndTag()->getPos() - $start;
        $code = substr($text, $start, $end);
        $highlighted = $hl->highlight($lang, $code);
        $tag->setAttribute('highlighted', $highlighted->value);
    }
}
