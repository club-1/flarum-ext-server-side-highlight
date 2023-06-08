<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="{{ $theme }}" />
        <style>
            html, body, pre, code {
                box-sizing: border-box;
                margin: 0;
                width: 100%;
                height: 100%;
            }
            pre>code.hljs {
                padding: 1em;
                background: {{ $bg }};
                color: {{ $text }};
            }
        </style>
    </head>
    <body>
        <pre><code class="hljs">{!! $code !!}</code></pre>
    </body>
</html>
