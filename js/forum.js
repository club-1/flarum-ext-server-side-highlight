if (flarum.extensions['fof-nightmode']) {
  document.addEventListener('fofnightmodechange', (event) => {
    let theme, bgColor, textColor;
    if (event.detail == 'day') {
      theme = app.forum.attribute('lightThemeHighlightTheme');
      bgColor = app.forum.attribute('lightThemeBgColor');
      textColor = app.forum.attribute('lightThemeTextColor');
    } else {
      theme = app.forum.attribute('darkThemeHighlightTheme');
      bgColor = app.forum.attribute('darkThemeBgColor');
      textColor = app.forum.attribute('darkThemeTextColor');
    }
    textContent = `:root { --codeblock-bg: ${bgColor}; --codeblock-color: ${textColor};}`;
    document.querySelector('link.club-1-server-side-highlight').href = theme;
    document.querySelector('style.club-1-server-side-highlight').textContent = textContent;
  });
}

module.exports = {};
